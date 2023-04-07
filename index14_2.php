<!DOCTYPE html>
<html lang="en">
  <head>
    <title>three.js - misc - octree collisions</title>
    <meta charset=utf-8 />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- three-js-inicio-bhz/threeJsMaster/examples/games_fps.html -->
    <link type="text/css" rel="stylesheet" href="./threeJsMaster/examples/main.css">
  </head>
  <body>
  </body>
</html>
<script type="importmap">
  {
    "imports": {
      "three": "./threeJsMaster/build/three.module.js",
      "three/addons/": "./threeJsMaster/examples/jsm/",
      "three/js/": "./threeJsMaster/examples/jsm/"
    }
  }
</script>
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  // three-js-inicio-bhz/threeJsMaster/examples/js/loaders/ColladaLoader.js
  import { ColladaLoader } from 'three/js/loaders/ColladaLoader.js';

  var sheep = {
    type:"",
    speed:0,
    maxSpeed:3400,
    power : 4200,
    acceleration:24,
    weight:7800,
    agility:10,
    orientation : {
      x:1,
      y:0,
      z:0
    },
    rotation : {
      x:0,
      y:0,
      z:0
    },
    position: {
      x:0,
      y:0.0,
      z:0.0, 
      rotate:0
    },
    fuel: 12000
  }






  var renderer, scene, camera, mesh, cone, bulbLight, cameraDistance = 2000 ;
  window.onload = function() {
    init();
    
  }






  
   
  


  function init(){
    console.log("inicializo");

    renderer = new THREE.WebGLRenderer();

    renderer.setSize(window.innerWidth, window.innerHeight);

    document.body.appendChild(renderer.domElement);
    renderer.setClearColor(0xA3A3A3);
    scene = new THREE.Scene();
    //add CAMARA
    camera = new THREE.PerspectiveCamera(
      75,
      window.innerWidth / window.innerHeight
    );
    camera.position.z = 20;
    scene.add(camera);

    const GRID = new THREE.GridHelper(30, 30);
    scene.add(GRID);
    
    var material = new THREE.MeshBasicMaterial({
      color: 0x00ff00,
      wireframe: true,
    });

    cone = new THREE.Mesh(
      new THREE.BoxGeometry(),
      material
    );
    scene.add(cone);
    
    renderer.render(scene, camera);
    
      
    var bulbGeometry = new THREE.SphereGeometry( 0.02, 16, 8 );
    bulbLight = new THREE.PointLight( 0xffee88, 1, 400000, 2 );
    let bulbMat = new THREE.MeshStandardMaterial( {
      emissive: 0xffffee,
      emissiveIntensity: 1,
      color: 0x000000
    });
    bulbLight.add( new THREE.Mesh( bulbGeometry, bulbMat ) );
    bulbLight.position.set(sheep.position.x + cameraDistance * sheep.orientation.x +400, sheep.position.y + cameraDistance * sheep.orientation.y +400, sheep.position.z + cameraDistance * sheep.orientation.z +400 );
    bulbLight.castShadow = true;
    scene.add( bulbLight ); 
    

    console.log(camera)
    console.log(cone)
  }


  function animate(){
      requestAnimationFrame( animate );
      renderer.render( scene, camera );
  }

  function generatePlanet(number, distance) {
    var planet = null;
    let min =  -6000;
    let max = 12000;
    let size = 0 
    while(number-- > 0) {
      size = Math.random() * 1200 + 80
      let x = Math.random() * max + min
      let y = Math.random() * max + min
      let z = Math.random() * max + min
      //x = 00
      //y = 00
      //z = -800
      
      //console.log('A planet is born!')
      let geometry = new THREE.SphereGeometry( size, 8, 8 );
      let material = new THREE.MeshPhongMaterial( { color: 0xcc2233, specular: 0x440000, shininess: 3 } );
      let planet = new THREE.Mesh( geometry, material );
      scene.add( planet );
      planet.position.set(x, y, z)
    }
  }







  function rad(degree)   { return degree*(Math.PI/180); }
</script>

