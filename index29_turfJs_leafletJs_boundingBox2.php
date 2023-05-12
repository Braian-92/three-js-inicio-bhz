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
  <!-- <script src='https://unpkg.com/@turf/turf@6.3.0/turf.min.js'></script> -->
  <script src="//api.tiles.mapbox.com/mapbox.js/plugins/turf/v1.4.0/turf.min.js"></script>
  <script type="text/javascript">
   var p=[];


    var map = L.map('mapid', {
      center: [35.205233347514536, -106.74728393554688],
      zoom:10
    });

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);



   function getColor(d) {
       return d > 15 ? '#800026' :
              d > 13  ? '#BD0026' :
              d > 11  ? '#E31A1C' :
              d > 6  ? '#FC4E2A' :
              d > 4   ? '#FD8D3C' :
              d > 2   ? '#FEB24C' :
              d > 0   ? '#FED976' :
                         '#FFFFFF';
   }


   function style(feature) {
       return {
           fillColor: getColor(feature.properties.count),
           weight: 2,
           opacity: 1,
           color: 'white',
           dashArray: '3',
           fillOpacity: 0.7
       };
   }

   var bbox = [
    -106.754150390625,
     35.02887183968363,
    -106.47674560546875,
     35.18615531474442
    ];
   // lat: -33.174341551002065, lng: -63.705322332680225
   // lat: -41.426253195072704, lng: -56.278564520180225

  // bbox = [
  //   -41.426253195072704,
  //   -33.174341551002065, 
  //   -56.278564520180225,
  //   -63.705322332680225,
  // ];
   var size = .01;
   var hexgrid = turf.hex(bbox, size);
  for(var x=0;x<Object.keys(hexgrid.features).length;x++){
    hexgrid.features[x].properties.count=0;
  }


   var url = "geoJsonArgentina - copia.geojson";

   // get greater Paris definition
   fetch(url)
     .then(response => response.json())
     .then(fc => {
       // L.geoJson(fc).addTo(map);
        for(x=0;x<Object.keys(hexgrid.features).length;x++){
          // console.log(fc.features[x].attributes.OBJECTID);
          console.log(fc);
          // var t = L.marker([fc.features[x].geometry.y,fc.features[x].geometry.x]);//.addTo(map);
          // p.push(t.toGeoJSON());
        }
       test();
    });


   function test(){
    for(var y=0;y<Object.keys(hexgrid.features).length-1;y++){

      for(var c=0;c<p.length-1;c++){
        var poly=turf.polygon(hexgrid.features[y].geometry.coordinates);
        
        if(turf.inside(p[c],poly)){
          hexgrid.features[y].properties.count+=1;
          console.log(hexgrid.features[y].properties.count);
        }
      }//inside inside for
      
    }//end for
    L.geoJson(hexgrid,{style: style}).addTo(map);  
   }

   // var polygon = turf.polygon([[[-81, 41], [-88, 36], [-84, 31], [-80, 33], [-77, 39], [-81, 41]]]);
   // var center = turf.centerOfMass(polygon);

  </script>
</html>