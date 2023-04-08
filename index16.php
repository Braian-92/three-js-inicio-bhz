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
      "three/addons/": "./threeJsMaster/examples/jsm/"
    }
  }
</script>
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { TransformControls } from 'three/addons/controls/TransformControls.js';
  import * as CANNON from 'three/addons/physics/cannon-es.js';

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
      pantalla.clientWidth / pantalla.clientHeight
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

  const boxGeo = new THREE.BoxGeometry(2, 2, 2);
  const boxMat = new THREE.MeshBasicMaterial({
    color: 0x00ff00,
    wireframe: true
  });
  const boxMesh = new THREE.Mesh(boxGeo, boxMat);
  ESCENA.add(boxMesh);

  const sphereGeo = new THREE.SphereGeometry(2);
  const sphereMat = new THREE.MeshBasicMaterial({ 
    color: 0xff0000, 
    wireframe: true,
   });
  const sphereMesh = new THREE.Mesh( sphereGeo, sphereMat);
  ESCENA.add(sphereMesh);

  const groundGeo = new THREE.PlaneGeometry(30, 30);
  const groundMat = new THREE.MeshBasicMaterial({ 
    color: 0xffffff,
    side: THREE.DoubleSide,
    wireframe: true 
   });
  const groundMesh = new THREE.Mesh(groundGeo, groundMat);
  ESCENA.add(groundMesh);

  const world = new CANNON.World({
      gravity: new CANNON.Vec3(0, -9.81, 0)
  });

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

  const boxPhysMat = new CANNON.Material();

  const boxBody = new CANNON.Body({
      mass: 1,
      shape: new CANNON.Box(new CANNON.Vec3(1, 1, 1)),
      position: new CANNON.Vec3(1, 20, 0),
      material: boxPhysMat
  });
  world.addBody(boxBody);

  boxBody.angularVelocity.set(0, 10, 0);
  boxBody.angularDamping = 0.5;

  const groundBoxContactMat = new CANNON.ContactMaterial(
      groundPhysMat,
      boxPhysMat,
      {friction: 0.04}
  );

  world.addContactMaterial(groundBoxContactMat);

  const spherePhysMat = new CANNON.Material();

  const sphereBody = new CANNON.Body({
      mass: 4,
      shape: new CANNON.Sphere(2),
      position: new CANNON.Vec3(0, 10, 0),
      material: spherePhysMat
  });
  world.addBody(sphereBody);

  sphereBody.linearDamping = 0.21

  const groundSphereContactMat = new CANNON.ContactMaterial(
      groundPhysMat,
      spherePhysMat,
      {restitution: 0.9}
  );

  world.addContactMaterial(groundSphereContactMat);

  const timeStep = 1 / 60;

  var animate = function () {
    world.step(timeStep);

    groundMesh.position.copy(groundBody.position);
    groundMesh.quaternion.copy(groundBody.quaternion);

    boxMesh.position.copy(boxBody.position);
    boxMesh.quaternion.copy(boxBody.quaternion);

    sphereMesh.position.copy(sphereBody.position);
    sphereMesh.quaternion.copy(sphereBody.quaternion);
    
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