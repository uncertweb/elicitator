<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElicitatorMessageBase
 *
 * @author williamw
 */
class ElicitatorMessageBase extends Swift_Message {

    public function __construct($subject, $body)
    {
        $subject = '[The Elicitator] '.$subject;
        $body .= <<<EOF
   
--------------------------------------
This is an automated email. Please do not reply to this message.
EOF;
        parent::__construct($subject, $body);
        $this->setFrom(array('elicitator@uncertweb.org' => 'The Elicitator'));
    }

}

?>
