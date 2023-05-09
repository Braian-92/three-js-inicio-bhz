<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
</head>
<body>
  <canvas id="canvas" width="800" height="500"></canvas>
</body>
<script type="text/javascript">
  class Point {
    //int x, y;
    constructor(x,y)
    {
      this.x=x;
      this.y=y;
    }
  }

  class line {
    //Point p1, p2;
    constructor(p1,p2)
    {
      this.p1=p1;
      this.p2=p2;
    }

  };

  function onLine(l1, p)
  {
    // Check whether p is on the line or not
    if (p.x <= Math.max(l1.p1.x, l1.p2.x)
      && p.x <= Math.min(l1.p1.x, l1.p2.x)
      && (p.y <= Math.max(l1.p1.y, l1.p2.y)
        && p.y <= Math.min(l1.p1.y, l1.p2.y)))
      return true;

    return false;
  }

  function direction(a, b, c)
  {
    console.log(a);
    console.log(b);
    console.log(c);
    let val = (b.y - a.y) * (c.x - b.x)
        - (b.x - a.x) * (c.y - b.y);

    if (val == 0)

      // Collinear
      return 0;

    else if (val < 0)

      // Anti-clockwise direction
      return 2;

    // Clockwise direction
    return 1;
  }

  function isIntersect(l1, l2)
  {
    // Four direction for two lines and points of other line
    let dir1 = direction(l1.p1, l1.p2, l2.p1);
    let dir2 = direction(l1.p1, l1.p2, l2.p2);
    let dir3 = direction(l2.p1, l2.p2, l1.p1);
    let dir4 = direction(l2.p1, l2.p2, l1.p2);

    // When intersecting
    if (dir1 != dir2 && dir3 != dir4)
      return true;

    // When p2 of line2 are on the line1
    if (dir1 == 0 && onLine(l1, l2.p1))
      return true;

    // When p1 of line2 are on the line1
    if (dir2 == 0 && onLine(l1, l2.p2))
      return true;

    // When p2 of line1 are on the line2
    if (dir3 == 0 && onLine(l2, l1.p1))
      return true;

    // When p1 of line1 are on the line2
    if (dir4 == 0 && onLine(l2, l1.p2))
      return true;

    return false;
  }

  function checkInside(poly, n, p)
  {

    // When polygon has less than 3 edge, it is not polygon
    if (n < 3)
      return false;

    // Create a point at infinity, y is same as point p
    let tmp=new Point(9999, p.y);
    let exline = new line( p, tmp );
    let count = 0;
    let i = 0;
    do {

      // Forming a line from two consecutive points of
      // poly
      let side = new line( poly[i], poly[(i + 1) % n] );
      if (isIntersect(side, exline)) {

        // If side is intersects exline
        if (direction(side.p1, p, side.p2) == 0)
          return onLine(side, p);
        count++;
      }
      i = (i + 1) % n;
    } while (i != 0);

    // When count is odd
    return count & 1;
  }

  // Driver code
    let polygon= [ new Point(0, 0 ), new Point(200, 0 ), new Point(200, 200 ), new Point(0, 200) ];
    polygon= poligonoHexa();
    let p = new Point( 5, 3 );
    let n = 4;


    // Function call
    if (checkInside(polygon, n, p))
      console.log("Point is inside.");
    else
      console.log("Point is outside.");

    var can = document.getElementById("canvas");
    var ctx = can.getContext("2d");
    dibujarPoligono(polygon);

    function dibujarPoligono(vetices) {
      ctx.beginPath();
      for (let i = 0; i < vetices.length; i++) {
        console.log(vetices[i]);  
        ctx.lineTo(vetices[i].x, vetices[i].y);
      }
      ctx.closePath();
      ctx.stroke();
    }


    //report the mouse position on click
    can.addEventListener("mousemove", function (evt) {
        var mousePos = getMousePos(can, evt);
        // alert(mousePos.x + ',' + mousePos.y);
        let mPoint = new Point(mousePos.x, mousePos.y );
        // console.log('mPoint', mPoint);
        if (checkInside(
            polygon,
            n,
            mPoint
            )){
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

    function poligonoHexa(){
      let arrHexa = [];
      var _side = 0,
      _size = 100,
      _x = 100,
      _y = 100;
      arrHexa.push(
        new Point(
          _x + _size * Math.cos(0),
          _y + _size * Math.sin(0)
        )
      );

      for (_side; _side < 7; _side++) {
        arrHexa.push(
          new Point(
            _x + _size * Math.cos(_side * 2 * Math.PI / 6),
            _y + _size * Math.sin(_side * 2 * Math.PI / 6)
          )
        );
      }
      return arrHexa;
    }
</script>
</html>