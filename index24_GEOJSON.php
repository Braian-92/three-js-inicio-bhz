<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .cursor-pointer{
      cursor: pointer;
    }

    .wrapper .content-wrapper {
      min-height: 100vh;
    }
  </style>
</head>

<body class="dark-mode text-sm">
  <div class="wrapper">
    <div class="content-wrapper kanban" style="margin-left: 0!important">
      <section class="content p-2">
        <div class="row h-100">
          <div class="col-12 h-100">
            <div class="card card-row card-default w-100 h-100">
              <div class="card-header bg-olive d-none">
                <h3 class="card-title">
                  Nueva
                </h3>
                <div class="card-tools d-none">
                  <div class="btn-group">
                    <a href="#" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                      <i class="fas fa-pen text-white"></i>
                    </a>
                    
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Editar</a>
                      <a href="#" class="dropdown-item">Eliminar</a>
                      <a href="#" class="dropdown-item">Asignados</a>
                      <div class="dropdown-divider bg-gray-50"></div>
                      <a href="#" class="dropdown-item">Invitar</a>
                    </div>
                  </div>
                  <a id="nuevaTarea" href="#" class="btn btn-tool">
                    <i class="fas fa-plus text-white"></i>
                  </a>
                </div>
                <div class="card-tools d-none">
                  <div class="btn-group">
                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                      <i class="fas fa-bars"></i>
                    </button>
                    <div class="dropdown-menu" role="menu">
                      <a href="#" class="dropdown-item">Add new event</a>
                      <a href="#" class="dropdown-item">Clear events</a>
                      <div class="dropdown-divider"></div>
                      <a href="#" class="dropdown-item">View calendar</a>
                    </div>
                    <button id="nuevaTarea" type="button" class="btn btn-info btn-sm" title="Agregar Tarea">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div id="pantalla" class="card-body p-0 m-0 h-100">
                
              </div>
            </div>            
          </div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
<script src="js/librerias/jquery-3.5.1.min.js"></script>
<script src="js/librerias/bootstrap.bundle.min.js"></script>
<script type="importmap">
  {
    "imports": {
      "three": "./threeJsMaster/build/three.module.js",
      "three/addons/": "./threeJsMaster/examples/jsm/"
    }
  }
</script>

<script async src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js"></script>

