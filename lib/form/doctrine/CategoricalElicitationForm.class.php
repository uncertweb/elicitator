<?php

/**
 * CategoricalElicitation form.
 *
 * @package    elicitor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CategoricalElicitationForm extends BaseCategoricalElicitationForm
{
  public function configure()
  {
      unset(
                $this['created_at'],
                $this['updated_at'],
                $this['expert_id'],
                $this['distribution_id'],
				$this['enabled'],
				$this['opt_out'],
				$this['reason']
        );

        $this->widgetSchema['variable_id'] = new sfWidgetFormInputHidden();

        $this->widgetSchema['read_briefing_document'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['results'] = new sfWidgetFormInputHidden();
  }
}
