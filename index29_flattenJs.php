<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- ALl credits to Turf https://github.com/Turfjs/turf/blob/master/examples/es-modules/index.html -->
    <title>ES Modules</title>
</head>
<body>
    <svg id="stage" width="500" height="500"></svg>

    <script type="module">
        import Flatten from "https://unpkg.com/@flatten-js/core?module";

        // make some construction
        // let s1 = segment(10,10,200,200);
        // let s2 = segment(10,160,200,30);
        // let c = circle(point(200, 110), 50);
        // let ip = s1.intersect(s2);

        // document.getElementById("stage").innerHTML = s1.svg() + s2.svg() + c.svg() + ip[0].svg();


        // import Flatten from "@flatten-js/core"
        const { polygon } = Flatten;
        const { unify } = Flatten.BooleanOperations;

        const p1 = polygon([[0, 30], [30, 30], [30, 0], [0, 0]]);
        const p2 = polygon([[20, 5], [20, 25], [40, 15]]);
        const p3 = unify(p1, p2);
        document.getElementById("stage").innerHTML = p3.svg();
        console.log(p3)
        console.log(p3.toArray())
        console.log(p3.vertices)
        console.log(p3.area())

    </script>
</body>
</html>