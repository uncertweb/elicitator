<?php

class sfJqueryFormValActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    
    // get the name of the form object we are building the validation for
    $form = $request->getParameter('form');
    
    // make sure that the form class specified actually exists
    // and it is really a symfony form
    $this->forward404Unless($this->isValidSfFormName($form));
    
    // errors have to be disabled because any kind of warning message will break
    // the outputted javascript
    error_reporting('E_NONE');    
    
    // create an instance of the sfJqueryFormValidationRules object
    $this->sf_jq_rules = new sfJqueryFormValidationRules(new $form);
    
    // add embedded forms
    foreach($request->getParameter('embedded_form') as $name => $form)
    {
    	if($this->isValidSfFormName($form))
    	{
    		$this->sf_jq_rules->addEmbeddedForm($name, new $form);
    	}
    }
    
  }
  
  
  private function isValidSfFormName($form_class_name)
  {
  	return class_exists($form_class_name) && is_subclass_of($form_class_name, 'sfForm');
  }
  
}
