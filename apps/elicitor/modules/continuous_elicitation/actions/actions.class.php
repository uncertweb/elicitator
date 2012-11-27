<?php

/**
 * continuous_elicitation actions.
 *
 * @package    elicitor
 * @subpackage continuous_elicitation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class continuous_elicitationActions extends sfActions {

    public function executeShow(sfWebRequest $request) {
        $variable_id = $request->getParameter('id');
        $variable = Doctrine_Core::getTable('Variable')->findOneById($variable_id);
        $this->variable = $variable;
        $min = null;
        $max = null;
        foreach ($variable->getResults() as $res) {
            if ($res->getProgress() == 100) {
                if (empty($min)) {
                    $min = $res->getMinimum();
                }
                if ($max == null) {
                    $max = $res->getMaximum();
                }
                if ($res->getMinimum() < $min) {
                    $min = $res->getMinimum();
                }
                if ($res->getMaximum() > $max) {
                    $max = $res->getMaximum();
                }
            }
        }

        $this->abs_min = $min;
        $this->abs_max = $max;
    }

	public function executeOptOut(sfWebRequest $request) {
		$ce = Doctrine::getTable('ContinuousElicitation')->findOneById($request->getParameter('id'));
		$ce->setOptOut(true);
		$ce->setReason($request->getParameter('reason'));
		$ce->save();

		$this->redirect($request->getReferer());
	}

    public function executeDisable(sfWebRequest $request) {
        // disable the expert for this elicitation
        $ce = Doctrine::getTable('ContinuousElicitation')->findOneById($request->getParameter('id'));
        $ce->setEnabled(false);
        $ce->save();

        // do pooling again :-(
        $this->poolDistributions($ce->getVariable()->getId());

        $this->redirect($request->getReferer());
    }

    public function executeEnable(sfWebRequest $request) {
        // enable the expert for this elicitation
        $ce = Doctrine::getTable('ContinuousElicitation')->findOneById($request->getParameter('id'));
        $ce->setEnabled(true);
        $ce->save();
        // do pooling again :-(
        $this->poolDistributions($ce->getVariable()->getId());
        $this->redirect($request->getReferer());
    }

    /*
     * Fits a PDF given a set of parameters
     * Updates the elicitation problem with the correct PDF
     */

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

    private function processParameters($parameters, $dist_name) {
        $params = array();
        $splits = explode(' ', $parameters);
        if (sizeof($splits) == 1) {
            $params['degreesOfFreedom'] = $splits[0];
        } else {
            // not a student-t variant
            if ($dist_name == 'Normal') {
                // normal distribution
                $params['mean'] = $splits[0];
                $params['standardDeviation'] = $splits[1];
            } else if ($dist_name == 'LogNormal') {
                $params['location'] = $splits[0];
                $params['scale'] = $splits[1];
            } else if ($dist_name == 'Beta') {
                // beta distribution
                $params['alpha'] = $splits[0];
                $params['beta'] = $splits[1];
            } else if ($dist_name == 'Gamma') {
                // gamma
                $params['shape'] = $splits[0];
                $params['scale'] = 1 / $splits[1];
            } else if ($dist_name == 'StudentT') {
                // student t
                $params['degreesOfFreedom'] = $splits[0];
                $params['nonCentralityParameter'] = $splits[1];
            }
        }
        return $params;
    }

    private function fitPDF($min, $max, $lower, $median, $upper) {
        $script_name = sfConfig::get('sf_lib_dir') . '/r/multi.pdf.fit.R';
        $rcmd = '"' . sfConfig::get('app_rscript_path') . '"' . "\Rscript $script_name ";
        $rcmd .= $min . ' ';
        $rcmd .= $max . ' ';
        $rcmd .= $lower . ' ';
        $rcmd .= $median . ' ';
        $rcmd .= $upper;
        $results = array();
        exec($rcmd, $results);
        if (!empty($results)) {
            $dist_name = $results[0];
            $params = $this->processParameters($results[1], $dist_name);
            return array('name' => $dist_name, 'parameters' => $params);
        }

        return null;
    }

    public function executeIndex(sfWebRequest $request) {
        $this->continuous_elicitations = Doctrine_Core::getTable('ContinuousElicitation')
                ->createQuery('a')
                ->execute();
    }

    public function executeNew(sfWebRequest $request) {
        $this->task = Doctrine_Core::getTable('Variable')->find(array($request->getParameter('id')));
        $this->forward404Unless($this->task);
        $this->form = new ContinuousElicitationForm(null, array('task_id' => $request->getParameter('id')));
    }

    public function executeCreate(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfRequest::POST));
        $this->form = new ContinuousElicitationForm();
        $values = $request->getParameter('continuous_elicitation');
        $this->task = Doctrine_Core::getTable('Variable')->find(array($values['variable_id']));
        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }

    public function executeEdit(sfWebRequest $request) {
        // id == task id
        // current user == expert id.

        $this->forward404Unless($continuous_elicitation = Doctrine_Core::getTable('ContinuousElicitation')->find(array($request->getParameter('id'))), sprintf('Object continuous_elicitation does not exist (%s).', $request->getParameter('id')));
        $this->forwardUnless($continuous_elicitation->expert_id == $this->getUser()->getGuardUser()->getId(), 'default', 'secure');
        $this->task = Doctrine_Core::getTable('Variable')->find(array($continuous_elicitation->getVariable()->getId()));
        $this->form = new ContinuousElicitationForm($continuous_elicitation);
    }

    public function executeUpdate(sfWebRequest $request) {

        $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
        $this->forward404Unless($continuous_elicitation = Doctrine_Core::getTable('ContinuousElicitation')->find(array($request->getParameter('id'))), sprintf('Object continuous_elicitation does not exist (%s).', $request->getParameter('id')));

        $this->form = new ContinuousElicitationForm($continuous_elicitation);

        $this->task = Doctrine_Core::getTable('Variable')->find(array($continuous_elicitation->getVariable()->getId()));

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();

        $this->forward404Unless($continuous_elicitation = Doctrine_Core::getTable('ContinuousElicitation')->find(array($request->getParameter('id'))), sprintf('Object continuous_elicitation does not exist (%s).', $request->getParameter('id')));
        $continuous_elicitation->delete();

        $this->redirect('continuous_elicitation/index');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            if ($form->isNew()) {
                $form->getObject()->setExpert($this->getUser()->getGuardUser());
            }

            $ep = $form->save();

            if ($ep->getProgress() == 100) {
                $id = $request->getParameter('id');
                $pdf = $this->fitPDF($ep->getMinimum(), $ep->getMaximum(), $ep->getLower(), $ep->getMedian(), $ep->getUpper());


                if ($ep->getDistribution()) {
                    $distn = $ep->getDistribution();
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
                    $ep->setDistribution($distn);
                    $ep->save();

                    // commence pooling algorithm
                    $this->poolDistributions($ep->getVariableId());
                } else {
                    $ep->setDistribution(null);
                }
            }
            $this->getUser()->setFlash('notice', 'Elicitation submitted successfully');
            $this->redirect('control_panel');
        }
    }

    private function poolDistributions($task_id) {
        // Load the task
        $task = Doctrine_Core::getTable('Variable')->findOneById($task_id);

        // generate R command
        $cmd = '"expert.pdf <- list(';

        //TODO add the actual pooling code here.
        // Load the latest distribution
        $temp_results = $task->Results;
        $results = Array();
        // filter null distributions
        foreach ($temp_results as $temp_r) {
            if (!is_null($temp_r->distribution_id) && $temp_r->getEnabled()) {
                $results[] = $temp_r;
            }
        }
        $exp_str = 'exp = c(';
        $min_str = 'min = c(';
        $max_str = 'max = c(';
        $pdf_str = 'pdf = c(';
        $par1_str = 'par1 = c(';
        $par2_str = 'par2 = c(';
        for ($i = 0; $i < sizeof($results); $i++) {
            $r = $results[$i];
            $exp_str .= $i;
            $min_str .= $r->minimum;
            $max_str .= $r->maximum;
            $pdf_str .= '\"' . $r->Distribution->name . '\"';
            //TODO Are these returned in the correct order?
            $par1_str .= $r->Distribution->Parameters[0]->value;
            //TODO What if par2 is null?
            $par2_str .= $r->Distribution->Parameters[1]->value;

            if ($i < sizeof($results) - 1) {
                $exp_str .= ',';
                $min_str .= ',';
                $max_str .= ',';
                $pdf_str .= ',';
                $par1_str .= ',';
                $par2_str .= ',';
            }
        }

        $exp_str .= ')';
        $min_str .= ')';
        $max_str .= ')';
        $pdf_str .= ')';
        $par1_str .= ')';
        $par2_str .= ')';

        $cmd .= $exp_str . ', ' . $min_str . ', ' . $max_str . ', ' . $pdf_str . ', ' . $par1_str . ', ' . $par2_str;

        $cmd .= ')"';

        $script_name = sfConfig::get('sf_lib_dir') . '/r/Equal.weight.pooling.R';   // pooling script
        $rcmd = '"' . sfConfig::get('app_rscript_path') . '"' . "\Rscript $script_name ";
        $rcmd .= $cmd;

        $r_results = Array(); // array to hold results from R
        //echo $rcmd; die;

        exec($rcmd, $r_results);



        if (!empty($r_results)) {
            $distribution = new Distribution();
            $distribution->setName($r_results[0]);
            $params = $this->processParameters($r_results[1], $r_results[0]);
            foreach ($params as $name => $value) {
                $param = new DistributionParameter();
                $param->setName($name);
                $param->setValue($value);
                $distribution->Parameters[] = $param;
            }
        } else {
            $distribution = null;
        }



        //$distribution->save();
        $task->setDistribution($distribution);
        $task->save();
    }

}
