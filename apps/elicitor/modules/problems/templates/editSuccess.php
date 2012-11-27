<div class="grid_11">
    <div class="box rounded shadow">
        <h2 class="blue">Edit Elicitation problem</h2>

        <?php include_partial('form', array('form' => $form)) ?>

        <h2 class="green">Variables</h2>
        <?php include_partial('tasks/tasks', array('tasks' => $form->getObject()->getVariables(), 'problem_owner' => true, 'edit_mode' => true, 'problem_id' => $problem_id)); ?>


        <input class="large blue button" type="submit" value="Save elicitation problem" />


        </form>
    </div>
</div>