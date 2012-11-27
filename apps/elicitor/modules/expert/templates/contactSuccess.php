<div class="grid_11">
    <div class="box rounded shadow">
        <h2 class="blue">Contact <?php echo $form['to_name']->getValue(); ?></h2>
        <form action="<?php echo url_for('@send_message'); ?>" method="POST">
            <table>
                <?php echo $form; ?>
            </table>
            <input class="large blue button" type="submit" value="Send message"/>
            <a class="large blue button" href="<?php echo url_for('user_show', $sf_user->getGuardUser()); ?>">Cancel</a>
        </form>
    </div>
</div>