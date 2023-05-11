<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ES Modules</title>
  <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
  <style type="text/css">
    #mapid { height: 200px; }
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
    // center map on Paris
    var map = L.map('mapid', {
        center: [48.856, 2.352],
        zoom: 9,
        maxZoom: 18,
        minZoom: 1
    });

    // add tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var url = "https://gist.githubusercontent.com/robinmackenzie/937e5bd42a0412c21281f69b8f0c8614/raw/fbed7c2783366463a250e4bb0ebcf3c5f6d54dfe/greaterParis.geo.json";

    // get greater Paris definition
    fetch(url)
      .then(response => response.json())
      .then(fc => { 

        // feature ids to remove e.g. central Paris
        var removeIds = [868, 869, 870, 871, 872, 873, 874, 875, 876, 877, 878, 879, 880, 881, 882, 883, 884, 885, 886, 887];

        // filter features to remove central Paris
        var hole = fc.features.filter(f => !removeIds.includes(f.properties.ID_APUR))

        // do the union over each feature
        var union = hole[0];
        for (let i=1; i<hole.length; i++) {
          union = turf.union(union, hole[i]);
        }

        // new Feature collection with unioned features
        var fc2 = {
          "type": "FeatureCollection",
          "features": [union] // note features has to be an array
        }

        // add to map
        L.geoJson(fc2).addTo(map);
      
      });
  </script>
</html>