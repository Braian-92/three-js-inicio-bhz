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

      let enJuego = false
      let frameCount = 0
      let vueltasPorSegundo = 0.2;
      let tiempoInicio = Date.now();

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

      function iniciar() {
           if (enJuego) {
                return
           }
           enJuego = true
           jugador.ACELERAR = 0
           jugador.angulo  = PI
           enemigo1.angulo = -PI
           enemigo2.angulo = -PI / 2
           enemigo3.angulo = PI
           // document.getElementById("info").style.visibility = "hidden"
      }

      document.addEventListener("keydown", (evnt) => {
           switch (evnt.keyCode) {
                case KEY_UP:
                case KEY_W:
                     jugador.ACELERAR = 1
                     break;
                case KEY_DOWN:
                case KEY_S:
                     jugador.ACELERAR = -1
                     break
                case KEY_ENTER:
                     iniciar();
                     break
                default:
                     break;
           }
      })

      document.addEventListener("keyup", (evnt) => {
           jugador.ACELERAR = 0
      })
      //add geometry
      // var geometry = new THREE.BoxGeometry(30,30,30,5,5,5);
      // var material = new THREE.MeshBasicMaterial({
      //   color: 0x00ff00,
      //   wireframe: true,
      // });
      // var objeto = new THREE.Mesh(geometry, material);
      // scene.add(objeto);

      let jugador  = Car();
      let enemigo1 = Car()
      let enemigo2 = Car()
      let enemigo3 = Car()
      scene.add(jugador, enemigo1, enemigo2, enemigo3)
      jugador.rotation.z = -PI/4
      // scene.add(jugador);
      scene.add(pista())

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

      iniciar();

      window.addEventListener('resize', redimensionar);

      function redimensionar(){
        camera.aspect = window.innerWidth / window.innerHeight;
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.updateProjectionMatrix();
        renderer.render(scene, camera);
      }

      function actualizar(){
      	const tiempo_transcurrido = (Date.now() - tiempoInicio) / 1000;
         if (enJuego) {
              {//Dibujado del primer jugador
                   const girar = 2 * PI * tiempo_transcurrido * vueltasPorSegundo * (2 ** jugador.ACELERAR)
                   jugador.angulo += girar
                   let p = trayectoriaCircular(
                         -150,
                         0,
                         270 + 40,
                         jugador.angulo
                   )
                   jugador.position.x = p.x
                   jugador.position.y = p.y
                   jugador.rotation.z = jugador.angulo + PI / 2
              }

              let carriles = [40, 40, -40]
              let velocidades = [1.5, 1.5, 3]
              let obstaculos = [enemigo1, enemigo2, enemigo3]
              for (let i = 0; i < obstaculos.length; i++) {
                   let jugadorObstaculo = obstaculos[i]
                   jugadorObstaculo.angulo -= velocidades[i] * 2 * PI * tiempo_transcurrido * vueltasPorSegundo
                   let p = trayectoriaCircular(
                        150,
                        0,
                        270 + carriles[i],
                        jugadorObstaculo.angulo
                   )
                   jugadorObstaculo.position.x = p.x
                   jugadorObstaculo.position.y = p.y
                   jugadorObstaculo.rotation.z = jugadorObstaculo.angulo - PI / 2
                   // if (distancia3D(jugador.position, jugadorObstaculo.position) < 35) {
                   //      finalizar()
                   // }
              }
         }
         // document.getElementById("Puntaje").innerHTML = int(jugador.angulo / (2 * PI))
         tiempoInicio = Date.now()
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

     function pista() {
        const planeGeometry = new THREE.PlaneBufferGeometry(1280, 1000)
        const material = new THREE.MeshPhongMaterial({ map: textura() })
        const plano = new THREE.Mesh(planeGeometry, material)
        plano.receiveShadow = true;
        return plano

        function textura() {
             const canvas = document.createElement("canvas")
             canvas.width = 1280
             canvas.height = 1000
             const ctx = canvas.getContext("2d")

             //Color del pasto
             ctx.fillStyle = "limegreen"
             ctx.fillRect(0, 0, 1280, 1000)

             //Relieve del pasto
             ctx.strokeStyle = "green"
             ctx.save()
             ctx.translate(1280 / 2, canvas.height / 2)
             ctx.lineWidth = 150
             {
                  ctx.beginPath();
                  ctx.arc(-150, -15, 270, 0, 2 * PI);
                  ctx.stroke()
             }
             {
                  ctx.beginPath();
                  ctx.arc(150, -15, 270, 0, 2 * PI);
                  ctx.stroke()
             }
             ctx.restore()

             //Concreto de la carretera
             ctx.strokeStyle = "darkslategray"
             ctx.save()
             ctx.translate(1280 / 2, canvas.height / 2)
             ctx.lineWidth = 150
             {
                  ctx.beginPath();
                  ctx.arc(-5 * 30, 0, 9 * 30, 0, 2 * PI);
                  ctx.stroke()
             }
             {
                  ctx.beginPath();
                  ctx.arc(5 * 30, 0, 9 * 30, 0, 2 * PI);
                  ctx.stroke()
             }
             ctx.restore()

             //Lineas de los carriles
             ctx.lineWidth = 2
             ctx.strokeStyle = "white"
             ctx.setLineDash([10, 14])

             ctx.save()
             ctx.translate(1280 / 2, canvas.height / 2)
             {
                  ctx.beginPath();
                  ctx.arc(-5 * 30, 0, 9 * 30, 0, 2 * PI);
                  ctx.stroke()
             }
             {
                  ctx.beginPath();
                  ctx.arc(5 * 30, 0, 9 * 30, 0, 2 * PI);
                  ctx.stroke()
             }
             ctx.restore()

             return new THREE.CanvasTexture(canvas)
        }
   }
    </script>
  </body>
</html>