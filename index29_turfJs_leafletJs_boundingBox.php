<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ES Modules</title>
  <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
  <style type="text/css">
    #mapid { height: 600px; }
  </style>
</head>
<body>
  <div class="container mt-3">
    <div class="row">
      <div class="col-12">
        <div id="mapid"></div>
      </div>
    </div>
  </div>
</body>
  <script src="js/librerias/jquery-3.5.1.min.js"></script>
  <script src="js/librerias/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <script src='https://unpkg.com/@turf/turf@6.3.0/turf.min.js'></script>
  <script type="text/javascript">
   //Some Points
   var somePoints = [
     [-3.535,55.62],
     [-3.54,55.61],
     [-3.547,55.6],
     [-3.55,55.59],
     [-3.57,55.58]
   ];

   var features = turf.points(somePoints);
   var center = turf.center(features);


   var map = L.map('mapid').setView([55.6, -3.55], 12);

   L.tileLayer(
       'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
       attribution: '&copy; <a href="https://openstreetmap.org" target=blank>OpenStreetMap</a> Contributors',
       maxZoom: 18,
   }).addTo(map);
     

   var line = turf.lineString(somePoints);
   var options = {units: 'miles'};
   L.geoJSON(line).addTo(map);
   
   var along = turf.along(line, 1.25, options);
   L.geoJSON(along).addTo(map).bindTooltip("my tooltip text");

   along = turf.along(line, 2.123, options);
   L.geoJSON(along).addTo(map).bindTooltip("12345");

   var bbox = turf.bbox(line);
   var bboxPolygon = turf.bboxPolygon(bbox);
   
   L.geoJSON(bboxPolygon ).addTo(map)
   

   var features = turf.points(somePoints);
   var center = turf.center(features);
  </script>
</html>