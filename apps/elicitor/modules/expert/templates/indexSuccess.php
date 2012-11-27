<div class="grid_11">
    <div class="rounded box shadow">
        <h2 class="red">My experts</h2>
        <?php include_partial('experts', array('experts'=>$pager->getResults(), 'pager'=> $pager, 'filter' => $filter)); ?>
    </div>
</div>


