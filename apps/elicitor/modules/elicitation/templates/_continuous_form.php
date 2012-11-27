<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php echo $form->renderFormTag('elicit/continuous'); ?>
<table>


    <tbody>
        <?php echo $form; ?>
    </tbody>

</table>

<input type="submit" class="large green button" value="Save progress" />

<a class="large green button">Control panel</a>
</form>