<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  </head>
  <body class="dark-mode text-sm">
    <div class="container-fluid">
      <div class="row mt-2">
        <div class="col-12">
          <div class="card card-outline card-success">
            <div class="card-header">
              <h3 class="card-title">THREE JS</h3>
            </div>
            <div id="pantalla" class="card-body p-0 m-0">
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="module">
    import * as THREE from "./three.module.js";
    import { OrbitControls } from "./OrbitControls.js";
    //creating scene
    const pantalla = document.querySelector('#pantalla');
    var scene = new THREE.Scene();
    scene.background = new THREE.Color(0x2a3b4c);

    //add camera
    var camera = new THREE.PerspectiveCamera(
      75,
      window.innerWidth / window.innerHeight
    );
    camera.position.z = 20;

    //renderer
    var renderer = new THREE.WebGLRenderer();
    // renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setSize(pantalla.clientWidth , 600);

    pantalla.appendChild(renderer.domElement);

    //add geometry
    var geometry = new THREE.BoxGeometry();
    var material = new THREE.MeshBasicMaterial({
      color: 0x00ff00,
      wireframe: true,
    });
    var cube = new THREE.Mesh(geometry, material);

    scene.add(cube);

    let CERO = new THREE.Mesh(
      new THREE.BoxGeometry(1,1,1),
      material
    );
    scene.add(CERO);

    let XXX = new THREE.Mesh(
      new THREE.SphereGeometry(1, 8, 8),
      material
    );
    XXX.position.x = 10;
    scene.add(XXX);

    let YYY = new THREE.Mesh(
      new THREE.TorusGeometry(1, 1, 6),
      material
    );
    YYY.position.y = 10;
    scene.add(YYY);

    let ZZZ = new THREE.Mesh(
      new THREE.ConeGeometry(1, 1, 32),
      material
    );
    ZZZ.position.z = 10;
    scene.add(ZZZ);


    const grid = new THREE.GridHelper(30, 30);
    scene.add(grid);

    var controls = new OrbitControls(camera, renderer.domElement);

    // controls.minDistance = 3;
    // controls.maxDistance = 10;

    //controls.enableZoom = false;

    //controls.enableRotate = false;

    controls.enableDamping = true;
    controls.dampingFactor = 0.5;

    controls.maxPolarAngle = Math.PI;

    controls.screenSpacePanning = true;

    //animation
    let tiempo = 0;
    let radio = 10;
    let centroX = 10;
    let centroY = 10;

    var animate = function () {
      requestAnimationFrame(animate);
      cube.position.x = centroX + Math.cos(Math.PI*tiempo) * radio;
      cube.position.y = centroY + Math.sin(Math.PI*tiempo) * radio;
      tiempo += 0.01;

      renderer.render(scene, camera);
    };

    animate();
  </script>
</html>