<?php use_stylesheet('jquery.css'); ?>
<?php use_javascript('flot/jquery.flot.js'); ?>
<?php use_javascript('jstat-0.1.0.js'); ?>
<?php slot('head') ?>
<!--[if IE]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<?php end_slot(); ?>
<div class="grid_11">
    <div class="box rounded shadow">
        <h1>
            <?php echo $variable->getElicitationProblem()->getName() . ' / ' . $variable->getName(); ?>
        </h1>


        <?php if (!is_null($variable->distribution_id)): ?>
            <h2 id="pooled_title" class="blue">Pooled probability distribution</h2>
            <div class="pool_container">
                <div id="plot_container"></div>

                <div id="pooled_plot_details">
                    <table class="vertical" id="pooled_info"  style="">
                        <tbody>
                            <tr>
                                <th>Distribution</th>
                                <td><?php echo ucwords($variable->Distribution->toString()); ?></td>
                            </tr>
                            <tr>
                                <th>Minimum</th>
                                <td id="pool_min"></td>
                            </tr>
                            <tr>
                                <th>Lower quartile</th>
                                <td id="pool_lower"></td>
                            </tr>
                            <tr>
                                <th>Median</th>
                                <td id="pool_med"></td>
                            </tr>
                            <tr>
                                <th>Upper quartile</th>
                                <td id="pool_upper"></td>
                            </tr>
                            <tr>
                                <th>Maximum</th>
                                <td id="pool_max"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            <a id="pool_view_details" href="#" class="background-image option-view-details">View details</a>
            <a id="pool_export" href="<?php echo url_for('@task_uncertml?hash=' . md5($variable->getId())); ?>" class="background-image option-export">Export as UncertML</a>
            <script type="text/javascript">
                var maxY = 0;
                $(document).ready(function() {
                    var distributions = [];
                    // push the main distribution
                    distributions.push(DistributionFactory.build(<?php echo $variable->Distribution->toJSON(); ?>));
                    // push the other distributions
    <?php for ($i = 0; $i < sizeof($variable->ContinuousResults); $i++): ?>
        <?php if (!is_null($variable->ContinuousResults[$i]->distribution_id)): ?>
                        var dist = DistributionFactory.build(<?php echo $variable->ContinuousResults[$i]->Distribution->toJSON(); ?>);
                        var pdfs = dist.density(new Range(<?php echo $abs_min ?>, <?php echo $abs_max ?>, 100));
                        var max = 0;
                        for(var i = 0; i < pdfs.length; i++) {
                            if(pdfs[i] > max)
                                max = pdfs[i];
                        }

                        if(max > maxY) {
                            maxY = max;
                        }

                        distributions.push(dist);
        <?php endif; ?>
    <?php endfor; ?>

            var plot = new MultiDistributionPlot('plot_container', distributions, new Range(<?php echo $abs_min; ?>,<?php echo $abs_max; ?>,100),{ legend: {show: false }, showMarkers: false});
            var lower = distributions[0].getQuantile(0.25).toFixed(2);
            var med = distributions[0].getQuantile(0.5).toFixed(2);
            var upper = distributions[0].getQuantile(0.75).toFixed(2);

            $('#pool_min').text('<?php echo $abs_min; ?>');
            $('#pool_lower').text(lower);
            $('#pool_med').text(med);
            $('#pool_upper').text(upper);
            $('#pool_max').text('<?php echo $abs_max; ?>');


            $('#pool_view_details').click(function() {
                $('#plot_container').toggle(500, function() {
                    if($(this).is(':hidden')) {
                        $('#pool_view_details').text('View graph');
                        $('#pool_view_details').removeClass('option-view-details');
                        $('#pool_view_details').addClass('option-view-graph');
                    } else {
                        $('#pool_view_details').text('View details');
                        $('#pool_view_details').removeClass('option-view-graph');
                        $('#pool_view_details').addClass('option-view-details');
                    }
                });
                $('#pooled_plot_details').toggle(500);
                return false;
            });
        });
            </script>
            <div class="clear"></div>
        <?php else: ?>
            <h2 id="pooled_title" class="blue">Pooled probability distribution</h2>
            <?php echo $variable->Distribution->name; ?>
            <p>
                A pooled distribution could not be calculated. This is due to either contrasting opinions
                or experts, or no experts have successfully completed the elicitation.
            </p>
        <?php endif; ?>


        <h2 class="red">Elicitated experts</h2>
        <?php if (count($variable->getCompletedExperts()) > 0): ?>
            <?php foreach ($variable->getExperts() as $expert): ?>
                <?php if ($expert->getTaskProgress($variable->getId()) == 100): ?>
                    <?php include_component('tasks', 'expert_plot', array('variable_id' => $variable->getId(), 'expert_id' => $expert->getId(), 'abs_min' => $abs_min, 'abs_max' => $abs_max)); ?>

				<?php elseif($variable->getOptOut($expert->getId())): ?>
					<div class="expert">
						<h3>
							<?php echo $expert->getFullName(); ?> (opted out)
						</h3>
						<p>
							<?php echo $variable->getReason($expert->getId())?>
						</p>
					</div>
				<?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>
                No experts have completed the elicitation yet.
            </p>
        <?php endif; ?>

        <div class="clear"></div>
        <h2 class="green">Experts still to be elicitated</h2>
        <?php if (count($variable->getCompletedExperts()) < count($variable->Experts)): ?>
            <ul class="experts">

                <?php foreach ($variable->getExperts() as $expert): ?>
                    <?php if ($expert->getTaskProgress($variable->getId()) < 100 &&  ! $variable->getOptOut($expert->getId())): ?>
                        <li><?php echo $expert->getFullName(); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>
                All experts have successfully completed the elicitation task.
            </p>
        <?php endif; ?>

    </div>
</div>


