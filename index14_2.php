<style type="text/css">
  @import url('https://fonts.googleapis.com/css?family=Ubuntu');
  * {
    font-family: 'Ubuntu', sans-serif;
    margin : 0;
    padding : 0;
    border : 0;
  }
  section#container {
    position : absolute;
    top : 0;
    left : 0;
  }
  section#cockpit {
    position : absolute;
    bottom : 0;
    left : 0;
    padding : 16px;
    background : rgba(0,0,0,0.7);
    border : 1px solid #ccc;
    margin : 8px;
    width : 600px;
    box-shadow : 0 0 20px #000;
  }
  body {
    background: #000;
    overflow : hidden;
    height : 100%;
  }
  div#uac span {
    display : block;
  }
  div#uac * {
    color : #ccc;
    font-size : 10px;
  }
</style>
<section id="container">
  
</section>
<section id="cockpit">
    <div id="uac">
      <span>puissance : <em class="power"></em></span>
      <span>speed : <em class="speed"></em></span>
      <span>carburant : <em class="fuel"></em></span>
      <span>rotation : <em class="rotation"></em></span>
      <span>position : <em class="position"></em></span>
      <span>orientation : <em class="orientation"></em></span>
      <span>command : <em class="command"></em></span>
    </div>
    <div id="board">
    </div>

  </section>
