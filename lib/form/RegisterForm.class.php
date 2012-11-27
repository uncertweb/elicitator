<?php

class RegisterForm extends UserForm {

    public function configure() {
        parent::configure();
        $this->widgetSchema['captcha'] = new sfWidgetFormReCaptcha(array(
                    'public_key' => sfConfig::get('app_recaptcha_public_key')
                ));

        $this->validatorSchema['captcha'] = new sfValidatorReCaptcha(array(
                    'private_key' => sfConfig::get('app_recaptcha_private_key')
                ));

        $this->widgetSchema['captcha']->setLabel('Security question');

        // Setup proper password validation with confirmation
        $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
        $this->validatorSchema['password']->setOption('required', true);
        $this->widgetSchema['confirm'] = new sfWidgetFormInputPassword(array('label' => 'Confirm password'));
        $this->validatorSchema['confirm'] = clone $this->validatorSchema['password'];
        $this->widgetSchema->moveField('confirm', 'after', 'password');

        $this->widgetSchema->setHelp('username', 'An email will be sent to this address with activation instructions.');
        $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'confirm', array(), array('invalid' => 'Passwords don\'t match')));
    }

}
