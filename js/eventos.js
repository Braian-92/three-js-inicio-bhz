$(function() {

const escena = new THREE.Scene();

//# Cubo Rojo
const geometria = new THREE.BoxGeometry(1, 1, 1);
const material = new THREE.MeshBasicMaterial({color:0xff0000});
const malla = new THREE.Mesh(geometria, material);
escena.add(malla);

//# dimenciones
const dimen = {
	ancho: 800,
	alto : 600
};

//# Camara
// const camara = new THREE.PerspectiveCamera(75, dimen.ancho, dimen.alto);
const camara = new THREE.PerspectiveCamera( 75, window.innerWidth / window.innerHeight, 0.1, 1000 );

camara.position.z = 2;
// camara.position.x = 3;
// camara.position.y = 3;
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