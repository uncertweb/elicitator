<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditUserForm
 *
 * @author williamw
 */
class EditUserForm extends UserForm {

    //put your code here
    public function configure() {
        parent::configure();

        unset($this['password']);

        

        
    }
    
    

}

?>
