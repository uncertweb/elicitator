<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class userComponents extends sfComponents {

    public function executeLogin(sfWebRequest $request) {
        $class = sfConfig::get('app_sf_guard_plugin_signin_form', 'sfGuardFormSignin');
        $this->form = new $class();
        $this->form->getWidgetSchema()->setFormFormatterName('div');
        $this->form->getWidgetSchema()->setLabels(array('remember'=>'remember me?', 'username'=>'Email address'));
    }

    public function executeOptions(sfWebRequest $request) {
        
    }

}

?>
