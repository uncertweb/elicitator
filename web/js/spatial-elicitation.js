var elicitated = false;
var initialising = false;
var adjusting = false;
var graph = null;

function calculatePercentage() {
    var complete = $('table.progress tr.complete').length;
    var total = $('table.progress tbody tr').length;
    return Math.round(complete/total*100);
}

function unbindEvent() {
    $(window).unbind('beforeunload');
}

function loadData(min, max, l, m, u) {
    // display the loading gif
    $('#loader').show();
    dist = null;
    var url = $('input[name=sf_data_load]').val();
    $.post(url,{
        min: min,
        max: max,
        lower: l,
        median: m,
        upper: u
    }, function(data) {
        // data loaded
        $('#loader').hide();
        try {
            var dist = DistributionFactory.build(data);
            if(graph == null) {
                graph = new ContinuousPlot("plot_container", dist, new Range(min, max, 100),[{
                    label: 'Lower quartile',
                    value: l
                },{
                    label:'Median',
                    value: m
                }, {
                    label: 'Upper quartile',
                    value: u
                }]);

            } else {
                // load the data and redraw
                graph.setDistribution(dist, new Range(min,max,100));
            }
            $('#results_title').text('Results:- ' + dist);
        } catch(err) {
            if(graph != null) {
                graph.clear();
            } else {
                graph = new ContinuousPlot("plot_container", null, new Range(min,max,100),[{
                    label: 'Lower quartile',
                    value: l
                },{
                    label:'Median',
                    value: m
                }, {
                    label: 'Upper quartile',
                    value: u
                }]);
            }

            $('#results_title').text('Results:- No suitable distribution found.');

        }
    }, 'json'
    );

}

function plot() {
    var minimumValue = $('#spatial_elicitation_minimum').val();
    var maximumValue = $('#spatial_elicitation_maximum').val();
    var lowerValue = $('#spatial_elicitation_lower').val();
    var medianValue = $('#spatial_elicitation_median').val();
    var upperValue = $('#spatial_elicitation_upper').val();

    //var distribution = DistributionFactory.build($('input[name=sf_distribution]').val());

    $('#plot_container').show();
    $('#df_choice').show();

    loadData(minimumValue, maximumValue,lowerValue,medianValue,upperValue);
//
}

function processProgress() {
    var percent = calculatePercentage();
    $('#percent').text(percent);
    if(percent == 100) {
        $('#plot_container').show(0, function() {
            $('#results_title').show();
            elicitated = true;
        });
    }
    // bind an event to the unload to ensure the progress has been saved.
    $(window).bind('beforeunload', function(){
        return 'Your elicitation results have not been saved.\r\nAre you sure you wish to leave?';
    });
}


function updateLowerQuartile(event, ui) {
    if(graph != null) {
        graph.setLowerQuartile(ui.value);
    }
}

function updateUpperQuartile(event, ui){
    if(graph != null) {
        graph.setUpperQuartile(ui.value);
    }
}

function updateMedian(event, ui) {
    if(graph != null) {
        graph.setMedian(ui.value);
    }
}


function briefingDocumentRead() {
    // update the progress to indicate the task has been completed
    $("input[name='spatial_elicitation[read_briefing_document]']").val('1');
    $('#task-briefing').switchClass('incomplete', 'complete', 0);
    $('#task-briefing td').text('complete');
    $('#start_instructions').hide();
    $('#elicitation').show();
    $('#start_instructions').html('Review the <a class="briefing-document-link">briefing document</a>')
    processProgress();
}

function changeMinimum() {
    var minimum = $('#spatial_elicitation_minimum').val();
    if(!isNaN(minimum) && minimum !== '') {
        // Change the sliders
        $('#slider_spatial_elicitation_lower').slider('option', 'min', parseInt(minimum));
        $('#slider_spatial_elicitation_median').slider('option', 'min', parseInt(minimum));
        $('#slider_spatial_elicitation_upper').slider('option', 'min', parseInt(minimum));
        updateSliders();
    }
}


function updateSliders() {
    var value = $('#slider_spatial_elicitation_lower').slider('value');
    var minimum = $('#spatial_elicitation_minimum').val();

    // TODO there is a bug here that if you change the minimum to be higher
    // than the values of a slider - they wont update...

    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_spatial_elicitation_lower').slider('value', value);
    }
    value = $('#slider_spatial_elicitation_median').slider('value');
    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_spatial_elicitation_median').slider('value', value);
    }
    value = $('#slider_spatial_elicitation_upper').slider('value');
    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_spatial_elicitation_upper').slider('value', value);
    }
}

