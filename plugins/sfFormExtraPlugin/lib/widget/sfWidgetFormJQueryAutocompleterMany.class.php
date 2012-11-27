<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormJQueryAutocompleter represents an autocompleter input widget rendered by JQuery.
 *
 * This widget needs JQuery to work.
 *
 * You also need to include the JavaScripts and stylesheets files returned by the getJavaScripts()
 * and getStylesheets() methods.
 *
 * If you use symfony 1.2, it can be done automatically for you.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryAutocompleter.class.php 15839 2009-02-27 05:40:57Z fabien $
 */
class sfWidgetFormJQueryAutocompleterMany extends sfWidgetFormInput {

    /**
     * Configures the current widget.
     *
     * Available options:
     *
     *  * url:            The URL to call to get the choices to use (required)
     *  * config:         A JavaScript array that configures the JQuery autocompleter widget
     *  * value_callback: A callback that converts the value before it is displayed
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetForm
     */
    protected function configure($options = array(), $attributes = array()) {
        $this->addOption('url');
        $this->addOption('value_callback');
        $this->addOption('config', '{ }');

        // this is required as it can be used as a renderer class for sfWidgetFormChoice
        $this->addOption('choices');
        $attributes['placeholder'] = 'Type to search...';
        parent::configure($options, $attributes);
    }

    /**
     * @param  string $name        The element name
     * @param  string $value       The date displayed in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        $choices = $this->getOption('choices');
        if ($choices instanceof sfCallable) {
            $choices = $choices->getCallable();
            $renderedWidget = $choices[0];
            $choices = $this->getChoices($renderedWidget->getOption('choices'));
            //var_dump($value);
            //var_dump($choices);
            $new_choices = array();
            foreach ($choices as $id => $choice_name) {
                if (in_array($id, $value)) {
                    $new_choices[$id] = $choice_name;
                }
            }
            $choices = $new_choices;

            if(empty($choices) && !empty($value)) {
                // There are some values, but no choices -
                // caused by a create validation error
                // TODO figure out how to load the choices in given that the form
                // hasnt been saved - i.e. the method wont run.
                
            }
        }

        $formattedValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;
        $widget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $choices));
        return $widget->render($name, $formattedValue) .
        sprintf(<<<EOF
<script language="JavaScript">
	$(document).ready(function()
	{
	  $("#%s").fcbkcomplete(
		%s
		//onremove: "testme",
		//onselect: "testme",
		);
	});
</script>
EOF
                ,
                $this->generateId($name),
                $this->getOption('config')
        );
    }

    public function getChoices($choices) {
        $objectChoices = array();
        $objects = $choices;
        foreach ($objects as $object) {
            try {
                $objectChoices[$object->getPrimaryKey()] = $object->__toString();
            } catch (Exception $e) {
                throw $e;
            }
        }
        return $objectChoices;
    }

    /**
     * Gets the stylesheet paths associated with the widget.
     *
     * @return array An array of stylesheet paths
     */
    public function getStylesheets() {
        return array('/sfFormExtraPlugin/css/jquery.autocompleter.many.css' => 'all');
//	: array('/css/autocompleteStyle.css' => 'all');
    }

    /**
     * Gets the JavaScript paths associated with the widget.
     *
     * @return array An array of JavaScript paths
     */
    public function getJavascripts() {
        return array('/sfFormExtraPlugin/js/jquery.fcbkcomplete.symfony.min.js');
    }

}
