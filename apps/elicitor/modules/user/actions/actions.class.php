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
class userActions extends sfActions {

    public function executeShow(sfWebRequest $request) {
        $user = $this->getUser()->getGuardUser();
        $this->experts = $user->getTop5Experts();
        $this->experts_count = $user->getNumberOfExperts();
        $this->problems =  $user->getTop5ElicitationProblems();
        $this->problems_count = $user->getNumberOfElicitationProblems();
        $this->tasks =  $user->getTop5ElicitationTasks();
        $this->tasks_count = $user->getNumberOfElicitationTasks();

    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
        $this->edit_form = new EditUserForm($user);
        $this->password_form = new ChangePasswordForm(null, array('user' => $this->getUser()));
        $this->processForm($request, $this->edit_form);

        $this->setTemplate('edit');
    }

    public function executePassword(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($user = Doctrine_Core::getTable('sfGuardUser')->find(array($request->getParameter('id'))), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
        $this->edit_form = new EditUserForm($user);
        $this->password_form = new ChangePasswordForm(null, array('user' => $this->getUser()));

        $this->processPasswordForm($request, $this->password_form);

        $this->setTemplate('edit');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($user = $this->getRoute()->getObject(), sprintf('Object user does not exist (%s).', $request->getParameter('id')));
        $this->edit_form = new EditUserForm($user);
        $this->password_form = new ChangePasswordForm(null, array('user' => $this->getUser()));
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $user = $form->save();
            $this->getUser()->setFlash('notice', 'Account details successfully updated.');
            $this->redirect('@user_edit?id=' . $user->getId());
        }
    }

    protected function processPasswordForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $user = $this->getUser()->getGuardUser();
            $user->setPassword($form->getValue('new_password'));
            $user->save();
            $this->getUser()->setFlash('notice', 'Your password has been successfully changed.');
            $this->redirect('@user_edit?id=' . $user->getId());
        }
    }

}

?>
