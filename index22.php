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

    .wrapper .content-wrapper {
      min-height: 100vh;
    }
  </style>
</head>

<body class="dark-mode text-sm">
  <div class="wrapper">
    <div class="content-wrapper kanban" style="margin-left: 0!important">
      <section class="content p-2">
        <div class="row h-100">
          <div class="col-12 h-100">
            <div class="card card-row card-default w-100 h-100">
              <div id="pantalla" class="card-body p-0 m-0 h-100 overflow-hidden">
                
              </div>
            </div>            
          </div>
          <div class="col-4 h-100 d-none">
            <div class="card card-row card-default w-100 h-100">
              <div id="" class="card-body p-0 m-0 h-100 overflow-hidden">
                
              </div>
            </div>            
          </div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
<script src="js/librerias/jquery-3.5.1.min.js"></script>
<script src="js/librerias/bootstrap.bundle.min.js"></script>
<script type="importmap">
  {
    "imports": {
      "three": "./threeJsMaster/build/three.module.js",
      "three/addons/": "./threeJsMaster/examples/jsm/",
      "cannon-es": "./threeJsMaster/examples/jsm/physics/cannon-es.js",
      "cannon-es-debugger": "./threeJsMaster/examples/jsm/physics/cannon-es-debugger.js"
    }
  }
