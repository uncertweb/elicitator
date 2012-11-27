<?php

/**
 * continuous_elicitation actions.
 *
 * @package    elicitor
 * @subpackage continuous_elicitation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categorical_elicitationActions extends sfActions {

    public function executeShow(sfWebRequest $request) {
        $variable_id = $request->getParameter('id');
        $variable = Doctrine_Core::getTable('Variable')->findOneById($variable_id);
        $this->variable = $variable;
    }

	public function executeOptOut(sfWebRequest $request) {
		$ce = Doctrine::getTable('CategoricalElicitation')->findOneById($request->getParameter('id'));
		$ce->setOptOut(true);
		$ce->setReason($request->getParameter('reason'));
		$ce->save();
		
		$this->redirect($request->getReferer());
	}

    public function executeDisable(sfWebRequest $request) {
        // disable the expert for this elicitation
        $ce = Doctrine::getTable('CategoricalElicitation')->findOneById($request->getParameter('id'));
        $ce->setEnabled(false);
        $ce->save();

        $this->redirect($request->getReferer());
    }

    public function executeEnable(sfWebRequest $request) {
        // enable the expert for this elicitation
        $ce = Doctrine::getTable('CategoricalElicitation')->findOneById($request->getParameter('id'));
        $ce->setEnabled(true);
        $ce->save();
        $this->redirect($request->getReferer());
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($categorical_elicitation = Doctrine_Core::getTable('CategoricalElicitation')->find(array($request->getParameter('id'))), sprintf('Object continuous_elicitation does not exist (%s).', $request->getParameter('id')));
        $this->forwardUnless($categorical_elicitation->expert_id == $this->getUser()->getGuardUser()->getId(), 'default', 'secure');
        $this->task = Doctrine_Core::getTable('Variable')->find(array($categorical_elicitation->getVariable()->getId()));
        $this->form = new CategoricalElicitationForm($categorical_elicitation);
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->form = new CategoricalElicitationForm();
        $values = $request->getParameter('categorical_elicitation');
        $this->task = Doctrine_Core::getTable('Variable')->find(array($values['variable_id']));
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeNew(sfWebRequest $request) {
        $this->task = Doctrine_Core::getTable('Variable')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->task);
        $this->form = new ContinuousElicitationForm(null, array('task_id' => $request->getParameter('id')));
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($categorical_elicitation = Doctrine_Core::getTable('CategoricalElicitation')->find(array($request->getParameter('id'))), sprintf('Object continuous_elicitation does not exist (%s).', $request->getParameter('id')));

        $this->form = new CategoricalElicitationForm($categorical_elicitation);

        $this->task = Doctrine_Core::getTable('Variable')->find(array($categorical_elicitation->getVariable()->getId()));

        $this->processForm($request, $this->form);

    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            if ($form->isNew()) {
                $form->getObject()->setExpert($this->getUser()->getGuardUser());
            }

            $ep = $form->save();

            $this->getUser()->setFlash('notice', 'Elicitation submitted successfully');
            $this->redirect('control_panel');
        }
    }

}
