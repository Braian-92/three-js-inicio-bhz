
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>World Population Density: 2010</title>
    <script type="text/javascript" src="js/three.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <link type="text/css" href="css/style.css" rel="stylesheet"/>
    <script type="text/javascript">

        

    </script>
</head>
<body>
<!-- <h1 class="page-header">
    World Population Density: 2010
</h1> -->
<!-- <div class="explanation">
   This page shows the population density throughout the world in 2010. The height of the column represents 
    the density in that specific area. For more information on how this was created look
    on <a href="http://www.smartjava.org.">www.smartjava.org</a>. Please be patient
    while loading and processing the density data (18.000 points). To view this page you need to have a browser that
    support WebGL (like newer versions of Firefox, Chrome or Opera).
</div> -->
<div id="globe">
</div>
<!-- <div class="explanation">
    This map is based on open data. The earth map is based on a download from
     <a href="http://earthobservatory.nasa.gov/.">the Nasa Earth observatory</a>. The density information
    was downloaded from <a href="http://sedac.ciesin.columbia.edu/"> the SocioEconomic Data and Application Center</a>.
</div> -->
<!-- https://es.wikipedia.org/wiki/Coordenadas_geogr%C3%A1ficas -->
<script type="text/javascript">

    // couple of constants
    var POS_X = 1800;
    var POS_Y = 500;
    var POS_Z = 1800;
    var WIDTH = 1000;
    var HEIGHT = 600;

    var FOV = 45;
    var NEAR = 1;
    var FAR = 4000;

    // some global variables and initialization code
    // simple basic renderer
    var renderer = new THREE.WebGLRenderer();
    renderer.setSize(WIDTH,HEIGHT);
    renderer.setClearColorHex(0x111111);

    // add it to the target element
    var mapDiv = document.getElementById("globe");
    mapDiv.appendChild(renderer.domElement);

    // setup a camera that points to the center
    var camera = new THREE.PerspectiveCamera(FOV,WIDTH/HEIGHT,NEAR,FAR);
    camera.position.set(POS_X,POS_Y, POS_Z);
    camera.lookAt(new THREE.Vector3(0,0,0));

    // create a basic scene and add the camera
    var scene = new THREE.Scene();
    scene.add(camera);

    // we wait until the document is loaded before loading the
    // density data.
    $(document).ready(function()  {
        jQuery.get('data/density.csv', function(data) {
            addDensity(CSVToArray(data));
            addLights();
            addEarth();
            addClouds();
            render();
        });
    });

    // simple function that converts the density data to the markers on screen
    // the height of each marker is relative to the density.
    function addDensity(data) {

        // the geometry that will contain all our cubes
        var geom = new THREE.Geometry();
        // material to use for each of our elements. Could use a set of materials to
        // add colors relative to the density. Not done here.
        var cubeMat = new THREE.MeshLambertMaterial({color: 0x000000,opacity:0.6, emissive:0xffffff});
        for (var i = 0 ; i < data.length-1 ; i++) {

            //get the data, and set the offset, we need to do this since the x,y coordinates
            //from the data aren't in the correct format
            var x = parseInt(data[i][0])+180;
            var y = parseInt((data[i][1])-84)*-1;
            var value = parseFloat(data[i][2]);

            // calculate the position where we need to start the cube
            var position = latLongToVector3(y, x, 600, 2);

            // create the cube
            var cube = new THREE.Mesh(new THREE.CubeGeometry(5,5,1+value/8,1,1,1,cubeMat));

            // position the cube correctly
            cube.position = position;
            cube.lookAt( new THREE.Vector3(0,0,0) );

            // merge with main model
            THREE.GeometryUtils.merge(geom,cube);
           // scene.add(cube);
        }

        // create a new mesh, containing all the other meshes.
        var total = new THREE.Mesh(geom,new THREE.MeshFaceMaterial());

        // and add the total mesh to the scene
        scene.add(total);
    }

    // add a simple light
    function addLights() {
        light = new THREE.DirectionalLight(0x3333ee, 3.5, 500 );
        scene.add( light );
        light.position.set(POS_X,POS_Y,POS_Z);
    }

    // add the earth
    function addEarth() {
        var spGeo = new THREE.SphereGeometry(600,50,50);
        var planetTexture = THREE.ImageUtils.loadTexture( "assets/world-big-2-grey.jpg" );
        var mat2 =  new THREE.MeshPhongMaterial( {
            map: planetTexture,
            perPixel: false,
            shininess: 0.2 } );
        sp = new THREE.Mesh(spGeo,mat2);
        scene.add(sp);
    }

    // add clouds
    function addClouds() {
        var spGeo = new THREE.SphereGeometry(600,50,50);
        var cloudsTexture = THREE.ImageUtils.loadTexture( "assets/earth_clouds_1024.png" );
        var materialClouds = new THREE.MeshPhongMaterial( { color: 0xffffff, map: cloudsTexture, transparent:true, opacity:0.3 } );

        meshClouds = new THREE.Mesh( spGeo, materialClouds );
        meshClouds.scale.set( 1.015, 1.015, 1.015 );
        scene.add( meshClouds );
    }

    // convert the positions from a lat, lon to a position on a sphere.
    function latLongToVector3(lat, lon, radius, heigth) {
        var phi = (lat)*Math.PI/180;
        var theta = (lon-180)*Math.PI/180;

        var x = -(radius+heigth) * Math.cos(phi) * Math.cos(theta);
        var y = (radius+heigth) * Math.sin(phi);
        var z = (radius+heigth) * Math.cos(phi) * Math.sin(theta);

        return new THREE.Vector3(x,y,z);
    }


    // render the scene
    function render() {
        // var timer = Date.now() * 0.0001; //! base
        // var timer = Date.now() * 0.0003;
        var timer = Math.PI / 3;
        camera.position.y = -600; //! comentado base
        camera.position.x = (Math.cos( timer ) *  1400);// 1800 base
        camera.position.z = (Math.sin( timer ) *  1400);
        console.log(timer);
        camera.lookAt( scene.position );
        light.position = camera.position;
        light.lookAt(scene.position);
        renderer.render( scene, camera );
        // requestAnimationFrame( render );
    }


    // from http://stackoverflow.com/questions/1293147/javascript-code-to-parse-csv-data
    function CSVToArray( strData, strDelimiter ){
        // Check to see if the delimiter is defined. If not,
        // then default to comma.
        strDelimiter = (strDelimiter || ";");

        // Create a regular expression to parse the CSV values.
        var objPattern = new RegExp(
                (
                    // Delimiters.
                        "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

                            // Quoted fields.
                                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

                            // Standard fields.
                                "([^\"\\" + strDelimiter + "\\r\\n]*))"
                        ),
                "gi"
        );


        // Create an array to hold our data. Give the array
        // a default empty first row.
        var arrData = [[]];

        // Create an array to hold our individual pattern
        // matching groups.
        var arrMatches = null;


        // Keep looping over the regular expression matches
        // until we can no longer find a match.
        while (arrMatches = objPattern.exec( strData )){

            // Get the delimiter that was found.
            var strMatchedDelimiter = arrMatches[ 1 ];

            // Check to see if the given delimiter has a length
            // (is not the start of string) and if it matches
            // field delimiter. If id does not, then we know
            // that this delimiter is a row delimiter.
            if (
                    strMatchedDelimiter.length &&
                            (strMatchedDelimiter != strDelimiter)
                    ){

                // Since we have reached a new row of data,
                // add an empty row to our data array.
                arrData.push( [] );

            }


            // Now that we have our delimiter out of the way,
            // let's check to see which kind of value we
            // captured (quoted or unquoted).
            if (arrMatches[ 2 ]){

                // We found a quoted value. When we capture
                // this value, unescape any double quotes.
                var strMatchedValue = arrMatches[ 2 ].replace(
                        new RegExp( "\"\"", "g" ),
                        "\""
                );

            } else {

                // We found a non-quoted value.
                var strMatchedValue = arrMatches[ 3 ];

            }


            // Now that we have our value string, let's add
            // it to the data array.
            arrData[ arrData.length - 1 ].push( strMatchedValue );
        }

        // Return the parsed data.
        return( arrData );
    }
</script>
</body>
</html>