<div class="grid_11">
    <div class="box rounded shadow">
        <h2 class="green">Create an account</h2>

        <?php echo $form->renderFormTag(url_for('register/index')) ?>
        <table>
            <?php echo $form ?>
        </table>
        <input class="large green button" type="submit" name="register" value="Create my account" />

        </form>
    </div>
</div>