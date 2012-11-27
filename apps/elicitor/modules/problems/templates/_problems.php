<?php use_helper('Text'); ?>
<?php use_stylesheet('jquery'); ?>
<?php use_javascript('problems'); ?>

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
                location.href = "<?php echo url_for('problems'); ?>?filter=" + $('#filter').val();
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

<?php if (sizeof($problems) > 0): ?>
    <table class="data" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Problem name</th>
                <th>Description</th>
                <th class="progress">Progress</th>
                <th class="actions">Actions</th>
            </tr>
        </thead>
        <tbody id="problems">
            <?php foreach ($problems as $problem): ?>
                <tr id="problem_<?php echo $problem->getId(); ?>">
                    <td><?php echo $problem->getName(); ?></td>
                    <td><?php echo truncate_text($problem->getDescription(), 40); ?></td>
                    <td>
                        <?php if (sizeof($problem->getVariables()) > 0): ?>
                            <div class="progress_details hidden rounded shadow">
                                <div class="content">
                                    <h3 class="blue">Current progress <?php echo $problem->getProgress(); ?>%</h3>
                                    <table class="vertical" cellpadding="0" cellspacing="0">
                                        <?php foreach ($problem->getVariables() as $variable): ?>
                                            <tr>
                                                <th><?php echo $variable->getName(); ?></th>
                                                <td style="width: 100px;">
                                                    <div class="<?php echo $variable->getProgress(); ?>" id="variable_<?php echo $variable->getId(); ?>"></div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>

                            <div class="main_progress"><div class="<?php echo $problem->getProgress(); ?>" id="progress_<?php echo $problem->getId(); ?>"></div></div>
                        <?php else: ?>
                            <p>No variables...</p>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a class="option-view" href="<?php echo url_for('problems_show', $problem); ?>">view</a>
                        <a class="option-edit" href="<?php echo url_for('problems_edit', $problem); ?>">edit</a>
                        <?php echo link_to('remove', 'problems/delete?id=' . $problem->getId(), array('method' => 'DELETE', 'confirm' => 'Are you sure you want to remove this problem?', 'class' => 'option-remove')); ?>
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
                <td>
                    <?php if (isset($count)): ?>
                        <a class="option-view-all-problems" href="<?php echo url_for('problems'); ?>">view all (<?php echo $count; ?>)</a>
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
                    <a  href="<?php echo url_for('problems') ?><?php
                if (isset($filter)) {
                    echo "?filter=$filter";
                }
                    ?>">&larr;</a>
                    <?php endif; ?>

                <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <?php echo $page ?>
                    <?php else: ?>
                        <a href="<?php echo url_for('problems') ?>?page=<?php echo $page ?><?php
                    if (isset($filter)) {
                        echo "&filter=$filter";
                    }
                        ?>"><?php echo $page ?></a>
                       <?php endif; ?>
                   <?php endforeach; ?>

                <?php if ($pager->isLastPage()): ?>
                    &rarr;
                <?php else: ?>
                    <a href="<?php echo url_for('problems') ?>?page=<?php echo $pager->getLastPage(); ?><?php
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
        You currently have no elicitation problems...
    </p>
<?php endif; ?>
<p>

    <a class="background-image option-add-problem" href="<?php echo url_for('problems_new'); ?>">Add new problem</a>
</p>