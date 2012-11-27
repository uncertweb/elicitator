<?php

/**
 * problem actions.
 *
 * @package    elicitor
 * @subpackage problem
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class problemsActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->pager = new sfDoctrinePager(
                        'ElicitationProblem',
                        10
        );
        $query = $this->getUser()->getGuardUser()->getElicitationProblemsQuery();
        if ($request->hasParameter('filter')) {
            $filter = "%" . $request->getParameter('filter') . "%";
            $query->andwhere("name like ? OR description like ?", array($filter, $filter));
        }
        $this->pager->setQuery($query);
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->filter = $request->getParameter('filter');
    }

    public function executeShow(sfWebRequest $request) {
        $this->problem = $this->getRoute()->getObject();
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new ElicitationProblemForm(null, array('user' => $this->getUser()));
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new ElicitationProblemForm(null, array('user' => $this->getUser()));

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($elicitation_problem = Doctrine_Core::getTable('ElicitationProblem')->find(array($request->getParameter('id'))), sprintf('Object elicitation_problem does not exist (%s).', $request->getParameter('id')));
        $this->problem_id = $request->getParameter('id');
        $this->form = new ElicitationProblemForm($elicitation_problem, array('user' => $this->getUser()));
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($elicitation_problem = Doctrine_Core::getTable('ElicitationProblem')->find(array($request->getParameter('id'))), sprintf('Object elicitation_problem does not exist (%s).', $request->getParameter('id')));
        $this->form = new ElicitationProblemForm($elicitation_problem, array('user' => $this->getUser()));

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($elicitation_problem = Doctrine_Core::getTable('ElicitationProblem')->find(array($request->getParameter('id'))), sprintf('Object elicitation_problem does not exist (%s).', $request->getParameter('id')));
        $elicitation_problem->delete();

        $this->redirect('user_show', $this->getUser()->getGuardUser());
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            if ($form->isNew()) {
                $form->getObject()->setProblemOwner($this->getUser()->getGuardUser());
            }

            $values = $form->getValues();
            $elicitation_problem = $form->save();

            if ($values['attached_file']) {
                $file_path = $values['attached_file']->getPath() . $elicitation_problem->getAttachedFile();
                $thumb_name = sha1($file_path) . '.jpg';

                $thumb_dir = $this->getUser()->getAttribute('upload_dir') . sfConfig::get('app_thumbnail_upload_dir');
                $thumb_path = $thumb_dir . $thumb_name;

                $creator = new mwThumbnailCreator(250, 250);
                $creator->loadData($file_path);
                $file_created = $creator->create($thumb_path);
                if ($file_created) {
                    // update the form
                    $elicitation_problem->setThumbnail($thumb_name);
                    $elicitation_problem->save();
                }
            }


            $this->getUser()->setFlash('notice', 'Elicitation problem saved successfully');

            $this->redirect('problems_edit', $elicitation_problem);
        }
    }

}
