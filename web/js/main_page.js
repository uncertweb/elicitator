$(document).ready(function(){
    $.featureList(
        $("#tabs li a"),
        $("#output li"), {
            start_item	:	0,
            transition_interval: 4000,
            pause_on_hover: true
        }
        );
});
