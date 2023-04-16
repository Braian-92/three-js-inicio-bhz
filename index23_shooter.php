<style>
  body {
    overflow: hidden;
    margin: 0px;
  }

  #menuPanel {
    position: absolute;
    background-color: rgba(255, 255, 255, 0.5);
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
  }

  #startButton {
    height: 50px;
    width: 200px;
    margin: -25px -100px;
    position: relative;
    top: 50%;
    left: 50%;
    font-size: 32px;
  }

</style>
<!--
Threejs Boilerplate : https://github.com/Sean-Bradley/Three.js-TypeScript-Boilerplate
Threejs Course : https://sbcode.net/threejs/
Discount Coupons : https://sbcode.net/coupons#threejs
-->
<!-- Import maps polyfill -->
<!-- Remove this when import maps will be widely supported -->
<script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>

<script type="importmap">
  {
    "imports": {
      "three": "https://cdn.skypack.dev/three@0.139.2/build/three.module",
      "three/": "https://cdn.skypack.dev/three@0.139.2/",      
      "cannon-es": "https://cdn.skypack.dev/cannon-es"
    }
  }
</script>

<div id="menuPanel">
  <button id="startButton">Click to Start</button>
</div>

<script type="module">
  // Based on https://sbcode.net/threejs/convexobjectbreaker/
  import * as THREE from "three";
  import { PointerLockControls } from "three/examples/jsm/controls/PointerLockControls";
  import Stats from "three/examples/jsm/libs/stats.module";
  import * as CANNON from "cannon-es";
  import { ConvexGeometry } from "three/examples/jsm/geometries/ConvexGeometry";
  import { ConvexObjectBreaker } from "three/examples/jsm/misc/ConvexObjectBreaker";
  import { Reflector } from "three/examples/jsm/objects/Reflector";

  const scene = new THREE.Scene();

  const camera = new THREE.PerspectiveCamera(
    75,
    window.innerWidth / window.innerHeight,
    0.1,
    1000
  );

  const renderer = new THREE.WebGLRenderer({
    antialias: true
  });
  renderer.setSize(window.innerWidth, window.innerHeight);
  document.body.appendChild(renderer.domElement);

  const menuPanel = document.getElementById("menuPanel");
  const startButton = document.getElementById("startButton");
  startButton.addEventListener(
    "click",
    function () {
      controls.lock();
    },
    false
  );

  const controls = new PointerLockControls(camera, renderer.domElement);
  controls.addEventListener("lock", () => (menuPanel.style.display = "none"));
  controls.addEventListener("unlock", () => (menuPanel.style.display = "block"));

  camera.position.y = 1;
  camera.position.z = 2;

  const onKeyDown = function (event) {
    switch (event.key) {
      case "w":
        controls.moveForward(0.25);
        break;
      case "a":
        controls.moveRight(-0.25);
        break;
      case "s":
        controls.moveForward(-0.25);
        break;
      case "d":
        controls.moveRight(0.25);
        break;
    }
  };
  document.addEventListener("keydown", onKeyDown, false);

  const world = new CANNON.World();
  world.gravity.set(0, -9.82, 0);

  world.addEventListener("postStep", function () {
    Object.keys(bulletBodies).forEach((b) => {
      const v = new CANNON.Vec3(0, 9.8, 0);
      bulletBodies[b].applyForce(v);
      bulletBodies[b].force.y += bulletBodies[b].mass; //cancel out world gravity
    });
  });

  const material = new THREE.MeshBasicMaterial({
    color: 0x00ff00,
    wireframe: true
  });

  const meshes = {};
  const bodies = {};
  let meshId = 0;

  const groundMirror = new Reflector(new THREE.PlaneBufferGeometry(1024, 1024), {
    color: new THREE.Color(0x000000),
    clipBias: 0.001,
    textureWidth: window.innerWidth * window.devicePixelRatio,
    textureHeight: window.innerHeight * window.devicePixelRatio
  });
  groundMirror.position.y = -0.05;
  groundMirror.rotateX(-Math.PI / 2);
  scene.add(groundMirror);

  const planeShape = new CANNON.Plane();
  const planeBody = new CANNON.Body({
    mass: 0
  });
  planeBody.addShape(planeShape);
  planeBody.quaternion.setFromAxisAngle(new CANNON.Vec3(1, 0, 0), -Math.PI / 2);
  world.addBody(planeBody);

  const convexObjectBreaker = new ConvexObjectBreaker();

  for (let i = 0; i < 20; i++) {
    const size = {
      x: Math.random() * 4 + 2,
      y: Math.random() * 10 + 5,
      z: Math.random() * 4 + 2
    };
    const geo = new THREE.BoxBufferGeometry(size.x, size.y, size.z);
    const cube = new THREE.Mesh(geo, material);

    cube.position.x = Math.random() * 50 - 25;
    cube.position.y = size.y / 2 + 0.1;
    cube.position.z = Math.random() * 50 - 25;

    scene.add(cube);
    meshes[meshId] = cube;
    convexObjectBreaker.prepareBreakableObject(
      meshes[meshId],
      1,
      new THREE.Vector3(),
      new THREE.Vector3(),
      true
    );

    const cubeShape = new CANNON.Box(
      new CANNON.Vec3(size.x / 2, size.y / 2, size.z / 2)
    );
    const cubeBody = new CANNON.Body({
      mass: 1
    });
    cubeBody.userData = {
      splitCount: 0,
      id: meshId
    };
    cubeBody.addShape(cubeShape);
    cubeBody.position.x = cube.position.x;
    cubeBody.position.y = cube.position.y;
    cubeBody.position.z = cube.position.z;

    world.addBody(cubeBody);
    bodies[meshId] = cubeBody;

    meshId++;
  }

  const bullets = {};
  const bulletBodies = {};
  let bulletId = 0;

  const bulletMaterial = new THREE.MeshBasicMaterial({
    color: 0xff0000,
    wireframe: true
  });
  document.addEventListener("click", onClick, false);

  function onClick() {
    if (controls.isLocked) {
      const bullet = new THREE.Mesh(
        new THREE.SphereGeometry(1, 4, 4),
        bulletMaterial
      );
      bullet.position.copy(camera.position);
      scene.add(bullet);
      bullets[bulletId] = bullet;

      const bulletShape = new CANNON.Sphere(1);
      const bulletBody = new CANNON.Body({
        mass: 1
      });
      bulletBody.addShape(bulletShape);
      bulletBody.position.x = camera.position.x;
      bulletBody.position.y = camera.position.y;
      bulletBody.position.z = camera.position.z;

      world.addBody(bulletBody);
      bulletBodies[bulletId] = bulletBody;

      bulletBody.addEventListener("collide", (e) => {
        if (e.body.userData) {
          if (e.body.userData.splitCount < 2) {
            splitObject(e.body.userData, e.contact);
          }
        }
      });
      const v = new THREE.Vector3(0, 0, -1);
      v.applyQuaternion(camera.quaternion);
      v.multiplyScalar(50);
      bulletBody.velocity.set(v.x, v.y, v.z);
      bulletBody.angularVelocity.set(
        Math.random() * 10 + 1,
        Math.random() * 10 + 1,
        Math.random() * 10 + 1
      );

      bulletId++;

      //remove old bullets
      while (Object.keys(bullets).length > 5) {
        scene.remove(bullets[bulletId - 6]);
        delete bullets[bulletId - 6];
        world.removeBody(bulletBodies[bulletId - 6]);
        delete bulletBodies[bulletId - 6];
      }
    }
  }

  const CreateConvexPolyhedron = function (geometry) {
    var position = geometry.attributes.position;
    var normal = geometry.attributes.normal;
    var vertices = [];
    for (var i = 0; i < position.count; i++) {
      vertices.push(new THREE.Vector3().fromBufferAttribute(position, i));
    }
    var faces = [];
    for (var i = 0; i < position.count; i += 3) {
      var vertexNormals =
        normal === undefined
          ? []
          : [
              new THREE.Vector3().fromBufferAttribute(normal, i),
              new THREE.Vector3().fromBufferAttribute(normal, i + 1),
              new THREE.Vector3().fromBufferAttribute(normal, i + 2)
            ];
      var face = {
        a: i,
        b: i + 1,
        c: i + 2,
        normals: vertexNormals
      };
      faces.push(face);
    }
    var verticesMap = {};
    var points = [];
    var changes = [];
    for (var i = 0, il = vertices.length; i < il; i++) {
      var v = vertices[i];
      var key =
        Math.round(v.x * 100) +
        "_" +
        Math.round(v.y * 100) +
        "_" +
        Math.round(v.z * 100);
      if (verticesMap[key] === undefined) {
        verticesMap[key] = i;
        points.push(new CANNON.Vec3(vertices[i].x, vertices[i].y, vertices[i].z));
        changes[i] = points.length - 1;
      } else {
        changes[i] = changes[verticesMap[key]];
      }
    }
    var faceIdsToRemove = [];
    for (var i = 0, il = faces.length; i < il; i++) {
      var face = faces[i];
      face.a = changes[face.a];
      face.b = changes[face.b];
      face.c = changes[face.c];
      var indices = [face.a, face.b, face.c];
      for (var n = 0; n < 3; n++) {
        if (indices[n] === indices[(n + 1) % 3]) {
          faceIdsToRemove.push(i);
          break;
        }
      }
    }
    for (var i = faceIdsToRemove.length - 1; i >= 0; i--) {
      var idx = faceIdsToRemove[i];
      faces.splice(idx, 1);
    }
    var cannonFaces = faces.map(function (f) {
      return [f.a, f.b, f.c];
    });
    return new CANNON.ConvexPolyhedron({
      vertices: points,
      faces: cannonFaces
    });
  };

  function splitObject(userData, contact) {
    const contactId = userData.id;
    if (meshes[contactId]) {
      const poi = bodies[contactId].pointToLocalFrame(
        contact.bj.position.vadd(contact.rj)
      );
      const n = new THREE.Vector3(
        contact.ni.x,
        contact.ni.y,
        contact.ni.z
      ).negate();
      const shards = convexObjectBreaker.subdivideByImpact(
        meshes[contactId],
        new THREE.Vector3(poi.x, poi.y, poi.z),
        n,
        1,
        0
      );

      scene.remove(meshes[contactId]);
      delete meshes[contactId];
      world.removeBody(bodies[contactId]);
      delete bodies[contactId];

      shards.forEach((d) => {
        const nextId = meshId++;

        scene.add(d);
        meshes[nextId] = d;
        d.geometry.scale(0.99, 0.99, 0.99);
        const shape = gemoetryToShape(d.geometry);

        const body = new CANNON.Body({
          mass: 1
        });
        body.addShape(shape);
        body.userData = {
          splitCount: userData.splitCount + 1,
          id: nextId
        };
        body.position.x = d.position.x;
        body.position.y = d.position.y;
        body.position.z = d.position.z;
        body.quaternion.x = d.quaternion.x;
        body.quaternion.y = d.quaternion.y;
        body.quaternion.z = d.quaternion.z;
        body.quaternion.w = d.quaternion.w;
        world.addBody(body);
        bodies[nextId] = body;
      });
    }
  }

  function gemoetryToShape(geometry) {
    const position = geometry.attributes.position.array;
    const points = [];
    for (let i = 0; i < position.length; i += 3) {
      points.push(
        new THREE.Vector3(position[i], position[i + 1], position[i + 2])
      );
    }
    const convexHull = new ConvexGeometry(points);
    const shape = CreateConvexPolyhedron(convexHull);
    return shape;
  }

  window.addEventListener("resize", onWindowResize, false);

  function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
    render();
  }

  const stats = Stats();
  document.body.appendChild(stats.dom);

  const options = {
    side: {
      FrontSide: THREE.FrontSide,
      BackSide: THREE.BackSide,
      DoubleSide: THREE.DoubleSide
    }
  };

  const clock = new THREE.Clock();
  let delta;

  function animate() {
    requestAnimationFrame(animate);

    delta = clock.getDelta();
    if (delta > 0.1) delta = 0.1;
    world.step(delta);

    Object.keys(meshes).forEach((m) => {
      meshes[m].position.set(bodies[m].position.x, bodies[m].position.y, bodies[m].position.z)
      meshes[m].quaternion.set(
        bodies[m].quaternion.x,
        bodies[m].quaternion.y,
        bodies[m].quaternion.z,
        bodies[m].quaternion.w
      )
    });

    Object.keys(bullets).forEach((b) => {
      bullets[b].position.set(
        bulletBodies[b].position.x,
        bulletBodies[b].position.y,
        bulletBodies[b].position.z
      );
      bullets[b].quaternion.set(
        bulletBodies[b].quaternion.x,
        bulletBodies[b].quaternion.y,
        bulletBodies[b].quaternion.z,
        bulletBodies[b].quaternion.w
      );
    });

    render();

    stats.update();
  }

  function render() {
    renderer.render(scene, camera);
  }

  animate();

</script>