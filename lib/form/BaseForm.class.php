<?php

/**
 * Base project form.
 * 
 * @package    elicitor
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony {

    public function __construct($object = null, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);

        // tell the widget schema which fields are required
        $this->widgetSchema->addOption(
                'required_fields',
                $this->getRequiredFields()
        );
    }

    protected function getRequiredFields(sfValidatorSchema $validatorSchema = null, $format = null) {
        if (is_null($validatorSchema)) {
            $validatorSchema = $this->validatorSchema;
        }

        if (is_null($format)) {
            $format = $this->widgetSchema->getNameFormat();
        }

        $fields = array();

        foreach ($validatorSchema->getFields() as $name => $validator) {
            $field = sprintf($format, $name);
            if ($validator instanceof sfValidatorSchema) {
                // recur
                $fields = array_merge(
                                $fields,
                                $this->getRequiredFields($validator, $field . '[%s]')
                );
            } else if ($validator->getOption('required')) {
                // this field is required
                $fields[] = $field;
            }
        }

        return $fields;
    }

}
