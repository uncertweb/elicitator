<?php

/**
 * sfGuardUser
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    elicitor
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class sfGuardUser extends PluginsfGuardUser {

    public function getFullName() {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function __toString() {
        return $this->getFullName();
    }

    public function getTaskProgress($task_id) {
        $task = Doctrine_Core::getTable('Variable')
                        ->findOneById($task_id);

        // Get the results
        if($task->getVariableType() == "Continuous") {
            $results = $task->getContinuousResults();
        } else {
            $results = $task->getCategoricalResults();
        }

        foreach($results as $result) {
            if($result->getExpert()->getId() == $this->getId()) {
                return $result->getProgress();
            }
        }
        return 0;
    }

    public function getTop5Experts() {
        $q = Doctrine_Query::create()
                        ->from('sfGuardUser u')
                        ->leftJoin('u.UserExpert ue')
                        ->where('ue.user_id = ?', $this->getId())
                        ->orderBy('last_name, first_name')
                        ->limit(5);
        return $q->execute();
    }

    public function getTop5ElicitationProblems() {
        $q = Doctrine_Query::create()
                        ->from('ElicitationProblem ep')
                        ->where('ep.user_id = ?', $this->getId())
                        ->orderBy('created_at DESC, name')
                        ->limit(5);
        return $q->execute();
    }

    public function getTop5ElicitationTasks() {
        $q = Doctrine_Query::create()
                        ->from('Variable v')
                        ->leftJoin('v.VariableExpert ve')
                        ->where('ve.expert_id = ?', $this->getId())
                        ->orderBy('deadline ASC, name')
                        ->limit(5);
        return $q->execute();
    }

    public function getNumberOfElicitationProblems() {
        $q = Doctrine_Query::create()
                        ->from('ElicitationProblem ep')
                        ->where('ep.user_id = ?', $this->getId());
        return $q->count();
    }

    public function getElicitationProblemsQuery() {
        $q = Doctrine_Query::create()
                ->from('ElicitationProblem ep')
                ->where('ep.user_id = ?', $this->getId());

        return $q;
    }

    public function getNumberOfElicitationTasks() {
        $q = Doctrine_Query::create()
                        ->from('VariableExpert ve')
                        ->where('ve.expert_id = ?', $this->getId());
        return $q->count();
    }

    public function getElicitationTasksQuery() {
        $q = Doctrine_Query::create()
                        ->from('Variable v')
                        ->leftJoin('v.Experts ve')
                        ->where('ve.id = ?', $this->getId());
        return $q;
    }

    public function getNumberOfExperts() {
        $q = Doctrine_Query::create()
                        ->from('sfGuardUser u, u.UserExpert ue')
                        ->where('ue.user_id = ?', $this->getId());
        return $q->count();
    }

    public function getKnownExperts() {
        $q = Doctrine_Query::create()
                        ->from('sfGuardUser u, u.UserExpert ue')
                        ->where('ue.user_id = ?', $this->getId());
        return $q->execute();
    }

    public function getKnownExpertsQuery() {
        $q = Doctrine_Query::create()
                        ->from('sfGuardUser u, u.UserExpert ue')
                        ->where('ue.user_id = ?', $this->getId());
        return $q;
    }

    public function getElicitationTasks() {
        $q = Doctrine_Query::create()
                        ->from('Variable v')
                        ->leftJoin('v.VariableExpert ve')
                        ->where('ve.expert_id = ?', $this->getId())
                        ->orderBy('v.variable_type, v.name');
        return $q->execute();
    }

    public function findKnownExperts($query) {
        $q = Doctrine_Query::create()
                        ->from('sfGuardUser u, u.UserExpert ue')
                        ->where('ue.user_id = ?', $this->getId())
                        ->andWhere('(u.first_name LIKE ? OR u.last_name LIKE ?)', array('%' . $query . '%', '%' . $query . '%'));
        return $q->execute();
    }

    public function ownsProblem($id) {
        $q = Doctrine_Query::create()
                        ->from('ElicitationProblem ep')
                        ->where('ep.user_id = ?', $this->getId())
                        ->andWhere('ep.id = ?', $id);

        return $q->count();
    }

    public static function generateRandomPassword($length = 8) {
        $possible_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= substr($possible_chars, mt_rand(0, strlen($possible_chars) -1), 1);
        }
        return $password;
    }

}