function changeMaximum() {
    var maximum = $('#spatial_elicitation_maximum').val();
    if(!isNaN(maximum) && maximum !== '') {
        $('#slider_spatial_elicitation_lower').slider('option', 'max', parseInt(maximum));
        $('#slider_spatial_elicitation_median').slider('option', 'max', parseInt(maximum));
        $('#slider_spatial_elicitation_upper').slider('option', 'max', parseInt(maximum));
        updateSliders();
    }
}

function enableMinimum() {
    $('#spatial_elicitation_minimum').removeClass('readonly');
    $('#spatial_elicitation_minimum').removeAttr('readonly');
}

function enableMaximum() {
    $('#spatial_elicitation_maximum').removeClass('readonly');
    $('#spatial_elicitation_maximum').removeAttr('readonly');
}

function enablelower() {
    $('#slider_spatial_elicitation_lower').closest('tr').fadeIn();
    $('#spatial_elicitation_lower').removeClass('readonly');
    $('#spatial_elicitation_lower').removeAttr('readonly');
    $('#slider_spatial_elicitation_lower').slider('option', 'disabled', false);
}

function enableMedian() {
    $('#spatial_elicitation_median').closest('tr').fadeIn();
    $('#spatial_elicitation_median').removeClass('readonly');
    $('#spatial_elicitation_median').removeAttr('readonly');
    $('#slider_spatial_elicitation_median').slider('option', 'disabled', false);
}

function enableUpper() {
    $('#spatial_elicitation_upper').closest('tr').fadeIn();
    $('#spatial_elicitation_upper').removeClass('readonly');
    $('#spatial_elicitation_upper').removeAttr('readonly');
    $('#slider_spatial_elicitation_upper').slider('option', 'disabled', false);
}

function setMinimumValue() {
    var contents = $('#spatial_elicitation_minimum').val();
    if(contents != '' && !isNaN(contents)) {
        // Valid minimum - update progress
        $('#task-minimum').switchClass('incomplete', 'complete',0);
        $('#task-minimum td').text('complete');
        // Change minimum of sliders
        changeMinimum();

    } else {
        // This is invalid
        $('#task-minimum').switchClass('complete', 'incomplete',0);
        $('#task-minimum td').text('incomplete');
        // not elicitated - stop drawing
        elicitated = false;
    }
    processProgress();

    adjusting = true;
    if(elicitated && !initialising) {
        plot();
    }
    adjusting = false;
}

function setMaximumValue() {
    var contents = $('#spatial_elicitation_maximum').val();
    if(contents != '' && !isNaN(contents)) {
        // This is a valid number - update progress
        $('#task-maximum').switchClass('incomplete', 'complete',0);
        $('#task-maximum td').text('complete');

        // Change maximum of sliders
        changeMaximum();

    } else {
        $('#task-maximum').switchClass('complete', 'incomplete',0);
        $('#task-maximum td').text('incomplete');
        elicitated = false;
    }
    processProgress();
    // Set adjusting to true to stop rendering multiple times
    adjusting = true;
    if(elicitated && !initialising) {
        plot();
    }
    adjusting = false;
}

function setLowerQuartile() {
    var value = $('#spatial_elicitation_lower').val();
    if(value !== '') {
        $('#task-lower').switchClass('incomplete', 'complete', 0);
        $('#task-lower td').text('complete');
        enableUpper();

    } else {
        $('#task-lower').switchClass('complete', 'incomplete', 0);
        $('#task-lower td').text('incomplete');
        elicitated = false;
    }
    processProgress();
    // redraw
    if(elicitated && !initialising && !adjusting) {
        if(graph != null) {
            graph.setLowerQuartile(value);
        }
        plot();
    }
}

function setMedian() {
    var value = $('#spatial_elicitation_median').val();
    if(value !== '') {
        $('#task-median').switchClass('incomplete', 'complete', 0);
        $('#task-median td').text('complete');
    // redraw

    } else {
        $('#task-median').switchClass('complete', 'incomplete', 0);
        $('#task-median td').text('incomplete');
        elicitated = false;
    }
    processProgress();
    if(elicitated && !initialising && !adjusting) {
        plot();
    }
}

