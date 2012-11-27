<?php

/**
 * SpatialElicitation form.
 *
 * @package    elicitor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SpatialElicitationForm extends BaseSpatialElicitationForm {

    public function configure() {
        unset(
                $this['created_at'], $this['updated_at'], $this['expert_id'], $this['enabled'], $this['opt_out'], $this['reason'], $this['distribution_id']
        );

        $this->widgetSchema['variable_id'] = new sfWidgetFormInputHidden();

        $this->widgetSchema['read_briefing_document'] = new sfWidgetFormInputHidden();

        $this->widgetSchema['minimum'] = new sfWidgetFormInputText(array(), array('class' => 'data_input'));
        $this->widgetSchema['maximum'] = new sfWidgetFormInputText(array(), array('class' => 'data_input readonly', 'readonly' => 'readonly'));

        $this->widgetSchema['lower'] = new sfWidgetFormJQuerySlider(array('slider_change' => 'setLowerQuartile(true);', 'onSlide' => 'updateLowerQuartile'));

        $this->widgetSchema['median'] = new sfWidgetFormJQuerySlider(
                        array(
                                'slider_change' => 'setMedian(true);',
                                'onSlide' => 'updateMedian'
                ));

        $this->widgetSchema['upper'] = new sfWidgetFormJQuerySlider(array('slider_change' => 'setUpperQuartile(true);', 'onSlide'=>'updateUpperQuartile'));


        $this->validatorSchema['minimum'] = new sfValidatorNumber(array('required' => false));
        $this->validatorSchema['maximum'] = new sfValidatorNumber(array('required' => false));
        $this->validatorSchema['lower'] = new sfValidatorNumber(array('required' => false));
        $this->validatorSchema['median'] = new sfValidatorNumber(array('required' => false));
        $this->validatorSchema['upper'] = new sfValidatorNumber(array('required' => false));

		// Set labels
		$this->widgetSchema->setLabels(array(
			'lower' => 'Lower quartile',
			'upper' => 'Upper quartile'
		));

        $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback'=> array($this, 'validateValues')))
        );

        /*
         * If this is a 'new' elicitation then set the task id
         */
        if ($this->isNew()) {
            $this->setDefault('variable_id', $this->getOption('task_id'));
            $this->setDefault('read_briefing_document', "0");
        }
    }


    public function validateValues($validator, $values) {

        if(!empty($values['minimum']) && !empty($values['maximum'])) {
            // min and max have been set
            if($values['minimum'] >= $values['maximum']) {
                $error = new sfValidatorError($validator, 'The minimum value must be less than the maximum');
                throw new sfValidatorErrorSchema($validator, array('minimum'=>$error));
            }
        }

        if(!empty($values['lower']) && !empty($values['median'])) {
            // Median and lower percentile set - validate
            if($values['lower'] > $values['median']) {
                $error = new sfValidatorError($validator, 'The lower percentile must be lower than the median');
                throw new sfValidatorErrorSchema($validator, array('lower'=>$error));
            }
        }

        return $values;
    }

}
