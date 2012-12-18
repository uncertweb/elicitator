<?php

class RequestPasswordForm extends sfForm
{

  public function configure()
  {
    $this->setWidgets(array(
      'email_address' => new sfWidgetFormInput(array(

      ))
    ));

    $this->widgetSchema->setNameFormat('request_password[%s]');

    $this->validatorSchema['email_address'] = new sfValidatorEmail(array(

    ), array(
      'invalid'   => 'You entered an invalid email address.',
      'required'  => 'Please enter an email address.'
    ));
  }

}