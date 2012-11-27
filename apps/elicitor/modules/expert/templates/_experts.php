<?php if (isset($pager)): ?>
    <div class="filter">
        <label for="filter">Filter:</label>
        <input type="text" id="filter" />
        <button class="red button" id="filter_button" value="Search">Search</button>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){

            $('#filter').val(getQueryVariable('filter'));

            $('#filter_button').click(function() {
                location.href = "<?php echo url_for('expert_list'); ?>?filter=" + $('#filter').val();
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

<?php if (sizeof($experts) > 0): ?>
    <table class="data" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
                <th>Institute</th>
                <th>Expertise</th>
                <th class="actions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($experts as $expert): ?>
                <tr>
                    <td><?php echo $expert->getFirstName(); ?></td>
                    <td><?php echo $expert->getLastName(); ?></td>
                    <td><?php echo $expert->getInstitute(); ?></td>
                    <td><?php echo $expert->getExpertise(); ?></td>
                    <td class="actions">
                        <!--<a class="option-view" href="">view</a>-->
                        <a class="option-contact" href="<?php echo url_for('@expert_contact?id=' . $expert->getId()) ?>">contact</a>
                        <?php echo link_to('remove', '@expert_delete?user_id=' . $sf_user->getGuardUser()->getId() . '&expert_id=' . $expert->getId(), array('confirm' => 'Are you sure you want to remove this expert?', 'class' => 'option-remove')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td></td>
                <td>

                </td>
                <td>
                    <?php if (isset($pager)): ?>
                        <?php if ($pager->haveToPaginate()): ?>
                            Viewing page <strong><?php echo $pager->getPage() ?></strong> of <strong><?php echo $pager->getLastPage(); ?></strong>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td></td>
                <td>
                    <?php
                    if (isset($count)):
                        // rendering on the control panel
                        ?>
                        <a class="option-view-all" href="<?php echo url_for('@expert_list'); ?>">view all (<?php echo $count; ?>)</a>
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
                    <a  href="<?php echo url_for('expert_list') ?><?php if(isset($filter)) { echo "?filter=$filter"; }?>">&larr;</a>
                <?php endif; ?>

                <?php foreach ($pager->getLinks() as $page): ?>
                    <?php if ($page == $pager->getPage()): ?>
                        <?php echo $page ?>
                    <?php else: ?>
                        <a href="<?php echo url_for('expert_list') ?>?page=<?php echo $page ?><?php if(isset($filter)) { echo "&filter=$filter"; }?>"><?php echo $page ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if ($pager->isLastPage()): ?>
                    &rarr;
                <?php else: ?>
                    <a href="<?php echo url_for('expert_list') ?>?page=<?php echo $pager->getLastPage(); ?><?php if(isset($filter)) { echo "&filter=$filter"; }?>">&rarr;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

<?php else: ?>
    <p>
        You currently have no experts...
    </p>
<?php endif; ?>


<p>
    <a href="<?php echo url_for('@expert_new?id=' . $sf_user->getGuardUser()->getId()); ?>" class="background-image option-add-expert">Add new expert</a>

</p>