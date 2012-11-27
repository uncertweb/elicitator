<?php

/**
 * Variable form.
 *
 * @package    elicitor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VariableForm extends BaseVariableForm {

    public function configure() {

        unset(
                $this['created_at'], $this['updated_at']
        );

        $this->widgetSchema['problem_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['variable_parameters'] = new sfWidgetFormInput();
        $this->widgetSchema['deadline'] = new sfWidgetFormJQueryDate(array(
                        'config' => '{}',
                        'image' => '/images/icons/calendar.png',
                        'date_widget' => new sfWidgetFormDate(array(
                                'format' => '%day%/%month%/%year%'
                        ))
                        //
                        ), array());

        $this->widgetSchema->setHelp('variable_parameters', 'Enter a comma-separated list of categories');

        $autocompleteWidget = new sfWidgetFormChoice(array(
                        'multiple' => true,
                        'choices' => $this->getObject()->getExperts(),
                        'renderer_class' => 'sfWidgetFormJQueryAutocompleterMany',
                        'renderer_options' => array(
                                'config' => '{
				json_url: "/expert/autocomplete",
				json_cache: true,
				filter_hide: true,
				filter_selected: true,
				maxshownitems: 8
			  }')
                ));
        $this->widgetSchema['experts_list'] = $autocompleteWidget;

        // TinyMCE fields

        $tinyMCE_theme_config = 'theme_advanced_disable: "formatselect,visualaid,removeformat,hr,undo,redo,anchor,image,cleanup,help,styleselect,code",
                                 theme_advanced_path: false,
                                 theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,link,unlink,|,sub,sup,charmap",
                                 theme_advanced_buttons2: "",
                                 theme_advanced_buttons3: "",
                                 theme_advanced_resizing: false,
                                 theme_advanced_statusbar_location: "none",
                                 theme_advanced_toolbar_align: "center",
                                 skin: "o2k7"';

        $this->widgetSchema['outline'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['research_objective'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['variable_characteristics'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['elicitation_techniques'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['definitions'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['requirements'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['bias_causes'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
        $this->widgetSchema['recommended_literature'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
                ));
                
        $this->widgetSchema['study_area_description'] = new sfWidgetFormTextareaTinyMCE(array(
                        'width' => 550,
                        'height' => 350,
                        'theme' => 'advanced',
                        'config' => $tinyMCE_theme_config,
        ));
        
        $this->widgetSchema['study_area_geometry'] = new sfWidgetFormInput(array(), array('class' => 'hidden'));

        /*
          $cols = 70;
          $rows = 15;

          $this->widgetSchema['research_objective'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['outline'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['variable_characteristics'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['elicitation_techniques'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['definitions'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['requirements'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['bias_causes'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
          $this->widgetSchema['recommended_literature'] = new sfWidgetFormTextarea(array(), array('rows'=>$rows, 'cols'=>$cols));
         */
        // labels
        $this->widgetSchema['experts_list']->setLabel('Experts to elicit');
        $this->widgetSchema['outline']->setLabel('Outline of elicitation task');
        $this->widgetSchema['variable_characteristics']->setLabel('Characteristics of uncertain variable');
        $this->widgetSchema['definitions']->setLabel('Definitions of probabilistic terms');
        $this->widgetSchema['requirements']->setLabel('Requirements from experts');
        $this->widgetSchema['bias_causes']->setLabel('Possible causes of biased judgements');

        // Help messages
        $this->widgetSchema->setHelp('research_objective', 'Statement of studying purpose, nature of problem and usage of elicitation result');
        $this->widgetSchema->setHelp('outline', 'Description of elicitation procedure');
        $this->widgetSchema->setHelp('variable_characteristics', 'Description of target variables and related conditions');
        $this->widgetSchema->setHelp('elicitation_techniques', 'Explanation of eliciting techniques');
        $this->widgetSchema->setHelp('definitions', 'Explanation of probabilistic terms');
        $this->widgetSchema->setHelp('requirements', 'Statement of requirements from experts');
        $this->widgetSchema->setHelp('bias_causes', 'Notices for experts about biased judgments');
        $this->widgetSchema->setHelp('experts_list', 'Search your list of experts and assign them to this variable.');

        if ($this->isNew()) {
            $this->setDefault('problem_id', $this->getOption('problem_id'));
        }

        $this->validatorSchema->setPostValidator(
                new sfValidatorCallback(array('callback' => array($this, 'validateBriefingDocument'))));
    }

    public function validateBriefingDocument($validator, $values) {
        if ($values['variable_type'] == "Categorical" && sizeof(explode(',', $values['variable_parameters'])) < 2) {
            $error = new sfValidatorError($validator, 'You must enter at least two categories.');
            throw new sfValidatorErrorSchema($validator, array('variable_parameters' => $error));
        }

        if (!empty($values['outline'])) {
            return $values;
        }
        if (!empty($values['research_objective'])) {
            return $values;
        }
        if (!empty($values['variable_characteristics'])) {
            return $values;
        }
        if (!empty($values['elicitation_techniques'])) {
            return $values;
        }
        if (!empty($values['definitions'])) {
            return $values;
        }
        if (!empty($values['requirements'])) {
            return $values;
        }
        if (!empty($values['bias_causes'])) {
            return $values;
        }
        if (!empty($values['recommended_literature'])) {
            return $values;
        }
        // password is correct, return the clean values
        throw new sfValidatorError($validator, 'You must fill in at least one section of the briefing document');
    }

}
