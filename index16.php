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
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { TransformControls } from 'three/addons/controls/TransformControls.js';

  const pantalla = document.querySelector('#pantalla');
  let elementoSeleccionadoListado = null;
  var ESCENA = new THREE.Scene();
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

    controls.screenSpacePanning = true;
    RENDERIZADO.render(ESCENA, CAMARA);

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

  
</script>