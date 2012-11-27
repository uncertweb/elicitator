<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php use_javascript('variable'); ?>
<?php use_stylesheet('jquery'); ?>
<?php use_javascript('http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing'); ?>
<?php use_javascript('study-area'); ?>
<div id="tabs">
    <ul>
        <li>
            <a href="#general-details">General</a>
        </li>
        <li>
            <a href="#briefing-document">Briefing document</a>
        </li>
        <li>
            <a href="#study-area">Study area</a>
        </li>
    </ul>
    <form action="<?php echo url_for('tasks/' . ($form->getObject()->isNew() ? 'create' : 'update') . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
        <?php if (!$form->getObject()->isNew()): ?>
            <input type="hidden" name="sf_method" value="put" />
        <?php endif; ?>
        <div id="general-details">
            <?php echo $form->renderHiddenFields(); ?>
            <table>
                <?php echo $form['name']->renderRow(); ?>
                <?php echo $form['variable_type']->renderRow(); ?>
                <tr id="parameters_row">
                    <th>
                        <?php echo $form['variable_parameters']->renderLabel(); ?>
                    </th>
                    <td>
                        <?php echo $form['variable_parameters']->render(); ?>
                    </td>
                </tr>
                <tr id="parameters_help_row">
                    <td>&nbsp;</td>
                    <td class="form_help">
                        Enter a comma-separated list of categories. They should be mutually exclusive.
                    </td>
                </tr>
                <?php echo $form['variable_parameters']->renderError(); ?>
                <?php echo $form['experts_list']->renderRow(); ?>
                <?php echo $form['deadline']->renderRow(); ?>
            </table>
        </div>
        <div id="briefing-document">
            <table>
                <tr>
                    <td></td>
                    <td><a style="color: #E54A4A" id="show-template" href="">View the briefing document template</a></td>
                </tr>
                <?php echo $form['research_objective']->renderRow(); ?>
                <?php echo $form['outline']->renderRow(); ?>
                <?php echo $form['variable_characteristics']->renderRow(); ?>
                <?php echo $form['elicitation_techniques']->renderRow(); ?>
                <?php echo $form['definitions']->renderRow(); ?>
                <?php echo $form['requirements']->renderRow(); ?>
                <?php echo $form['bias_causes']->renderRow(); ?>
                <?php echo $form['recommended_literature']->renderRow(); ?>
            </table>
        </div>
        <div id="study-area">
          <table>
            <?php echo $form['study_area_description']->renderRow(); ?>
            <?php echo $form['study_area_geometry']->render(); ?>
            
          </table>
            <div id="study-area-map" style="width: 548px; height: 450px; margin-top: 20px; margin-left: 147px; border: 1px solid #CCC;">
            
            </div>
        </div>
        <input type="submit" value="Save variable" class="large green button"/>
</div>

</form>

<div class="hidden" id="template" title="Briefing document template">
    <h2>Research objective</h2>
    <div id="research_objective_template">
        <p>
            The research aims to characterise and quantify spatial variation of
            variable Y in soil surface in area X. Results of the research will be used
            for scientific report only. The main audiences of the report will be
            students (mainly graduate), experts and scientists in soil science.
        </p>
    </div>
    <a style="color: #E54A4A" href="" id="research_objective_copy">Copy to document</a>
    <h2>Outline</h2>
    <div id="outline_template">
        <p>
            The elicitation procedure has two main rounds. The first round is
            elicitation of marginal continuous distribution of variable Y at random
            location in study area X. The second is the elicitation of the variogram.
            Each round will take around 30 minutes to complete with four questions in
            round 1 and seven questions in round 2. Round 2, however, will not be
            proceeded immediately after round 1. There will be a
            seven&ndash;day&ndash;break in between two rounds to allow all experts
            modifying their judgements.
        </p>
    </div>
    <a style="color: #E54A4A" href="" id="outline_copy">Copy to document</a>
    <h2>Variable characteristics</h2>
    <div id="variable_characteristics_template">
        <p>
            Variable Y is topsoil property. The study area X is located in lowland
            area in the south-west delta of the Netherlands. This is the delta of the
            Rhine-Meuse River near Rotterdam.
        </p>
    </div>
    <a style="color: #E54A4A" href="" id="variable_characteristics_copy">Copy to document</a>
    <h2>Elicitation techniques</h2>
    <div id="elicitation_techniques_template">
        <p>
            To characterise marginal distribution, the quartile method is used. In
            this method, experts will be asked to judge value of median, and upper and
            lower quartiles. Besides, experts will be asked to judge the maximum and
            the minimum value of Y as well.  To characterise the variogram, the
            technique vary between two kind of marginal distributions: normal and
            log-normal. In case of Gaussian random field, experts will be asked to
            give judgements for the median of each difference in value between two
            locations. In case of log-normal random field, the ratio of difference,
            which is assumed to be a factor of lager value over smaller value, will be
            elicited.
        </p>
    </div>
    <a style="color: #E54A4A" href="" id="elicitation_techniques_copy">Copy to document</a>
    <h2>Definitions</h2>
    <div id="definitions_template">
        <ul><li>Median divides a data set into two equal parts.
            </li><li>Lower quartile is median of the lower half of the data.
            </li><li>Upper quartile is median of the upper half of the data.
            </li></ul>
    </div>
    <a style="color: #E54A4A" href="" id="definitions_copy">Copy to document</a>
    <h2>Requirements</h2>
    <div id="requirements_template">
        <p>
            Experts participating in this research are kindly required to provide
            personal data. Experts are kindly required to involve in 2 rounds of
            elicitation procedure.
        </p>
    </div>
    <a style="color: #E54A4A" href="" id="requirements_copy">Copy to document</a>
    <h2>Bias causes</h2>
    <div id="bias_causes_template">
        <p>Experts can possibly make biased judgements. Some causes of biased
            judgements that experts are kindly asked to be aware of when giving
            judgements:</p><ol><li>Availability bias: judgements given by
                experts are affected by easy recall of recent experience.</li><li>
                Representativeness bias: judgements given by experts are based on
                inappropriate or too specific evidences.</li><li>Anchoring and
                adjustment: experts judge by first choosing a starting point as first
                estimation and then adjusting the estimation.</li><li>Motivational
                bias: judgements given by experts are motivated by inappropriate factors
                such as satisfying expectation of society, legal constraints, and
                professional responsibility.</li></ol>
    </div>
    <a style="color: #E54A4A" href="" id="bias_causes_copy">Copy to document</a>
</div>