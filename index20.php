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
              <div id="pantalla" class="card-body p-0 m-0 h-100 overflow-hidden">
                
              </div>
            </div>            
          </div>
          <div class="col-4 h-100 d-none">
            <div class="card card-row card-default w-100 h-100">
              <div id="" class="card-body p-0 m-0 h-100 overflow-hidden">
                
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
      "three/addons/": "./threeJsMaster/examples/jsm/",
      "cannon-es": "./threeJsMaster/examples/jsm/physics/cannon-es.js",
      "cannon-es-debugger": "./threeJsMaster/examples/jsm/physics/cannon-es-debugger.js"
    }
  }
</script>
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { TransformControls } from 'three/addons/controls/TransformControls.js';
  import * as CANNON from 'cannon-es'
  import CannonDebugger from 'cannon-es-debugger'

  const pantalla = document.querySelector('#pantalla');
  let elementoSeleccionadoListado = null;
  var ESCENA = new THREE.Scene();
  var RENDERIZADO = new THREE.WebGLRenderer( { antialias: true } );
  var CAMARA;
  // let helper;
  init();

  function init() {
    ESCENA.name = 'ESCENA';
    ESCENA.background = new THREE.Color(0x2a3b4c);
    CAMARA = new THREE.PerspectiveCamera(
      75,
      pantalla.clientWidth / pantalla.clientHeight
    );
    CAMARA.name = 'CAMARA';
    CAMARA.position.set(0, 20, -20);
    RENDERIZADO.setSize(pantalla.clientWidth , pantalla.clientHeight);
    pantalla.appendChild(RENDERIZADO.domElement);
    // helper = new CannonHelper(ESCENA);
    // helper.addLights(this.renderer);
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

  const world = new CANNON.World({
      gravity: new CANNON.Vec3(0, -9.81, 0)
  });

  world.broadphase = new CANNON.SAPBroadphase(world);
  world.gravity.set(0, -10, 0);
  world.defaultContactMaterial.friction = 0;

  const groundMaterial = new CANNON.Material("groundMaterial");
  const wheelMaterial = new CANNON.Material("wheelMaterial");
  const wheelGroundContactMaterial = new CANNON.ContactMaterial(wheelMaterial, groundMaterial, {
    friction: 0.3,
    restitution: 0,
    contactEquationStiffness: 1000
  });

  world.addContactMaterial(wheelGroundContactMaterial);

  const groundPhysMat = new CANNON.Material();
  const groundBody = new CANNON.Body({
      //shape: new CANNON.Plane(),
      //mass: 10
      shape: new CANNON.Box(new CANNON.Vec3(15, 15, 0.1)),
      type: CANNON.Body.STATIC,
      material: groundPhysMat
  });
  world.addBody(groundBody);
  groundBody.quaternion.setFromEuler(-Math.PI / 2, 0, 0);

  const chassisShape = new CANNON.Box(new CANNON.Vec3(1, 0.5, 2));
  const chassisBody = new CANNON.Body({ mass: 150, material: groundMaterial });
  chassisBody.addShape(chassisShape);
  chassisBody.position.set(0, 4, 0);
  // helper.addVisual(chassisBody, 'car');

  const options = {
    radius: 0.5,
    directionLocal: new CANNON.Vec3(0, -1, 0),
    suspensionStiffness: 30,
    suspensionRestLength: 0.3,
    frictionSlip: 5,
    dampingRelaxation: 2.3,
    dampingCompression: 4.4,
    maxSuspensionForce: 100000,
    rollInfluence:  0.01,
    axleLocal: new CANNON.Vec3(-1, 0, 0),
    chassisConnectionPointLocal: new CANNON.Vec3(1, 1, 0),
    maxSuspensionTravel: 0.3,
    customSlidingRotationalSpeed: -30,
    useCustomSlidingRotationalSpeed: true
  };

  // Create the vehicle
  const vehicle = new CANNON.RaycastVehicle({
    chassisBody: chassisBody,
    indexRightAxis: 0,
    indexUpAxis: 1,
    indeForwardAxis: 2
  });

  options.chassisConnectionPointLocal.set(1, 0, -1);
  vehicle.addWheel(options);

  options.chassisConnectionPointLocal.set(-1, 0, -1);
  vehicle.addWheel(options);

  options.chassisConnectionPointLocal.set(1, 0, 1);
  vehicle.addWheel(options);

  options.chassisConnectionPointLocal.set(-1, 0, 1);
  vehicle.addWheel(options);

  vehicle.addToWorld(world);

  const wheelBodies = [];
  vehicle.wheelInfos.forEach( function(wheel){
    const cylinderShape = new CANNON.Cylinder(wheel.radius, wheel.radius, wheel.radius / 2, 20);
    const wheelBody = new CANNON.Body({ mass: 1, material: wheelMaterial });
    const q = new CANNON.Quaternion();
    q.setFromAxisAngle(new CANNON.Vec3(0, 1, 0), Math.PI / 2);
    wheelBody.addShape(cylinderShape, new CANNON.Vec3(), q);
    wheelBodies.push(wheelBody);
    // helper.addVisual(wheelBody, 'wheel');
  });

  //! ##################################################

  const cannonDebugger = CannonDebugger(
    ESCENA,
    world
  );


  const timeStep = 1 / 60;

  var animate = function () {
    world.step(timeStep);

    cannonDebugger.update();

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