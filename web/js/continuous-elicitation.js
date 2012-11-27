var elicitated = false;
var initialising = false;
var adjusting = false;
var graph = null;

function briefingDocumentRead() {
    // update the progress to indicate the task has been completed
    $("input[name='continuous_elicitation[read_briefing_document]']").val('1');
    $('#task-briefing').switchClass('incomplete', 'complete', 0);
    $('#task-briefing td').text('complete');
    $('#start_instructions').hide();
    $('#elicitation').show();
    $('#start_instructions').html('Review the <a class="briefing-document-link">briefing document</a>')
    processProgress();
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
            // invalid distribution
            /*
                $('#dialog').attr('title','Error!');
                $('#dialog').html('<p>A suitable distribution that matches the values you provided could not be found. Please try adjusting the sliders above.</p><p>Alternatively, if you believe the values you have chosen are correct you can save your progress.</p>');
                $('#dialog').dialog({
                    modal: true,
                    width: 400,
                    height: 300,
                    resiable: false,
                    dragable: false,
                    buttons: {
                        "OK": function() {
                            $( this ).dialog( "close" );
                        }
                    }
                })
                */
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
    var minimumValue = $('#continuous_elicitation_minimum').val();
    var maximumValue = $('#continuous_elicitation_maximum').val();
    var lowerValue = $('#continuous_elicitation_lower').val();
    var medianValue = $('#continuous_elicitation_median').val();
    var upperValue = $('#continuous_elicitation_upper').val();

    //var distribution = DistributionFactory.build($('input[name=sf_distribution]').val());

    $('#plot_container').show();
    $('#df_choice').show();

    loadData(minimumValue, maximumValue,lowerValue,medianValue,upperValue);
//
}

function calculatePercentage() {
    var complete = $('table.progress tr.complete').length;
    var total = $('table.progress tbody tr').length;
    return Math.round(complete/total*100);
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

function updateSliders() {
    var value = $('#slider_continuous_elicitation_lower').slider('value');
    var minimum = $('#continuous_elicitation_minimum').val();

    // TODO there is a bug here that if you change the minimum to be higher
    // than the values of a slider - they wont update...

    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_continuous_elicitation_lower').slider('value', value);
    }
    value = $('#slider_continuous_elicitation_median').slider('value');
    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_continuous_elicitation_median').slider('value', value);
    }
    value = $('#slider_continuous_elicitation_upper').slider('value');
    if(value !== '' && minimum !== '' && !isNaN(value) && value != minimum) {
        $('#slider_continuous_elicitation_upper').slider('value', value);
    }
}

function changeMinimum() {
    var minimum = $('#continuous_elicitation_minimum').val();
    if(!isNaN(minimum) && minimum !== '') {
        // Change the sliders
        $('#slider_continuous_elicitation_lower').slider('option', 'min', parseInt(minimum));
        $('#slider_continuous_elicitation_median').slider('option', 'min', parseInt(minimum));
        $('#slider_continuous_elicitation_upper').slider('option', 'min', parseInt(minimum));
        updateSliders();
    }
}

function changeMaximum() {
    var maximum = $('#continuous_elicitation_maximum').val();
    if(!isNaN(maximum) && maximum !== '') {
        $('#slider_continuous_elicitation_lower').slider('option', 'max', parseInt(maximum));
        $('#slider_continuous_elicitation_median').slider('option', 'max', parseInt(maximum));
        $('#slider_continuous_elicitation_upper').slider('option', 'max', parseInt(maximum));
        updateSliders();
    }
}



function enableMinimum() {
    $('#continuous_elicitation_minimum').removeClass('readonly');
    $('#continuous_elicitation_minimum').removeAttr('readonly');
}

function enableMaximum() {
    $('#continuous_elicitation_maximum').removeClass('readonly');
    $('#continuous_elicitation_maximum').removeAttr('readonly');
}

function enablelower() {
	$('#slider_continuous_elicitation_lower').closest('tr').fadeIn();
    $('#continuous_elicitation_lower').removeClass('readonly');
    $('#continuous_elicitation_lower').removeAttr('readonly');
    $('#slider_continuous_elicitation_lower').slider('option', 'disabled', false);
}

