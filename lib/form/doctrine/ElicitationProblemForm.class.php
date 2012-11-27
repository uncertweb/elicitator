<?php

/**
 * ElicitationProblem form.
 *
 * @package    elicitor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ElicitationProblemForm extends BaseElicitationProblemForm {

    public function configure() {
        unset(
                $this['user_id'],
                $this['created_at'],
                $this['updated_at'],
                $this['thumbnail']
        );

        // file is an input file
        // get source
        if ($this->getObject()->getAttachedFile() != null) {
            $src = $this->getObject()->getAttachedFile();
        } else {
            $src = "no file uploaded.";
        }

        $this->widgetSchema->setHelp('name', 'A short name to describe the problem.');
        $this->widgetSchema->setHelp('description', 'A brief summary describing the problem.');
        $this->widgetSchema->setHelp('attached_file', 'A file containing more detailed information about the problem this is for your use only.');

        $this->widgetSchema['attached_file'] = new sfWidgetFormInputFileEditable(array(
                    'label' => 'Attached file',
                    'file_src' => $src,
                    'with_delete' => false,
                    'template' => '%input%<tr><td></td><td class="filename">%file%</td></tr>'
                ));

        $this->validatorSchema['attached_file'] = new sfValidatorFile(array(
                    'required' => false,
                    'path' => sfConfig::get('app_elicitation_problem_upload_dir'),
                ));

        $tinyMCE_theme_config = 'theme_advanced_disable: "formatselect,visualaid,removeformat,hr,undo,redo,anchor,image,cleanup,help,styleselect,code",
                                 theme_advanced_path: false,
                                 theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,link,unlink,|,sub,sup,charmap",
                                 theme_advanced_buttons2: "",
                                 theme_advanced_buttons3: "",
                                 theme_advanced_resizing: false,
                                 theme_advanced_statusbar_location: "none",
                                 theme_advanced_toolbar_align: "center",
                                 skin: "o2k7"';

        $this->widgetSchema['description'] = new sfWidgetFormTextareaTinyMCE(array(
                    'width' => 550,
                    'height' => 350,
                    'theme' => 'advanced',
                    'config' => $tinyMCE_theme_config,
                ));
    }


}
