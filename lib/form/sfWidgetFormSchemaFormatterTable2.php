<?php

class sfWidgetFormSchemaFormatterTable2 extends sfWidgetFormSchemaFormatter {

    protected
    $rowFormat = "<tr>\n  <th>%label%</th>\n  <td>%field%%hidden_fields%</td>\n</tr>%error%%help%\n",
    $errorListFormatInARow = '<tr class="error_list"><td>&nbsp;</td><td>%errors%</td></tr>',
    $errorRowFormatInARow = ' %error% ',
    $namedErrorRowFormatInARow = '%name%: %error%',
    $helpFormat = '<tr><td>&nbsp;</td><td class="form_help">%help%</td>',
    $decoratorFormat = "<table class='form'>\n  %content%%errors%</table>",
    $requiredLabel = '<span> *</span>',
    $requiredLabelClass = 'required';

    public function generateLabel($name, $attributes = array()) {
        // loop up to find the "required_fields" option
        if ($this->checkRequired($name)) {
            $attributes['class'] = isset($attributes['class']) ?
                    $attributes['class'] . ' ' . $this->requiredLabelClass :
                    $this->requiredLabelClass;
        }

        return parent::generateLabel($name, $attributes);
    }

    public function generateLabelName($name) {
        $label = parent::generateLabelName($name);
        if ($this->checkRequired($name)) {
            $label .= $this->requiredLabel;
        }
        return $label;
    }

    protected

    function checkRequired($name) {
        // loop up to find the "required_fields" option
        $widget = $this->widgetSchema;
        do {
            $requiredFields = (array) $widget->getOption('required_fields');
        } while ($widget = $widget->getParent());

        // add a class (non-destructively) if the field is required
        if (in_array($this->widgetSchema->generateName($name), $requiredFields)) {
            return true;
        }

        return false;
    }

}

?>