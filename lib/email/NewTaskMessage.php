<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewTaskMessage
 *
 * @author williamw
 */
class NewTaskMessage extends ElicitatorMessageBase {
    public function __construct($task, $expert, $task_url)
    {
        $subject = "New elicitation task.";

        $expert_name = $expert->getFirstName();
        $problem_owner = $task->getElicitationProblem()->getProblemOwner()->getFullName();
        $problem_name = $task->getElicitationProblem()->getName();
        $task_name = $task->getName();
        $description = $task->getElicitationProblem()->getDescription();
        $deadline_text = "";

        if($task->getDeadline() != null) {
            $deadline_text = "a deadline of " . $task->getDeadline() . " for this task.";
        } else {
            $deadline_text = "no deadline for this task.";
        }

        $body = <<<EOF
Hello $expert_name,

$problem_owner has assigned you to a new elicitation task:

$problem_name: $task_name

To complete this task please visit the following URL:

$task_url

Alternatively, if you would prefer to opt-out then you can do this from your control panel:

http://elicitator.uncertweb.org/control_panel

$problem_owner has indicated that there is $deadline_text

Regards,

The Elicitator
EOF;


        parent::__construct($subject, $body);
        $this->setTo(array($expert->getUsername() => $expert->getFullName()));
    }
}

?>
