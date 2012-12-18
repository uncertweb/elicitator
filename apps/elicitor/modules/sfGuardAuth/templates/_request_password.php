<?php use_helper('I18N') ?>
<form action="<?php echo url_for('sf_guard_password') ?>" method="post">
  <fieldset class="inputs">
    <?php echo $form->renderHiddenFields(); ?>
        <?php echo $form['email_address']->renderLabel(); ?>
        <?php echo $form['email_address']->render(); ?>
        <input type="submit" value="<?php echo __('Request', null, 'sf_guard') ?>" />
  </fieldset>
</form>