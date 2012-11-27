<?php use_stylesheet('jquery.css'); ?>
<?php use_javascript('https://www.google.com/jsapi'); ?>
<?php slot('head') ?>
<!--[if IE]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<?php end_slot(); ?>

<div class="grid_11">
    <div class="box rounded shadow">
        <input type="hidden" id="results" value='<?php echo json_encode($variable->getCombinedCategoricalResults()); ?>' />
        <h1>
            <?php echo $variable->getElicitationProblem()->getName() . ' / ' . $variable->getName(); ?>
        </h1>

        <h2 id="pooled_title" class="blue">Pooled results</h2>
        <?php if ($variable->hasCategoricalResults()): ?>
            <div class="pool_container">
                <div id="plot_container"></div>

                <div id="pooled_plot_details">
                    <table class="vertical" id="pooled_info"  style="">
                        <tbody>
                            <?php foreach($variable->getCombinedCategoricalResults() as $key=>$value): ?>
                                <tr>
                                    <th><?php echo $key ?></th>
                                    <td><?php echo round($value / count($variable->getCompletedExperts(false)), 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <a id="pool_view_details" href="#" class="background-image option-view-details">View details</a>
        <?php else: ?>
            <p>
                A pooled result could not be calculated. This is because no experts have successfully completed the elicitation.
            </p>
        <?php endif; ?>


        <!-- Experts views -->
        <h2 class="red">Elicitated experts</h2>
        <?php if (count($variable->getCompletedExperts()) > 0): ?>
            <?php foreach ($variable->getExperts() as $expert): ?>
                <?php if ($expert->getTaskProgress($variable->getId()) == 100): ?>
                    <?php include_component('tasks', 'categorical_plot', array('variable_id' => $variable->getId(), 'expert_id' => $expert->getId())); ?>
                <?php elseif($variable->getOptOut($expert->getId())): ?>
				<div class="expert">
					<h3 id="expert_<?php echo $expert->getId(); ?>">
				        <?php echo $expert->getFullName(); ?> (opted out)
					</h3>
					<p><?php echo $variable->getReason($expert->getId()); ?></p>
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
                    <?php if ($expert->getTaskProgress($variable->getId()) < 100 && ! $variable->getOptOut($expert->getId())): ?>
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

<script type="text/javascript">
    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Category');
        data.addColumn('number', 'Probability');
        var text = $('#results').val()
        var res = JSON.parse(text);

        var length = 0;
        for(var key in res) {
            length++;
        }

        data.addRows(length);

        var i = 0;
        for(var key in res) {
            data.setValue(i, 0, key);
            data.setValue(i, 1, parseFloat((res[key] / <?php echo count($variable->getCompletedExperts(false)) ?>).toFixed(3)));
            i++;
        }


        var chart = new google.visualization.ColumnChart(document.getElementById('plot_container'));
        var width = $('#plot_container').width();
        var height = $('#plot_container').height();
        chart.draw(data, {width: width, height: height,legend: 'none', hAxis: {title: 'Category'}, vAxis: {format: '####.###', minValue: 0}
        });
    }

    google.load("visualization", "1", {
        packages:["corechart"]
    });
    google.setOnLoadCallback(drawChart);


    $(document).ready(function() {
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
