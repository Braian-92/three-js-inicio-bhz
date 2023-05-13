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

   // https://apexcharts.com/docs/options/theme/
    var map = L.map('mapid', {
      // center: [35.205233347514536, -106.74728393554688],
      // center: [ -36.795209, -60.246827],
      center: [-37.13229299359739, -64.13379147648811],
      zoom:4
      // zoom:10
    });

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);



   function getColor(d) {
       return d > 15  ? '#800026' :
              d > 13  ? '#BD0026' :
              d > 11  ? '#E31A1C' :
              d > 6   ? '#FC4E2A' :
              d > 4   ? '#FD8D3C' :
              d > 2   ? '#FEB24C' :
              d > 0   ? '#FED976' :
                         '#FFFFFF';
   }


   function style(feature) {
       return {
           fillColor: getColor(feature.properties.count),
           weight: 3,
           // opacity: 1,
           opacity: feature.properties.count == 0 ? 0 : 1,
           color: 'white',
           dashArray: '3',
           fillOpacity: feature.properties.count == 0 ? 0 : 1
           // fillOpacity: 1
       };
   }

   // lat: 36.2265501474709 4, lng: -107.82312015071511 1
   // 33.852169701407426 2, lng: -105.32922366634011 3

  var bbox = [
    -107.754150390625, // x1
     34.02887183968363, //!y2
    -105.47674560546875, //x2
     36.18615531474442 // y1
  ];
   // lat: -33.174341551002065, lng: -63.705322332680225
   // lat: -41.426253195072704, lng: -56.278564520180225

  // lat: -21.453068633086772, lng: -72.1845705807209
  // lat: -57.72292718324142, lng: -50.93261852860451

  // lat: -21.53484700204879, lng: -76.4033205807209
  // lat: -55.178867663282, lng: -51.4423830807209

  // bbox = [
  //   -63.705322332680225,
  //   -41.426253195072704,
  //   -56.278564520180225,
  //   -33.174341551002065, 
  // ];

  bbox = [
    -72.1845705807209,
    -57.72292718324142,
    -50.93261852860451,
    -21.453068633086772, 
  ];

   map.on('click',function (event) {
      // control.getContainer().innerHTML = "lat: " + event.latlng.lat + ", long: " + event.latlng.lng;
     console.log('wrap', event.latlng.wrap());
  });

   var size = .4;
   var hexgrid = turf.hex(bbox, size);
  for(var x=0;x<Object.keys(hexgrid.features).length;x++){
    hexgrid.features[x].properties.count=0;
  }


   var url = "geoJsonArgentina - copia.geojson";

   let formasProvincias = {};

   // get greater Paris definition
   fetch(url)
     .then(response => response.json())
     .then(fc => {
       // L.geoJson(fc).addTo(map);
      console.log(fc);
        for(x=0;x<fc.features.length;x++){
          // console.log(fc.features[x]['geometry']['coordinates']);
          const provinciaTemp = fc.features[x]['properties']['NAME_1'];
          if(provinciaTemp == 'BuenosAires'){
            for(y=0;y<fc.features[x]['geometry']['coordinates'].length;y++){
              // console.log(fc.features[x]['geometry']['coordinates'][y]);
              for(z=0;z<fc.features[x]['geometry']['coordinates'][y].length;z++){
                // console.log(fc.features[x]['geometry']['coordinates'][y][z]);
                const seccionTemp = fc.features[x]['geometry']['coordinates'][y][z];
                if(typeof formasProvincias[provinciaTemp] == 'undefined'){
                  formasProvincias[provinciaTemp] = seccionTemp;
                  console.log('formasProvincias', formasProvincias[provinciaTemp]);
                  console.log('seccionTemp', seccionTemp);
                }else{
                  console.log('formasProvincias2', formasProvincias[provinciaTemp]);
                  console.log('seccionTemp2', seccionTemp);
                  formasProvincias[provinciaTemp] = turf.union(formasProvincias[provinciaTemp], seccionTemp);
                }
                for(a=0;a<fc.features[x]['geometry']['coordinates'][y][z].length;a++){

                  // console.log(fc.features[x]['geometry']['coordinates'][y][z][a]);
                  p.push(L.marker([
                    fc.features[x]['geometry']['coordinates'][y][z][a][1],
                    fc.features[x]['geometry']['coordinates'][y][z][a][0],
                  ]).toGeoJSON());
                  // var t = L.marker([
                  //   fc.features[x]['geometry']['coordinates'][y][z][a][0],
                  //   fc.features[x]['geometry']['coordinates'][y][z][a][1],
                  // ]);//.addTo(map);
                  // p.push(t.toGeoJSON(t));
                }
              }
            }
          }
          // p.push(L.marker([-36.79081088937694, -59.85845952294767]).toGeoJSON());
          // console.log(fc.features[x].attributes.OBJECTID);
          // console.log(fc);
        }
       test();
        console.log('formasProvincias', formasProvincias);
        var bsGeo = {
         "type": "FeatureCollection",
         "features": [ formasProvincias['BuenosAires'] ] // note features has to be an array
        }
        console.log('bsGeo', bsGeo);

       // add to map
       L.geoJson(bsGeo).addTo(map);
    });

    // var polygonBs = L.polygon([[formasProvincias['BuenosAires']]], {color: 'red'}).addTo(map);
    // L.geoJson(polygonBs).addTo(map)

   function test(){
    // console.log(hexgrid.features);
    for(var i=0;i<hexgrid.features.length;i++){
        // console.log(hexgrid.features[i]['geometry']['coordinates'][0]);
        for(var c=0;c<p.length-1;c++){
          var poly=turf.polygon(hexgrid.features[i]['geometry']['coordinates']);
          // console.log(poly);
          // var poly=turf.polygon(hexgrid.features[y].geometry.coordinates);
          
          if(turf.inside(p[c],poly)){
            hexgrid.features[i].properties.count+=1;
            // console.log('dentro');
          }else{
            // console.log('fuera');

          }
        }//insid
      // for(var j=0;j<hexgrid.features[i]['geometry'][0];j++){
      //   console.log(hexgrid.features[i]['geometry'][0][j]);

      // }
    }

    //   for(var c=0;c<p.length-1;c++){
    //     var poly=turf.polygon(hexgrid.features[y].geometry.coordinates);
        
    //     if(turf.inside(p[c],poly)){
    //       hexgrid.features[y].properties.count+=1;
    //       console.log(hexgrid.features[y].properties.count);
    //     }
    //   }//inside inside for
      
    // }//end for
    // L.geoJson(hexgrid,{style: style}).addTo(map);  
   }

   // var polygon = turf.polygon([[[-81, 41], [-88, 36], [-84, 31], [-80, 33], [-77, 39], [-81, 41]]]);
   // var center = turf.centerOfMass(polygon);

  </script>
</html>