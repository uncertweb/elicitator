<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewExpertForm
 *
 * @author williamw
 */
class ExpertForm extends UserForm {

    //put your code here
    public function configure() {
        parent::configure();
        unset($this['password']);
    }

}

?>
