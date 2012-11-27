var dist;
var plot;
var paramValues =[0.0,1.0];
var index = -1;
var commands = [];
var numPoints = 100;

function nMeanChange(value) {
    paramValues[0] = value;
    dist = new NormalDistribution(paramValues[0], paramValues[1]);
    $('#param1-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function nSDChange(value) {
    paramValues[1] = value;
    dist = new NormalDistribution(paramValues[0], paramValues[1]);
    $('#param2-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function bAlphaChange(value) {
    paramValues[0] = value;
    dist = new BetaDistribution(paramValues[0], paramValues[1]);
    $('#param1-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function bBetaChange(value) {
    paramValues[1] = value;
    dist = new BetaDistribution(paramValues[0], paramValues[1]);
    $('#param2-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function gShapeChange(value) {
    paramValues[0] = value;
    dist = new GammaDistribution(paramValues[0], paramValues[1]);
    $('#param1-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function gScaleChange(value) {
    paramValues[1] = value;
    dist = new GammaDistribution(paramValues[0], paramValues[1]);
    $('#param2-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function lLocationChange(value) {
    paramValues[0] = value;
    dist = new LogNormalDistribution(paramValues[0], paramValues[1]);
    $('#param1-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function lScaleChange(value) {
    paramValues[1] = value;
    dist = new LogNormalDistribution(paramValues[0], paramValues[1]);
    $('#param2-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function tDOFChange(value) {
    paramValues[0] = value;
    dist = new StudentTDistribution(paramValues[0], paramValues[1]);
    $('#param1-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

function tNCPChange(value) {
    paramValues[1] = value;
    dist = new StudentTDistribution(paramValues[0], paramValues[1]);
    $('#param2-value').text(value);
    plot.setDistribution(dist, dist.getRange(5, numPoints));
}

$(document).ready(function() {

    $('#console-button').button();
    $('#d-radio').buttonset();
    $('#p-radio').buttonset();
    $('#param1').slider({
        slide: function(event, ui) {
            nMeanChange(ui.value);
        },
        step: 0.1,
        min: -100.0,
        value: 0.0
    });
    $('#param2').slider({
        slide: function(event, ui) {
            nSDChange(ui.value);
        },
        step: 0.1,
        min: 1.0,
        value: 1.0
    });

    // click events
    $('label[for=pdf]').click(function(){
        plot.hideCDF();
        plot.showPDF();
    });

    $('label[for=cdf]').click(function(){
        plot.showCDF();
        plot.hidePDF();
    });

    $('label[for=both]').click(function(){
        plot.showCDF();
        plot.showPDF();
    });

    $('label[for=beta]').click(function() {
        // change to beta distribution
        //
        // initial values
        paramValues[0] = 2.0;
        paramValues[1] = 2.0;
        dist = new BetaDistribution(paramValues[0], paramValues[1]);
        plot.setDistribution(dist, dist.getRange(5, numPoints));

        // change the slider labels
        $('#param1-name').text('Alpha');
        $('#param2-name').text('Beta');

        // init values
        $('#param1-value').text(paramValues[0]);
        $('#param2-value').text(paramValues[1]);

        // setup sliders
        $('#param1').slider({
            slide: function(event, ui) {
                bAlphaChange(ui.value);
            },
            step: 0.01,
            min: 0.1,
            max: 20,
            value: paramValues[0]
        });

        $('#param2').slider({
            slide: function(event, ui) {
                bBetaChange(ui.value);
            },
            step: 0.01,
            min: 0.1,
            max: 20,
            value: paramValues[1]
        });

    });

    $('label[for=normal]').click(function() {
        // change to beta distribution
        //
        // initial values
        paramValues[0] = 0.0;
        paramValues[1] = 1.0;
        dist = new NormalDistribution(paramValues[0], paramValues[1]);
        plot.setDistribution(dist, dist.getRange(5, numPoints));

        // change the slider labels
        $('#param1-name').text('Mean');
        $('#param2-name').text('Variance');

        // init values
        $('#param1-value').text(paramValues[0]);
        $('#param2-value').text(paramValues[1]);

        // setup sliders
        $('#param1').slider({
            slide: function(event, ui) {
                nMeanChange(ui.value);
            },
            step: 0.1,
            min: -100.0,
            max: 100,
            value: paramValues[0]
        });

        $('#param2').slider({
            slide: function(event, ui) {
                nSDChange(ui.value);
            },
            step: 0.1,
            min: 0.1,
            value: paramValues[1]
        });

    });

    $('label[for=gamma]').click(function() {
        // change to beta distribution
        //
        // initial values
        paramValues[0] = 1.0;
        paramValues[1] = 2.0;
        dist = new GammaDistribution(paramValues[0], paramValues[1]);
        plot.setDistribution(dist, dist.getRange(5, numPoints));

        // change the slider labels
        $('#param1-name').text('Shape');
        $('#param2-name').text('Scale');

        // init values
        $('#param1-value').text(paramValues[0]);
        $('#param2-value').text(paramValues[1]);

        // setup sliders
        $('#param1').slider({
            slide: function(event, ui) {
                gShapeChange(ui.value);
            },
            step: 0.1,
            min: 1.0,
            value: paramValues[0]
        });

        $('#param2').slider({
            slide: function(event, ui) {
                gScaleChange(ui.value);
            },
            step: 0.1,
            min: 1.0,
            value: paramValues[1]
        });

    });

    $('label[for=log-normal]').click(function() {
        // change to beta distribution
        //
        // initial values
        paramValues[0] = 0.0;
        paramValues[1] = 1/4;
        dist = new LogNormalDistribution(paramValues[0], paramValues[1]);
        plot.setDistribution(dist, dist.getRange(5, numPoints));

        // change the slider labels
        $('#param1-name').text('Location');
        $('#param2-name').text('Scale');

        // init values
        $('#param1-value').text(paramValues[0]);
        $('#param2-value').text(paramValues[1]);

        // setup sliders
        $('#param1').slider({
            slide: function(event, ui) {
                lLocationChange(ui.value);
            },
            step: 0.0001,
            min: -5.0,
            max: 10,
            value: paramValues[0]
        });

        $('#param2').slider({
            slide: function(event, ui) {
                lScaleChange(ui.value);
            },
            step: 0.0001,
            min: 0.1,
            max: 10,
            value: paramValues[1]
        });

    });

    $('label[for=student-t]').click(function() {
        // change to beta distribution
        //
        // initial values
        paramValues[0] = 5;
        paramValues[1] = 0.0;
        dist = new StudentTDistribution(paramValues[0], paramValues[1]);
        plot.setDistribution(dist, dist.getRange(5, numPoints));

        // change the slider labels
        $('#param1-name').text('Degrees of freedom');
        $('#param2-name').text('Non-centraility parameter');

        // init values
        $('#param1-value').text(paramValues[0]);
        $('#param2-value').text(paramValues[1]);

        // setup sliders
        $('#param1').slider({
            slide: function(event, ui) {
                tDOFChange(ui.value);
            },
            step: 0.1,
            min: 3.0,
            max: 100,
            value: paramValues[0]
        });

        $('#param2').slider({
            slide: function(event, ui) {
                tNCPChange(ui.value);
            },
            step: 0.01,
            min: 0,
            max: 50,
            value: paramValues[1]
        });

    });

    $('body').keyup(function(event) {
        if(event.which == 27) {
            $('#console-dialog').slideUp(200);
      
        }
    })

    $('#console-button').click(function() {
        $('#console-dialog').slideDown(500, function() {
            $('#prompt').focus();
        });
    });

    //    $('#console-button').click(function() {
    //        $(this).fadeOut();
    //
    //        $('#console-dialog').slideDown(500, function() {
    //            $('#prompt').focus();
    //        });
    //    });

    $('#console-dialog').click(function() {
        $('#prompt').focus();
    });

    $('#console').click(function() { 
        $('#prompt').focus();
    });

    $('#type-radio').buttonset();

    // click events
    $('label[for=line]').click(function(){
        plot.setType('line');
    });
    $('label[for=points]').click(function(){
        plot.setType('points');
    });
    $('label[for=type-both]').click(function(){
        plot.setType('both');
    });

    $('#fill-radio').buttonset();
    $('label[for=fill-on]').click(function(){
        plot.setFill(true);
    });
    $('label[for=fill-off]').click(function(){
        plot.setFill(false)
    });

    $('#hover-radio').buttonset();
    $('label[for=hover-on]').click(function() {
        plot.setHover(true);
    });
    $('label[for=hover-off]').click(function() {
        plot.setHover(false);
    });


    $('#num-points').slider({
        min: 20,
        max: 1000,
        step: 20,
        slide: function(event, ui) {
            $('#num-points-title').text('Number of points: ' + ui.value);
            numPoints = ui.value;
            plot.setDistribution(dist, dist.getRange(5, numPoints));
        },
        value: 100
    })
    //alert(jstat.gamma(14));

    $('#prompt').keyup(function(event) {
        //        /alert(event.which);
        // 13 == enter;
        if(event.which == 13) {
            var command = $('#prompt').val();
            // save it
            commands.push(command);
            // set index
            index = commands.length;
            if(command == ':clear') {
                $('#console-table').html('<tr></tr>');
            } else if(command == ':exit') {
                $('#console-dialog').slideUp(200);
            } else {
                try {
                    var execCommand = 'with(jstat) { ' + command + ' }';
                    var res = eval(execCommand);   // eval the code

                    if($('#console-table').html() != '') {
                        $('#console-table tr:last').after('<tr><td>> <span class="command">' + command  +  '</span></td></tr>');
                    } else {
                        $('#console-table tr:last').after('<tr><td>> <span class="command">'  + command + '</span></td></tr>');
                    }

                    if(res != undefined && res != '' && command.indexOf('=') == -1) {
                        // print the result
                        if(res.constructor.toString().indexOf("Array") != -1) {
                            // print array
                            var array = '[' + res.length + '] ';
                            for(var i = 0; i < res.length; i++) {
                                array += res[i] + ' ';
                            }
                            $('#console-table tr:last').after('<tr><td><span class="data">' + array.trim() + '</span></td></tr>');
                        } else if(!isNaN(res)) {
                            // single numeric variable
                            $('#console-table tr:last').after('<tr><td><span class="data">[1] ' + res + '</span></td></tr>');
                        } else {
                            // string variable
                            $('#console-table tr:last').after('<tr><td><span class="data">[1] "' + res + '"</span></td></tr>');
                        }

                    }

                    
                } catch(err) {
                    if($('#console-table').html() != '') {
                        $('#console-table tr:last').after('<tr><td>> <span class="command">' + command  +  '</span></td></tr>');
                    } else {
                        $('#console-table tr:last').after('<tr><td>> <span class="command">'  + command + '</span></td></tr>');
                    }
                    // error
                    $('#console-table tr:last').after('<tr><td><span class="error">'+ err + '</span></td></tr>');
                    
                }
            }
            
            $(this).val('');
        } else if(event.which == 38) {  // up arrow
            if(index != -1) {
                if(index > 0) {
                    index--;
                }
                $('#prompt').val(commands[index]);
            }
            
        } else if(event.which == 40) {// down arrow
            if(index < commands.length) {
                index ++;
                $('#prompt').val(commands[index]);
            }
        }
    });

    x = [1,2,3];
    y = [3,2,1];

    dp.SyntaxHighlighter.HighlightAll('code');

    // Init to normal distribution
    dist = new NormalDistribution(0,1);
    plot = new DistributionPlot('plot', dist);

    $('span.info').qtip({
        position: {
            corner: {
                target: 'topMiddle',
                tooltip: 'bottomMiddle'
            }
        },
        show: 'mouseover',
        hide: 'mouseout',
        style: {
            name: 'blue',
            border: {
                width: 3,
                radius: 5
            },
            textAlign: 'center',
            tip: 'bottomMiddle'
        }
    });
    
});

