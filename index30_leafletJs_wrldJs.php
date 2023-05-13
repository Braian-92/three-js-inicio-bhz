<!DOCTYPE HTML>
<html>
  <head>
    <script src="https://unpkg.com/wrld.js@1.x.x"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.0.1/leaflet.css" rel="stylesheet" />
  </head>
  
  <body>
  <div style="position: relative">
    <div id="map" style="height: 400px"></div>
    <script>
      var map = Wrld.map("map", "your_api_key_here", {
        center: [37.7900, -122.401],
        zoom: 15
      });

      var polygonPoints = [
        [37.786617, -122.404654],
        [37.797843, -122.407057],
        [37.798962, -122.398260],
        [37.794299, -122.395234]];
      var poly = Wrld.polygon(polygonPoints).addTo(map);
    </script>
  </div>
  </body>
</html>