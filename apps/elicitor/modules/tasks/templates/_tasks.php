<?php use_helper('Text'); ?>
<?php use_helper('Date'); ?>

<?php
use_stylesheet('jquery');
use_javascript('tasks');
?>

<?php if (isset($pager)): ?>
    <div class="filter">
        <label for="filter">Filter:</label>
        <input type="text" id="filter" />
        <button class="blue button" id="filter_button" value="Search">Search</button>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){

            $('#filter').val(getQueryVariable('filter'));

            $('#filter_button').click(function() {
                location.href = "<?php echo url_for('tasks'); ?>?filter=" + $('#filter').val();
            });
        });

        function getQueryVariable(variable)
        {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++)
            {
                var pair = vars[i].split("=");
                if (pair[0] == variable)
                {
                    return pair[1];
                }
            }
        }

    </script>
<?php endif; ?>

<?php if ($tasks != null && sizeof($tasks) > 0): ?>
    <table class="data" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>Variable type</th>
                <th>Deadline</th>
                <th class="progress">Progress</th>
                <th class="actions">Actions</th>
            </tr>
        </thead>
        <tbody id="variables">
            <?php foreach ($tasks as $task): ?>
			
                <tr id="variable_<?php echo $task->getId(); ?>" <?php if($task->getOptOut($sf_user->getGuardUser()->getId())) { echo 'class="disabled"'; } ?>> 
                    <td><?php echo $task->getName(); ?></td>
                    <td><?php echo $task->getVariableType(); ?></td>
                    <td><?php echo $task->getDateTimeObject('deadline')->format('d/m/Y'); ?></td>
                    <td>
                        <?php if (sizeof($task->getExperts()) > 0): ?>
                            <?php if ($problem_owner): ?>
                                <div class="progress_details hidden rounded shadow">
                                    <div class="content">
                                        <h3 class="blue">Current progress <?php echo $task->getProgress(); ?>%</h3>
                                        <table class="vertical" cellpadding="0" cellspacing="0">
                                            <?php foreach ($task->getExperts() as $expert): ?>
                                                <tr>
                                                    <th><?php echo $expert->getFullName(); ?></th>
                                                    <td style="width: 100px;">
                                                        <div class="<?php echo $expert->getTaskProgress($task->getId()); ?>" id="expert_<?php echo $expert->getId(); ?>"></div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="main_progress"><div class="<?php echo ($problem_owner ? $task->getProgress() : $task->getExpertProgress($sf_user->getGuardUser()->getId())); ?>" id="progress_<?php echo $task->getId(); ?>"></div></div>
                        <?php else: ?>
                            <div class="main_progress"><div class="0" id="progress_<?php echo $task->getId(); ?>"></div></div>
                        <?php endif; ?>
                    </td>

                    <td class="actions">
                        <?php if (isset($edit_mode)): ?>
                            <a class="option-edit-task" href="<?php echo url_for('tasks_edit', $task) ?>">edit</a>
                            <?php echo link_to('remove', '@tasks_delete?id=' . $task->getId(), array('confirm' => 'Are you sure you want to remove this variable?', 'method' => 'DELETE', 'class' => 'option-remove')); ?>
                        <?php else: ?>
                            <?php if ($problem_owner): ?>
                                <a class="option-view" href="<?php echo url_for('tasks_show', $task) ?>">view</a>
                            <?php elseif( ! $task->getOptOut($sf_user->getGuardUser()->getId())): ?>
                                <a class="option-elicit" href="<?php echo url_for('tasks_show', $task) ?>">elicit</a>
								<?php if($task->getVariableType() == "Continuous"): ?>
								<a class="option-opt-out" href="javascript:optOut('<?php echo url_for('continuous_opt_out', array('id' => $task->getResultId($sf_user->getGuardUser()->getId())))?>', '<?php echo $task->getName(); ?>')">opt out</a>
								<?php else: ?>
								<a class="option-opt-out" href="javascript:optOut('<?php echo url_for('categorical_opt_out', array('id' => $task->getResultId($sf_user->getGuardUser()->getId())))?>', '<?php echo $task->getName(); ?>')">opt out</a>
								<?php endif; ?>
                            <?php else: ?>
							<p>Opted out</p>
							<?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td style="text-align: center">
                    <?php if (isset($pager)): ?>
                        <?php if ($pager->haveToPaginate()): ?>
                            Viewing page <strong><?php echo $pager->getPage() ?></strong> of <strong><?php echo $pager->getLastPage(); ?></strong>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td></td>
                <td></td>
                <td>
                    <?php if (isset($count)): ?>
                        <a class="option-view-all-tasks" href="<?php echo url_for('tasks'); ?>">view all (<?php echo $count; ?>)</a>
                    <?php endif; ?>
                </td>
            </tr>
        </tfoot>
    </table>


    <?php if (isset($pager)): ?>
        <?php if ($pager->haveToPaginate()): ?>
            <div class="pagination">
                <?php if ($pager->isFirstPage()): ?>
                    &larr;
                <?php else: ?>
                    <a  href="<?php echo url_for('tasks') ?><?php
                if (isset($filter)) {
                    echo "?filter=$filter";
                }
                    ?>">&larr;</a>
                    <?php endif; ?>

                <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <?php echo $page ?>
                    <?php else: ?>
                        <a href="<?php echo url_for('tasks') ?>?page=<?php echo $page ?><?php
                    if (isset($filter)) {
                        echo "&filter=$filter";
                    }
                        ?>"><?php echo $page ?></a>
                       <?php endif; ?>
                   <?php endforeach; ?>

                <?php if ($pager->isLastPage()): ?>
                    &rarr;
                <?php else: ?>
                    <a href="<?php echo url_for('tasks') ?>?page=<?php echo $pager->getLastPage(); ?><?php
                if (isset($filter)) {
                    echo "&filter=$filter";
                }
                    ?>">&rarr;</a>
                   <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
    <p>
        You currently have no pending tasks...
    </p>
<?php endif; ?>

<?php if ($problem_owner): ?>
    <p>
        <a class="background-image option-add-variable" href="<?php echo url_for('@tasks_new?id=' . $problem_id); ?>">Add new variable</a>
    </p>
<?php endif; ?>

<div class="hidden" id="opt-out" title="Opt out">
<form id="opt-out-form" name="opt-out" action="" method="post">
	<label for="reason">Reason:</label><br />
	<textarea placeholder="Enter your reason for opting out here..." name="reason" id="opt-out[reason]"></textarea> 
</form>
</div>