<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <title><?php include_slot('title', 'The Elicitator') ?></title>
        <link rel="shortcut icon" href="<?php echo image_path('favicon.png') ?>" />
        <link rel="apple-touch-icon" href="<?php echo image_path('elicitator-icon.png') ?>" />
        <?php include_slot('head'); ?>
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div class="container_12">
                    <div class="grid_8">
                        <div class="container">
                            <?php if ($sf_user->isAuthenticated()): ?>
                                <a href="<?php echo url_for('@control_panel'); ?>"><img id="logo" src="<?php echo image_path('logo.png') ?>" /></a>
                            <?php else: ?>
                                <a href="http://elicitator.uncertweb.org"><img id="logo" src="<?php echo image_path('logo.png') ?>" /></a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid_3">
                        <?php if ($sf_user->isAuthenticated()): ?>
                        <?php include_component('user', 'options'); ?>
                        <?php else: ?>
                        <?php include_component('user', 'login') ?>
                        <?php endif; ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                    <div id="body">
                        <div class="container_12">
                    <?php if ($sf_user->hasFlash('notice')): ?>
                                    <div class="grid_12">
                                        <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
                                    </div>
                                    <div class="clear"></div>
                    <?php endif ?>
                    <?php echo $sf_content ?>
                </div>
            </div>
        </div>
    </body>
</html>
