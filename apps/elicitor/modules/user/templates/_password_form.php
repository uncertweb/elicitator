<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('@change_password?id=' . $form->getOption('user')->getGuardUser()->getId()); ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put" />
    <?php endif; ?>
        <table>
        <tbody>
            <?php echo $form; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <?php echo $form->renderHiddenFields(false) ?>
                    <input class="large red button" type="submit" value="Change password" />
                </td>
            </tr>
        </tfoot>
    </table>
</form>