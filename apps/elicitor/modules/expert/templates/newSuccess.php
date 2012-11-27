<div class="grid_7">
    <div class="box rounded shadow">
        <h2 class="red">Create a new expert</h2>
        <p>
            Fill in the form below with the contact details of the domain expert you wish to add.
            They will not be contacted until they have been assigned an elicitation problem by you.
        </p>
        <?php echo $form->renderFormTag(url_for('@expert_new?id=' . $sf_user->getGuardUser()->getId())); ?>
        <table>
            <?php echo $form ?>
        </table>

        <input class="large red button" type="submit" name="submit" value="Create expert" />
    </div>
</div>