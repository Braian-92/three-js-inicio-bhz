<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  </head>
  <body>
  	<script src="ascii_teclas.js"></script>
  	<script src="matematica.js"></script>
  	<script src="js/librerias/jquery-3.5.1.min.js"></script>
  	<script src="js/librerias/bootstrap.bundle.min.js"></script>
  	<script src="js/librerias/adminlte.min.js"></script>
    <script type="module">
      import * as THREE from "./three.module.js";
      import { OrbitControls } from "./OrbitControls.js";
      //creating scene
      var scene = new THREE.Scene();
      scene.background = new THREE.Color(0x223344);

      
      var camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight
      );
      
      camera.position.set(0, -10, 10);
      camera.lookAt(0, 0, 0);

      //renderer
      var renderer = new THREE.WebGLRenderer({
      	antialias:true //! para suavizar las lineas del dibujado
      });
      renderer.setSize(window.innerWidth, window.innerHeight); //!  ajuste de dimenciones del canvas	
      document.body.appendChild(renderer.domElement); //! agregarlo al DOM HTML

     

      document.addEventListener("keydown", (evnt) => {
           switch (evnt.keyCode) {
                case KEY_UP:
                     break;
                case KEY_W:
                     break;
                case KEY_DOWN:
                     break
                case KEY_S:
                     break
                case KEY_ENTER:
                     break
                default:
                     break;
           }
      })

      document.addEventListener("keyup", (evnt) => {

      })
      //add geometry

      var material = new THREE.MeshPhongMaterial({ color: 0x00ff00 })
      var objeto = new THREE.Mesh(
        new THREE.PlaneGeometry(100, 100, 5, 5),
        material
      );
      objeto.position.y = 10;
      // objeto.rotation.x = Math.PI / 2;
      scene.add(objeto);

      // var geometry = new THREE.BoxGeometry(30,30,30,5,5,5);
      material = new THREE.MeshPhongMaterial({ color: 0xFF6A00FF })
      objeto = new THREE.Mesh(
        new THREE.CylinderGeometry(0.5, 0.5, 2, 12, 32, false),
        material
      );
      // var material = new THREE.MeshBasicMaterial({
      //   color: 0x00ff00,
      //   // wireframe: true,
      // });
      // var objeto = new THREE.Mesh(geometry, material);
      scene.add(objeto);

      const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
      scene.add(ambientLight);

      const dirLight = new THREE.DirectionalLight(0xffffff, 0.8);
      dirLight.position.set(6, -6, 6)
      scene.add(dirLight);

      var controls = new OrbitControls(camera, renderer.domElement);
      // controls.minDistance = 3;
      // controls.maxDistance = 18;
      //controls.enableZoom = false;
      //controls.enableRotate = false;
      controls.enableDamping = true;
      controls.dampingFactor = 0.5;
      // controls.maxPolarAngle = Math.PI;
      controls.screenSpacePanning = true;

      window.addEventListener('resize', redimensionar);

      function redimensionar(){
        camera.aspect = window.innerWidth / window.innerHeight;
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.updateProjectionMatrix();
        renderer.render(scene, camera);
      }

      

      //animation
      var animate = function () {
        requestAnimationFrame(animate);
        scene.traverse(function(objeto){
          if(objeto.isMesh === true){
            // objeto.rotation.x += 0.01;
            // objeto.rotation.y += 0.01;
          }
        });
        

        renderer.render(scene, camera);
      };

      animate();

      
    </script>
  </body>
</html>