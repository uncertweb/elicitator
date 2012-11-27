<div class="grid_11">
    <div class="box rounded shadow">
        <h1><?php echo $sf_user->getGuardUser()->getFullName() ?>'s control panel</h1>

        <h2 class="red">My experts</h2>
        <?php include_partial('expert/experts', array('experts' => $experts, 'count' => $experts_count)); ?>

        <h2 class="blue">My elicitation problems</h2>

        <?php include_partial('problems/problems', array('problems' => $problems, 'count' => $problems_count)); ?>

        <h2 class="green">My elicitation tasks</h2>
        <?php include_partial('tasks/tasks', array('tasks' => $tasks, 'count' => $tasks_count, 'problem_owner' => false)); ?>
    </div>
</div>