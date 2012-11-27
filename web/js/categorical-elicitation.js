var results = {};

function briefingDocumentRead() {
    // update the progress to indicate the task has been completed
    $("input[name='categorical_elicitation[read_briefing_document]']").val('1');
    $('#bean_silo').show();
    $('.bean_tin_container').show();
    $('#start_instructions').html('Review the <a class="briefing-document-link">briefing document.</a>')

}

function setResultsValue() {
    // set the hidden value
    delete results["undefined"];
    $('#categorical_elicitation_results').val(JSON.stringify(results));

    var ask = false;
    for(var key in results) {
        if(results[key] > 0) {
            ask = true;
            break;
        }
    }

    if(ask) {
        confirmatoryQuestions();
        return false;
    } else {
        return true;
    }

}

function unbindEvent() {
    $(window).unbind('beforeunload');
}

function confirmatoryQuestions() {
    // clear existing questions
    $("#confirm-statement").text('');
    $("#confirm-probabilities").html('');

    // calculate total beans
    var total_beans = 0;
    for(var key in results) {
        if(key == '') {
            continue;
        }
        total_beans += results[key];
    }

    // convert to probabilities
    var probs = [];
    for(var key in results) {
        if(key == '') {
            continue;
        }
        probs[key] = results[key] / total_beans;
    }
    var length = 0;
    // add probabilities to dialog
    for(var prob_key in probs) {
        length++;
        var tr = $('<tr></tr>').append('<th>' + prob_key + '</th>').append('<td>' + (probs[prob_key] * 100).toFixed(2) + '%</td>');
        $('#confirm-probabilities').append(tr);
    }

    // choose 2 random categories
    var cat1_index = Math.floor(Math.random() * length);
    var cat2_index = Math.floor(Math.random() * length);
    if(length > 1) {
        while(cat2_index == cat1_index) {
            cat2_index = Math.floor(Math.random() * length);
        }
    }

    var cat1_category, cat2_category, cat1_value, cat2_value;
    var index = 0;
    for(prob_key in probs) {
        if(index == cat1_index) {
            cat1_category = prob_key;
            cat1_value = probs[prob_key];
        }
        if(index == cat2_index) {
            cat2_category = prob_key;
            cat2_value = probs[prob_key];
        }
        index++;
    }

    // add statment
    $('#confirm-statement').html("There is a <strong>" + ((cat1_value + cat2_value) * 100).toFixed(0) + "%</strong> chance of being in the category <strong>'" + cat1_category + "'</strong> or <strong>'" + cat2_category + "'</strong>, is this correct?")


    $( "#confirmatory-questions" ).dialog({
        resizable: false,
        height:440,
        width: 400,
        modal: true,
        buttons: {
            "Agree": function() {
                $( this ).dialog( "close" );
                $('#form_categorical_elicitation').submit();
            },
            "Disagree": function() {
                $( this ).dialog( "close" );
            }
        }
    });

    return false;
}

$(document).ready(function() {
    var read_doc = $("input[name='categorical_elicitation[read_briefing_document]']").val();
    // The briefing document has been read, display it.
    if(read_doc == 1) {
        briefingDocumentRead();
    }

    if($('#categorical_elicitation_results').val() == "") {
        // load the results object with the categories
        $('.bean_tin').each(function() {
            var cat = $(this).attr('id');
            results[cat] = 0;
        });
        setResultsValue();
    } else {
        // load the beans.
        results = JSON.parse($('#categorical_elicitation_results').val());
        for(var category in results) {
            // find a container with the id of category
            for(var i = 0; i < results[category]; i++) {
                $('#'+category).append('<div class="bean"></div>');
            }
        }
    }



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

    $('#form_categorical_elicitation_submit').click(function() {
        // unbind the save warning event
        unbindEvent();
        return setResultsValue();
    });


    var removeFrom = "";
    $( ".column" ).sortable({
        connectWith: ".column",
        placeholder: 'bean_placeholder',
        stop: function(event, ui) {
            if(ui.item.parent().attr('id') != 'bean_silo_column') {
                results[ui.item.parent().attr('id')]++;
                if(removeFrom != undefined) {
                    results[removeFrom]--;
                }

                $(window).bind('beforeunload', function(){
                    return 'Your elicitation results have not been saved.\r\nAre you sure you wish to leave?';
                });
            }
        },
        start: function(event, ui) {
            if(ui.item.parent().attr('id') != 'bean_silo_column') {
                removeFrom = ui.item.parent().attr('id');
            } else {
                removeFrom = undefined;
            }

        }
    });

    $(".bean_silo").disableSelection();
    $(".bean").disableSelection();

    $('.bean_tin .bean').click(function() {
        results[$(this).parent().attr('id')]--;
        $(this).remove();
    });

    $('#more_beans').click(function() {
        var newBeansCount = 91 - $('.bean_silo div.bean').size();
        for(var i = 0; i < newBeansCount; i++) {
            $('.bean_silo').append('<div class="bean"></div>');
        }
    });



});