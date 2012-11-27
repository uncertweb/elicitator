<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actions
 *
 * @author williamw
 */
class expertActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager(
                        'sfGuardUser',
                        10
        );
        $query = $this->getUser()->getGuardUser()->getKnownExpertsQuery();
        if ($request->hasParameter('filter')) {
            $filter = "%" . $request->getParameter('filter') . "%";
            $query->andwhere("first_name like ? OR last_name like ? OR email_address like ? OR institute like ? OR expertise like ?", array($filter, $filter, $filter, $filter, $filter));
        }
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->filter = $request->getParameter('filter');
    }

    public function executeAutocomplete(sfWebRequest $request) {
        $this->getResponse()->setHttpHeader('Content-Type', 'application/json; charset=utf-8');
        $experts = $this->getUser()->getGuardUser()->findKnownExperts($request->getParameter('q'));
        $json_results = array();
        foreach ($experts as $expert) {
            $json_results[] = array('caption' => (string) $expert->getFullName(), 'value' => $expert->getId());
        }
        return $this->renderText(json_encode($json_results));
    }

    public function executeRemove_expert(sfWebRequest $request) {
        $uid = $request->getParameter('user_id');
        $eid = $request->getParameter('expert_id');
        $table = Doctrine_Core::getTable('UserExpert');
        $expert = $table->findSpecific($uid, $eid);
        $expert->delete();
        $referer = $request->getReferer();
        if ($referer) {
            $this->redirect($referer);
        }
        $this->redirect('user_show', $this->getUser()->getGuardUser());
    }

    public function executeContact(sfWebRequest $request) {
        $this->forward404Unless($user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id'))); #
        $experts = $this->getUser()->getGuardUser()->Experts;
        $allowed = false;
        foreach ($experts as $expert) {
            if ($expert->getId() == $user->getId()) {
                $allowed = true;
                break;
            }
        }
        if ($user->getId() == $this->getUser()->getGuardUser()->getId()) {
            $allowed = true;
        }
        $this->forwardUnless($allowed, 'sfGuardAuth', 'secure');
        $this->form = new ContactForm(array('to_name' => $user->getFullName(), 'to_address' => $user->getUsername()));
    }

    public function executeSend(sfWebRequest $request) {

        $form = new ContactForm();
        $form->bind($request->getParameter('contact'));
        if ($form->isValid()) {
            $values = $request->getParameter('contact');
            $params = array(
                    'from_name' => $this->getUser()->getGuardUser()->getFullName(),
                    'from_address' => $this->getUser()->getGuardUser()->getUsername(),
                    'to_name' => $values['to_name'],
                    'to_address' => $values['to_address'],
                    'subject' => $values['subject'],
                    'message' => $values['message']
            );


            //die;
            $this->getUser()->setFlash('notice', "You message to " . $params['to_name'] . " has been sent successfully.");
            $this->redirect('user_show', $this->getUser()->getGuardUser());
        }
        $this->form = $form;
        $this->setTemplate('contact');
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new ExpertForm();
        if ($request->isMethod('post')) {
            $this->processForm($request);
        }
    }

    private function processForm($request) {
        $user = $request->getParameter('sf_guard_user');
        $this->form->bind($user);
        $values = $request->getParameter('sf_guard_user');
        // Find out whether the user already exists...
        $expert = Doctrine_Core::getTable('sfGuardUser')->findOneByUsername($values['username']);
        if ($expert) {
            // Check to see whether this use already knows this expert.
            $ue = Doctrine_Core::getTable('UserExpert')->findSpecific($this->getUser()->getGuardUser()->getId(), $expert->getId());
            if (!$ue) {
                // User already exists, add them to their list
                $ue = new UserExpert();
                $ue->setExpertId($expert->getId());
                $ue->setUserId($this->getUser()->getGuardUser()->getId());
                $ue->save();
                $this->getUser()->setFlash('notice', $expert->getFullName() . ' already exists in the system, they have been added to your list.');
            } else {
                $this->getUser()->setFlash('notice', $expert->getFullName() . ' is already on your contacts list.');
            }

            $this->redirect('@control_panel');
        }

        if ($this->form->isValid()) {
            // Create a new user
            $user = $this->form->save();
            $password = sfGuardUser::generateRandomPassword(8);
            $user->setPassword($password);
            $user->save();
            $relation = new UserExpert();
            $relation->user_id = $this->getUser()->getGuardUser()->getId();
            $relation->expert_id = $user->getId();
            $relation->save();
            $this->getUser()->setFlash('notice', $user->getFullName() . ' has been added to your list.');
            $this->getMailer()->send(new InviteExpertMessage($this->getUser()->getGuardUser(), $user, $password));
            $this->redirect('@control_panel');
        }
    }

}

?>
