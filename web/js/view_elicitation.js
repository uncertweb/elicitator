$(document).ready(function(){
    var dist = new NormalDistribution(0,1);
    var plot = new ContinuousPlot('plot_container', dist);
});