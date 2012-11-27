<div class="grid_11">
    <div class="rounded box shadow">
        <h2 class="green">My elicitation tasks</h2>
        <?php include_partial('tasks', array('tasks' => $pager->getResults(), 'pager'=>$pager, 'filter'=>$filter, 'problem_owner'=>false)); ?>
    </div>
</div>