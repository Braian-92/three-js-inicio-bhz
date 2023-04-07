function Animacion(escenaT) {
   this.escena = escenaT;
   this.sprites = [];
   this.sprite;
   this.conectado = false;
   this.procesando = [];
   this.spritesExcluir = [];
   this.procesandoExcluir = [];
   this.ultimoCiclo = 0;
   this.transcurrido = 0;
}
Animacion.prototype = {
   nuevoElemento: function(sprite) {
      sprite.escena.add(sprite.nave);
      this.sprite = sprite;
      sprite.animacion = this;
   },
   conectar: function() {
      this.ultimoCiclo = 0;
      this.conectado = true;
      this.proximoFrame();
   },
   desconectar: function() {
      this.conectado = false;
   },
   proximoFrame: function() {
      // Posso continuar?
      // console.log('proximoFrame');
      // console.log(this);
      if ( ! this.conectado ) return;
      
      var agora = new Date().getTime();
      if (this.ultimoCiclo == 0) this.ultimoCiclo = agora;
      this.transcurrido = agora - this.ultimoCiclo;

      this.sprite.actualizar();
      // Atualizamos o estado dos sprites
      for (var i in this.sprites)
         this.sprites[i].atualizar();

      // Desenhamos os sprites
      for (var i in this.sprites)
         this.sprites[i].desenhar();
         
      // Processamentos gerais
      for (var i in this.procesando)
         this.procesando[i].processar();
         
      // Processamento de exclusões
      this.procesoExclusiones();
      
      // Atualizar o instante do último ciclo
      this.ultimoCiclo = agora;

      // Chamamos o próximo ciclo
      var animacion = this;
      requestAnimationFrame(function() {
         animacion.proximoFrame();
      });
   },
   nuevoProcesamiento: function(processamento) {
      this.procesando.push(processamento);
      processamento.animacion = this;
   },
   excluirSprite: function(sprite) {
      this.spritesExcluir.push(sprite);
   },
   excluirProcessamento: function(processamento) {
      this.procesandoExcluir.push(processamento);
   },
   procesoExclusiones: function() {
      // Criar novos arrays
      var novoSprites = [];
      var novoProcessamentos = [];
      
      // Adicionar somente se não constar no array de excluídos
      for (var i in this.sprites) {
         if (this.spritesExcluir.indexOf(this.sprites[i]) == -1)
            novoSprites.push(this.sprites[i]);
      }
      
      for (var i in this.procesando) {
         if (this.procesandoExcluir.indexOf(this.procesando[i])
             == -1)
            novoProcessamentos.push(this.procesando[i]);
      }
      
      // Limpar os arrays de exclusões
      this.spritesExcluir = [];
      this.procesandoExcluir = [];
      
      // Substituir os arrays velhos pelos novos
      this.sprites = novoSprites;
      this.procesando = novoProcessamentos;
   }
}