function enableMedian() {
	$('#continuous_elicitation_median').closest('tr').fadeIn();
    $('#continuous_elicitation_median').removeClass('readonly');
    $('#continuous_elicitation_median').removeAttr('readonly');
    $('#slider_continuous_elicitation_median').slider('option', 'disabled', false);
}

function enableUpper() {
	$('#continuous_elicitation_upper').closest('tr').fadeIn();
    $('#continuous_elicitation_upper').removeClass('readonly');
    $('#continuous_elicitation_upper').removeAttr('readonly');
    $('#slider_continuous_elicitation_upper').slider('option', 'disabled', false);
}

function setMinimumValue() {
    var contents = $('#continuous_elicitation_minimum').val();
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
    var contents = $('#continuous_elicitation_maximum').val();
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
    var value = $('#continuous_elicitation_lower').val();
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
    var value = $('#continuous_elicitation_median').val();
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
    var value = $('#continuous_elicitation_upper').val();
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

function unbindEvent() {
    $(window).unbind('beforeunload');
}

function confirmatoryQuestions() {
    // check progress...
    // if progress is < 100 then just continue
    if(calculatePercentage() < 100) {
        return true;
    }

    // clear existing questions
    $("#confirmatory-questions ol").html('');

    // get the distribution...
    var dist = graph.getDistribution();

    // Set the distribution name
    $('#elicit_dist_name').text(dist.getName());

    // create confirmatory questions
    var unlikelyX = dist.getQuantile(0.99).toFixed(2);
    var q1 = "Your responses suggest that a value of <strong>" + unlikelyX + "</strong> is highly unlikely.";
    var oneInFiveLower = dist.getQuantile(0.1).toFixed(2);
    var oneInFiveUpper = dist.getQuantile(0.9).toFixed(2);
    var q2 = "Given the fitted density, there is a <strong>1 in 5</strong> chance of the variable falling outside the range (<strong>" + oneInFiveLower + ", " + oneInFiveUpper + "</strong>)";

    $("#confirmatory-questions ol").append("<li>" + q1 + "</li>");
    $("#confirmatory-questions ol").append("<li>" + q2 + "</li>");

    $( "#confirmatory-questions" ).dialog({
        resizable: false,
        height:440,
        width: 400,
        modal: true,
        buttons: {
            "Agree": function() {
                $( this ).dialog( "close" );
                $('#form_continuous_elicitation').submit();
            },
            "Disagree": function() {
                $( this ).dialog( "close" );
            }
        }
    });

    return false;
}

/*
 * Initializes the page, showing the relevant sections
 */
function init() {
    initialising = true;
    elicitated = false;
    var read_doc = $("input[name='continuous_elicitation[read_briefing_document]']").val();
    var minimum = $('#continuous_elicitation_minimum').val();
    var maximum = $('#continuous_elicitation_maximum').val();
    var lower = $('#continuous_elicitation_lower').val();
    var median = $('#continuous_elicitation_median').val();
    var upper = $('#continuous_elicitation_upper').val();

	$('div[id*=slider_continuous_elicitation]').closest('tr').hide();

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

    $('#form_continuous_elicitation_submit').click(function(){
        // unbind the save warning event
        unbindEvent();
        return confirmatoryQuestions();
    });

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

    $('#continuous_elicitation_minimum').attr('onChange', 'javascript:setMinimumValue();')
    // minimum text change event
    /*$('#continuous_elicitation_minimum').change(function() {
        // validate the contents
        var contents = $(this).val();
        setMinimumValue(contents);
    });*/

    $('#continuous_elicitation_minimum').keyup(function() {
        var contents = $(this).val();
        if(contents != '' && !isNaN(contents)) {
            enableMaximum();
        }
    });

    // maximum text change event
    $('#continuous_elicitation_maximum').attr('onChange', 'javascript:setMaximumValue();');
    /*$('#continuous_elicitation_maximum').change(function(){
        // validate the contents
        var contents = $(this).val();
        setMaximumValue(contents);

    });*/

    $('#continuous_elicitation_maximum').keyup(function(){
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

