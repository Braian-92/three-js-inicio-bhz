<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- ALl credits to Turf https://github.com/Turfjs/turf/blob/master/examples/es-modules/index.html -->
  <title>ES Modules</title>
  <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
</head>
<body>
  <div class="container mt-3">
    <div class="row">
      <div class="col-12">
        <svg id="stage" width="500" height="500"></svg>
      </div>
    </div>
  </div>
</body>
  <?php 
  $json = file_get_contents('geoJsonArgentina.json');
  $json_data = json_decode($json,true);
  // $jsonData2 = json_decode(file_get_contents('geoJsonArgentinaV1.json'), true);
  $jsonData3 = json_decode(file_get_contents('geoJsonArgentinaV2.json'), true);
  $GEODATA = [];
  $GEODATA3 = [];

  foreach ($json_data as $ind => $val) {
    foreach ($json_data['features'] as $indF => $valF) {
      $pais        = $valF['properties']['COUNTRY'];
      $provincia   = $valF['properties']['NAME_1'];
      $localidad   = $valF['properties']['NAME_2'];
      $coordenadas = $valF['geometry']['coordinates'];

      $GEODATA[$pais][$provincia][$localidad] = $coordenadas;
    }
  }
  ?>
  <script src="js/librerias/jquery-3.5.1.min.js"></script>
  <script src="js/librerias/bootstrap.bundle.min.js"></script>
  <script type="module">
      import Flatten from "https://unpkg.com/@flatten-js/core?module";
      const { polygon } = Flatten;
      const { unify } = Flatten.BooleanOperations;

      let GEODATA = <?php echo json_encode($GEODATA); ?>;
      let provinciasObj = {}

      function isObjEmpty (obj) {
          return Object.keys(obj).length == 0;
      }

      function desface2D(vec2dTArr, offX, offY){
        let _vec2dTArr = [];
        $.each(vec2dTArr, function (indVecT, Vec2dT) {
          _vec2dTArr.push([Vec2dT[0] + offX, Vec2dT[0] + offY])
        });
        return _vec2dTArr;
      }



      let contador = 0;
      generarDimencionesGeo(GEODATA);
      function generarDimencionesGeo(jsonData){
        $.each(jsonData, function (pais, provincias) {
          $.each(provincias, function (provincia, localidades) {
            // console.log(provincia);
            $.each(localidades, function (licalidad, subsectores) {
              $.each(subsectores, function (indSubsectores, sectores) {
                $.each(sectores, function (sectores, sector) {
                  if(provincia == 'BuenosAires'){
                    if(Object.keys(provinciasObj).length == 0){
                      // provinciasObj[provincia] = polygon(sector);
                      provinciasObj[provincia] = polygon(desface2D(sector, 70, 70));
                      // provinciasObj[provincia] = 1;
                      console.log('inicializar');
                    }else{
                      contador++;
                      if(contador <= 2){
                        const sumaPoligono = polygon(desface2D(sector, 70, 70));
                        provinciasObj[provincia] = unify(provinciasObj[provincia], sumaPoligono);
                        console.log('sumar');
                      }
                    }
                  }
                });
              });
            });
          });
        });
      }

      console.log(provinciasObj['BuenosAires'].vertices)
      document.getElementById("stage").innerHTML = provinciasObj['BuenosAires'].svg();


      // make some construction
      // let s1 = segment(10,10,200,200);
      // let s2 = segment(10,160,200,30);
      // let c = circle(point(200, 110), 50);
      // let ip = s1.intersect(s2);

      // document.getElementById("stage").innerHTML = s1.svg() + s2.svg() + c.svg() + ip[0].svg();


      // import Flatten from "@flatten-js/core"

      // const p1 = polygon([[0, 30], [30, 30], [30, 0], [0, 0]]);
      // const p2 = polygon([[20, 5], [20, 25], [40, 15]]);
      // const p3 = unify(p1, p2);
      // document.getElementById("stage").innerHTML = p3.svg();
      // console.log(p3)
      // console.log(p3.toArray())
      // console.log(p3.vertices)
      // console.log(p3.area())

  </script>
</html>