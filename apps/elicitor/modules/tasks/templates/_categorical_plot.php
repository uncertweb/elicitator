<?php use_stylesheet('jquery.css'); ?>

<div class="expert">
    <h3 id="expert_<?php echo $expert->getId(); ?>">
        <?php echo $expert->getFullName(); ?>
		<?php if($elicitation_results->getEnabled()): ?>
        <a href="<?php echo url_for('disable_categorical_expert', array('id'=>$elicitation_results->getId())); ?>">disable</a>
        <?php else: ?>
        <a href="<?php echo url_for('enable_categorical_expert', array('id'=>$elicitation_results->getId())); ?>">enable</a>
        <?php endif; ?>
    </h3>
    <div class="expert_plot_container  <?php if(!$elicitation_results->getEnabled()) echo "disabled" ?>">
        <div class="expert_plot" id="expert_plot_<?php echo $elicitation_results->getId(); ?>">
        </div>

        <div id="expert_plot_details_<?php echo $elicitation_results->getId(); ?>" class="expert_plot_details">
            <table class="vertical">
                <tbody>
                    <?php foreach ($elicitation_results->getResultsArray() as $key => $value): ?>
                        <?php if ($key == '_empty_')
                            continue; ?>
                        <tr>
                            <th><?php echo $key ?></th>
                            <td><?php echo $value / $elicitation_results->getNumberOfBeans() ?></td>
                        </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            var id = 'expert_plot_<?php echo $elicitation_results->getId(); ?>';

            // draw the graph
            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Category');
                data.addColumn('number', 'Count');
                var res = <?php echo json_encode($elicitation_results->getResultsArray()) ?>;

                var length = 0;
                for(var key in res) {
                    length++;
                }


                data.addRows(length);

                var i = 0;
                for(var key in res) {
                    if(key == '_empty_') continue;
                    data.setValue(i, 0, key);
                    data.setValue(i, 1, parseFloat((res[key] / <?php echo $elicitation_results->getNumberOfBeans() ?>).toFixed(3)));
                    i++;
                }


                var chart = new google.visualization.ColumnChart(document.getElementById(id));
                var width = $(id).width();
                var height = $(id).height();
                chart.draw(data, {width: width, height: height, legend: 'none', hAxis: {title: 'Category'}, vAxis: {format: '#.###', minValue: 0}
                });
            }

            google.load("visualization", "1", {
                packages:["corechart"]
            });
            google.setOnLoadCallback(drawChart);
        });
    </script>

</div>