<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { TransformControls } from 'three/addons/controls/TransformControls.js';

  const pantalla = document.querySelector('#pantalla');
  let elementoSeleccionadoListado = null;
  var ESCENA = new THREE.Scene();
  let GRUPO = new THREE.Group();
  var RENDERIZADO = new THREE.WebGLRenderer();
  var CAMARA;
  init();

  function init() {
    ESCENA.name = 'ESCENA';
    ESCENA.background = new THREE.Color(0x2a3b4c);
    CAMARA = new THREE.PerspectiveCamera(
      75,
      window.innerWidth / window.innerHeight
    );
    CAMARA.name = 'CAMARA';
    CAMARA.position.z = 20;
    RENDERIZADO.setSize(pantalla.clientWidth , pantalla.clientHeight);
    pantalla.appendChild(RENDERIZADO.domElement);
    const GRID = new THREE.GridHelper(30, 30);
    GRID.name = 'GRID';
    ESCENA.add(GRID);
    var controls = new OrbitControls(CAMARA, RENDERIZADO.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.5;

    controls.maxPolarAngle = Math.PI;

    ESCENA.add( CAMARA );
    ESCENA.add( GRUPO );
    const light = new THREE.PointLight( 0xffffff, 0.8 );
    CAMARA.add( light );
    controls.screenSpacePanning = true;
    RENDERIZADO.render(ESCENA, CAMARA);

    GRUPO.position.y = 45;
    GRUPO.position.x = 70;


    GRUPO.rotation.x = - Math.PI / 2;
    GRUPO.position.y = 0; // altura
    GRUPO.position.x = 65;
    GRUPO.position.z = -40;

  }

  var animate = function () {
    requestAnimationFrame(animate);
    RENDERIZADO.render(ESCENA, CAMARA);
  };
  animate();

  window.addEventListener('resize', function() {
    CAMARA.aspect = pantalla.clientWidth / pantalla.clientHeight;
    CAMARA.updateProjectionMatrix();
    RENDERIZADO.setSize(pantalla.clientWidth, pantalla.clientHeight);
  });


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


  foreach ($jsonData3 as $ind => $val) {
    foreach ($json_data['features'] as $indF => $valF) {
      $pais        = $valF['properties']['COUNTRY'];
      $provincia   = $valF['properties']['NAME_1'];
      $coordenadas = $valF['geometry']['coordinates'];

      $GEODATA3[$pais][$provincia][] = $coordenadas;
    }
  }
  ?>

  const extrudeSettings = { depth: 1, bevelEnabled: true, bevelSegments: 2, steps: 2, bevelSize: 0, bevelThickness: 0 };
  let GEODATA = <?php echo json_encode($GEODATA); ?>;
  let GEODATA3 = <?php echo json_encode($GEODATA3); ?>;
  console.log(GEODATA);
  console.log(GEODATA3);
  let primero = false;
  generarDimencionesGeo(GEODATA);

  function generateRandomIntegerInRange(min, max) {
      // return Math.floor(Math.random() * (max - min + 1)) + min;
      return Math.random() * (max - min + 1) + min;
  }

  function addShape( shape, extrudeSettings, color) {
    let geometry = new THREE.ExtrudeGeometry( shape, extrudeSettings );

    let mesh = new THREE.Mesh( geometry, new THREE.MeshPhongMaterial( { color: color } ) );
    // mesh.position.set( x, y, z - 75 );
    // mesh.rotation.set( rx, ry, rz );
    // mesh.scale.set( s, s, s );
    GRUPO.add( mesh );
    CAMARA.lookAt(mesh.position)

  }
  function generarDimencionesGeo(jsonData){
    $.each(jsonData, function (pais, provincias) {
      $.each(provincias, function (provincia, localidades) {
          let colorLocalidadT = random_color( 'hex' );
        $.each(localidades, function (licalidad, subsectores) {
          extrudeSettings.depth = generateRandomIntegerInRange(.5, 1);
          
          $.each(subsectores, function (indSubsectores, sectores) {
            $.each(sectores, function (sectores, sector) {
              // if(!primero){
                // console.log('pais', pais);
                // console.log('provincia', provincia);
                // console.log('licalidad', licalidad);
                // console.log('sector', sector);
                // console.log('---------------------');
                dibujarSector(sector, colorLocalidadT)
                primero = true;
              // }
            });
          });
        });
      });
    });
  }

  function random_color( format ){
    var rint = Math.floor( 0x100000000 * Math.random());
    switch( format ){
      case 'hex':
        return '#' + ('00000'   + rint.toString(16)).slice(-6).toUpperCase();
      case 'hexa':
        return '#' + ('0000000' + rint.toString(16)).slice(-8).toUpperCase();
      case 'rgb':
        return 'rgb('  + (rint & 255) + ',' + (rint >> 8 & 255) + ',' + (rint >> 16 & 255) + ')';
      case 'rgba':
        return 'rgba(' + (rint & 255) + ',' + (rint >> 8 & 255) + ',' + (rint >> 16 & 255) + ',' + (rint >> 24 & 255)/255 + ')';
      default:
        return rint;
    }
  }

  function dibujarSector(sector, colorLocalidad){
    let forma = [];
    $.each(sector, function (indS, obj) {
      forma.push( new THREE.Vector2( obj[0], obj[1] ) );
      // forma[ indS ].multiplyScalar( 0.25 ); 
    });
    // console.log('forma', forma);

    const formaSelector = new THREE.Shape( forma );

    addShape( formaSelector, extrudeSettings, colorLocalidad);
  }

  
</script>