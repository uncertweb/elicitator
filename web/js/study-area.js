var last_shape = null;
var map = null;

function updateGeometry(rectangle) {
  $('#variable_study_area_geometry').val("" + rectangle.getBounds().toUrlValue());
}
      
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
  last_shape = rect;
  map.fitBounds(last_shape.getBounds());
}

$(document).ready(function() {
  
  var myOptions = {
    center: new google.maps.LatLng(0, 0),
    zoom: 4,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
   
  var drawingManager = null;
  
  var geom;
  
  
  
  $('#tabs').bind('tabsshow', function(event, ui) {
    if (ui.panel.id == 'study-area')
    {
      if(map == null) {
       map = new google.maps.Map(document.getElementById("study-area-map"), myOptions);
      }
      
      if(drawingManager == null) {
        drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: google.maps.drawing.OverlayType.RECTANGLE,
          drawingControl: true,
          drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [google.maps.drawing.OverlayType.RECTANGLE]
          },
          rectangleOptions: {
            editable: false,
            fillColor: 'rgb(160,25,25)',
            geodesic: true,
            strokeColor: 'rgb(160,25,25)',
          }
        });
        drawingManager.setMap(map);
        
        // bind the polygon complete event
        google.maps.event.addListener(drawingManager, 'rectanglecomplete', function(rectangle) {
            // delete other rectangles
            if(last_shape != null) {
              last_shape.setMap(null);
              last_shape = null;
            }
            // update geometry
            updateGeometry(rectangle);
            // set last shape
            last_shape = rectangle;
        });        
        
      }
      if($('#variable_study_area_geometry').val() != '') {
        initGeometry($('#variable_study_area_geometry').val());
      }
      google.maps.event.trigger(map, 'resize');
    }
  });

});