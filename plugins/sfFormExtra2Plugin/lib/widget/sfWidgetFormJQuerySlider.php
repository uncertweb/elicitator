<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfWidgetFormSlider
 *
 * @author williamw
 */
class sfWidgetFormJQuerySlider extends sfWidgetForm {

    public function configure($options = array(), $attributes = array()) {

        $this->addOption('min', 0);
        $this->addOption('max', 100);
        $this->addOption('step', 0.01);
        $this->addOption('readonly', true);

        $this->addOption('onSlide', '');

        // Javascript config
        $this->addOption('config', '{ }');
        // Allows the user to add custom code to the change event
        $this->addOption('slider_change', '');
        // Allows the user to append JavaScript to the keyup function
        $this->addOption('key_change', '');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $id = $this->generateId('slider_' . $name);
        $value_text = '';
        if($value != null) {
            $value_text = 'value: ' . $value . ',';
        }
        $config = array('type' => 'text', 'name' => $name, 'value' => $value, 'class' => 'slider_input');
        if($this->getOption('readonly')) {
            $config['class'] .= ' readonly';
            $config['readonly'] = 'readonly';
        }
        return "<div class='slider_input' id='$id'></div>" .
        $this->renderTag('input', $config) .
        sprintf(<<<EOF
<script type="text/javascript">
    jQuery(document).ready(function() {
        // Update function for textbox
        jQuery("#%s").change(function(event) {
            var value = $(this).val();
            if(value !== '' && !isNaN(value)) {
                jQuery("#%s").slider('value', value);
            }
            %s
        });

        jQuery("#%s").slider({
            min: %s,
            max: %s,
            step: %s,
            disabled: %s,
            animate: true,
            %s
            slide: function(event, ui) {
                jQuery('#%s').val(ui.value);
                %s(event, ui);
            },
            change: function() {
                jQuery('#%s').val(jQuery(this).slider('value'));
                %s;
            }
        }, %s
        );
    });
</script>
EOF
                ,
                $this->generateId($name),
                $this->generateId('slider_' . $name),
                $this->getOption('key_change'),
                $this->generateId('slider_' . $name),
                $this->getOption('min'),
                $this->getOption('max'),
                $this->getOption('step'),
                $this->getOption('readonly'),
                $value_text,
                $this->generateId($name),
                $this->getOption('onSlide'),
                $this->generateId($name),
                $this->getOption('slider_change'),
                $this->getOption('config')
        );
    }

    /**
     * Gets the stylesheet paths associated with the widget.
     *
     * @return array An array of stylesheet paths
     */
    public function getStylesheets() {
        return array('/sfFormExtra2Plugin/css/jquery.slider.css' => 'all');
    }

    /**
     * Gets the JavaScript paths associated with the widget.
     *
     * @return array An array of JavaScript paths
     */
    public function getJavascripts() {
        //return array('/sfFormExtra2Plugin/js/jquery.autocompleter.js');
        return array();
    }

}

?>
