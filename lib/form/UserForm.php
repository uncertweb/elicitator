<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserForm
 *
 * @author williamw
 */
class UserForm extends sfGuardUserForm {

    public function configure() {
        // Remove all widgets we don't want to show
        unset(
                $this['is_active'],
                $this['is_super_admin'],
                $this['updated_at'],
                $this['groups_list'],
                $this['permissions_list'],
                $this['last_login'],
                $this['created_at'],
                $this['salt'],
                $this['algorithm'],
                $this['email_address'],
                $this['experts_list'],
                $this['variable_list']
        );



        $this->widgetSchema['username']->setLabel('Email address');
        $this->validatorSchema['username'] = new sfValidatorAnd(array($this->validatorSchema['username'], new sfValidatorEmail(array(), array('invalid' => 'The email address is invalid.', 'required' => 'A valid email address is required'))));

        $this->validatorSchema['password'] = new sfValidatorAnd(array($this->validatorSchema['password'], new sfValidatorString(array('min_length' => 6), array('min_length' => 'The password you chose is too short, a minimum of 6 characters is required'))));
        $this->widgetSchema->setHelp('password', 'Minimum of 6 characters');


        // $this->widgetSchema['remember']->setLabel('remember me?');
    }

}

?>
