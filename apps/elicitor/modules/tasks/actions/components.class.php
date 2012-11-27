<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of components
 *
 * @author williamw
 */
class tasksComponents extends sfComponents {

    public function executeExpert_plot(sfWebRequest $request) {
        $this->elicitation_results = Doctrine_Core::getTable('ContinuousElicitation')->findByExpertAndTask($this->getVar('expert_id'), $this->getVar('variable_id'));
        $this->distribution = $this->elicitation_results->getDistribution()->toJSON();

        $this->expert = Doctrine_Core::getTable('sfGuardUser')->findOneById($this->getVar('expert_id'));
        $this->abs_min = $this->getVar('abs_min');
        $this->abs_max = $this->getVar('abs_max');
    }

    public function executeCategorical_plot(sfWebRequest $request) {
        $this->elicitation_results = Doctrine_Core::getTable('CategoricalElicitation')->findByExpertAndTask($this->getVar('expert_id'), $this->getVar('variable_id'));
        $this->expert = Doctrine_Core::getTable('sfGuardUser')->findOneById($this->getVar('expert_id'));
    }

}
?>
