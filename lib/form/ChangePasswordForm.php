<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChangePasswordForm
 *
 * @author williamw
 */
class ChangePasswordForm extends UserForm {

    public function configure() {
        parent::configure();
        unset(
                $this['first_name'],
                $this['last_name'],
                $this['username'],
                $this['password'],
                $this['institute'],
                $this['web_address'],
                $this['expertise']
        );
        $this->widgetSchema['new_password'] = new sfWidgetFormInputPassword();
        //$this->widgetSchema->setHelp('new_password', 'Leave this field blank if you do not want to change your password');
        $this->validatorSchema['new_password'] = new sfValidatorString(array('required' => false, 'min_length'=>6), array('min_length' => 'The password you chose is too short.'));
        $this->widgetSchema->setHelp('new_password', 'Minimum of 6 characters');

        $this->widgetSchema['confirm_new_password'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['confirm_new_password'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema['current_password'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['current_password'] = new sfValidatorString(array('required' => true));
        $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback' => array($this, 'checkPassword')))
        );
    }

    public function checkPassword($validator, $values) {
        // have they entered a new password?
        if ($values['new_password'] != null) {
            // test that they are equal
            if ($values['new_password'] != $values['confirm_new_password']) {
                $error = new sfValidatorError($validator, 'The passwords do not match');
                throw new sfValidatorErrorSchema($validator, array('new_password'=>$error, 'confirm_new_password'=>$error));
            }
            $values['password'] = $values['new_password'];
        }

        // test the entered password against the one of the current user
        if (!$this->getOption('user')->checkPassword($values['current_password'])) {
            $error = new sfValidatorError($validator, 'The password you entered is not correct');
            throw new sfValidatorErrorSchema($validator, array('current_password'=>$error));
        }
        return $values;
    }

}

?>
