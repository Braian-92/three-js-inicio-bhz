<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style>
    #canvas {
      border: 1px solid black;
    }
  </style>
</head>
<body>
  <canvas id="canvas" width="800" height="500"></canvas>
</body>
<script type="text/javascript">


  var canvas = document.getElementById('canvas');
  canvas.width = 300;
  canvas.height = 275;
  var context = canvas.getContext('2d');

  function poligonoHexa(){
    let arrHexa = [];
    var _side = 0,
    _size = 100,
    _x = 100,
    _y = 100;
    arrHexa.push(
      [
        _x + _size * Math.cos(0),
        _y + _size * Math.sin(0)
      ]
    );

    for (_side; _side < 7; _side++) {
      arrHexa.push(
        [
          _x + _size * Math.cos(_side * 2 * Math.PI / 6),
          _y + _size * Math.sin(_side * 2 * Math.PI / 6)
        ]
      );
    }
    return arrHexa;
  }

  function inside(point, vs) {
      // ray-casting algorithm based on
      // https://wrf.ecse.rpi.edu/Research/Short_Notes/pnpoly.html
      
      var x = point[0], y = point[1];
      
      var inside = false;
      for (var i = 0, j = vs.length - 1; i < vs.length; j = i++) {
          var xi = vs[i][0], yi = vs[i][1];
          var xj = vs[j][0], yj = vs[j][1];
          
          var intersect = ((yi > y) != (yj > y))
              && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
          if (intersect) inside = !inside;
      }
      
      return inside;
  };

  function dibujarPoligono(vetices) {
    context.beginPath();
    for (let i = 0; i < vetices.length; i++) {
      context.lineTo(vetices[i][0], vetices[i][1]);
    }
    context.closePath();
    context.stroke();
  }

  let exagono = poligonoHexa();
  dibujarPoligono(exagono);

  canvas.addEventListener("mousemove", function (evt) {
      var mousePos = getMousePos(canvas, evt);
      let mPoint = [mousePos.x, mousePos.y ];
      if (inside(mPoint, exagono)){
        document.body.style.background = 'red';
      }else{
        document.body.style.background = 'blue';
      }
  }, false);

  //Get Mouse Position
  function getMousePos(canvas, evt) {
      var rect = canvas.getBoundingClientRect();
      return {
          x: evt.clientX - rect.left,
          y: evt.clientY - rect.top
      };
  }

</script>
</html>