<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actions
 *
 * @author williamw
 */
require_once(sfConfig::get('sf_plugins_dir') . '/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions {

    public function executePassword($request)
    {
        $this->form = new RequestPasswordForm();
        if($request->isMethod('post'))
        {
            // process request
            $this->form->bind($request->getParameter('request_password'));
          if ($this->form->isValid()) {
                // check that the email address exists in the system
                $exists = Doctrine::getTable('sfGuardUser')->findOneByUsername($this->form->getValue('email_address'));
                if($exists) {
                    // found
                    $new_password = $exists->resetPassword();
                    $mailer = $this->getMailer();
                    $mailer->sendNextImmediately()->send(new ResetPasswordMessage($exists, $new_password));
                    $this->getUser()->setFlash('notice', 'Your password has been reset, you should receive an email at your registered address with your new password.');
                    $this->redirect('sf_guard_signin');
                } else {
                    $this->getUser()->setFlash('alert', 'The email address you entered was not found in our system. Please try again.', false);
                }
            } else {
                $schema = $this->form->getFormFieldSchema();

                $this->getUser()->setFlash('alert', $schema['email_address']->getError(), false);
            }
        }
    }

    public function executeSignin($request) {
        $user = $this->getUser();
        if ($user->isAuthenticated()) {
            return $this->redirect('@homepage');
        }

        $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
        $this->form = new $class();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('signin'));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->getUser()->signin($values['user'], array_key_exists('remember', $values) ? $values['remember'] : false);

                // always redirect to a URL set in app.yml
                // or to the referer
                // or to the homepage
                $signinUrl = sfConfig::get('app_sf_guard_plugin_success_signin_url', $user->getReferer($request->getReferer()));

                $test = $this->getController()->genUrl('@info_page', true);

                /*
                echo $signinUrl . '<br/>';
                echo $test;
                die;
                */
                if(str_replace('/', '', $this->getController()->genUrl('@info_page', true)) == str_replace('/', '', $signinUrl)) {
                    $signinUrl = $this->getController()->genUrl('@control_panel', true);
                }

                return $this->redirect('' != $signinUrl ? $signinUrl : '@homepage');
            }
        } else {
            if ($request->isXmlHttpRequest()) {
                $this->getResponse()->setHeaderOnly(true);
                $this->getResponse()->setStatusCode(401);

                return sfView::NONE;
            }

            // if we have been forwarded, then the referer is the current URL
            // if not, this is the referer of the current request
            $user->setReferer($this->getContext()->getActionStack()->getSize() > 1 ? $request->getUri() : $request->getReferer());

            $module = sfConfig::get('sf_login_module');
            if ($this->getModuleName() != $module) {
                return $this->redirect($module . '/' . sfConfig::get('sf_login_action'));
            }

            $this->getResponse()->setStatusCode(401);
        }
    }

    public function executeSignout($request) {
        $this->getUser()->signOut();

        $signoutUrl = sfConfig::get('app_sf_guard_plugin_success_signout_url', $request->getReferer());

        $this->redirect('' != $signoutUrl ? $signoutUrl : '@homepage');
    }

    public function executeSecure($request) {
        $this->getResponse()->setStatusCode(403);
    }

}

?>
