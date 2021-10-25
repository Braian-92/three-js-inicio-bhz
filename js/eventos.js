$(function() {

const escena = new THREE.Scene();
const grupo = new THREE.Group();
escena.add(grupo);

//# Cubo Rojo
const geometria = new THREE.BoxGeometry(1, 1, 1);
const material = new THREE.MeshBasicMaterial({color:0xff0000});
const material2 = new THREE.MeshBasicMaterial({color:0x00ff00});
const malla = new THREE.Mesh(geometria, material);
const malla2 = new THREE.Mesh(geometria, material2);
malla.position.set(1,1,0);
malla.scale.set(1,1,.5);
escena.add(malla);
// malla2.reOrder('YXZ');
malla2.rotation.y = Math.PI*0.25;
malla2.rotation.x = Math.PI*0.25;
malla2.rotation.z = .5;
grupo.add(malla2);

//! eje de ayuda 

const ejeAyuda = new THREE.AxesHelper();
escena.add(ejeAyuda);



//# dimenciones
const dimen = {
	ancho: 800,
	alto : 600
};

//# Camara
// const camara = new THREE.PerspectiveCamera(75, dimen.ancho, dimen.alto);
const camara = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 );

camara.position.z = 3;
// camara.position.x = 1;
// camara.position.y = 1;
// camara.lookAt(new THREE.Vector3(1,0,0));
camara.lookAt(malla.position);
escena.add(camara);

// //# Renderizar
const canvasWGL = document.querySelector('.contenidoWGL');
console.log(canvasWGL);
const renderizado = new THREE.WebGLRenderer({
	canvas:canvasWGL
});

renderizado.setSize(dimen.ancho, dimen.alto);


const animar = function () {
	requestAnimationFrame( animar );

	malla.rotation.x += 0.01;
	malla.rotation.y += 0.01;

	renderizado.render(escena, camara);
};

animar();

});