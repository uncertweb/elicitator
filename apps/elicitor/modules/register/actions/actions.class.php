<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actions
 *
 * @author Matthew
 */
class registerActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {
        $this->form = new RegisterForm();
        if ($request->isMethod('post')) {
            $captcha = array(
                'recaptcha_challenge_field' => $request->getParameter('recaptcha_challenge_field'),
                'recaptcha_response_field' => $request->getParameter('recaptcha_response_field'),
            );
            $this->form->bind(array_merge($request->getParameter('sf_guard_user'), array('captcha' => $captcha)));
            if ($this->form->isValid()) {
                $user = $this->form->save();
                $this->getUser()->signIn($this->form->getObject());
                $this->getUser()->setFlash('notice', 'Your account has been successfully created.');
                $this->redirect('@control_panel');
            }
        }
    }

}

?>
