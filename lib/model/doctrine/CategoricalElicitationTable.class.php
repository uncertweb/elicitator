<?php

/**
 * CategoricalElicitationTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CategoricalElicitationTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CategoricalElicitationTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CategoricalElicitation');
    }

    public function findByExpertAndTask($expert_id, $task_id) {
        $q = $this->createQuery('ce')
                        ->where('ce.expert_id = ?', $expert_id)
                        ->andWhere('ce.variable_id = ?', $task_id);
        return $q->fetchOne();
    }
}