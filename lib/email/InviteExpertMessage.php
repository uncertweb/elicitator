<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InviteExpertMessage
 *
 * @author williamw
 */
class InviteExpertMessage extends ElicitatorMessageBase {

    public function __construct($problem_owner, $expert, $temp_password = 'password')
    {
        $expert_name = $expert->getFirstName();
        $expert_email = $expert->getUsername();
        $expert_id = $expert->getId();
        $problem_owner_name = $problem_owner->getFullName();
        $problem_owner_email = $problem_owner->getUsername();

        $subject = 'Invitation to sign up';
        $body = <<<EOF
Hello $expert_name,

$problem_owner_name has invited you to sign up to The Elicitator.

We have automatically set you up an account and you can login at the following URL:

http://elicitator.uncertweb.org/login

Using the following credentials:

username: $expert_email
password: $temp_password

It is recommended that you change this password for security reasons. You can do this by clicking on your name
at the top right of the screen, or by visiting the following URL:

http://elicitator.uncertweb.org/user/$expert_id/edit

Once you have logged in to The Elicitator, you can see a list of elicitation tasks currently assigned to you
on your control panel. Feel free to complete the tasks in your own time. If you would rather opt-out
of the tasks then select the 'Opt-out' link and briefly explain why you have chosen to opt-out.

If you have any questions about the elicitator then please contact us at:

elicitator-support@uncertweb.org

However, specific questions about this elicitation task should be directed to the problem owner ($problem_owner_name):

$problem_owner_email


Regards,

The Elicitator

EOF;
        parent::__construct($subject, $body);

        $this->setTo(array($expert_email => $expert->getFullName()));
    }

}

?>
