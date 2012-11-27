<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">

          <?php echo $form['username']->renderRow(); ?>
          <?php echo $form['password']->renderRow(); ?>
          <?php echo $form['_csrf_token']->render(); ?>

          <input class="small green button" type="submit" value="<?php echo __('Sign in', null, 'sf_guard') ?>" />
          <div class="rememberme">
              <?php echo $form['remember']->render(); echo $form['remember']->renderLabel(); ?>
          </div>

          <?php $routes = $sf_context->getRouting()->getRoutes() ?>
          <?php if (isset($routes['sf_guard_forgot_password'])): ?>
            <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
          <?php endif; ?>

          <?php if (isset($routes['sf_guard_register'])): ?>
            &nbsp; <a href="<?php echo url_for('@sf_guard_register') ?>"><?php echo __('Want to register?', null, 'sf_guard') ?></a>
          <?php endif; ?>
</form>