function setUpperQuartile() {
    var value = $('#spatial_elicitation_upper').val();
    if(value !== '') {
        $('#task-upper').switchClass('incomplete', 'complete', 0);
        $('#task-upper td').text('complete');
        enableMedian();

    } else {
        $('#task-upper').switchClass('complete', 'incomplete', 0);
        $('#task-upper td').text('incomplete');
        elicitated = false;
    }
    processProgress();
    // redraw
    if(elicitated && !initialising && !adjusting) {
        plot();
    }
}

function init()
{
    var read_doc = $("input[name='spatial_elicitation[read_briefing_document]']").val();
    var minimum = $('#spatial_elicitation_minimum').val();
    var maximum = $('#spatial_elicitation_maximum').val();
    var lower = $('#spatial_elicitation_lower').val();
    var median = $('#spatial_elicitation_median').val();
    var upper = $('#spatial_elicitation_upper').val();

    // The briefing document has been read, display it.
    if(read_doc == 1) {
        briefingDocumentRead();
    }

    // The minimum value has been entered, display it.
    if(minimum != '' && !isNaN(minimum)) {
        setMinimumValue(minimum);
        enableMinimum();
        enableMaximum();
    }

    if(maximum != '' && !isNaN(maximum)) {
        setMaximumValue(maximum);
        enableMaximum();
        setTimeout("enablelower()",1);
    }

    if(lower != '' && !isNaN(lower)) {
        setLowerQuartile();
        setTimeout("enablelower()", 1);
        setTimeout("enableUpper()", 1);
    }

    if(median != '' && !isNaN(median)) {
        setMedian();
        setTimeout("enableMedian()", 1);
    }

    if(upper != '' && !isNaN(upper)) {
        setUpperQuartile();
        setTimeout("enableUpper()", 1);
        setTimeout("enableMedian()", 1);
    }

    setTimeout("changeMinimum()", 1);
    setTimeout("changeMaximum()", 1);
    setTimeout("updateSliders()", 1);
    setTimeout("unbindEvent()", 100);

    if(elicitated) {
        plot();
    }
    setTimeout("initialising = false", 100);
}

$(document).ready(function(){
    init();

    $('.briefing-document-link').click(function(){
        $('#briefing-document').dialog({
            height: 600,
            width: 420,
            modal: true,
            resizable: false,
            buttons: {
                "I understand": function() {
                    briefingDocumentRead();
                    $(this).dialog("close");
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });
    });

    $('#form_spatial_elicitation_submit').click(function(){
        // unbind the save warning event
        unbindEvent();
        return confirmatoryQuestions();
    });

    $('#spatial_elicitation_minimum').attr('onChange', 'javascript:setMinimumValue();')
    // minimum text change event
    /*$('#spatial_elicitation_minimum').change(function() {
        // validate the contents
        var contents = $(this).val();
        setMinimumValue(contents);
    });*/

    $('#spatial_elicitation_minimum').keyup(function() {
        var contents = $(this).val();
        if(contents != '' && !isNaN(contents)) {
            enableMaximum();
        }
    });

    // maximum text change event
    $('#spatial_elicitation_maximum').attr('onChange', 'javascript:setMaximumValue();');
    /*$('#spatial_elicitation_maximum').change(function(){
        // validate the contents
        var contents = $(this).val();
        setMaximumValue(contents);

    });*/

    $('#spatial_elicitation_maximum').keyup(function(){
        var contents = $(this).val();
        if(contents != '' && !isNaN(contents)) {
            enablelower();
        }
    });


    $('#df_choice').buttonset();


    /*
     * Dirty hack because the jQuery buttonset doesnt work. It has something to
     * do with the location of the radio buttons within the web page.
     */
    $('[for=radio-cdf]').click(function(){
        $('#radio-pdf').removeAttr('checked');
        $('#radio-both').removeAttr('checked');
        $('[for=radio-pdf]').removeClass('ui-state-active');
        $('[for=radio-both]').removeClass('ui-state-active');

        graph.showCDF();
        graph.hidePDF();
    });

    $('[for=radio-pdf]').click(function(){
        $('#radio-cdf').removeAttr('checked');
        $('#radio-both').removeAttr('checked');
        $('[for=radio-cdf]').removeClass('ui-state-active');
        $('[for=radio-both]').removeClass('ui-state-active');

        graph.showPDF();
        graph.hideCDF();
    });

    $('[for=radio-both]').click(function(){
        $('#radio-cdf').removeAttr('checked');
        $('#radio-pdf').removeAttr('checked');
        $('[for=radio-cdf]').removeClass('ui-state-active');
        $('[for=radio-pdf]').removeClass('ui-state-active');

        graph.showPDF();
        graph.showCDF();
    });

});