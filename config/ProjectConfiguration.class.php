<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array('sfDoctrinePlugin', 'sfDoctrineGuardPlugin', 'sfDoctrineGuardLoginHistoryPlugin', 'sfFormExtraPlugin', 'sfFormExtra2Plugin', 'fzTagPlugin'));
    // Set the default formatter for all forms
    sfWidgetFormSchema::setDefaultFormFormatterName('table2');
  }
}
