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
  	<script src="https://cdn.jsdelivr.net/gh/Jeff-Aporta/lib-jeff-aporta@main/matematica.js"></script>
  	<script src="https://cdn.jsdelivr.net/gh/Jeff-Aporta/lib-jeff-aporta@latest/ascii_teclas.js"></script>
  	<script src="js/librerias/jquery-3.5.1.min.js"></script>
  	<script src="js/librerias/bootstrap.bundle.min.js"></script>
  	<script src="js/librerias/adminlte.min.js"></script>
    <script type="module">
      import * as THREE from "./three.module.js";
      import { OrbitControls } from "./OrbitControls.js";
      //creating scene
      var scene = new THREE.Scene();
      scene.background = new THREE.Color(0x223344);

      let frameCount = 0

      //add camera
      let zoom = 0.2;
      var camera = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight
      );
      // var camera = new THREE.OrthographicCamera(
      //   -zoom * window.innerWidth / 2,  zoom *  window.innerWidth / 2, //! dist H
      //    zoom * window.innerHeight / 2, zoom * -window.innerHeight / 2,//! dist V
      //    0, //! plano trasero
      //    100 //! plano delantero
      // );
      camera.position.set(0, -200, 300);
      camera.lookAt(0, 0, 0);

      //renderer
      var renderer = new THREE.WebGLRenderer({
      	antialias:true //! para suavizar las lineas del dibujado
      });
      renderer.setSize(window.innerWidth, window.innerHeight); //!  ajuste de dimenciones del canvas	
      document.body.appendChild(renderer.domElement); //! agregarlo al DOM HTML

      //add geometry
      // var geometry = new THREE.BoxGeometry(30,30,30,5,5,5);
      // var material = new THREE.MeshBasicMaterial({
      //   color: 0x00ff00,
      //   wireframe: true,
      // });
      // var objeto = new THREE.Mesh(geometry, material);
      // scene.add(objeto);

      let jugador = Car();
      jugador.rotation.z = -PI/4
      scene.add(jugador);

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

      function actualizar(){
      	jugador.rotation.z = -(frameCount/100) - PI / 4;
      	frameCount++;
      }
      

      //animation
      var animate = function () {
      	actualizar();
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

      function Car() {
       const g = new THREE.Group()

       const llantasTraseras = new THREE.Mesh(
            new THREE.BoxBufferGeometry(12, 33, 12),
            new THREE.MeshPhongMaterial({ color: 0x333333 })
       )
       llantasTraseras.position.z = 6
       llantasTraseras.position.x = -18

       g.add(llantasTraseras)

       const llantasDelanteras = new THREE.Mesh(
            new THREE.BoxBufferGeometry(12, 33, 12),
            new THREE.MeshPhongMaterial({ color: 0x333333 })
       )
       llantasDelanteras.position.z = 6
       llantasDelanteras.position.x = 18

       g.add(llantasDelanteras)

       const cuerpo = new THREE.Mesh(
            new THREE.BoxBufferGeometry(60, 30, 15),
            new THREE.MeshPhongMaterial({ color: `hsl(${random() * 360},100%,50%)` })
       )
       cuerpo.receiveShadow = true;
       cuerpo.castShadow = true;
       cuerpo.position.z = 12

       g.add(cuerpo)

       let ventanas1 = document.createElement("canvas")
       {
            let e = 2
            ventanas1.width = 128 * e
            ventanas1.height = 32 * e
            const ctx = ventanas1.getContext("2d")
            ctx.fillStyle = "white"
            ctx.fillRect(0, 0, 128 * e, 32 * e)
            ctx.fillStyle = "black"
            ctx.fillRect(10 * e, 10 * e, (128 - 20) * e, 32 * e)
       }
       let ventanasDelantera = new THREE.CanvasTexture(ventanas1)
       ventanasDelantera.center = new THREE.Vector2(0.5, 0.5)
       ventanasDelantera.rotation = PI / 2
       let ventanasTrasera = new THREE.CanvasTexture(ventanas1)
       ventanasTrasera.center = new THREE.Vector2(0.5, 0.5)
       ventanasTrasera.rotation = -PI / 2

       let ventanas2 = document.createElement("canvas")
       {
            let e = 5
            ventanas2.width = 128 * e
            ventanas2.height = 32 * e
            const ctx = ventanas2.getContext("2d")
            ctx.fillStyle = "white"
            ctx.fillRect(0, 0, 128 * e, 32 * e)
            ctx.fillStyle = "black"
            ctx.fillRect(10 * e, 8 * e, 38 * e, 24 * e)
            ctx.fillRect(58 * e, 8 * e, 60 * e, 24 * e)
       }
       let ventanasDerecha = new THREE.CanvasTexture(ventanas2)

       let ventanasIzquierda = new THREE.CanvasTexture(ventanas2)
       ventanasIzquierda.flipY = false

       const cabina = new THREE.Mesh(
            new THREE.BoxBufferGeometry(33, 24, 12),
            [
                 new THREE.MeshPhongMaterial({ map: ventanasDelantera }),
                 new THREE.MeshPhongMaterial({ map: ventanasTrasera }),
                 new THREE.MeshPhongMaterial({ map: ventanasIzquierda }),
                 new THREE.MeshPhongMaterial({ map: ventanasDerecha }),
                 new THREE.MeshPhongMaterial({ color: "white" }),
                 new THREE.MeshPhongMaterial({ color: "white" })
            ]
       )
       cabina.position.z = 25
       cabina.position.x = -6

       g.add(cabina)

       return g
     }
    </script>
  </body>
</html>