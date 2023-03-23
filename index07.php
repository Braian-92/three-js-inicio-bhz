<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <script type="module">
      import * as THREE from "./three.module.js";
      import { OrbitControls } from "./OrbitControls.js";
      //creating scene
      var scene = new THREE.Scene();
      scene.background = new THREE.Color(0x2a3b4c);

      //add camera
      var camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight
      );

      //renderer
      var renderer = new THREE.WebGLRenderer();
      renderer.setSize(window.innerWidth, window.innerHeight);
      document.body.appendChild(renderer.domElement);

      //add geometry
      var geometry = new THREE.BoxGeometry(2,2,2,5,5,5);
      var material = new THREE.MeshBasicMaterial({
        color: 0x00ff00,
        wireframe: true,
      });
      var objeto = new THREE.Mesh(geometry, material);
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.CircleGeometry(2,32,0, Math.PI / 2),
        material
      );
      objeto.position.x = 5;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.ConeGeometry(1, 3, 32, 5, true, 0, Math.PI),
        material
      );
      objeto.position.x = -5;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.CylinderGeometry(0.5, 0.5, 2, 12, 32, true),
        material
      );
      objeto.position.y = 3;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.PlaneGeometry(20, 20, 5, 5),
        material
      );
      objeto.position.y = -7;
      objeto.rotation.x = Math.PI / 2;
      scene.add(objeto);


      objeto = new THREE.Mesh(
        new THREE.TetrahedronGeometry(2),
        material
      );
      objeto.position.x = -5;
      objeto.position.y = 5;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.SphereGeometry(2, 8, 8, 0, Math.PI, 0, Math.PI/2),
        material
      );
      objeto.position.x = 5;
      objeto.position.y = 5;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.TorusGeometry(2, 1, 6),
        material
      );
      objeto.position.x = -5;
      objeto.position.y = -5;
      scene.add(objeto);

      objeto = new THREE.Mesh(
        new THREE.RingGeometry(.3 , 1.5, 16, 0, Math.PI),
        material
      );
      objeto.position.x = 5;
      objeto.position.y = -5;
      scene.add(objeto);

      camera.position.z = 5;

      var controls = new OrbitControls(camera, renderer.domElement);

      window.addEventListener('resize', redimensionar);

      function redimensionar(){
        camera.aspect = window.innerWidth / window.innerHeight;
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.updateProjectionMatrix();
        renderer.render(scene, camera);
      }

      controls.minDistance = 3;
      controls.maxDistance = 18;

      //controls.enableZoom = false;

      //controls.enableRotate = false;

      controls.enableDamping = true;
      controls.dampingFactor = 0.5;

      controls.maxPolarAngle = Math.PI;

      controls.screenSpacePanning = true;

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