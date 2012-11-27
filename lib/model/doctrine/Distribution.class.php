<?php

/**
 * Distribution
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    elicitor
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Distribution extends BaseDistribution
{

    public function toJSON() {
        $str = '{ "' . ucwords($this->name) . 'Distribution": {';
        $params = $this->Parameters;
        for($i = 0; $i < sizeof($params); $i++) {
            $str .= '"' . $params[$i]->name . '": [' . $params[$i]->value . ']';
            if($i < sizeof($params) - 1) {
                $str .= ', ';
            }
        }
        return $str .= '}}';
    }

    public function toString() {
        $str = ucwords($this->getName()) . ' (';
        $params = $this->getParameters();
        for($i = 0; $i < sizeof($params); $i++) {
            $str .= $params[$i]->getValue();
            if($i < sizeof($params) - 1) {
                $str .= ', ';
            }
        }
        return $str .= ')';
    }

}
