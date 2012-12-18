<?php use_helper('I18N') ?>
<?php slot('title', 'Request password | The Elicitator'); ?>
<div id="content_wrapper">
  <div id="active_admin_content">
    <div id="login">
      <h2 class="small">The Elicitator: Request new password</h2>
      <p>
        Please enter your registered email address and a new password will be sent to you.
      </p>
      <?php include_partial('request_password', array('form' => $form)); ?>
    </div>
  </div>
</div>