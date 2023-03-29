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
      
      camera.position.set(30, 30, 30);
      camera.lookAt(0, 0, 0);
      

      //renderer
      var renderer = new THREE.WebGLRenderer({
      	antialias:true //! para suavizar las lineas del dibujado
      });
      renderer.setSize(window.innerWidth, window.innerHeight); //!  ajuste de dimenciones del canvas	
      document.body.appendChild(renderer.domElement); //! agregarlo al DOM HTML

     
      //add geometry

      var material = new THREE.MeshPhongMaterial({ color: 0xA583FD })
      var suelo = new THREE.Mesh(
        new THREE.PlaneGeometry(100, 100, 5, 5),
        material
      );
      suelo.position.y = 0;
      suelo.position.x = 0;
      suelo.rotation.x = -(Math.PI / 2);
      scene.add(suelo);

      function crearAuto(){
        let grupoT = new THREE.Group();
        let material1T = new THREE.MeshPhongMaterial({ color: 0x4CC6E5 });
        let material2T = new THREE.MeshPhongMaterial({ color: 0x0CE64A });
        let material3T = new THREE.MeshPhongMaterial({ color: 0x0F053A });
        //! base ##############
        let objetoT = new THREE.Mesh(
          new THREE.BoxGeometry(5,1,2),
          material1T
        );

        objetoT.position.y = 1;
        grupoT.add(objetoT);

        //! techo ##############
        objetoT = new THREE.Mesh(
          new THREE.BoxGeometry(3,.5,1.5),
          material2T
        );
        objetoT.position.y = 1.75;
        grupoT.add(objetoT);

        //! ruedas ##############
        objetoT = new THREE.Mesh(
          new THREE.CylinderGeometry(0.5, 0.5, .5, 12, 32),
          material3T
        );
        objetoT.position.y = .5;
        objetoT.position.x = 1.25;
        objetoT.position.z = 1;
        objetoT.rotation.x = -(Math.PI / 2);
        grupoT.add(objetoT);

        objetoT = new THREE.Mesh(
          new THREE.CylinderGeometry(0.5, 0.5, .5, 12, 32),
          material3T
        );
        objetoT.position.y = .5;
        objetoT.position.x = 1.25;
        objetoT.position.z = -1;
        objetoT.rotation.x = -(Math.PI / 2);
        grupoT.add(objetoT);

        objetoT = new THREE.Mesh(
          new THREE.CylinderGeometry(0.5, 0.5, .5, 12, 32),
          material3T
        );
        objetoT.position.y = .5;
        objetoT.position.x = -1.25;
        objetoT.position.z = 1;
        objetoT.rotation.x = -(Math.PI / 2);
        grupoT.add(objetoT);

        objetoT = new THREE.Mesh(
          new THREE.CylinderGeometry(0.5, 0.5, .5, 12, 32),
          material3T
        );
        objetoT.position.y = .5;
        objetoT.position.x = -1.25;
        objetoT.position.z = -1;
        objetoT.rotation.x = -(Math.PI / 2);
        grupoT.add(objetoT);

        return grupoT;
      }

      let auto = crearAuto();
      auto.rotation.y = Math.PI / 4;
      scene.add(auto);
      

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

      let velocidad = 0;
      let direccion = -(Math.PI/2);
      let direccionInc = 0;

      document.addEventListener("keydown", (evnt) => {
        switch (evnt.keyCode) {
          case KEY_UP:
          case KEY_W:
            velocidad = .1;
          break;
          case KEY_DOWN:
          case KEY_S:
            velocidad = -.1;
          break
          case KEY_A:
            direccionInc = direccionInc + .5;
            direccion = -(Math.PI/(2+direccionInc));
          break
          case KEY_A:
            direccionInc = direccionInc - .5;
            direccion = -(Math.PI/(2+direccionInc));
          break
          case KEY_ENTER:
            
          break
          default:
          break;
        }
      });

      document.addEventListener("keyup", (evnt) => {
        velocidad = 0;
      });
      

      //animation
      var animate = function () {
        requestAnimationFrame(animate);
        var xrot  = 0;  
        const euler = new THREE.Euler(xrot,direccion,0,'XYZ');
        const quat  = new THREE.Quaternion().setFromEuler(euler);
        const vector= new THREE.Vector3(0,0,-velocidad).applyQuaternion(quat);

        auto.position.add(vector);
        auto.lookAt(auto.position.y, 0, 0);

        // scene.traverse(function(objeto){
        //   if(objeto.isMesh === true){
        //   }
        // });
        

        renderer.render(scene, camera);
      };

      animate();

      
    </script>
  </body>
</html>