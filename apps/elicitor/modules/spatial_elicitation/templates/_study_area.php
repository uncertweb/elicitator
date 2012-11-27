<div id="study-area-map" style="width: 390px; height: 390px;">

</div>
<span class="hidden" id="study-area-geometry"><?php echo $task->getStudyAreaGeometry(); ?></span>
<?php use_javascript('http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing'); ?>
<?php use_javascript('study-area-view.js'); ?>