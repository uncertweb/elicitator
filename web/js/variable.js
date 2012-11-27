$(document).ready(function(){

    $(window).scroll(function(){
        var offset = $(document).scrollTop();
        if($('#template').is(':visible')) {
            $('#template').parent('div').animate({
                marginTop: offset + 'px'
            }, 200)
        }

    });

    $('#show-template').click(function() {
        $('#template').dialog({
            height: 700,
            width: 400,
            position: ['right','top']
        });
        return false;
    });

    $('a[id*=_copy]').each(function() {
        var element = $(this).attr('id').replace('_copy', '');
        $(this).click(function() {
            var id = '#' + element + '_template';
            var content = $(id).html();
            tinyMCE.get('variable_' + element).setContent(content);
            return false;
        });
    })

    // hide parameters if not categorical
    var value = $("#variable_variable_type option:selected").val();
    if(value != "Categorical") {
        $('#parameters_help_row').hide();
        $('#parameters_row').hide();
    }
    if(value == "Spatial") {
      $('#tabs').tabs();
    } else {
      $('#tabs').tabs({
        disabled: [2]
      })
    }


    $('#variable_variable_type').change(function() {
        var value = $("#variable_variable_type option:selected").val();
        if(value == "Categorical") {
            // show the text box
            $('#parameters_help_row').show();
            $('#parameters_row').show();
        } else {
            $('#parameters_help_row').hide();
            $('#parameters_row').hide();
        }
        if(value == "Spatial") {
          // enable the tab
          $('#tabs').tabs('option', 'disabled', []);
        } else {
          $('#tabs').tabs('option', 'disabled', [2]);
        }
    });

});