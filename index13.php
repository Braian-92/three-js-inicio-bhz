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
      <div class="row mt-2">
        <div class="col-8">
          <div class="card card-outline card-success">
            <div class="card-header p-2">
              <h3 class="card-title">Pantalla</h3>
            </div>
            <div id="pantalla" class="card-body p-0 m-0">
            </div>
          </div>
        </div>
        <div class="col-2">
          <div class="card card-outline card-success">
            <div class="card-header p-2">
              <h3 class="card-title">Elementos</h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 600px;">
              <ul id="listaElementosPantalla" class="nav nav-pills flex-column px-2">
                
              </ul>
            </div>
          </div>
        </div>
        <div class="col-2">
          <div class="card card-outline card-success">
            <div class="card-header p-2">
              <h3 id="tituloPropiedadesElemento" class="card-title">Propiedades</h3>
            </div>
            <div class="card-body table-responsive p-0 m-0" style="height: 600px;">
              <div class="row m-0">
                <div class="col-4">
                  <div class="form-group">
                    <label>Eje X</label>
                    <input id="elementoPropX" type="number" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Eje Y</label>
                    <input id="elementoPropY" type="number" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="">
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label>Eje Z</label>
                    <input id="elementoPropZ" type="number" class="form-control form-control-sm" id="exampleInputEmail1" placeholder="">
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
  <script type="module">
    import * as THREE from "./three.module.js";
    import { OrbitControls } from "./OrbitControls.js";
    //creating ESCENA
    const pantalla = document.querySelector('#pantalla');
    var ESCENA = new THREE.Scene();
    ESCENA.name = 'ESCENA';
    ESCENA.background = new THREE.Color(0x2a3b4c);

    //add CAMARA
    var CAMARA = new THREE.PerspectiveCamera(
      75,
      window.innerWidth / window.innerHeight
    );
    CAMARA.name = 'CAMARA';
    CAMARA.position.z = 20;

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
    cube.name = 'CUBO';

    ESCENA.add(cube);

    let CERO = new THREE.Mesh(
      new THREE.BoxGeometry(1,1,1),
      material
    );
    CERO.name = 'CERO';
    ESCENA.add(CERO);

    let XXX = new THREE.Mesh(
      new THREE.SphereGeometry(1, 8, 8),
      material
    );
    XXX.position.x = 10;
    XXX.name = 'XXX';
    ESCENA.add(XXX);

    let YYY = new THREE.Mesh(
      new THREE.TorusGeometry(1, 1, 6),
      material
    );
    YYY.position.y = 10;
    YYY.name = 'YYY';
    ESCENA.add(YYY);

    let ZZZ = new THREE.Mesh(
      new THREE.ConeGeometry(1, 1, 32),
      material
    );
    ZZZ.position.z = 10;
    ZZZ.name = 'ZZZ';
    ESCENA.add(ZZZ);


    const GRID = new THREE.GridHelper(30, 30);
    GRID.name = 'GRID';
    ESCENA.add(GRID);

    var controls = new OrbitControls(CAMARA, renderer.domElement);

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

      renderer.render(ESCENA, CAMARA);
    };
    animate();

    dibujarElementosListado();
    function dibujarElementosListado(){
      let listaElementosPantalla = ``;
      ESCENA.traverse(function(objeto){
        let etiquetaElemento = objeto.name;
        listaElementosPantalla += `
        <li class="nav-item text-nowrap">
            <i class="far fa-circle text-danger cursor-pointer" title="Desplegar opciones" data-toggle="dropdown"></i>
            <span nombreElemento="${etiquetaElemento}" class="elementoListadoPantalla cursor-pointer" title="Abrir tabla">${etiquetaElemento}</span>
            <div class="dropdown-menu d-none" role="menu">
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
      let elementoSeleccionadoT = ESCENA.getObjectByName(nombreElementoT);
      console.log(elementoSeleccionadoT);
      $('#tituloPropiedadesElemento').text('Propiedades: ' + nombreElementoT);
      $('#elementoPropZ')
    });

  </script>
</html>