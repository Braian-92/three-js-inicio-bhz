<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" type="text/css" href="css/librerias/adminlte.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    .cursor-pointer{
      cursor: pointer;
    }

    .wrapper .content-wrapper {
      min-height: 100vh;
    }
  </style>
</head>

<body class="dark-mode text-sm">
  <div class="wrapper">
    <div class="content-wrapper kanban" style="margin-left: 0!important">
      <section class="content p-2">
        <div class="row h-100">
          <div class="col-12 h-100">
            <div class="card card-row card-default w-100 h-100">
              <div id="pantalla" class="card-body p-0 m-0 h-100 overflow-hidden d-none">
                  <canvas id="canvas" width="1400" height="700" style="border:1px solid #d3d3d3;">
              </div>
              <div id="pantalla" class="card-body p-0 m-0 h-100 overflow-hidden">
                  <canvas id="canvas2" width="1600" height="900" style="border:1px solid #d3d3d3;">
              </div>
            </div>            
          </div>
          
        </div>
      </section>
    </div>
  </div>
</body>
</html>
<script src="js/librerias/jquery-3.5.1.min.js"></script>
<script src="js/librerias/bootstrap.bundle.min.js"></script>
<script>
  let geoMardel = "-58.0469,-37.9581 -57.7373,-37.7026 -57.6702,-37.7508 -57.6794,-37.7603 -57.7007,-37.7641 -57.5213,-37.8959 -57.5218,-37.9049 -57.5268,-37.9263 -57.5307,-37.9332 -57.5332,-37.9482 -57.5418,-37.9696 -57.5421,-37.9804 -57.5438,-37.9824 -57.544,-37.9912 -57.5404,-37.9974 -57.5421,-38.0037 -57.5326,-38.0076 -57.531,-38.0149 -57.5238,-38.0151 -57.5232,-38.0165 -57.5271,-38.0207 -57.5268,-38.0243 -57.529,-38.0249 -57.5312,-38.0282 -57.5318,-38.0326 -57.534,-38.0329 -57.5349,-38.0349 -57.5346,-38.0363 -57.5318,-38.0363 -57.5321,-38.0374 -57.5363,-38.0385 -57.5387,-38.0429 -57.5382,-38.0449 -57.5343,-38.0457 -57.5354,-38.0476 -57.5346,-38.051 -57.5315,-38.0496 -57.5312,-38.0504 -57.5412,-38.0668 -57.5418,-38.0776 -57.5351,-38.0812 -57.5354,-38.0826 -57.5396,-38.084 -57.541,-38.0949 -57.5429,-38.0949 -57.5438,-38.0976 -57.5701,-38.1065 -57.591,-38.1174 -57.6049,-38.1307 -57.6071,-38.1346 -57.6068,-38.1385 -57.6113,-38.1468 -57.6343,-38.1674 -57.6351,-38.1721 -57.6393,-38.1729 -57.6504,-38.1804 -57.6521,-38.1835 -57.6551,-38.184 -57.6632,-38.1921 -57.6796,-38.2029 -57.6976,-38.2107 -57.7026,-38.2163 -57.726,-38.2265 -57.7287,-38.229 -57.7482,-38.2343 -57.7743,-38.2457 -57.7783,-38.246 -57.7842,-38.2315 -57.7838,-38.2093 -57.7926,-38.2024 -57.8109,-38.1492 -57.8094,-38.1444 -57.8209,-38.1287 -57.8195,-38.1152 -57.8146,-38.1097 -57.8132,-38.105 -57.8149,-38.0927 -57.8182,-38.0892 -57.8245,-38.0875 -57.8358,-38.0871 -57.8366,-38.0791 -57.8434,-38.0671 -57.8467,-38.0644 -57.8296,-38.0521 -57.8566,-38.0366 -57.8822,-38.0578 -57.9435,-38.0146 -57.9599,-38.0273 -58.0469,-37.9581";

  const cordenadasTot = geoMardel.split(" ");
  // console.log(cordenadasTot);
  let cordenadasToObj = [];
  let cordenadasToObjMin = [];
  let minX = 0;
  let maxX = 0;
  let minY = 0;
  let maxY = 0;

  $.each(cordenadasTot, function (indc, cordT) {
    const cordArr = cordT.split(",");


   


    // cordenadasToObj.push({
    //   lat:  (parseFloat(cordArr[1], 4) + 60) + 1.5 ,
    //   long: (parseFloat(cordArr[0], 4) + 60) + 1.5 ,
    // })

    cordenadasToObj.push(geoToPixel(cordArr[1], cordArr[0]));
  });

  $.each(cordenadasToObj, function (indc, cordT) {
    if(cordT.x < minX || minX == 0){
      minX = cordT.x;
    }
    if(cordT.x > maxX || maxX == 0){
      maxX = cordT.x;
    }

    if(cordT.y < minY || minY == 0){
      minY = cordT.y;
    }
    if(cordT.y > maxY || maxY == 0){
      maxY = cordT.y;
    }
  });
  let escala = 8;
  let desfaceX = 20;
  let desfaceY = 20;
  $.each(cordenadasToObj, function (indc, cordT) {
    cordenadasToObjMin.push({
      x: ((cordT.x - minX) * escala) + desfaceX,
      y: ((cordT.y - minY) * escala) + desfaceY,
    });
  });

  console.log('minX', minX);
  console.log('maxX', maxX);
  console.log('maxY', maxY);
  console.log('maxY', maxY);

  console.log('cordenadasToObj', cordenadasToObj);
  console.log('cordenadasToObjMin', cordenadasToObjMin);

  var c = document.getElementById("canvas2");
  var ctx = c.getContext("2d");
  ctx.beginPath();
  $.each(cordenadasToObjMin, function (indc, cordT) { //! no funciono reacer
    if(indc == 0){
      ctx.moveTo(cordT.x, cordT.y);
    }else{
      ctx.lineTo(cordT.x, cordT.y);
    }
  });
  ctx.stroke();


  // console.log(cordenadasToObj);


  // var c = document.getElementById("myCanvas");
  // var ctx = c.getContext("2d");
  // ctx.beginPath();
  // $.each(cordenadasToObj, function (indc, cordT) { //! no funciono reacer
  //   if(indc == 0){
  //     ctx.moveTo(cordT.lat, cordT.long);
  //   }else{
  //     ctx.lineTo(cordT.lat, cordT.long);
  //   }
  // });
  // ctx.stroke();
  

  /* 
    Pasos para dimensionar
    primero matar la negatividad de los ejes llevando al entero negativo a positivo empujando al grupo por el mínimo indistinto en ambos ejes
    -- sumar el mínimo del eje a todos (indistinto de long y lat)
    para cambiar la escala hay que tomar la resultante de ambos ejes y buscar la distancia entre un el punto mínimo y máximo y convertir la distancia más larga en el tamaño del canvas y con esa relación de aspecto, realizar 3 simples sobre todos los puntos y sobre los 2 ejes con el mismo valor de 1
    con este desplazamiento y escala podremos situar nuevamente puntos dentro del gráfico
  */

  function geoToPixel(lat, lon) {

    var imageNorthLat = 59.545457;  // Latitude of the image's northern edge
    var imageSouthLat = 49.431947;  // Latitude of the image's southern edge

    var imageWestLong = -11.140137; // Longitude of the image's western edge
    var imageEastLong = 2.757568;   // Longitude of the image's eastern edge

    var imageLongPixels = 1250;   // Width of the image in pixels
    var imageLatPixels = 1600;    // Height of the image in pixels

    var pixelsPerLat = imageLatPixels / (imageNorthLat - imageSouthLat);
    var pixelsPerLong = imageLongPixels / (imageEastLong - imageWestLong);

    var x = (lon-imageWestLong) * pixelsPerLong;
    var y = Math.abs(lat-imageNorthLat) * pixelsPerLat;

    return {
      'x' : x,
      'y' : y
    };

  };


  //! importador mapa

  const canvas = document.getElementById("canvas");
  // document.body.appendChild(canvas);
  // canvas.width = window.innerWidth;
  // canvas.height = window.innerHeight;
  setupCanvas();

  const img = document.createElement("img");
  img.onload = loaded;
  img.src = "./world.svg";
  img.style.display = 'none';
  // img.width =  window.innerWidth;
  // img.height = 'none';
  document.body.appendChild(img)

  function loaded() {
    const ctx = canvas.getContext("2d");
    ctx.fillStyle = "#1099bb";
    ctx.fillRect(0, 0, img.width, img.height);
    ctx.drawImage(img, 0, 0);
  }

  function setupCanvas() {
    // Get the device pixel ratio, falling back to 1.
    var dpr = window.devicePixelRatio || 1;
    // Get the size of the canvas in CSS pixels.
    var rect = canvas.getBoundingClientRect();
    // Give the canvas pixel dimensions of their CSS
    // size * the device pixel ratio.
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
   

      // ...then scale it back down with CSS
      canvas.style.width = rect.width + 'px';
      canvas.style.height = rect.height + 'px';
    
    var ctx = canvas.getContext("2d");
    // Scale all drawing operations by the dpr, so you
    // don't have to worry about the difference.
    ctx.scale(dpr, dpr);
    return ctx;
  }

</script>