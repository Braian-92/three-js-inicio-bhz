<!DOCTYPE html>
<html lang="en">
  <head>
    <title>three.js - misc - octree collisions</title>
    <meta charset=utf-8 />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <!-- three-js-inicio-bhz/threeJsMaster/examples/games_fps.html -->
    <link type="text/css" rel="stylesheet" href="./threeJsMaster/examples/main.css">
  </head>
  <body>
    <!-- <div id="info">
      Octree threejs demo - basic collisions with static triangle mesh<br />
      MOUSE to look around and to throw balls<br/>
      WASD to move and SPACE to jump
    </div> -->
    <div id="container"></div>

    <!-- Import maps polyfill -->
    <!-- Remove this when import maps will be widely supported -->
    <script async src="https://unpkg.com/es-module-shims@1.6.3/dist/es-module-shims.js"></script>
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
      import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';

      const monkeyUrl = new URL('doggo2.glb', import.meta.url);

      const renderer = new THREE.WebGLRenderer();

      renderer.setSize(window.innerWidth, window.innerHeight);

      document.body.appendChild(renderer.domElement);

      const scene = new THREE.Scene();

      const camera = new THREE.PerspectiveCamera(
          45,
          window.innerWidth / window.innerHeight,
          0.1,
          1000
      );

      renderer.setClearColor(0xA3A3A3);

      const orbit = new OrbitControls(camera, renderer.domElement);

      camera.position.set(10, 10, 10);
      orbit.update();

      const grid = new THREE.GridHelper(30, 30);
      scene.add(grid);

      const assetLoader = new GLTFLoader();

      let mixer;
      assetLoader.load(monkeyUrl.href, function(gltf) {
          const model = gltf.scene;
          scene.add(model);
          mixer = new THREE.AnimationMixer(model);
          const clips = gltf.animations;

          // Play a certain animation
          // const clip = THREE.AnimationClip.findByName(clips, 'HeadAction');
          // const action = mixer.clipAction(clip);
          // action.play();

          // Play all animations at the same time
          clips.forEach(function(clip) {
              const action = mixer.clipAction(clip);
              action.play();
          });

      }, undefined, function(error) {
          console.error(error);
      });

      const clock = new THREE.Clock();
      function animate() {
          if(mixer)
              mixer.update(clock.getDelta());
          renderer.render(scene, camera);
      }

      renderer.setAnimationLoop(animate);

      window.addEventListener('resize', function() {
          camera.aspect = window.innerWidth / window.innerHeight;
          camera.updateProjectionMatrix();
          renderer.setSize(window.innerWidth, window.innerHeight);
      });

      

    </script>
  </body>
</html>
