<?php use_helper('I18N') ?>

<div class="grid_6 prefix_3 margin50">
    <div class="box rounded shadow">
        <h2 class="red">Sign in to The Elicitator!</h2>
        <?php echo $form->renderFormTag(url_for('@sf_guard_signin')) ?>
        <table>

            <?php echo $form['username']->renderRow(); ?>
            <?php echo $form['password']->renderRow(); ?>
            <tr class="forgot">
                <td></td>
                <td>
                    <a href="">Forgot your password?</a>
                </td>
            </tr>
            <?php echo $form['_csrf_token']->render(); ?>


        </table>

        <div id="signin-button-container">
        <input id="signin-button"  class="large red button" type="submit" value="<?php echo __('Sign in', null, 'sf_guard') ?>" />
        <ul style="display:inline; list-style: none; float:left;">
            <li><?php echo $form['remember']->render(); ?> <label for="signin_remember">Remember me</label></li>
            <li>
                Not a member? <a href="<?php echo url_for('@register'); ?>">Register for free</a>
            </li>
        </ul>
          
        </div>
        <div class="clear"></div>

    </div>
</div>