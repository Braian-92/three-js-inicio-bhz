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
    </style>
  </head>
  <body class="dark-mode text-sm">
    <div class="container-fluid">
      <div class="row mt-0">
        <div class="col-8 p-1">
          <div class="card card-outline card-success">
            <div class="card-header p-2">
              <h3 class="card-title">Pantalla</h3>
            </div>
            <div id="pantalla" class="card-body p-0 m-0">
            </div>
          </div>
        </div>
        <div class="col-2 p-1">
          <div class="row">
            <div class="col-12 p-0 px-1">
              <div class="card card-outline card-success">
                <div class="card-header p-2">
                  <h3 class="card-title">Elementos</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 282px;">
                  <ul id="listaElementosPantalla" class="nav nav-pills flex-column px-2">
                    
                  </ul>
                </div>
                <div class="card-header p-2 card-outline card-success">
                  <h3 class="card-title">Primitivas</h3>
                </div>
                <div class="card-body table-responsive p-0" style="height: 280px;">
                  <ul id="listaPrimitivas" class="nav nav-pills flex-column px-2">
                    <li class="nav-item text-nowrap">
                      <i class="far fa-circle text-danger cursor-pointer" title="Desplegar opciones"></i>
                      <span class="cursor-pointer" id="crearCubo">
                        Cubo
                      </span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-2 p-1">
          <div class="card card-outline card-success">
            <div class="card-header p-2">
              <h3 id="tituloPropiedadesElemento" class="card-title">Propiedades</h3>
            </div>
            <div class="card-body table-responsive p-0 m-0" style="height: 600px;">
              <div class="row m-0">
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Eje X</label>
                    <input id="elementoEjeX" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Eje Y</label>
                    <input id="elementoEjeY" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Eje Z</label>
                    <input id="elementoEjeZ" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
              </div>
              <div class="row m-0">
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Rot X</label>
                    <input id="elementoRotX" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Rot Y</label>
                    <input id="elementoRotY" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label class="px-0 m-0 text-sm w-100 text-center">Rot Z</label>
                    <input id="elementoRotZ" type="number" class="form-control form-control-sm" placeholder="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script src="js/librerias/jquery-3.5.1.min.js"></script>
  <script src="js/librerias/bootstrap.bundle.min.js"></script>

  <script src="disparo.js"></script>
  <script src="animacion.js"></script>
  <script src="teclado.js"></script>
  <script src="controlNave.js"></script>

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



    var  bodyRect = document.body.getBoundingClientRect(),
    elemRect = document.querySelector('#pantalla').getBoundingClientRect(),
    offsetT   = elemRect.top - bodyRect.top,
    offsetL   = elemRect.left - bodyRect.left;

    // alert('Element is ' + offset + ' vertical pixels from <body>');

    //creating ESCENA


    const pantalla = document.querySelector('#pantalla');
    let elementoSeleccionadoListado = null;
    var ESCENA = new THREE.Scene();
    var RENDERIZADO = new THREE.WebGLRenderer();
    var CAMARA;
    var material = new THREE.MeshBasicMaterial({
      color: 0x00ff00,
      wireframe: true,
    });


    const ELEMENTOS = [];
    let transformControl;
    const pointer = new THREE.Vector2();
    const raycaster = new THREE.Raycaster();
    const onUpPosition = new THREE.Vector2();
    const onDownPosition = new THREE.Vector2();

    let TECLADO;
    let PERSONAJE;
    let ANIMACION;
    let PANTALLA = {
      width  : 1000,
      height : 600
    };

    init();

    function init() {
      ESCENA.name = 'ESCENA';
      ESCENA.background = new THREE.Color(0x2a3b4c);

      //add CAMARA
      CAMARA = new THREE.PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight
      );
      CAMARA.name = 'CAMARA';
      CAMARA.position.z = 20;

      //RENDERIZADO
      RENDERIZADO.setSize(pantalla.clientWidth , 600);

      pantalla.appendChild(RENDERIZADO.domElement);

      //add geometry



      const GRID = new THREE.GridHelper(30, 30);
      GRID.name = 'GRID';
      ESCENA.add(GRID);

      var controls = new OrbitControls(CAMARA, RENDERIZADO.domElement);

      // controls.minDistance = 3;
      // controls.maxDistance = 10;

      //controls.enableZoom = false;

      //controls.enableRotate = false;

      controls.enableDamping = true;
      controls.dampingFactor = 0.5;

      controls.maxPolarAngle = Math.PI;

      controls.screenSpacePanning = true;

      transformControl = new TransformControls( CAMARA, RENDERIZADO.domElement );
      // transformControl.addEventListener( 'change', render );
      transformControl.addEventListener( 'dragging-changed', function ( event ) {
        controls.enabled = ! event.value;
      } );

      document.addEventListener( 'pointerdown', onPointerDown );
      document.addEventListener( 'pointerup', onPointerUp );
      ESCENA.add( transformControl );

      TECLADO = new Teclado(document);

      ANIMACION = new Animacion(ESCENA);

      var NAVE = new THREE.Mesh(
        new THREE.BoxGeometry(),
        material
      );

      var EXPLOSION = new THREE.Mesh(
        new THREE.BoxGeometry(),
        material
      );

      var DISPARO = new THREE.Mesh(
        new THREE.BoxGeometry(),
        material
      );

      PERSONAJE = new Nave(PANTALLA, ESCENA, TECLADO, NAVE, EXPLOSION, DISPARO); 
      PERSONAJE.posicionar();
      PERSONAJE.velocidad = 200;

      ANIMACION.nuevoElemento(PERSONAJE);
      ANIMACION.conectar();
      PERSONAJE.acabaramVidas = function() {
         // ANIMACION.desconectar();
      }

      activarDisparo(true);

    }

    function activarDisparo(ativar) {
       if (ativar) {
          TECLADO.disparar(ESPACIO, function() {
             PERSONAJE.disparar();
          });
       }else{
          TECLADO.disparar(ESPACIO, null);
       }
    }

    function onPointerDown( event ) {
      onDownPosition.x = event.clientX;
      onDownPosition.y = event.clientY;

      const {top, left, width, height} = RENDERIZADO.domElement.getBoundingClientRect();

      pointer.x = -1 + 2 * (event.clientX - left) / width;
      pointer.y = 1 - 2 * (event.clientY - top) / height;

      // pointer.x = ( event.clientX / pantalla.clientWidth ) * 2 - 1 ;
      // pointer.y = - ( event.clientY / 600 ) * 2 + 1;

      raycaster.setFromCamera( pointer, CAMARA );

      const intersects = raycaster.intersectObjects( ELEMENTOS, false );

      if ( intersects.length > 0 ) {

        const object = intersects[ 0 ].object;

        if ( object !== transformControl.object ) {
          elementoSeleccionadoListado = object;
          transformControl.attach( object );

        }

      }

    }
    function onPointerUp( event ) {

      onUpPosition.x = event.clientX;
      onUpPosition.y = event.clientY;

      // if ( onDownPosition.distanceTo( onUpPosition ) === 0 ) transformControl.detach();

    }

    
    // var CUBO = new THREE.Mesh(
    //   new THREE.BoxGeometry(),
    //   material
    // );

    // CUBO.name = 'CUBO';
    // ELEMENTOS.push(CUBO);
    // ESCENA.add(CUBO);

    // let CERO = new THREE.Mesh(
    //   new THREE.BoxGeometry(1,1,1),
    //   material
    // );
    // CERO.name = 'CERO';
    // ELEMENTOS.push(CERO);
    // ESCENA.add(CERO);

    // let XXX = new THREE.Mesh(
    //   new THREE.SphereGeometry(1, 8, 8),
    //   material
    // );
    // XXX.position.x = 10;
    // XXX.name = 'XXX';
    // ELEMENTOS.push(XXX);
    // ESCENA.add(XXX);

    // let YYY = new THREE.Mesh(
    //   new THREE.TorusGeometry(1, 1, 6),
    //   material
    // );
    // YYY.position.y = 10;
    // YYY.name = 'YYY';
    // ELEMENTOS.push(YYY);
    // ESCENA.add(YYY);

    // let ZZZ = new THREE.Mesh(
    //   new THREE.ConeGeometry(1, 1, 32),
    //   material
    // );
    // ZZZ.position.z = 10;
    // ZZZ.name = 'ZZZ';
    // ELEMENTOS.push(ZZZ);
    // ESCENA.add(ZZZ);

    //animation
    let tiempo = 0;
    let radio = 10;
    let centroX = 10;
    let centroY = 10;

    var animate = function () {
      requestAnimationFrame(animate);

      // CERO.rotation.x += 0.01;
      // CERO.rotation.y += 0.01;

      // CUBO.position.x = centroX + Math.cos(Math.PI*tiempo) * radio;
      // CUBO.position.y = centroY + Math.sin(Math.PI*tiempo) * radio;
      tiempo += 0.01;
      if(elementoSeleccionadoListado != null){
        $('#elementoEjeX').val(elementoSeleccionadoListado.position.x);
        $('#elementoEjeY').val(elementoSeleccionadoListado.position.y);
        $('#elementoEjeZ').val(elementoSeleccionadoListado.position.z);

        $('#elementoRotX').val(elementoSeleccionadoListado.rotation.x);
        $('#elementoRotY').val(elementoSeleccionadoListado.rotation.y);
        $('#elementoRotZ').val(elementoSeleccionadoListado.rotation.z);
      }

      RENDERIZADO.render(ESCENA, CAMARA);
    };
    animate();

    window.addEventListener('resize', function() {
      CAMARA.aspect = pantalla.clientWidth / 600;
      CAMARA.updateProjectionMatrix();
      RENDERIZADO.setSize(pantalla.clientWidth, 600);
    });

    dibujarElementosListado();
    function dibujarElementosListado(){
      let listaElementosPantalla = ``;
      $.each(ELEMENTOS, function (indObj, objeto) {
      // ESCENA.traverse(function(objeto){
        let etiquetaElemento = objeto.name;
        listaElementosPantalla += `
        <li class="nav-item text-nowrap">
            <i class="far fa-circle text-danger cursor-pointer" title="Desplegar opciones" data-toggle="dropdown"></i>
            <span nombreElemento="${etiquetaElemento}" class="elementoListadoPantalla cursor-pointer" title="Abrir tabla">${etiquetaElemento}</span>
            <div class="dropdown-menu" role="menu">
              <span class="dropdown-item py-0 my-0">${etiquetaElemento}</span>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item py-0 my-0 insertTabla" href="#">Insertar</a>
              <a class="dropdown-item py-0 my-0 updateTabla" href="#">Actualizar</a>
              <a class="dropdown-item py-0 my-0 clonarTabla" href="#">Clonar</a>
              <a class="dropdown-item py-0 my-0 cambiarNombre" href="#">Renombrar</a>
              <a class="dropdown-item py-0 my-0 crearTabla" href="#">Crear Tabla</a>
              <a class="dropdown-item py-0 my-0 copiarRegistros" href="#">Copiar Registros</a>
            </div>
        </li>
        `;
      });
      $('#listaElementosPantalla').html(listaElementosPantalla); 
    }

    

    $(document).on('click', '.elementoListadoPantalla', function(e){
      const nombreElementoT = $(this).attr('nombreElemento');
      elementoSeleccionadoListado = ESCENA.getObjectByName(nombreElementoT);
      
      aplicarAyudanteTransform(elementoSeleccionadoListado);

      console.log(elementoSeleccionadoListado);
      $('#tituloPropiedadesElemento').text('Propiedades: ' + nombreElementoT);
    });

    function aplicarAyudanteTransform(objetoTemp){
      if ( objetoTemp !== transformControl.object ) {
        transformControl.attach( objetoTemp );
      }
    }

    $(document).on('click', '#crearCubo', function(e){
      crearCubo(0, 0, 0);
      dibujarElementosListado();
    });

    function crearCubo(_x, _y, _z){
      var CUBO = new THREE.Mesh(
        new THREE.BoxGeometry(),
        material
      );
      CUBO.position.x = _x; 
      CUBO.position.y = _y; 
      CUBO.position.z = _z; 
      ELEMENTOS.push(CUBO);
      CUBO.name = 'CUBO_'+ELEMENTOS.length;
      ESCENA.add(CUBO);
    }

  </script>
</html>