<!-- <script src="https://mrdoob.github.com/three.js/build/three.min.js"></script> -->
<!-- <script src="https://raw.githubusercontent.com/mrdoob/three.js/master/examples/js/loaders/ColladaLoader.js"></script> -->
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
    refresh_uac();
    navigate();
    init();
    // Generate 40 planets
    generatePlanet( 40, 1 );
    animate();
    
  }






  function refresh_uac() {
    document.querySelector('#uac .speed').innerHTML = sheep.speed + 'm/s';
    document.querySelector('#uac .fuel').innerHTML = sheep.fuel + 'L';
    document.querySelector('#uac .power').innerHTML = sheep.power + 'CV';
    document.querySelector('#uac .orientation').innerHTML = Math.round(sheep.orientation.x*100)/100 + 'x - ' + Math.round(sheep.orientation.y*100)/100 + 'y - ' + Math.round(sheep.orientation.z*100)/100 + 'z';
    document.querySelector('#uac .rotation').innerHTML = Math.round(sheep.rotation.x*100)/100 + 'x - ' + Math.round(sheep.rotation.y*100)/100 + 'y - ' + Math.round(sheep.rotation.z*100)/100 + 'z';
    document.querySelector('#uac .position').innerHTML = Math.round(sheep.position.x*100)/100 + ' - ' + Math.round(sheep.position.y*100)/100 + ' - ' + Math.round(sheep.position.z*100)/100;
  }
  document.onkeydown  = function (e) {
    e = e || window.event;
    document.querySelector('#uac .command').innerHTML = e.keyCode
    // Ship acceleration
    if(e.keyCode == 33) 
      speedUp(sheep);
    else if(e.keyCode == 34) 
      speedDown(sheep);
    
    // ship orientation
    else if(e.keyCode == 38) 
      turnUp(sheep);
    else if(e.keyCode == 39) 
      turnRight(sheep);
    else if(e.keyCode == 40) 
      turnDown(sheep);
    else if(e.keyCode == 37) 
      turnLeft(sheep);
    
    // Camera control (zoomin and zoomout)
    else if(e.keyCode == 107) 
      cameraDistance -= 60;
    else if(e.keyCode == 109) 
      cameraDistance += 60;
  }


  function navigate() {
    move(sheep,2);
    
    refresh_uac()
    setTimeout(function() { navigate() }, 20);
    
  }

  // move item according to its position, orientation and speed for a specified time
  function move(item, time) {
    var newPosition = {x:0,y:0,z:0};
    item.position.x += item.speed * item.orientation.x * 1.0;
    item.position.y += item.speed * item.orientation.y * 1.0;
    item.position.z += item.speed * item.orientation.z * 1.0;
    
  }

  function speedUp(item) {
    if(item.fuel <= 0)
      exit;
    if(item.speed >= item.maxSpeed - item.acceleration) {
      item.speed = item.maxSpeed;
    }
    else {
      item.speed += item.acceleration;
      item.fuel -= (item.weight * item.acceleration) / 9000;
    }
  }


  function speedDown(item) {
    if(item.fuel <= 0)
      exit;
    if(item.speed <= item.acceleration) {
      item.speed = 0 
    } 
    else {
      item.speed -= item.acceleration;
      item.fuel -= (item.weight * item.acceleration) / 9000;
    }
  }

  function turnUp(item) {
    item.rotation.z -= rad(item.agility) % 6.28;
    updateDirection(item);
  }
  function turnDown(item) {
    item.rotation.z += rad(item.agility) % 6.28;
    updateDirection(item);
  }

  function turnLeft(item) {
    item.rotation.y += rad(item.agility) % 6.28;
    updateDirection(item);
  }
  function turnRight(item) {
    item.rotation.y -= rad(item.agility % 6.28);
    updateDirection(item);
  }


  // Calculate values of direction vector considering rotation angles
  function updateDirection(item) {
    item.orientation.x = Math.cos((item.rotation.y));
    item.orientation.z = -1 * Math.sin((item.rotation.y));
    item.orientation.y = Math.sin((item.rotation.z));
  }











  function init(){
    console.log("inicializo");
      // on initialise le moteur de rendu
      renderer = new THREE.WebGLRenderer();

      // si WebGL ne fonctionne pas sur votre navigateur vous pouvez utiliser le moteur de rendu Canvas à la place
      // renderer = new THREE.CanvasRenderer();
      renderer.setSize( window.innerWidth, window.innerHeight );
      document.getElementById('container').appendChild(renderer.domElement);

      // on initialise la scène
      scene = new THREE.Scene();

    
      // on initialise la camera que l’on place ensuite sur la scène
      camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 1, 10000 );
      
      //camera.rotation.z= rad(20);
      //camera.position.set(sheep.position.x + cameraDistance * sheep.orientation.x, sheep.position.y + cameraDistance * sheep.orientation.y, sheep.position.z + cameraDistance * sheep.orientation.z);
    
      camera.rotation.y = rad(-90);
      //camera.rotation.z= rad(20);
      scene.add(camera);
        
      // on créé un  cube au quel on définie un matériau puis on l’ajoute à la scène 
      let geometry = new THREE.ConeGeometry( 90, 400, 6, 3, false, 1, 6.3 );
      let material = new THREE.MeshPhongMaterial( { color: 0xaaaaaa, specular: 0xffffff, shininess: 70 } );
      cone = new THREE.Mesh( geometry, material );
      //cone.rotation.x = rad(sheep.orientation.x);
      //cone.rotation.y = rad(sheep.orientation.y);
      //cone.rotation.z = rad(sheep.orientation.z + 90);
      cone.rotation.x = rad(0);
      cone.rotation.y = rad(0);
      cone.rotation.z = rad(-90);
      cone.scale.z = 3;
    
      scene.add( cone );
      // cone.position.set(sheep.position.x, sheep.position.y, sheep.position.z)
    
    
    
      /*
      // Import d'un modèle 3D pour le vaisseau
      var loader = new THREE.ColladaLoader();
      loader.load(
        // resource URL
        'http://kunpac.com/ressources/Jet%20Fighter.dae',
        // Function when resource is loaded
        function ( collada ) {
          scene.add( collada.scene );
        },
        // Function called when download progresses
        function ( xhr ) {
          console.log( (xhr.loaded / xhr.total * 100) + '% loaded' );
        }
      );
      */
    
      //var light = new THREE.light( 0xffffbb, 1 );
      //light.position.set( 500, 500, 500 );
      //scene.add( light );
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
      cone.position.set(sheep.position.x, sheep.position.y, sheep.position.z)
      camera.position.set(sheep.position.x + cameraDistance * sheep.orientation.z * (-1), sheep.position.y + cameraDistance * sheep.orientation.y * (-1) + 700, sheep.position.z + cameraDistance * sheep.orientation.z * (-1));
    camera.position.set(sheep.position.x + cameraDistance * sheep.orientation.x * (-1), sheep.position.y + cameraDistance * sheep.orientation.y * (-1) + 700, sheep.position.z + cameraDistance * sheep.orientation.z * (-1));
    //camera.up = new THREE.Vector3(0,1,0);
    //camera.lookAt(sheep.position)
      bulbLight.position.set(sheep.position.x + cameraDistance * sheep.orientation.x - 10, sheep.position.y + cameraDistance * sheep.orientation.y - 10, sheep.position.z + cameraDistance * sheep.orientation.z - 10);
      bulbLight.position.set(sheep.position.x + cameraDistance * sheep.orientation.x * (-1), sheep.position.y + cameraDistance * sheep.orientation.y * (-1), sheep.position.z + cameraDistance * sheep.orientation.z * (-1));
      cone.rotation.x = (sheep.rotation.x);
      cone.rotation.y = (sheep.rotation.y);
      cone.rotation.z = (sheep.rotation.z - 90);
      //camera.rotation.x = (cone.rotation.z);
      //camera.rotation.y = (cone.rotation.y - rad(90));
      camera.lookAt(sheep.position)
      //camera.rotation.z = (cone.rotation.z);
      //camera.rotation.z += 0.05;
    
      //camera.rotation.z += rad(1);
      //camera.rotation.y += rad(2);
      //camera.rotation.x += rad(1);
      //cone.rotation.z += rad(1);
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

