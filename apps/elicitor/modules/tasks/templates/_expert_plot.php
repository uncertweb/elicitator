<?php use_stylesheet('jquery.css'); ?>
<?php use_javascript('flot/jquery.flot.js'); ?>
<?php use_javascript('jquery.qtip.min.js'); ?>
<?php use_javascript('jstat-0.1.0.js'); ?>
<?php use_javascript('flot-plot.js'); ?>



<div class="expert">
    <h3 id="expert_<?php echo $expert->getId(); ?>">
        <?php echo $expert->getFullName(); ?>
        <?php if($elicitation_results->getEnabled()): ?>
        <a href="<?php echo url_for('disable_continuous_expert', array('id'=>$elicitation_results->getId())); ?>">disable</a>
        <?php else: ?>
        <a href="<?php echo url_for('enable_continuous_expert', array('id'=>$elicitation_results->getId())); ?>">enable</a>
        <?php endif; ?>
    </h3>
    <div class="expert_plot_container <?php if(!$elicitation_results->getEnabled()) echo "disabled" ?>">
        <div class="expert_plot" id="expert_plot_<?php echo $elicitation_results->getId(); ?>">

        </div>

        <div id="expert_plot_details_<?php echo $elicitation_results->getId(); ?>" class="expert_plot_details">
            <table class="vertical">
                <tbody>
                    <tr>
                        <th>Distribution</th>
                        <td><?php echo ucwords($elicitation_results->Distribution->toString()); ?></td>
                    </tr>
                    <tr>
                        <th>Minimum</th>
                        <td><?php echo $elicitation_results->getMinimum(); ?></td>
                    </tr>
                    <tr>
                        <th>Lower quartile</th>
                        <td><?php echo $elicitation_results->getLower(); ?></td>
                    </tr>
                    <tr>
                        <th>Median</th>
                        <td><?php echo $elicitation_results->getMedian(); ?></td>
                    </tr>
                    <tr>
                        <th>Upper quartile</th>
                        <td><?php echo $elicitation_results->getUpper(); ?></td>
                    </tr>
                    <tr>
                        <th>Maximum</th>
                        <td><?php echo $elicitation_results->getMaximum(); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <a id="expert_plot_link_<?php echo $elicitation_results->getId(); ?>" href="#" class="background-image option-view-details">View details</a>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').click(function() {

                $('#expert_plot_<?php echo $elicitation_results->getId(); ?>').toggle(500, function() {
                    if($(this).is(':visible')) {
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').text('View details');
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').removeClass('option-view-graph');
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').addClass('option-view-details');
                    } else {
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').text('View graph');
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').removeClass('option-view-details');
                        $('#expert_plot_link_<?php echo $elicitation_results->getId(); ?>').addClass('option-view-graph');
                    }
                });
                $('#expert_plot_details_<?php echo $elicitation_results->getId(); ?>').toggle(500, function() {

                });
                return false;
            });
            var id = 'expert_plot_<?php echo $elicitation_results->getId(); ?>';
            var marker_obj = [
                {
                    label: 'Lower quartile',
                    value: <?php echo $elicitation_results->getLower(); ?>
                },
                {
                    label: 'Median',
                    value: <?php echo $elicitation_results->getMedian(); ?>
                },
                {
                    label: 'Upper quartile',
                    value: <?php echo $elicitation_results->getUpper(); ?>
                }
            ];
            try
            {
                var dist = DistributionFactory.build(<?php echo $distribution; ?>);
                new ContinuousPlot(id, dist, new Range(<?php echo $abs_min; ?>,<?php echo $abs_max; ?>,100), marker_obj,{ legend: {show: false }, showMarkers: false}, maxY);
            } catch(err) {
                // null distribution
                new ContinuousPlot(id, null, new Range(<?php echo $abs_min; ?>,<?php echo $abs_max; ?>,100), marker_obj,{ legend: {show: false }, showMarkers: false});
            }



        });
    </script>

</div>