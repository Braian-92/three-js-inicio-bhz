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
  var ctx = canvas.getContext('2d');

  // the values as set out in the question image
  var r = 50; 
  var w = r * 2;
  var h = Math.sqrt(3) * r;
  // returns the hex grid x,y position in the object retPos.
  // retPos is created if not supplied;
  // argument x,y is pixel coordinate (for mouse or what ever you are looking to find)
  function getHex (x, y, retPos){
      if(retPos === undefined){
          retPos = {};
      }
      var xa, ya, xpos, xx, yy, r2, h2;
      r2 = r / 2;
      h2 = h / 2;
      xx = Math.floor(x / r2);
      yy = Math.floor(y / h2);
      xpos = Math.floor(xx / 3);
      xx %= 6;
      if (xx % 3 === 0) {      // column with diagonals
          xa = (x % r2) / r2;  // to find the diagonals
          ya = (y % h2) / h2;
          if (yy % 2===0) {
              ya = 1 - ya;
          }
          if (xx === 3) {
              xa = 1 - xa;
          }
          if (xa > ya) {
              retPos.x = xpos + (xx === 3 ? -1 : 0);
              retPos.y = Math.floor(yy / 2);
              return retPos;
          }
          retPos.x = xpos + (xx === 0 ? -1 : 0);
          retPos.y = Math.floor((yy + 1) / 2);
          return retPos;
      }
      if (xx < 3) {
          retPos.x = xpos + (xx === 3 ? -1 : 0);
          retPos.y = Math.floor(yy / 2);
          return retPos;
      }
      retPos.x = xpos + (xx === 0 ? -1 : 0);
      retPos.y = Math.floor((yy + 1) / 2);
      return retPos;
  }

  // Helper function draws a cell at hex coordinates cellx,celly
  // fStyle is fill style
  // sStyle is strock style;
  // fStyle and sStyle are optional. Fill or stroke will only be made if style given
  function drawCell1(cellPos, fStyle, sStyle){    
      var cell = [1,0, 3,0, 4,1, 3,2, 1,2, 0,1];
      var r2 = r / 2;
      var h2 = h / 2;
      function drawCell(x, y){
          var i = 0;
          ctx.beginPath();
          ctx.moveTo((x + cell[i++]) * r2, (y + cell[i++]) * h2)
          while (i < cell.length) {
              ctx.lineTo((x + cell[i++]) * r2, (y + cell[i++]) * h2)
          }
          ctx.closePath();
      }
      ctx.lineWidth = 2;
      var cx = Math.floor(cellPos.x * 3);
      var cy = Math.floor(cellPos.y * 2);
      if(cellPos.x  % 2 === 1){
          cy -= 1;
      }
      drawCell(cx, cy);
      if (fStyle !== undefined && fStyle !== null){  // fill hex is fStyle given
          ctx.fillStyle = fStyle
          ctx.fill();
      }
      if (sStyle !== undefined ){  // stroke hex is fStyle given
          ctx.strokeStyle = sStyle
          ctx.stroke();
      }
  }

  drawCell1(100, '#FF0000', '#FFFF00');
</script>
</html>