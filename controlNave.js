function Nave(pantallaT, escenaT, tecladoT, naveT, explosionT, disparoT) {
// function Nave(context, teclado, imagem, imgExplosao, imgTiro) {
   this.pantalla = pantallaT;
   this.escena = escenaT;
   this.teclado = tecladoT;
   this.nave = naveT;
   this.elemento;
   this.xSize = 1;
   this.ySize = 1;
   this.zSize = 2;
   this.x = 0;
   this.y = 0;
   this.z = 0;
   this.velocidad = 0;
   this.explosion = explosionT;
   this.acabaramVidas = null;
   this.vidasExtras = 5;
   this.disparo = disparoT;
}
Nave.prototype = {
   actualizar: function() {
      console.log('actualizar');
      var incremento = this.velocidad * this.animacion.transcurrido / 1000;
      
      if (this.teclado.presionada(FLECHA_IZQUIERDA) && this.x > 0){
         this.x -= incremento;
      }
         
      if (this.teclado.presionada(FLECHA_DERECHA) && this.x < this.pantalla.width){
         this.x += incremento;
      }
         
      if (this.teclado.presionada(FLECHA_ABAJO) && this.y > 0){
         this.y -= incremento;
      }
         
      if (this.teclado.presionada(FLECHA_ARRIBA) && this.y < this.pantalla.height){
         this.y += incremento;
      }

      this.nave.position.x = this.x;
      this.nave.position.y = this.y;
      this.nave.position.z = this.z;
   },
   desenhar: function() {
      if (this.teclado.presionada(FLECHA_IZQUIERDA)){

         this.spritesheet.linha = 1;
      }else if (this.teclado.presionada(FLECHA_DERECHA)){
         this.spritesheet.linha = 2;
      }else{
         this.spritesheet.linha = 0;      
      }
      
      this.spritesheet.desenhar(this.x, this.y);
      this.spritesheet.proximoQuadro();
   },
   disparar: function() {
      var t = new Disparo(this.escena, this, this.disparo);
      this.animacion.novoSprite(t);
      this.colisor.novoSprite(t);
   },
   retangulosColisao: function() {
      // Estes valores vão sendo ajustados aos poucos

      var rets = 
      [ 
         {x: this.x-2, y: this.y+29, largura: 18, altura: 34},
         {x: this.x+17, y: this.y+3, largura: 18, altura: 60},
         {x: this.x+35, y: this.y+29, largura: 18, altura: 34}
      ];    
      
      return rets;
   },
   colidiuCom: function(outro) {
      // Se colidiu com um Ovni...
      if (outro instanceof Ovni || outro instanceof Deoxys) {
         this.animacion.excluirSprite(this);
         this.animacion.excluirSprite(outro);
         this.colisor.excluirSprite(this);
         this.colisor.excluirSprite(outro);
         
         var exp1 = new Explosao(this.context, this.imgExplosao,
                                 this.x, this.y, 'snd/explosao.mp3');
         var exp2 = new Explosao(this.context, this.imgExplosao,
                                 outro.x, outro.y, 'snd/explosao.mp3');
         
         this.animacion.novoSprite(exp1);
         this.animacion.novoSprite(exp2);
         
         var nave = this;
         exp1.fimDaExplosao = function() {
            nave.vidasExtras--;
            
            if (nave.vidasExtras < 0) {
               if (nave.acabaramVidas){
                  nave.acabaramVidas();
               }
            }else{
               // Recolocar a nave no engine
               nave.colisor.novoSprite(nave);
               nave.animacion.novoSprite(nave);
               
               nave.posicionar();
            }
         }
      }
   },
   posicionar: function() {
      console.log('posicionar');
      // this.x = pantalla.width / 2 - 18;  // 36 / 2
      // this.y = pantalla.height - 68;
      this.x = this.pantalla.width / 2;  // 36 / 2
      this.y = this.pantalla.height / 2;

      // console.log('this', this);

      this.nave.position.set(this.x, this.y, this.z);
      
      // 
   }
}
