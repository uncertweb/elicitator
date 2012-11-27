var map = null;
   
function initGeometry(geom_string) {
  var splits = geom_string.split(',');
  var sw_var = new google.maps.LatLng(splits[0], splits[1]);
  var ne_var = new google.maps.LatLng(splits[2], splits[3]);
  var bounds = new google.maps.LatLngBounds(sw_var,  ne_var);
  
  var rect_opts = {
    bounds: bounds,
    map: map,
    editable: false,
    fillColor: 'rgb(160,25,25)',
    geodesic: true,
    strokeColor: 'rgb(160,25,25)',
  };
  var rect = new google.maps.Rectangle(rect_opts);
  map.fitBounds(rect.getBounds());
}

$(document).ready(function() {
  
  var myOptions = {
    center: new google.maps.LatLng(0, 0),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false
  };  
  
  $('#briefing-document').bind('dialogopen', function(event, ui) {
      if(map == null) {
       map = new google.maps.Map(document.getElementById("study-area-map"), myOptions);
      }
      var geom_string = $('#study-area-geometry').text();
      initGeometry(geom_string);
      google.maps.event.trigger(map, 'resize');
  });

});