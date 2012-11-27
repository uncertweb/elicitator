<?php use_helper('I18N') ?>
<ul id="signin">
    <li><a  id="signin-button">sign in</a></li>
</ul>
<div id="signin-container">
    <div id="signin-box">
        <?php echo get_partial('sfGuardAuth/mini_login', array('form' => $form)) ?>
    </div>
</div>