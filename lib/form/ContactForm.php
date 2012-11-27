<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContactForm
 *
 * @author williamw
 */
class ContactForm extends BaseForm {

    //put your code here
    public function configure() {
        $tinyMCE_theme_config = 'theme_advanced_disable: "formatselect,visualaid,removeformat,hr,undo,redo,anchor,image,cleanup,help,styleselect,code",
                                 theme_advanced_path: false,
                                 theme_advanced_buttons1: "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,link,unlink,|,sub,sup,charmap",
                                 theme_advanced_buttons2: "",
                                 theme_advanced_buttons3: "",
                                 theme_advanced_resizing: false,
                                 theme_advanced_statusbar_location: "none",
                                 theme_advanced_toolbar_align: "center",
                                 skin: "o2k7"';

        $this->setWidgets(array(
            'subject' => new sfWidgetFormInputText(),
            'message' => new sfWidgetFormTextareaTinyMCE(array(
                'width' => 550,
                'height' => 350,
                'theme' => 'advanced',
                'config' => $tinyMCE_theme_config,
            )),
            'to_name' => new sfWidgetFormInputHidden(),
            'to_address' => new sfWidgetFormInputHidden()
        ));
        $this->widgetSchema->setNameFormat('contact[%s]');



        $this->setValidators(array(
            'subject' => new sfValidatorString(array('required' => true)),
            'message' => new sfValidatorString(array('required' => true)),
            'to_name' => new sfValidatorString(array('required' => true)),
            'to_address' => new sfValidatorEmail()
        ));
    }

}

?>
