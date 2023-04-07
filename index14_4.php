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

  window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

  // WebGL SETUP

  // WebGL renderer
  var renderer = new THREE.WebGLRenderer();
  var playerShip;
  var canvas = window;
    renderer.setSize(window.innerWidth, window.innerHeight);

    document.body.appendChild(renderer.domElement);
    // renderer.setClearColor(0xA3A3A3);

  // Initialize height and width of renderer based on container (100%)
  var WIDTH  = canvas.clientWidth,
      HEIGHT = canvas.clientHeight;

  renderer.setSize(WIDTH, HEIGHT);

  // Scene & Camera
  var camera = new THREE.PerspectiveCamera(
               45,              // VIEW_ANGLE
               canvas.clientWidth / canvas.clientHeight,  // ASPECT
               1,               // NEAR
               1000             // FAR
           );
  var scene  = new THREE.Scene();
   
  // add the camera to the scene
  scene.add(camera);
   
  // set a default position for the camera
  // not doing this somehow messes up shadow rendering
  camera.position.z = 320;


  onresize = function(){
    WIDTH = canvas.clientWidth,
    HEIGHT = canvas.clientHeight;
    
    //canvas.width  = WIDTH;
    //canvas.height = HEIGHT;
    renderer.setSize(WIDTH, HEIGHT);
    
    camera.aspect = WIDTH / HEIGHT;
    camera.updateProjectionMatrix();
  }
  onresize()

  // CONTROLS
  // // Wheel scroll to zoom
  var handleMouseWheelEvents = function(e) {
    if(e.wheelDelta < 0) camera.position.z++
      else camera.position.z--;
    return false; // prevent page scroll
  }
  onwheel      = handleMouseWheelEvents;
  onmousewheel = handleMouseWheelEvents;

  // Ship Controls
  onmousemove = function (e) {
    // rect     = canvas.getBoundingClientRect();
    reticule = {
      x: e.clientX,
      y: e.clientY
    }
  }

  onkeydown = function(e) {
    //console.log(e.keyCode);
    switch(e.keyCode) {
      // directional input
      case 68: // 'd' - turn right
        playerShip.turnLeft();
        break;
      case 65: // 'a' - turn left
        playerShip.turnRight();
        break;
      case 87: // 'w' - forward
        playerShip.forwardThrust();
        break;
      case 83: // 's' - reverse
        playerShip.reverseThrust();
        break;
      case 78: // 'n' for nullify velocity
        playerShip.nullifyVelocity();
        break;
      // utility
      case 82: // 'r' for reset
        init();
        break;
    };
  }


  // GAME LOGIC
  var reticule = {};
  var ship     = {};

  /**
   * Ship prototype
   **/
  function Ship(name) {
    var that  = this;
    this.name = name || 'Unnamed Ship';
    
    this.rotationVel = 0;
    this.thrust      = 0;
    
    this.xv = 0;
    this.yv = 0;
    
    // TODO improve this logic
    this.nullifyVelocity = function() {
      if(that.xv > 0) that.xv=0;
      if(that.xv < 0) that.xv=0;
      if(that.yv > 0) that.yv=0;
      if(that.yv < 0) that.yv=0;
      if(that.rotationVel > 0) that.rotationVel-=0.25;
      if(that.rotationVel < 0) that.rotationVel+=0.25;
    }
    
    this.forwardThrust = function() {
      that.thrust+=0.03;
    }
    this.reverseThrust = function() {
      that.thrust=0;
    }
    
    this.turnRight = function() {
      if(that.rotationVel<3) that.rotationVel+=0.25;
    }
    this.turnLeft = function() {
       if(that.rotationVel>-3) that.rotationVel-=0.25;
    }
  }

  // SCENE UTILITY FUNCTIONS
  function sign(x) {
      return typeof x === 'number' ? x ? x < 0 ? -1 : 1 : x === x ? 0 : NaN : NaN;
  }

  // // create a point light
  function createPointLight() {
    
    var pointLight = new THREE.PointLight(0xF8D898);
   
    // set its position
    pointLight.position.x = -1000;
    pointLight.position.y = 0;
    pointLight.position.z = 1000;
    pointLight.intensity = 2.9;
    pointLight.distance = 10000;
   
    // add to the scene
    scene.add(pointLight);
  }

  var tetra;
  var plane;
  var particles = [];
  var particleCount = 60;

  // // set the Scene
  function setScene() {  
    // setup particles
    /*
    // create the particle variables
    var particles = new THREE.Geometry(),
        pMaterial = new THREE.ParticleBasicMaterial({
          color: 0xFFFFFF,
          size: 20
        });

    // now create the individual particles
    for (var p = 0; p < particleCount; p++) {

      // create a particle with random
      // position values, -250 -> 250
      var pX = Math.random() * 500 - 250,
          pY = Math.random() * 500 - 250,
          pZ = Math.random() * 500 - 250,
          particle = new THREE.Vertex(
            new THREE.Vector3(pX, pY, pZ)
          );
      
      particle.velocity = new THREE.Vector3(0,-Math.random(),0);
      
      // add it to the geometry
      particles.vertices.push(particle);
    }

    // create the particle system
    var particleSystem = new THREE.ParticleSystem(
      particles,
      pMaterial);

    // add it to the scene
    scene.add(particleSystem);
    */
    
    // background
    var planeMaterial = new THREE.MeshLambertMaterial({
                          color: new THREE.Color('rgb(18,24,42)')
                        });
    // create plane
    plane = new THREE.Mesh(
              new THREE.PlaneGeometry(210,210,5,5),
              planeMaterial
            );
    
    // create cube
    var cubeGeometry = new THREE.CylinderGeometry(0,5,10,4,4);
    var cubeMaterial = new THREE.MeshPhongMaterial({
                         color: new THREE.Color('rgb(200,80,80)')
                       });
    tetra = new THREE.Mesh(cubeGeometry, cubeMaterial);
    
    scene.add(plane);
    scene.add(tetra); 
    
    createPointLight();
  }

  // SETUP
  function setup() { 
    setScene();
    playerShip = new Ship('Santa Maria');
    draw();
  }

  // GAME LOOP
  function calculate() {
    
    var accel = playerShip.thrust;
    var dy    = Math.cos(tetra.rotation.z);
    var dx    = -Math.sin(tetra.rotation.z);

    // basic boundary box
    if(tetra.position.y >= 100) {
      playerShip.yv = -Math.abs(0.3*playerShip.yv);
      tetra.position.y = 99.9;
    } else if (tetra.position.y <= -100) {
      playerShip.yv = Math.abs(0.3*playerShip.yv);
      tetra.position.y = -99.9;
    } else if(tetra.position.x >= 100) {
      playerShip.xv = -Math.abs(0.3*playerShip.xv);
      tetra.position.x = 99.9;
    } else if (tetra.position.x <= -100) {
      playerShip.xv = Math.abs(0.3*playerShip.xv);
      tetra.position.x = -99.9;
    } else {
      playerShip.xv += (accel*dx);
      playerShip.yv += (accel*dy);
    }
    
    tetra.position.y+=playerShip.yv;
    tetra.position.x+=playerShip.xv; 
    
    tetra.rotation.z += playerShip.rotationVel * Math.PI/180;

  }

  function handleParticles() {
    var pCount = particleCount;
    while (pCount--) {

      // get the particle
      var particle = particles.vertices[pCount];

      // check if we need to reset
      if (particle.position.y < -200) {
        particle.position.y = 100;
        particle.velocity.y = 0;
      }

      // update the velocity with
      // a splat of randomniz
      particle.velocity.y -= Math.random() * .1;

      // and the position
      particle.position.addSelf(
        particle.velocity);
    }

    // flag to the particle system
    // that we've changed its vertices.
    particleSystem.
      geometry.
      __dirtyVertices = true; 
  }

  function draw() { 
    //handleParticles();
    // draw the scene
    renderer.render(scene, camera);
    // game logic
    calculate();
    // loop draw()
    requestAnimationFrame(draw);
    camera.lookAt(tetra.position);
  }

  setup();
</script>