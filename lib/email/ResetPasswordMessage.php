<?php
class ResetPasswordMessage extends ElicitatorMessageBase
{
  public function __construct($user, $password)
  {
    $subject  = 'Your password has been reset';
    $name     = $user->getFirstName();
    $username = $user->getUsername();
    $id       = $user->getId();
    $body = <<<EOF
Hello $name,

We have reset the password on your account for you. You can log in at the following URL:

  http://elicitator.uncertweb.org/login

With the following details:

  username: $username
  password: $password

It is recommended that you change your password immediately for security reasons, this can be done at the following URL:

  http://elicitator.uncertweb.org/user/$id/edit

The Elicitator

EOF;

    parent::__construct($subject, $body);

    $this->setTo(array($user->getUsername() => $user->getFullName()));
  }
}