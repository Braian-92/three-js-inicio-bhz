<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <style type="text/css">
    body{
      margin: 0;
    }
      
    canvas{
      display: block;
    }
      
    button{
      position: absolute;
      top: 1em;
      right: 1em;
      background-color: rgba(0,0,0,.3);
      border: none;
      color: rgba(255,255,255,.5);
      padding: 0.5em 1em;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>

<body class="dark-mode text-sm">
  <button onclick="randomColors();">Generate new planet</button>
</body>
</html>
<script src="js/librerias/jquery-3.5.1.min.js"></script>
<script src="js/librerias/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/110/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/stats-js@1.0.1/build/stats.min.js"></script>
<script src="https://klevron.github.io/codepen/three.js/OrbitControls.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.1.1/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplex-noise/2.4.0/simplex-noise.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.5.4/randomColor.min.js"></script>

<script>

var detail = 10;
var shapeSize = 10;
var size = 3;
var height = 7;
var stars = [];
var meteorParticles = [];
var craterSize = 2;
// var starAmnt = 50;
var starAmnt = 0;
var vertexAmount = 10;

var colors = [
  new THREE.Color("#0000ff"),
  new THREE.Color("#5c5cff"),
  new THREE.Color("#00ff00"),
  new THREE.Color("#4c6c24"),
  new THREE.Color("#b5651d"),
  new THREE.Color("#ffffff")
];
var changedHeight = false;

var scene = new THREE.Scene();
var camera = new THREE.PerspectiveCamera(
  75,
  window.innerWidth / window.innerHeight,
  0.1,
  1000
);
camera.position.z = 20;

const scale = (num, in_min, in_max, out_min, out_max) => {
  return ((num - in_min) * (out_max - out_min)) / (in_max - in_min) + out_min;
};

const random = (mn, mx) => {
  return Math.random() * (mx - mn) + mn;
};

var stats = new Stats();
stats.showPanel( 0 );
document.body.appendChild( stats.dom );

var renderer = new THREE.WebGLRenderer({ antialias: true });
renderer.setClearColor("#000000");
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

window.addEventListener("resize", () => {
  renderer.setSize(window.innerWidth, window.innerHeight);
  camera.aspect = window.innerWidth / window.innerHeight;

  camera.updateProjectionMatrix();
});

scene.fog = new THREE.Fog("#2F3B45", 10, 50);

var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2(-10,0);

function onMouseMove(e) {
  mouse.x = ( e.clientX / window.innerWidth ) * 2 - 1;
  mouse.y = - ( e.clientY / window.innerHeight ) * 2 + 1;
}
window.addEventListener( 'mousemove', onMouseMove, false );

// var colorMeteorGeo = new THREE.IcosahedronGeometry(0.7,0);
// var colorMeteorMat = new THREE.MeshLambertMaterial({flatShading: true,color: colors[0],transparent: true});
// var colorMeteorMesh = new THREE.Mesh(colorMeteorGeo,colorMeteorMat);
// colorMeteorMesh.position.set(100,100,100);
// scene.add(colorMeteorMesh);

var geometry = new THREE.IcosahedronGeometry(shapeSize, size);

// const verticesOfCube = [
//     -1,-1,-1,    1,-1,-1,    1, 1,-1,    -1, 1,-1,
//     -1,-1, 1,    1,-1, 1,    1, 1, 1,    -1, 1, 1,
// ];

// const indicesOfFaces = [
//     2,1,0,    0,3,2,
//     0,4,7,    7,3,0,
//     0,1,5,    5,4,0,
//     1,2,6,    6,5,1,
//     2,3,7,    7,6,2,
//     4,5,6,    6,7,4
// ];

// const geometry = new THREE.PolyhedronGeometry( verticesOfCube, indicesOfFaces, 6, 2 );

// var geometry = new THREE.PolyhedronGeometry(shapeSize, size);
var material = new THREE.MeshLambertMaterial({
  flatShading: true,
  vertexColors: THREE.VertexColors,
  wireframe : true
});
var mesh = new THREE.Mesh(geometry, material);
scene.add(mesh);
console.log(mesh.geometry)
generateColors();
changedHeight = true;

// for (var i = 0; i < starAmnt; i++) {
//   var starGeo = new THREE.IcosahedronGeometry(0.2,0);
//   var cVal = random(0.5,1);
//   var starMat = new THREE.MeshLambertMaterial({flatShading: true,color: new THREE.Color(cVal,cVal,cVal),reflectivity:1});
//   var starMesh = new THREE.Mesh(starGeo,starMat);
//   var starAngle = scale(i,0,starAmnt-1,0,Math.PI*2);
//   var radius = random(12,16);
//   starMesh.position.set(Math.cos(starAngle) * radius,random(-1,1),Math.sin(starAngle) * radius);
//   starMesh.rotation.x = random(0,Math.PI*2);
//   starMesh.rotation.z = random(0,Math.PI*2);
//   scene.add(starMesh);
//   stars.push(starMesh);
//   starMesh.waveOff = Math.random()*1000;
//   starMesh.doWave = true;
// }


var light = new THREE.PointLight("#fefefe", 1, 1000);
light.position.set(0, -30, 0);
scene.add(light);

var light = new THREE.HemisphereLight('#bebebe', 0x080820, 1.5);
scene.add(light);

var controls = new THREE.OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.dampingFactor = 0.04;
controls.rotateSpeed = 0.06;

var frames = 0;
var render = function() {
  controls.update();
  
  stats.begin();

  mesh.geometry.colorsNeedUpdate = true;
  mesh.geometry.verticesNeedUpdate = true;
  
  
  // for (var i = 0; i < stars.length; i++) {
  //   stars[i].rotation.y += 0.005;
  //   stars[i].rotation.x += 0.008;
  // }
  
  // let x = Math.cos(frames/300) * 20;
  // let z = Math.sin(frames/300) * 20;
  // let y = scale(mouse.y,-1,1,-0.5,0.5);
  // camera.position.set(x,y+2,z);
  // camera.lookAt(0,0,0);
  
  // raycaster.setFromCamera( mouse, camera );
  // var intersects = raycaster.intersectObject(mesh);
  // if (intersects.length > 0) {
  //   for (var i = 0; i < intersects.length; i++) {
  //     if (!intersects[i].face.isAnimating) {
  //       intersects[i].face.isAnimating = true;
  //       let oldColor = intersects[i].face.color.clone();
  //       this.tl = new TimelineMax();
  //       this.tl.to(intersects[i].face.color,.25, {r: oldColor.r+0.3, g: oldColor.g+0.3, b: oldColor.b+0.3, ease: Expo.easeOut})
  //       this.tl.to(intersects[i].face.color,.25, {r: oldColor.r, g: oldColor.g, b: oldColor.b, ease: Expo.easeOut})
  //       let index = i;
  //       setTimeout(() => {
  //         intersects[index].face.isAnimating = false;
  //       },500)
  //     }
  //   }
  // }

  // for (var i = 0; i < stars.length; i++) {
  //   if (stars[i].doWave) {
  //     let newY = Math.sin(stars[i].waveOff);
  //     stars[i].position.y = newY;
  //     stars[i].waveOff += 0.01;
  //   }
  // }
  
  frames ++;
  renderer.render(scene, camera);
  
  stats.end();
  
  requestAnimationFrame(render);
};
render();

function generateColors() {
  var flatGeometry = new THREE.IcosahedronGeometry(shapeSize, size);
  for (var i = 0; i < geometry.faces.length; i++) {
    geometry.faces[i].color.set(mesh.geometry.faces[i].color.clone());
  }
  var simplex = new SimplexNoise();
  let chosenIndex = Math.floor(random(0, mesh.geometry.faces.length - 1));
  var vertices = flatGeometry.vertices;
  var actualVertices = mesh.geometry.vertices;
  var v1 = vertices[mesh.geometry.faces[chosenIndex].a];
  var v2 = vertices[mesh.geometry.faces[chosenIndex].b];
  var v3 = vertices[mesh.geometry.faces[chosenIndex].c];
  let chosenPosition = new THREE.Vector3(
    (v1.x + v2.x + v3.x) / 3,
    (v1.y + v2.y + v3.y) / 3,
    (v1.z + v2.z + v3.z) / 3
  );
  
  // colorMeteorMesh.material.color.set(colors[0]);
  // this.tl = new TimelineMax();
  // colorMeteorMesh.position.set(chosenPosition.x*10,chosenPosition.y*10,chosenPosition.z*10);
  // this.tl.to(colorMeteorMesh.position,2,{x: chosenPosition.x*0.8, y: chosenPosition.y*0.8, z: chosenPosition.z*0.8, ease: Expo.easeOut});
  // this.tl.to(colorMeteorMesh.material.color,.5,{r: 0.2, g: 0.2, b: 0.2});
  
  // var amnt = 100;
  // var amnt = 0;
  // for (var i = 0; i < amnt; i++) {
  //   var particleGeo = new THREE.IcosahedronGeometry(0.1,0);
  //   var particleMat = new THREE.MeshBasicMaterial({
  //     color: getColor(Math.random()), 
  //     transparent: true,
  //     opacity: 0
  //   });
  //   var particle = new THREE.Mesh(particleGeo,particleMat);
  //   meteorParticles.push(particle);
  //   var dist = scale(i,0,amnt-1,5,1);
  //   particle.position.set(
  //     chosenPosition.x*dist+random(-0.1,0.1),
  //     chosenPosition.y*dist+random(-0.1,0.1),
  //     chosenPosition.z*dist+random(-0.1,0.1)
  //   );
  //   scene.add(particle);
  //   this.tl = new TimelineMax({delay: scale(i,0,amnt-1,0,1)});
  //   this.tl.to(particle.material,.1,{opacity: 1});
  //   this.tl.to(particle.material,.3,{opacity: 0})
  // }
  // setTimeout(() => {
  //   for (var i = meteorParticles.length-1; i >= 0; i--) {
  //     scene.remove(meteorParticles[i]);
  //     meteorParticles.splice(i,1);
  //   }
  // },1500)

  for (var i = 0; i < mesh.geometry.faces.length; i++) {
    var v1 = vertices[mesh.geometry.faces[i].a];
    var v2 = vertices[mesh.geometry.faces[i].b];
    var v3 = vertices[mesh.geometry.faces[i].c];
    var vs = [v1, v2, v3];
    
    var av1 = actualVertices[mesh.geometry.faces[i].a];
    var av2 = actualVertices[mesh.geometry.faces[i].b];
    var av3 = actualVertices[mesh.geometry.faces[i].c];
    var avs = [av1, av2, av3];

    var x = (v1.x + v2.x + v3.x) / 3;
    var y = (v1.y + v2.y + v3.y) / 3;
    var z = (v1.z + v2.z + v3.z) / 3;

    var material = new THREE.MeshBasicMaterial({
      color: 0x00ff00,
      wireframe: true,
    });
    let objeto = new THREE.Mesh(
      // new THREE.ConeGeometry(.2, .5, 4),
      new THREE.BoxGeometry(.2,.2,1,1,1,1),
      material
    );
    objeto.position.x = x;
    objeto.position.y = y;
    objeto.position.z = z;
    objeto.lookAt(0,0,0);
    const posGeo = circuloToLatLong(shapeSize, x, y, z);
    console.log('posGeo', posGeo);

    scene.add(objeto);

    var val = scale(simplex.noise3D(x / detail, y / detail, z / detail),-1,1,0,1);
    let index = i;
    let v = val;
    let distance = new THREE.Vector3(x,y,z).distanceTo(chosenPosition);
    let delay = scale(distance,0,shapeSize,0,500) + 950;
    
    var clr = getColor(v);
    // var remove = scale(distance,0,craterSize,0.75,0.25);
    // if (distance < craterSize*1.5) { clr.r -= remove; clr.g -= remove; clr.b -= remove; }
    // if (false) { clr.r -= remove; clr.g -= remove; clr.b -= remove; }
    // this.tl = new TimelineMax({delay: delay/1000});
    // this.tl.to(mesh.geometry.faces[index].color,.2,{r: clr.r, g: clr.g, b: clr.b, ease: Expo.easeOut});
    // this.tl.to(mesh.geometry.faces[index].color,.2,{r: clr.r, g: clr.g, b: clr.b, ease: Expo.easeOut});
    // mesh.geometry.faces[index].color = 0xffffff;
    mesh.geometry.faces[index].color.r = clr.r;
    mesh.geometry.faces[index].color.g = clr.g;
    mesh.geometry.faces[index].color.b = clr.b;
    for (var j = 0; j < 3; j++) {
      let currentV = avs[j];
      let flatV = vs[j];
      var val = scale(simplex.noise3D(x / detail, y / detail, z / detail),-1,1,0,1);
      var mult = 0; 
      mult = -0.05; 
      // mult = -0.05 * (i / 10); 
      // mult = random(-0.05, -0.2); 
      // if (val > 0.6) mult = 0.05; if (val < 0.4) mult = -0.05; if (val < 0.2) mult = -0.07; if (distance < craterSize) mult += scale(distance,0,craterSize,0.3,0.1);
      let outwardsVector = new THREE.Vector3(0, 0, 0).sub(flatV).normalize().multiply(new THREE.Vector3(mult*height, mult*height, mult*height));
      
      // this.tl = new TimelineMax({delay: delay/1000});
      // this.tl.to(avs[j],.1,{x: vs[j].x, y: vs[j].y, z: vs[j].z});
      // avs[j].x = vs[j].x;
      // avs[j].y = vs[j].y;
      // avs[j].z = vs[j].z;
      // this.tl.to(avs[j],.1,{x: (vs[j].x+outwardsVector.x), y: (vs[j].y+outwardsVector.y), z: (vs[j].z+outwardsVector.z)});

      avs[j].x = vs[j].x + outwardsVector.x;
      avs[j].y = vs[j].y + outwardsVector.y;
      avs[j].z = vs[j].z + outwardsVector.z;
    }
  }
  
  // var arrange = false; if (Math.random() <= .4) arrange = true; 
  // var waveAmount = 2*Math.floor(random(1,10));
  // for (var i = 0; i < stars.length; i++) {
  //   var clr = getColor(Math.random());
  //   if (arrange) clr = colors[Math.floor(scale(i,0,stars.length,0,colors.length-1))]
  //   this.tl = new TimelineMax({delay: 1.5});
  //   this.tl.to(stars[i].material.color,.5,{r: clr.r, g: clr.g, b: clr.b});
  //   if (arrange) {stars[i].doWave = false}
  //   else this.tl.to(stars[i],.1,{doWave: true});
  //   var a = scale(i,0,stars.length,0,Math.PI*2);
  //   var r = random(12,16); if (arrange) r = 14;
  //   let waveY = Math.sin(stars[i].waveOff);
  //   var chosenY = waveY; if (arrange) chosenY = scale(Math.sin(scale(i,0,stars.length,0,Math.PI*waveAmount)),-1,1,-1,1);
  //   this.tl.to(stars[i].position,.5,{x: Math.cos(a) * r, y: chosenY, z: Math.sin(a) * r},'-=.5')
  // }
}

function getColor(v) {
  var noiseVal = scale(v, 0, 1, 1, 0);
  if (noiseVal < 0.3) {
    return colors[0]
      .clone()
      .lerp(colors[1].clone(), scale(noiseVal, 0.3, 0, 0, 1));
  } else if (noiseVal < 0.31) {
    return colors[1]
      .clone()
      .lerp(colors[2].clone(), scale(noiseVal, 0.3, 0.31, 0, 1));
  } else if (noiseVal < 0.6) {
    return colors[2]
      .clone()
      .lerp(colors[3].clone(), scale(noiseVal, 0.31, 0.6, 0, 1));
  } else if (noiseVal < 0.61) {
    return colors[3]
      .clone()
      .lerp(colors[4].clone(), scale(noiseVal, 0.6, 0.61, 0, 1));
  } else {
    return colors[4]
      .clone()
      .lerp(colors[5].clone(), scale(noiseVal, 0.61, 1, 0, 1));
  }
}

function randomColors() {
  let choices = ['dark','light','bright'];
  let chosenIndex = Math.floor(random(0,3));
  let chosenHue = 'random';
  if (Math.random() <= 0.2) chosenHue = 'monochrome';
  let chosenColors = randomColor({
    luminosity: choices[chosenIndex],
    hue: chosenHue,
    count: colors.length
  })
  for (var i = 0; i < colors.length; i++) {
    colors[i] = new THREE.Color(chosenColors[i]);
  }
  generateColors();
  mesh.geometry.colorsNeedUpdate = true;
  mesh.geometry.verticesNeedUpdate = true;
}

function circuloToLatLong(_RADIUS_SPHERE, _x, _y, _z){
  return {
    lat : 90 - (Math.acos(_y / _RADIUS_SPHERE)) * 180 / Math.PI,
    lon : ((270 + (Math.atan2(_x , _z)) * 180 / Math.PI) % 360) -180,
  }
}
  
</script>