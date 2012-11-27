<?php

/**
 * variable actions.
 *
 * @package    elicitor
 * @subpackage variable
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tasksActions extends sfActions {

    public function executeUncertml(sfWebRequest $request) {
        $this->forward404Unless($task = Doctrine_Core::getTable('Variable')->findOneByHash($request->getParameter('hash')));
        $this->getResponse()->setHttpHeader('Content-Type', 'application/json; charset=utf-8');

        return $this->renderText($task->Distribution->toJSON());
    }

    public function executeIndex(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager(
                        'Variable',
                        10
        );
        $query = $this->getUser()->getGuardUser()->getElicitationTasksQuery();
        if ($request->hasParameter('filter')) {
            $filter = "%" . $request->getParameter('filter') . "%";
            $query->andwhere("name like ? OR variable_type like ?", array($filter, $filter));
        }
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->filter = $request->getParameter('filter');
    }

    public function executeShow(sfWebRequest $request) {
        $variable_id = $request->getParameter('id');
        // is this the problem owner?
        $variable = Doctrine_Core::getTable('Variable')->findOneById($variable_id);
        if ($variable->getVariableType() == 'Categorical') {
            $module = 'categorical_elicitation';
        } else if($variable->getVariableType() == 'Continuous') {
            $module = 'continuous_elicitation';
        } else {
            $module = 'spatial_elicitation';
        }

        if ($this->getUser()->getGuardUser()->ownsProblem($variable->getElicitationProblem()->getId())) {
            $action = 'show';
        } else {
            // get the continuous_elicitation/categorical_elicitation id...
            $type = preg_replace('/(?:^|_)(.?)/e', "strtoupper('$1')", $module);
            $elicitation = Doctrine_Core::getTable($type)->findByExpertAndTask($this->getUser()->getGuardUser()->getId(), $request->getParameter('id'));

            // overwrite the request id with the continuous elicitation id
            $request->setParameter('id', $elicitation->getId());

            $action = 'edit';
        }

        $this->forward($module, $action);
    }

    public function executeNew(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getGuardUser()->ownsProblem($request->getParameter('id')));

        $this->form = new VariableForm(null, array('problem_id' => $request->getParameter('id')));
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $var = $request->getParameter('variable');

        $this->form = new VariableForm(null, array('problem_id' => $var['problem_id']));
        $this->processForm($request, $this->form);
        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($variable = Doctrine_Core::getTable('Variable')->find(array($request->getParameter('id'))), sprintf('Object variable does not exist (%s).', $request->getParameter('id')));
        $this->form = new VariableForm($variable, array('problem_id' => $request->getParameter('id')));
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($variable = Doctrine_Core::getTable('Variable')->find(array($request->getParameter('id'))), sprintf('Object variable does not exist (%s).', $request->getParameter('id')));
        $vars = $request->getParameter('variable');
        $this->form = new VariableForm($variable, array('problem_id' => $vars['problem_id']));

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($variable = Doctrine_Core::getTable('Variable')->find(array($request->getParameter('id'))), sprintf('Object variable does not exist (%s).', $request->getParameter('id')));
        $variable->delete();

        $this->redirect($request->getReferer());
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $temp_old_experts = $form->getObject()->getExperts();
        $old_experts = array();
        foreach ($temp_old_experts as $temp) {
            $old_experts[] = $temp->getId();
        }

        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        $elicitation_type = str_replace('_', '', ucwords($form->getObject()->getVariableType())) . 'Elicitation';
        if ($form->isValid()) {
            $values = $form->getValues();
            $new_experts = $values['experts_list'] ? $values['experts_list'] : array();
            if (!$form->isNew()) {
                // This is an update
                // Test whether any experts have been removed.
                // If there are still some experts
                $to_delete = array_diff($old_experts, $new_experts);
                foreach ($to_delete as $id) {
                    // This expert has been deleted!
                    $ce = Doctrine_Core::getTable($elicitation_type)->findByExpertAndTask($id, $values['id']);
                    $ce->delete();
                }
            }
            $variable = $form->save();
            $to_mail = array_diff($new_experts, $old_experts);
            $mailer = $this->getMailer();
            foreach ($to_mail as $id) {
                $mailer->send(new NewTaskMessage($variable, Doctrine::getTable('sfGuardUser')->findOneById($id), $this->generateUrl('tasks_show', array('id' => $variable->getId()), true)));
            }
            $this->getUser()->setFlash('notice', 'Elicitation task saved successfully.');
            $this->redirect('problems/edit?id=' . $variable->getElicitationProblem()->getId());
        }
    }

}
