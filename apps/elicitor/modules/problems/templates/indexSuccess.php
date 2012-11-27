<div class="grid_11">
    <div class="rounded box shadow">
        <h2 class="blue">My elicitation problems</h2>
        <?php include_partial('problems', array('problems'=>$pager->getResults(), 'pager'=>$pager, 'filter'=>$filter)); ?>
    </div>
</div>