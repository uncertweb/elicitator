/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(window).bind("load", function() {

    $('div[id*=progress_]:visible').each(function() {
        var percent = $(this).attr('class');
        $(this).progressbar({
            value: parseInt(percent)
        });
    });


    $('div[id*=expert_]').each(function() {
        var percent = $(this).attr('class');
        $(this).progressbar({
            value: parseInt(percent)
        });
    });

    $('table.data tbody#variables>tr').each(function() {
        var id = $(this).attr('id');
        var selector = 'tr#' + id + ' div.progress_details';
        var details_element = $(selector);
        if(details_element.length > 0) {
            var progress_bar = '#' + id + ' div.main_progress div.ui-progressbar';
            var position = $(progress_bar).position();
            var top = position.top - details_element.height() -  30;
            var left = (position.left + $(progress_bar).width() / 2) - (details_element.width() / 2) - 15;

            $('#' + id + ' div.main_progress').mouseenter(function(){
                details_element.css('top', top);
                details_element.css('left', left);
                details_element.show();
            });

            $('#' + id + ' div.main_progress').mouseout(function() {
                details_element.hide();
            });
        }
    });

	// Opt out dialog
	

});


function optOut(forwardURL, title) {
	// set the form URL
	$('#opt-out-form').attr('action', forwardURL);
	$('#opt-out').attr('title', 'Opt out from elicitation of ' + title);
	$('#opt-out').dialog({
		modal: true,
		width: 440,
		height: 500,
		buttons: {
			"Confirm": function() {
				document.forms['opt-out'].submit();
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
		
	})
}