</script>
<script type="module">
  import * as THREE from 'three';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { TransformControls } from 'three/addons/controls/TransformControls.js';
  import * as CANNON from 'cannon-es'
  import CannonDebugger from 'cannon-es-debugger'

  const pantalla = document.querySelector('#pantalla');


    
    
      let scene = new THREE.Scene(),
      // camera = new THREE.PerspectiveCamera(75, w/h, 0.001, 100),
      camera = new THREE.PerspectiveCamera(
        75,
        pantalla.clientWidth / pantalla.clientHeight
      ),
      renderConfig = {antialias: true, alpha: true},
      renderer = new THREE.WebGLRenderer(renderConfig);
  camera.position.set(0, 1, -10);
  camera.lookAt(0,0,0);
  renderer.setPixelRatio(window.devicePixelRatio);


  renderer.setSize(pantalla.clientWidth , pantalla.clientHeight);
  pantalla.appendChild(renderer.domElement);

  var controls = new OrbitControls(camera, renderer.domElement);
  controls.enableDamping = true;
  controls.dampingFactor = 0.5;

  controls.maxPolarAngle = Math.PI;

  controls.screenSpacePanning = true;

  window.addEventListener('resize', function() {
  
  
    // camera.aspect = w/h;
    // camera.updateProjectionMatrix();
    // renderer.setSize(w, h);

    camera.aspect = pantalla.clientWidth / pantalla.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(pantalla.clientWidth, pantalla.clientHeight);
  })

  // var geometry = new THREE.PlaneGeometry(10, 10, 10);
  // var material = new THREE.MeshBasicMaterial({color: 0x14EA2AFF, side: THREE.DoubleSide});
  // var plane = new THREE.Mesh(geometry, material);
  // plane.rotation.x = Math.PI/2;
  // scene.add(plane);

  var sunlight = new THREE.DirectionalLight(0xffffff, 1.0);
  sunlight.position.set(-10, 10, 0);
  scene.add(sunlight)

  /**
  * Physics
  **/

  const world = new CANNON.World();
  let fixedTimeStep = 1.0/60.0;
  let damping = 0.01;
  
  world.broadphase = new CANNON.NaiveBroadphase();
  world.gravity.set(0, -10, 0);
  // debugRenderer = new THREE.CannonDebugRenderer(scene, world);
  
  const groundShape = new CANNON.Plane();
      const groundMaterial = new CANNON.Material();
  const groundBody = new CANNON.Body({ mass: 0, material: groundMaterial });
  groundBody.quaternion.setFromAxisAngle( new CANNON.Vec3(1,0,0), -Math.PI/2);
  groundBody.addShape(groundShape);
  world.addBody(groundBody);
      
  addVisual(groundBody, 'ground', false, true);
      
  let shapes = {};
  shapes.sphere = new CANNON.Sphere(0.5);
  shapes.box = new CANNON.Box(new CANNON.Vec3(0.5,0.5,0.5));


  function render() {
    requestAnimationFrame(render);
    world.step(fixedTimeStep);
    updateBodies(world);
    renderer.render(scene, camera);
  }

  function addBody( sphere=true ){
    const material = new CANNON.Material();
    const body = new CANNON.Body({ mass: 5, material: material });
    if (sphere){
      body.addShape(shapes.sphere);
    }else{
      body.addShape(shapes.box);
    }
        
    const x = Math.random()*0.3 + 1;
    body.position.set((sphere) ? -x : x, 5, 0);
    body.linearDamping = damping;
    world.addBody(body);
        
    addVisual(body, (sphere) ? 'sphere' : 'box', true, false);
        
    // Create contact material behaviour
    const material_ground = new CANNON.ContactMaterial(groundMaterial, material, { friction: 0.0, restitution: (sphere) ? 0.9 : 0.3 });
    
    world.addContactMaterial(material_ground);
  }

  

  function addVisual(body, name, castShadow=true, receiveShadow=true){
    body.name = name;
    let mesh = agregarMaya(body, castShadow, receiveShadow);
    body.threemesh = mesh;
    mesh.castShadow = castShadow;
    mesh.receiveShadow = receiveShadow;
    scene.add(mesh);
  }

  function agregarMaya(body, castShadow, receiveShadow){
    const obj = new THREE.Object3D();
    let material = new THREE.MeshLambertMaterial({color:0x888888});
    const game = this;
    let index = 0;

    let particleGeo = new THREE.SphereGeometry( 1, 16, 8 );
    let particleMaterial = new THREE.MeshLambertMaterial( { color: 0xff0000 } );

    let s = {
      stepFrequency: 60,
      quatNormalizeSkip: 2,
      quatNormalizeFast: true,
      gx: 0,
      gy: 0,
      gz: 0,
      iterations: 3,
      tolerance: 0.0001,
      k: 1e6,
      d: 3,
      scene: 0,
      paused: false,
      rendermode: "solid",
      constraints: false,
      contacts: false,  // Contact points
      cm2contact: false, // center of mass to contact points
      normals: false, // contact normals
      axes: false, // "local" frame axes
      particleSize: 0.1,
      shadows: false,
      aabbs: false,
      profiling: false,
      maxSubSteps:3
    };
    
    body.shapes.forEach (function(shape){
      let mesh;
      let geometry;
      let v0, v1, v2;

      switch(shape.type){

      case CANNON.Shape.types.SPHERE:
        const sphere_geometry = new THREE.SphereGeometry( shape.radius, 8, 8);
        mesh = new THREE.Mesh( sphere_geometry, material );
        break;

      case CANNON.Shape.types.PARTICLE:
        mesh = new THREE.Mesh( particleGeo, particleMaterial );
        
        mesh.scale.set(s.particleSize,s.particleSize,s.particleSize);
        break;

      case CANNON.Shape.types.PLANE:
        geometry = new THREE.PlaneGeometry(10, 10, 4, 4);
        mesh = new THREE.Object3D();
        const submesh = new THREE.Object3D();
        const ground = new THREE.Mesh( geometry, material );
        ground.scale.set(100, 100, 100);
        submesh.add(ground);

        mesh.add(submesh);
        break;

      case CANNON.Shape.types.BOX:
        const box_geometry = new THREE.BoxGeometry(  shape.halfExtents.x*2,
                              shape.halfExtents.y*2,
                              shape.halfExtents.z*2 );
        mesh = new THREE.Mesh( box_geometry, material );
        break;

      case CANNON.Shape.types.CONVEXPOLYHEDRON:
        const geo = new THREE.Geometry();

        // Add vertices
        shape.vertices.forEach(function(v){
          geo.vertices.push(new THREE.Vector3(v.x, v.y, v.z));
        });

        shape.faces.forEach(function(face){
          // add triangles
          const a = face[0];
          for (let j = 1; j < face.length - 1; j++) {
            const b = face[j];
            const c = face[j + 1];
            geo.faces.push(new THREE.Face3(a, b, c));
          }
        });
        geo.computeBoundingSphere();
        geo.computeFaceNormals();
        mesh = new THREE.Mesh( geo, material );
        break;

      case CANNON.Shape.types.HEIGHTFIELD:
        geometry = new THREE.Geometry();

        v0 = new CANNON.Vec3();
        v1 = new CANNON.Vec3();
        v2 = new CANNON.Vec3();
        for (let xi = 0; xi < shape.data.length - 1; xi++) {
          for (let yi = 0; yi < shape.data[xi].length - 1; yi++) {
            for (let k = 0; k < 2; k++) {
              shape.getConvexTrianglePillar(xi, yi, k===0);
              v0.copy(shape.pillarConvex.vertices[0]);
              v1.copy(shape.pillarConvex.vertices[1]);
              v2.copy(shape.pillarConvex.vertices[2]);
              v0.vadd(shape.pillarOffset, v0);
              v1.vadd(shape.pillarOffset, v1);
              v2.vadd(shape.pillarOffset, v2);
              geometry.vertices.push(
                new THREE.Vector3(v0.x, v0.y, v0.z),
                new THREE.Vector3(v1.x, v1.y, v1.z),
                new THREE.Vector3(v2.x, v2.y, v2.z)
              );
              var i = geometry.vertices.length - 3;
              geometry.faces.push(new THREE.Face3(i, i+1, i+2));
            }
          }
        }
        geometry.computeBoundingSphere();
        geometry.computeFaceNormals();
        mesh = new THREE.Mesh(geometry, material);
        break;

      case CANNON.Shape.types.TRIMESH:
        geometry = new THREE.Geometry();

        v0 = new CANNON.Vec3();
        v1 = new CANNON.Vec3();
        v2 = new CANNON.Vec3();
        for (let i = 0; i < shape.indices.length / 3; i++) {
          shape.getTriangleVertices(i, v0, v1, v2);
          geometry.vertices.push(
            new THREE.Vector3(v0.x, v0.y, v0.z),
            new THREE.Vector3(v1.x, v1.y, v1.z),
            new THREE.Vector3(v2.x, v2.y, v2.z)
          );
          var j = geometry.vertices.length - 3;
          geometry.faces.push(new THREE.Face3(j, j+1, j+2));
        }
        geometry.computeBoundingSphere();
        geometry.computeFaceNormals();
        mesh = new THREE.Mesh(geometry, MutationRecordaterial);
        break;

      default:
        throw "Visual type not recognized: "+shape.type;
      }

      mesh.receiveShadow = receiveShadow;
      mesh.castShadow = castShadow;
            
            mesh.traverse( function(child){
                if (child.isMesh){
                    child.castShadow = castShadow;
          child.receiveShadow = receiveShadow;
                }
            });

      var o = body.shapeOffsets[index];
      var q = body.shapeOrientations[index++];
      mesh.position.set(o.x, o.y, o.z);
      mesh.quaternion.set(q.x, q.y, q.z, q.w);

      obj.add(mesh);
    });

    return obj;
  }

  function updateBodies(world){
    world.bodies.forEach( function(body){
        if ( body.threemesh != undefined){
          body.threemesh.position.copy(body.position);
          body.threemesh.quaternion.copy(body.quaternion);
        }
    });
  }

  function navigate(e) {
    if (e.type != 'keydown' && e.type != 'keyup') return;
    var keyup = e.type == 'keyup';
    switch(e.keyCode) {
      case 32: // espacio
        addBody();
        break;
    }
  }

  window.addEventListener('keydown', navigate);
  window.addEventListener('keyup', navigate);

  render();
  
</script>