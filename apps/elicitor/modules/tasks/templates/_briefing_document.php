<h1 class="nopadding">Briefing document</h1>
<?php if (!$task->hasBriefingDocument()): ?>
    <h2 class="blue">Problem description</h2>
    <p>
    <?php echo $task->getElicitationProblem()->getDescription(); ?>
</p>
<?php else: ?>
<?php if ($task->getResearchObjective()): ?>
            <h2 class="blue">Research objectives</h2>
<?php echo $task->getResearchObjective(); ?>
<?php endif; ?>

<?php if ($task->getOutline()): ?>
                <h2 class="blue">Elicitation task outline</h2>
<?php echo $task->getOutline(); ?>
<?php endif; ?>

<?php if ($task->getVariableCharacteristics()): ?>
                    <h2 class="blue">Variable characteristics</h2>
<?php echo $task->getVariableCharacteristics(); ?>
<?php endif; ?>

<?php if ($task->getElicitationTechniques()): ?>
                        <h2 class="blue">Elicitation techniques</h2>
<?php echo $task->getElicitationTechniques(); ?>
<?php endif; ?>

<?php if ($task->getDefinitions()): ?>
                            <h2 class="blue">Definitions of probabilistic terms</h2>
<?php echo $task->getDefinitions(); ?>
<?php endif; ?>

<?php if ($task->getRequirements()): ?>
                                <h2 class="blue">Your requirements</h2>
<?php echo $task->getRequirements(); ?>
<?php endif; ?>

<?php if ($task->getBiasCauses()): ?>
                                    <h2 class="blue">Possible causes of biased judgements</h2>
<?php echo $task->getBiasCauses(); ?>
<?php endif; ?>

<?php if ($task->getRecommendedLiterature()): ?>
                                    <h2 class="blue">Recommended literature</h2>
<?php echo $task->getRecommendedLiterature(); ?>
<?php endif; ?>

<?php endif; ?>