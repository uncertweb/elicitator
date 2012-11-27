<?php

class sfJqueryFormValRouting
{
  static public function addRoute(sfEvent $event)
  {
    $event->getSubject()->prependRoute('sf_jquery_form_validation', new sfRoute('/sfJqueryFormVal/:form', array('module' => 'sfJqueryFormVal', 'action' => 'index')));
  }
}
