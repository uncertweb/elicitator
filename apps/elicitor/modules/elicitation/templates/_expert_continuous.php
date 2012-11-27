<?php use_javascript('elicit.js'); ?>
<?php use_stylesheet('jquery.css'); ?>
<?php use_stylesheet('forms.css'); ?>
<h1>Continuous variable elicitation</h1>
<div class="grid_5 alpha">
    <!--
        <h2 class="red">Task details</h2>
        <table class="vertical" cellpadding="0" cellspacing="0">
            <tr>
                <th>Elicitation problem</th>
                <td>LandsFACTS</td>
            </tr>
            <tr>
                <th>Problem owner</th>
                <td>Matthew Williams</td>
            </tr>
            <tr>
                <th>Variable name</th>
                <td>Transition matrices</td>
            </tr>
        </table>
    -->


    <h2 class="blue">Elicitation</h2>
    <p id="start_instructions">
        You must read the <a class="briefing-document-link">briefing document</a> before continuing.
    </p>
    <div id="elicitation" class="hidden">
    <?php include_partial('continuous_elicitation/form', array('form'=>$form)); ?>


        <!--<form id="elicit">
            <table>
                <tbody>
                    <tr>
                        <th><label for="minimum">Minimum</label></th>
                        <td><input id="minimum" type="text" /></td>
                        <td></td>
                    </tr>
                    <tr id="minimum_error_list" class="hidden error_list">
                        <td></td>
                        <td>Not a valid number</td>
                    </tr>
                    <tr>
                        <th><label for="maximum">Maximum</label></th>
                        <td><input id="maximum" type="text"/></td>
                        <td></td>
                    </tr>
                    <tr id="maximum_error_list" class="hidden error_list">
                        <td></td>
                        <td>Not a valid number</td>
                    </tr>
                    <tr id="tenth-row" class="">
                        <th><label for="tenth-slider">10th percentile</label></th>
                        <td><input type="text" id="tenth" /></td>
                        <td><div id="tenth-slider"></div></td>
                    </tr>
                    <tr class="hidden">
                        <th><label for="median">Median</label></th>
                        <td><input type="text" id="median" /></td>
                        <td><div id="median-slider"></div></td>
                    </tr>
                    <tr class="hidden">
                        <th><label for="nintieth">90th percentile</label></th>
                        <td><input type="text" id="nintieth" /></td>
                        <td><div id="nintieth-slider"></div></td>
                    </tr>
                </tbody>

            </table>
        </form>-->
    </div>
</div>

<div class="grid_4 prefix_1 omega">
    <h2 class="green">Your progress: <span id="percent">0</span>%</h2>
    <table class="progress" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>Task</th>
                <th>Status</th>
            </tr>
        </thead>
        <tr class="incomplete" id="task-briefing">
            <th>1. <a class="briefing-document-link">Read briefing document</a></th>
            <td>incomplete</td>
        </tr>
        <tr class="incomplete" id="task-minimum">
            <th>2. Set minimum value</th>
            <td>incomplete</td>
        </tr>

        <tr class="incomplete" id="task-maximum">
            <th>3. Set maximum value</th>
            <td>incomplete</td>
        </tr>
        <tr class="incomplete" id="task-tenth">
            <th>4. Set 10th percentile</th>
            <td>incomplete</td>
        </tr>
        <tr class="incomplete" id="task-nintieth">
            <th>5. Set 90th percentile</th>
            <td>incomplete</td>
        </tr>
        <tr class="incomplete" id="task-median">
            <th>6. Set median value</th>
            <td>incomplete</td>
        </tr>
    </table>
</div>
<div class="clear"></div>


<p>
    &nbsp;
</p>



<!-- Briefing document -->
<div class="hidden" id="briefing-document" title="Briefing document">
    <?php include_partial('tasks/briefing_document', array('task' => $task)); ?>
</div>
