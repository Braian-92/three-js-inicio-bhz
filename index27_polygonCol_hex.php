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
  // setup canvas for demo
  var canvas = document.getElementById('canvas');
  canvas.width = 300;
  canvas.height = 275;
  var context = canvas.getContext('2d');
  var hexPath;
  var hex = {
    x: 50,
    y: 50,
    R: 100
  }

  // Place holders for mouse x,y position
  var mouseX = 0;
  var mouseY = 0;

  // Test for collision between an object and a point
  function pointInHexagon(target, pointX, pointY) {
    var side = Math.sqrt(target.R*target.R*3/4);
    
    var startX = target.x
    var baseX = startX + target.R / 2;
    var endX = target.x + 2 * target.R;
    var startY = target.y;
    var baseY = startY + side; 
    var endY = startY + 2 * side;
    var square = {
      x: startX,
      y: startY,
      side: 2*side
    }

    hexPath = new Path2D();
    hexPath.lineTo(baseX, startY);
    hexPath.lineTo(baseX + target.R, startY);
    hexPath.lineTo(endX, baseY);
    hexPath.lineTo(baseX + target.R, endY);
    hexPath.lineTo(baseX, endY);
    hexPath.lineTo(startX, baseY);

    if (pointX >= square.x && pointX <= (square.x + square.side) && pointY >= square.y && pointY <= (square.y + square.side)) {
      var auxX = (pointX < target.R / 2) ? pointX : (pointX > target.R * 3 / 2) ? pointX - target.R * 3 / 2 : target.R / 2;
      var auxY = (pointY <= square.side / 2) ? pointY : pointY - square.side / 2;
      var dPointX = auxX * auxX;
      var dPointY = auxY * auxY;
      var hypo = Math.sqrt(dPointX + dPointY);
      var cos = pointX / hypo;

      if (pointX < (target.x + target.R / 2)) {
        if (pointY <= (target.y + square.side / 2)) {
          if (pointX < (target.x + (target.R / 2 * cos))) return false;
        }
        if (pointY > (target.y + square.side / 2)) {
          if (pointX < (target.x + (target.R / 2 * cos))) return false;
        }
      }

      if (pointX > (target.x + target.R * 3 / 2)) {
        if (pointY <= (target.y + square.side / 2)) {
          if (pointX < (target.x + square.side - (target.R / 2 * cos))) return false;
        }
        if (pointY > (target.y + square.side / 2)) {
          if (pointX < (target.x + square.side - (target.R / 2 * cos))) return false;
        }
      }
      return true;
    }
    return false;
  }

  // Loop
  setInterval(onTimerTick, 33);

  // Render Loop
  function onTimerTick() {
    // Clear the canvas
    canvas.width = canvas.width;

    // see if a collision happened
    var collision = pointInHexagon(hex, mouseX, mouseY);

    // render out text
    context.fillStyle = "Blue";
    context.font = "18px sans-serif";
    context.fillText("Collision: " + collision + " | Mouse (" + mouseX + ", " + mouseY + ")", 10, 20);

    // render out square    
    context.fillStyle = collision ? "red" : "green";
    context.fill(hexPath);
  }

  // Update mouse position
  canvas.onmousemove = function(e) {
    mouseX = e.offsetX;
    mouseY = e.offsetY;
  }
</script>
</html>