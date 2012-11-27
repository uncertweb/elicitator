<?php

/**
 * spatial_elicitation actions.
 *
 * @package    elicitor
 * @subpackage spatial_elicitation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class spatial_elicitationActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->spatial_elicitations = Doctrine_Core::getTable('SpatialElicitation')
                ->createQuery('a')
                ->execute();
    }

    public function executeNew(sfWebRequest $request) {
        $this->form = new SpatialElicitationForm();
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));

        $this->form = new SpatialElicitationForm();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->forward404Unless($spatial_elicitation = Doctrine_Core::getTable('SpatialElicitation')->find(array($request->getParameter('id'))), sprintf('Object spatial_elicitation does not exist (%s).', $request->getParameter('id')));
        $this->task = Doctrine_Core::getTable('Variable')->find(array($spatial_elicitation->getVariable()->getId()));
        $this->form = new SpatialElicitationForm($spatial_elicitation);
    }

    public function executeUpdate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($spatial_elicitation = Doctrine_Core::getTable('SpatialElicitation')->find(array($request->getParameter('id'))), sprintf('Object spatial_elicitation does not exist (%s).', $request->getParameter('id')));
        $this->form = new SpatialElicitationForm($spatial_elicitation);

        $this->processForm($request, $this->form);

        $this->task = Doctrine_Core::getTable('Variable')->find(array($spatial_elicitation->getVariable()->getId()));
        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($spatial_elicitation = Doctrine_Core::getTable('SpatialElicitation')->find(array($request->getParameter('id'))), sprintf('Object spatial_elicitation does not exist (%s).', $request->getParameter('id')));
        $spatial_elicitation->delete();

        $this->redirect('spatial_elicitation/index');
    }

    public function executeData(sfWebRequest $request) {
        $min = $request->getParameter('min');
        $max = $request->getParameter('max');
        $lower = $request->getParameter('lower');
        $upper = $request->getParameter('upper');
        $median = $request->getParameter('median');

        $this->getResponse()->setHttpHeader('Content-Type', 'application/json; charset=utf-8');

        $pdf = $this->fitPDF($min, $max, $lower, $median, $upper);
        $output = array('name' => $pdf['name'], 'parameters' => $pdf['parameters']);
        
        $d = new Distribution();
        $d->setName($pdf['name']);
        //var_dump($pdf['parameters']);
        foreach ($pdf['parameters'] as $id => $p) {

            $p1 = new DistributionParameter();
            $p1->setName($id);
            $p1->setValue($p);
            $d->Parameters[] = $p1;
        }
        //var_dump($d);
        return $this->renderText($d->toJSON());
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            if ($form->isNew()) {
                $form->getObject()->setExpert($this->getUser()->getGuardUser());
            }
            $spatial_elicitation = $form->save();

            if ($spatial_elicitation->getProgress() == 100) {
                $id = $request->getParameter('id');
                $pdf = $this->fitPdf($spatial_elicitation->getMinimum(), $spatial_elicitation->getMaximum(), $spatial_elicitation->getLower(), $spatial_elicitation->getMedian(), $spatial_elicitation->getUpper());

                if ($spatial_elicitation->getDistribution()) {
                    $distn = $spatial_elicitation->getDistribution();
                    $distn->getParameters()->delete();
                } else {
                    $distn = new Distribution();
                }

                if (!is_null($pdf)) {
                    $distn->setName($pdf['name']);

                    $params = $pdf['parameters'];
                    foreach ($params as $name => $value) {
                        $p = new DistributionParameter();
                        $p->setName($name);
                        $p->setValue($value);
                        $distn->Parameters[] = $p;
                    }
                    // save the distribution and its parameters
                    $distn->save();
                    $spatial_elicitation->setDistribution($distn);
                    $spatial_elicitation->save();

                    // commence pooling algorithm
                    //$this->poolDistributions($spatial_elicitation->getVariableId());
                } else {
                    $spatial_elicitation->setDistribution(null);
                }

            }

            $this->redirect('spatial_elicitation/edit?id=' . $spatial_elicitation->getId());
        }
    }

    private function fitPdf($min, $max, $fq, $med, $tq) {
        $script_name = sfConfig::get('sf_lib_dir') . '/r/mpdf.R';
        $rcmd = '"' . sfConfig::get('app_rscript_path') . '"' . "\Rscript $script_name ";
        
        $rcmd .= $max . ' ';
        $rcmd .= $min . ' ';
        $rcmd .= $med . ' ';
        $rcmd .= $fq . ' ';
        $rcmd .= $tq;
        $results = array();
        $out = array();
        if (!empty($results)) {
            $dist_name = $results[0];
            return array('name' => $dist_name, 'parameters' => array('mean'=>$results[1], 'standardDeviation'=>$results[2]));
        }
    }

}
