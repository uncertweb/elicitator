<div class="grid_11">
    <div class="box rounded shadow">
        <h1><?php echo $problem->getName(); ?></h1>
        <?php if ($problem->getThumbnail() != null): ?>
            <div class="grid_7 alpha">
                <p>
                <?php echo $problem->getDescription(); ?>
            </p>
        </div>
        <div class="grid_3 omega">
            <div class="right">
                <div class="rounded document_overview shadow">
                    <img width="<?php echo $problem->getThumbnailWidth(); ?>" src="<?php echo '/' . $sf_user->getAttribute('upload_dir') . sfConfig::get('app_thumbnail_upload_dir') . $problem->getThumbnail() ?>" />

                </div>
                <p class="center">
                    <a href="<?php echo '/' . $sf_user->getAttribute('upload_dir') . sfConfig::get('app_elicitation_problem_upload_dir') . $problem->getAttachedFile() ?>">view document</a>
                </p>
            </div>
        </div>
        <div class="clear"></div>
        <?php else: ?>
                    <p>
            <?php echo $problem->getDescription(); ?>
                </p>
        <?php endif; ?>


                <h2 class="green">Variables</h2>
        <?php include_partial('tasks/tasks', array('tasks' => $problem->getVariables(), 'view_only'=>true, 'problem_owner'=>true, 'problem_id'=>$problem->getId())); ?>
    </div>

</div>