/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    
    $('div[id*=progress_]:visible').each(function() {
        var percent = $(this).attr('class');

        $(this).progressbar({
            value: parseInt(percent)
        });
    });


    $('div[id*=variable_]').each(function() {
        var percent = $(this).attr('class');
        $(this).progressbar({
            value: parseInt(percent)
        });
    });

    $('table.data tbody#problems > tr').each(function() {
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
});

