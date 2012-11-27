<?php

/**
 * VariableExpert
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    elicitor
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class VariableExpert extends BaseVariableExpert {

    public function postInsert($event) {
        // for each expert assigned, create a continuous elicitation...
        if ($this->getVariable()->getVariableType() == 'Continuous') {
            // Create new ContinuousElicitation
            $ce = new ContinuousElicitation();
            // Set the expert
            $ce->setExpertId($this->getExpertId());
            // Set the variable
            $ce->setVariableId($this->getVariableId());
            // Save
            $ce->save();
        } elseif ($this->getVariable()->getVariableType() == 'Categorical') {
            // Create new ContinuousElicitation
            $ce = new CategoricalElicitation();
            // Set the expert
            $ce->setExpertId($this->getExpertId());
            // Set the variable
            $ce->setVariableId($this->getVariableId());
            // Save
            $ce->save();
        } else {
            // Spatial
            $se = new SpatialElicitation();
            $se->setExpertId($this->getExpertId());
            $se->setVariableId($this->getVariableId());
            $se->save();
        }
    }

}