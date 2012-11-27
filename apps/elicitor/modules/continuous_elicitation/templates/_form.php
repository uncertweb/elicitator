<?php use_javascript('uncertml.js'); ?>
<?php use_javascript('flot/jquery.flot.js'); ?>

<?php use_javascript('jstat-0.1.0.js'); ?>

<?php use_javascript('flot-plot.js'); ?>
<?php use_javascript('continuous-elicitation.js'); ?>

<?php use_stylesheet('jquery.css'); ?>
<?php use_stylesheet('forms.css'); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php slot('head') ?>
<!--[if IE]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<?php end_slot(); ?>
<div class="grid_11">
    <div class="box rounded shadow">
        <h1>Continuous variable elicitation</h1>
        <div class="grid_6 alpha">
            <h2 class="green"><?php echo $form->getObject()->getVariable()->getName(); ?></h2>
            <p id="start_instructions">
                You must read the <a class="briefing-document-link">briefing document</a> before continuing.
            </p>
            <div id="elicitation" class="hidden">
                <form id="form_continuous_elicitation" action="<?php echo url_for('continuous_elicitation/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

                    <input type="hidden" name="sf_data_load" value="<?php echo url_for('@continuous_elicitation_fit_pdf') ?>" />
                    <input type="hidden" name="sf_distribution" value='<?php echo $form->getObject()->getDistribution()->toJSON(); ?>' />
                    <?php foreach ($form->getObject()->getDistribution()->getParameters() as $param): ?>
                        <input type="hidden" name="sf_parameter_<?php echo $param->getName(); ?>" value="<?php echo $param->getValue(); ?>" />
                    <?php endforeach; ?>


                    <?php if (!$form->getObject()->isNew()): ?>
                        <input type="hidden" name="sf_method" value="put" />
                    <?php endif; ?>
                    <table>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <?php echo $form->renderHiddenFields(false) ?>

                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php echo $form; ?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="grid_4 omega">
            <h2 class="red">Progress: <span id="percent">0</span>%</h2>
            <table class="progress" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th class="last-child">Status</th>
                    </tr>
                </thead>
                <tr class="incomplete" id="task-briefing">
                    <th>1. <a class="briefing-document-link">Read briefing document</a></th>
                    <td>incomplete</td>
                </tr>
                <tr class="incomplete" id="task-minimum">
                    <th>2. Set minimum value</th>
                    <td>incomplete</td>
                </tr>

                <tr class="incomplete" id="task-maximum">
                    <th>3. Set maximum value</th>
                    <td>incomplete</td>
                </tr>
                <tr class="incomplete" id="task-lower">
                    <th>4. Set lower quartile</th>
                    <td>incomplete</td>
                </tr>
                <tr class="incomplete" id="task-upper">
                    <th>5. Set upper quartile</th>
                    <td>incomplete</td>
                </tr>
                <tr class="incomplete" id="task-median">
                    <th>6. Set median value</th>
                    <td>incomplete</td>
                </tr>


            </table>

        </div>
        <div class="clear"></div>

        <div class="grid_10">
            <h2 id="results_title" class="blue hidden">Results <span class="hidden" id="loader"><img src="/images/ajax-loader.gif" /></span></h2>



            <div id="df_choice" class="right hidden">
                <input type="radio" id="radio-cdf" name="df_choice" /><label for="radio-cdf">CDF</label>
                <input type="radio" id="radio-pdf" name="df_choice" checked="checked" /><label for="radio-pdf">PDF</label>
                <input type="radio" id="radio-both" name="df_choice" /><label for="radio-both">Both</label>
            </div>
            <div class="clear"></div>
            <div id="plot_container" class="hidden">

            </div>

            <input id="form_continuous_elicitation_submit" class="large green button" type="submit" value="Save progress" />
            </form>
        </div>
        <div class="clear"></div>
        <!-- Briefing document -->
        <div class="hidden" id="briefing-document" title="Briefing document">
            <?php include_partial('tasks/briefing_document', array('task' => $task)); ?>
        </div>
        <!-- Confirmatory questions -->
        <div class="hidden" id="confirmatory-questions" title="Elicitation confirmation">
            <p>
                Using the data you provided we have elicited a <strong id="elicit_dist_name"></strong> distribution
                as the 'best fit'. To ensure you agree with our analysis, please read and confirm your agreement with the following statements:
            </p>
            <ol>
            </ol>
            <p>
                If you do not agree with these consequences of the fitted distribution, please click 'Disagree' and re-examine your inputs.
            </p>
        </div>

        <div class="hidden" id="dialog"></div>
    </div>
</div>


