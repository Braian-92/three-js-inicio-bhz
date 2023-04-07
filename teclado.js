// Códigos de teclas - aqui vão todos os que forem necessários
var FLECHA_IZQUIERDA = 37;
var FLECHA_ARRIBA = 38;
var FLECHA_DERECHA = 39;
var FLECHA_ABAJO = 40;
var ESPACIO = 32;
var ENTER = 13;

function Teclado(elemento) {
   this.elemento = elemento;

   // Array de teclas presionadas
   this.presionadas = [];

   // Array de teclas disparadas
   this.disparadas = [];

   // Funções de disparo registradas
   this.funcoesDisparo = [];

   var teclado = this;

   elemento.addEventListener('keydown', function(evento) {
      var tecla = evento.keyCode;  // Tornando mais legível ;)
      console.log('tecla', tecla);
      teclado.presionadas[tecla] = true;

      // Disparar somente se for o primeiro keydown da tecla
      if (teclado.funcoesDisparo[tecla] && !teclado.disparadas[tecla]) {

          teclado.disparadas[tecla] = true;
          teclado.funcoesDisparo[tecla] () ;
      }
   });

   elemento.addEventListener('keyup', function(evento) {
      console.log('tecla', evento.keyCode);
      teclado.presionadas[evento.keyCode] = false;
      teclado.disparadas[evento.keyCode] = false;
   });
}
Teclado.prototype = {
   presionada: function(tecla) {
      // console.log('presionada');
      return this.presionadas[tecla];
   },
   disparar: function(tecla, callback) {
      // console.log('disparar');
      this.funcoesDisparo[tecla] = callback;
   }
}
