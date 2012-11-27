<?php use_javascript('uncertml.js'); ?>
<?php use_javascript('flot/jquery.flot.js'); ?>

<?php use_javascript('jstat-0.1.0.js'); ?>

<?php use_javascript('flot-plot.js'); ?>
<?php use_javascript('categorical-elicitation.js'); ?>

<?php use_stylesheet('jquery.css'); ?>
<?php use_stylesheet('forms.css'); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php slot('head') ?>
<!--[if IE]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<?php end_slot(); ?>
<div class="grid_11">
    <div class="box rounded shadow">
        <h1>Categorical variable elicitation</h1>
        <div class="grid_6 alpha">
            <h2 class="green"><?php echo $form->getObject()->getVariable()->getName(); ?></h2>
            <p id="start_instructions">
                You must read the <a class="briefing-document-link">briefing document</a> before continuing.
            </p>

            <?php foreach ($form->getObject()->getCategories() as $category): ?>
                <div class="bean_tin_container" style="display:none;">
                    <h2 class="blue"><?php echo $category; ?></h2>
                    <div id="<?php echo $category; ?>" class="bean_tin column">

                    </div>
                </div>
            <?php endforeach; ?>
            <div class="clear"></div>
            <form id="form_categorical_elicitation" action="<?php echo url_for('categorical_elicitation/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
                <?php echo $form ?>
                <?php if (!$form->getObject()->isNew()): ?>
                    <input type="hidden" name="sf_method" value="put" />
                <?php endif; ?>
                <input id="form_categorical_elicitation_submit" class="large green button" type="submit" value="Save progress" />
            </form>
        </div>
        <div class="grid_3 prefix_1 omega">
            <div id="bean_silo" style="display:none;">
                <h2 class="red">Bean silo:</h2>
                <div id="bean_silo_column" class="bean_silo column">
                    <?php for ($i = 0; $i < 91; $i++): ?>
                        <div class="bean">bean</div>
                    <?php endfor ?>
                </div>
                <button id="more_beans" class="large red button" style="margin-top: 20px">More beans!</button>
            </div>
        </div>
        <div class="clear"></div>

        <div class="grid_10">
            <h2 id="results_title" class="blue hidden">Results <span class="hidden" id="loader"><img src="/images/ajax-loader.gif" /></span></h2>
        </div>
        <div class="clear"></div>
        <!-- Briefing document -->
        <div class="hidden" id="briefing-document" title="Briefing document">
            <?php include_partial('tasks/briefing_document', array('task' => $task)); ?>
        </div>

        <!-- Confirmatory questions -->
        <div class="hidden" id="confirmatory-questions" title="Elicitation confirmation">
            <p>
                Using the beans we have calculated the following probabilities:
            </p>
            <table id="confirm-probabilities" class="vertical">
            </table>
            <p id="confirm-statement">

            </p>
        </div>

    </div>
</div>