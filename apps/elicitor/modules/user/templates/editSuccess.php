<div class="grid_7">
    <div class="box rounded shadow">
        <h2 class="green">Update your profile information</h2>
            <?php include_partial('edit_form', array('form' => $edit_form)); ?>
        <h2 class="red">Change your password</h2>
            <?php include_partial('password_form', array('form' => $password_form)); ?>
    </div>
</div>