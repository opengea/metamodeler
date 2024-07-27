// Metamodel.js
// By Jordi Berenguer Rodrigo
// Apache 2.0 License
// (c)2024 Opengea SCCL
// jordi@opengea.org

// MAIN

// standard global variables
var container, scene, camera, mouse, renderer, controls, stats, objects;
var keyboard = new THREEx.KeyboardState();

// var clock = new THREE.Clock();

// custom global variables
var cube;
var projector, mouse = { x: 0, y: 0 }, INTERSECTED;
var sprite1;
var canvas1, context1, texture1;

//global

var torusgeometry;
var torusmaterial;
var torus;
var geometrySphere;
var materialSphere;
var sphere;
var tropic_can;
var tropic_cap;
var equador;
var globalium=[]; // arrays amb objectes

//objects
var objects=[]; //objects to click
var cat=[]; //labels in the model
var cat2=[];

//Raycaster (for clicking objects)
raycaster = new THREE.Raycaster();



function init(mobile) 
{

        // SCENE
        scene = new THREE.Scene();
        // CAMERA
        var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
        var VIEW_ANGLE = 20, ASPECT = SCREEN_WIDTH / SCREEN_HEIGHT, NEAR = 0.1, FAR = 20000;
        camera = new THREE.PerspectiveCamera( VIEW_ANGLE, ASPECT, NEAR, FAR);

        scene.add(camera);
        camera.position.set(90,-1460,0);
        camera.lookAt(scene.position);  

        // RENDERER
        if ( Detector.webgl )
                renderer = new THREE.WebGLRenderer( {antialias:true} );
        else
                renderer = new THREE.CanvasRenderer(); 
        renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
        container = document.getElementById( 'ThreeJS' );
        container.appendChild( renderer.domElement );


        // EVENTS
        THREEx.WindowResize(renderer, camera);
        THREEx.FullScreen.bindKey({ charCode : 'f'.charCodeAt(0) }); // f=fullscreen

        renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );

	//TROPICS I EQUADOR
	ecuador_geo = new THREE.CircleGeometry(100,8);
	ecuador_mat = new THREE.MeshBasicMaterial({ color: 0x111111, wireframe:true });
	ecuador = new THREE.Mesh(ecuador_geo, ecuador_mat);
	scene.add(ecuador);
	ecuador.position.set(0,0,0);
	ecuador.rotation.y=300.02;//Math.PI / 2;

        ecuador_geo = new THREE.CircleGeometry(80,8);
        ecuador_mat = new THREE.MeshBasicMaterial({ color: 0x111111, wireframe:true });
        tropic_cap = new THREE.Mesh(ecuador_geo, ecuador_mat);
        scene.add(tropic_cap);
        tropic_cap.position.set(-60,0,0);
	tropic_cap.rotation.y=300.02;

        ecuador_geo = new THREE.CircleGeometry(80,8);
        ecuador_mat = new THREE.MeshBasicMaterial({ color: 0x111111, wireframe:true });
        tropic_can = new THREE.Mesh(ecuador_geo, ecuador_mat);
        scene.add(tropic_can);
	tropic_can.position.set(60,0,0);
        tropic_can.rotation.y=300.02;
	
	// ROTATE CAM
	window.addEventListener( 'keydown', onDocumentKeyDown, false );

        // CONTROLS
        controls = new THREE.TrackballControls( camera,container );


        if (mobile) { controls.rotateSpeed = 2.0; } else {  controls.rotateSpeed = 10.0; }
        controls.zoomSpeed = 0.05;
        controls.panSpeed = 0.8;
        controls.noZoom = false;
        controls.noPan = true; //false;
        controls.staticMoving = true;
        controls.dynamicDampingFactor = 0.3;
        controls.keys = [ 65, 83, 68 ]; //A, S, D (http://cherrytree.at/misc/vk.htm)
        controls.addEventListener( 'change', render );

        // STATS
/*      stats = new Stats();
        stats.domElement.style.position = 'absolute';
        stats.domElement.style.bottom = '0px';
        stats.domElement.style.zIndex = 100;
        container.appendChild( stats.domElement );
*/

	addLights();
	//addBezier();
	addSeny();
	//addFloor();

	/* dimensional axis - eixos */

	//PRA
	var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( -155, 0, 0 ); var vertex2 = new THREE.Vector3( 0, 0, 0 );
	var positions = new Float32Array([
	    vertex1.x, vertex1.y, vertex1.z,
    	    vertex2.x, vertex2.y, vertex2.z,
	]); 
	geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
	var material = new THREE.LineDashedMaterial({ color: 0xff5500, linewidth: 1, scale: 10, dashSize: 2, gapSize: 2, transparent: true, opacity:0.5 });
	var line = new THREE.Line( geometry, material );
	scene.add( line );

	//TEO
	var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( 0, 0, 0 ); var vertex2 = new THREE.Vector3( 155, 0, 0 );
        var positions = new Float32Array([
            vertex1.x, vertex1.y, vertex1.z,
            vertex2.x, vertex2.y, vertex2.z,
        ]);
	geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        var material = new THREE.LineDashedMaterial({ color: 0x0040ff, linewidth: 220, dashSize: 2, gapSize: 2 });
        var line = new THREE.Line( geometry, material );
        scene.add( line );	

	//NOU
        var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( 0, 0, 0 ); var vertex2 = new THREE.Vector3( 0, 155, 0 );
        var positions = new Float32Array([
            vertex1.x, vertex1.y, vertex1.z,
            vertex2.x, vertex2.y, vertex2.z,
        ]);
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        //geometry.computeLineDistances();
        var material = new THREE.LineDashedMaterial({ color: 0x761ad2, dashSize: 2, gapSize: 2 });
        var line = new THREE.Line( geometry, material );
        scene.add( line );

	//FEN
        var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( 0, 0, 0 ); var vertex2 = new THREE.Vector3( 0, -155, 0 );
        var positions = new Float32Array([
            vertex1.x, vertex1.y, vertex1.z,
            vertex2.x, vertex2.y, vertex2.z,
        ]);
	geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        //geometry.computeLineDistances();
        var material = new THREE.LineDashedMaterial({ color: 0x43bd18, dashSize: 2, gapSize: 2 });
        var line = new THREE.Line( geometry, material );
        scene.add( line );

	//SUB
	var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( 0, 0, 0 ); var vertex2 = new THREE.Vector3( 0, 0, 155 );
        var positions = new Float32Array([
            vertex1.x, vertex1.y, vertex1.z,
            vertex2.x, vertex2.y, vertex2.z,
        ]);
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        var material = new THREE.LineDashedMaterial({ color: 0xffaaaa, dashSize: 2, gapSize: 2, transparent: true, opacity:0.7 })
        var line = new THREE.Line( geometry, material );
        scene.add( line );

	//OBJ
	var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( 0, 0, 0 ); var vertex2 = new THREE.Vector3( 0, 0, -155 );
        var positions = new Float32Array([
            vertex1.x, vertex1.y, vertex1.z,
            vertex2.x, vertex2.y, vertex2.z,
        ]);
        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        //geometry.computeLineDistances();
        var material = new THREE.LineDashedMaterial({ color: 0x55ffff, dashSize: 2, gapSize: 2, transparent: true, opacity:0.5  });
        var line = new THREE.Line( geometry, material );
        scene.add( line );



        // SKYBOX/FOG
/*      var skyBoxGeometry = new THREE.CubeGeometry( 10000, 10000, 10000 );
        var skyBoxMaterial = new THREE.MeshBasicMaterial( { color: 0x9999ff, side: THREE.BackSide } );
        var skyBox = new THREE.Mesh( skyBoxGeometry, skyBoxMaterial );
        scene.add(skyBox);
*/      
        ////////////
        // CUSTOM //
        ////////////
        
/*      var cubeGeometry = new THREE.CubeGeometry( 100, 100, 100 );
        var cubeMaterial = new THREE.MeshNormalMaterial();
        cube = new THREE.Mesh( cubeGeometry, cubeMaterial );
        cube.position.set(0,50.1,0);
        cube.name = "Cube";
        scene.add(cube);
*/     

	//SPHERE PLASMA
	geometrySphere = new THREE.SphereGeometry( 50 , 100, 100 );
	materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.09});

	var materialSphere = new THREE.MeshLambertMaterial({
	    color: 0xff0000,
	    emissive: 0x000000,
	    emissiveIntensity: 0,
	    wireframe: true,
	    transparent: true,
	    opacity: 0.05,
	    flatShading: false
	});

	sphere = new THREE.Mesh( geometrySphere, materialSphere );
	scene.add( sphere );

        //SPHERE NEUTRAL
        geometrySphere = new THREE.SphereGeometry( $('#sphere_rad').val(), 126, 126 );
//      materialSphere = new THREE.MeshBasicMaterial( { color: 0xffffff,wireframe:true,transparent:true,opacity:0.02 } );
        materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.05});

	var materialSphere = new THREE.MeshLambertMaterial({
	 color: 0xffffff,
	    emissive: 0x000000,
	    emissiveIntensity: 0,
	    wireframe: false,
	    transparent: true,
	    opacity: 0.1,
	    flatShading: false
	});

        sphere = new THREE.Mesh( geometrySphere, materialSphere );
        scene.add( sphere );

        //SPHERE MON
        geometrySphere = new THREE.SphereGeometry( 155, 180, 180 );
        materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.06});

        var materialSphere = new THREE.MeshLambertMaterial({
         color: 0x0077ff,
            emissive: 0x000000,
            emissiveIntensity: 0,
            wireframe: true,
            transparent: true,
            opacity: 0.09,
            flatShading: false
        });


        sphere = new THREE.Mesh( geometrySphere, materialSphere );
        scene.add( sphere );
	
        //TORUS
        torusgeometry=new THREE.TorusGeometry(parseInt($('#geo_rad').val()),parseInt($('#geo_tub').val()),parseInt($('#geo_seg').val()),parseInt($('#geo_arc').val()));
        torusmaterial=new THREE.MeshLambertMaterial({wireframe: $('#render_wireframe').is(':checked'), color: 0xffffff,transparent:true,opacity:0.1});
        torus= new THREE.Mesh(torusgeometry,torusmaterial);


/*
console.log('----------------- OBJExporter-----------------');
var exporter = new THREE.OBJExporter();
var obj_data=exporter.parse( torus );
var data = new FormData();
data.append("data" , obj_data);
var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new activeXObject("Microsoft.XMLHTTP");
xhr.open( 'post', '/sources/metamodel/tools/save.php', true );
xhr.send(data);
console.log('-----------------------------------------------');
*/
        torus.position.set(0,0,0);
	torus.rotation.set(1.5,1.5,0);
        torus.name = "Torus";
        scene.add(torus);

        var spritey;
}

//positioning
function situa(PT,FN,OS) {  //PT=Pra-Teo  FN=Fen-Nou OS=Obj-Sub

	//determine x axis;	
	if (globalium['PRA'].pos[0]!=0) var x='PT';
	else if (globalium['FEN'].pos[0]!=0) var x='FN';
	else if (globalium['OBJ'].pos[0]!=0) var x='OS';
	
	//determine y axis;
	if (globalium['PRA'].pos[1]!=0) var y='PT';
        else if (globalium['FEN'].pos[1]!=0) var y='FN';
        else if (globalium['OBJ'].pos[1]!=0) var y='OS';

	//determine z axis;
	if (globalium['PRA'].pos[2]!=0) var z='PT';
        else if (globalium['FEN'].pos[2]!=0) var z='FN';
        else if (globalium['OBJ'].pos[2]!=0) var z='OS';

/*
	//determine position x
	if (x=='PT') x=posx(PT,cat['dim']['MONT']);
	else if (x=='FN') x=posx(FN,cat['dim']['MONN']);
	else if (x=='OS') x=posx(OS,cat['dim']['MONS']);
	//determine position y
	if (y=='PT') y=posy(PT,cat['dim']['MONT']);
        else if (y=='FN') y=posy(FN,cat['dim']['MONN']);
        else if (y=='OS') y=posy(OS,cat['dim']['MONS']);
	//determine position z
	if (z=='PT') z=posz(PT,cat['dim']['MONT']);
        else if (z=='FN') z=posz(FN,cat['dim']['MONN']);
        else if (z=='OS') z=posz(OS,cat['dim']['MONS']);
*/
	var pos=[x,y,z];
	return pos;

}


function addLights() {

        //yelow center 
        var light = new THREE.PointLight(0xffff00,2,1900,1);
        var x=0;var y=0;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

         // object
         var light = new THREE.PointLight(0x00ffff,2,1000,1);
        var x=0;var y=-200;var z=-200;
        light.position.set(x,y,z);
        scene.add(light);

        // subject
        var light = new THREE.PointLight(0xff0055,2,1000,1);
        var x=-0;var y=-200;var z=200;
        light.position.set(x,y,z);
        scene.add(light);

        // teo 
         var light = new THREE.PointLight(0x0040ff,1,1000,2);
        var x=200;var y=0;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

        // pra
         var light = new THREE.PointLight(0xff0000,20,1000,2);
        var x=-200;var y=0;var z=0;
        light.position.set(x,y,z);
       scene.add(light);

        // fen 
         var light = new THREE.PointLight(0x00ff00,2,1000,1);
        var x=0;var y=-200;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

        // nou
        var light = new THREE.PointLight(0x6611aa,2,1000,1);
        var x=0;var y=200;var z=0;
        light.position.set(x,y,z);
        scene.add(light);
}

function addBezier() {

        // entrellacament corbes bezier
        var curve = new THREE.CubicBezierCurve3(new THREE.Vector3( 40, 40, 40 ),
        new THREE.Vector3( 100, 30, 0 ),
        new THREE.Vector3( 100, -30, 0 ),
        new THREE.Vector3( 50, -50, -40 )
        );

        var curvegeometry = new THREE.Geometry();
        curvegeometry.vertices = curve.getPoints( 50 );
        var curvematerial = new THREE.LineBasicMaterial( { color : 0xff0000 } );
        var curveObject = new THREE.Line( curvegeometry, curvematerial );
        scene.add(curveObject);

        var curve = new THREE.CubicBezierCurve3(new THREE.Vector3( 50, -50, -40 ),
        new THREE.Vector3( 0, -50, -40 ),
        new THREE.Vector3( 0, -40, 0 ),
        new THREE.Vector3( -40, -30, 40 )
        );

        var curvegeometry = new THREE.Geometry();
        curvegeometry.vertices = curve.getPoints( 50 );
        var curvematerial = new THREE.LineBasicMaterial( { color : 0xff0000 } );
        var curveObject = new THREE.Line( curvegeometry, curvematerial );
        scene.add(curveObject);

}

function addSeny() {

        //cami del seny ---------------------------------
        //ona 1
        var curve = new THREE.SplineCurve( [
/*                new THREE.Vector3(  0,  90,   0 ), //top
                new THREE.Vector3( 80,  80,  50 ),
                new THREE.Vector3( 95,   0,   0 ), //right
                new THREE.Vector3( 80, -80, -50 ),
                new THREE.Vector3(  0, -90,   0 ), //bottom
                new THREE.Vector3(-80, -80,  50 ),
                new THREE.Vector3(-95,   0,   0 ), //left
                new THREE.Vector3(-80,  80, -50 ),
                new THREE.Vector3(  0,  90,   0 ) //top
*/
         new THREE.Vector3( 50,  50,   0 ), //top
                new THREE.Vector3( 50,  50,  50 ),
                new THREE.Vector3( 50,   0,   0 ), //right
                new THREE.Vector3( 50, -50, -50 ),
                new THREE.Vector3(  0, -50,   0 ), //bottom
                new THREE.Vector3(-50, -50,  50 ),
                new THREE.Vector3(-50,   0,   0 ), //left
                new THREE.Vector3(-50,  50, -50 ),
                new THREE.Vector3(  0,  50,   0 ) //top
        ] );

        //geometry.vertices = curve.getPoints( 50 );
        var geometry = new THREE.BufferGeometry().setFromPoints(curve.getPoints( 50 ));
        var material = new THREE.LineBasicMaterial( { color : 0xff0000,transparent:true,opacity:1 } );
        onaSeny[0] = new THREE.Line( geometry, material );
        scene.add(onaSeny[0]);
        // ona 2
        var curve2 = new THREE.SplineCurve( [
                new THREE.Vector3(  0,  90,   0 ), //top
                new THREE.Vector3( 80,  80, -50 ),
                new THREE.Vector3( 95,   0,   0 ), //right
                new THREE.Vector3( 80, -80,  50 ),
                new THREE.Vector3(  0, -90,   0 ), //bottom
                new THREE.Vector3(-80, -80, -50 ),
                new THREE.Vector3(-95,   0,   0 ), //left
                new THREE.Vector3(-80,  80,  50 ),
                new THREE.Vector3(  0,  90,   0 ) //top
        ]);
        var geometry2 = new THREE.BufferGeometry().setFromPoints(curve2.getPoints( 50 ));
        //geometry2.vertices = curve2.getPoints( 50 );
        var material2 = new THREE.LineBasicMaterial( { color : 0x7777ff,transparent:true,opacity:1 } );
        onaSeny[1] = new THREE.Line( geometry2, material2 );
        scene.add(onaSeny[1]);

/*
        //ona 3
        var curve = new THREE.SplineCurve( [
                new THREE.Vector3(  0,  90,  50 ), //top
                new THREE.Vector3( 80,  80,   0 ),
                new THREE.Vector3( 95,   0, -50 ), //right
                new THREE.Vector3( 80, -80,   0 ),
                new THREE.Vector3(  0, -90,  50 ), //bottom
                new THREE.Vector3(-80, -80,   0 ),
                new THREE.Vector3(-95,   0, -50 ), //left
                new THREE.Vector3(-80,  80,   0 ),
                new THREE.Vector3(  0,  90,  50 ) //top

        ] );
        var geometry = new THREE.Geometry();
        geometry.vertices = curve.getPoints( 50 );
        var material = new THREE.LineBasicMaterial( { color : 0xff0000,transparent:true,opacity:1 } );
        onaSeny[2] = new THREE.Line( geometry, material );
        scene.add(onaSeny[2]);
        // ona 4
        var curve2 = new THREE.SplineCurve( [
                new THREE.Vector3(  0,  90, -50 ), //top
                new THREE.Vector3( 90,  90,   0 ),
                new THREE.Vector3( 95,   0,  50 ), //right
                new THREE.Vector3( 90, -90,   0 ),
                new THREE.Vector3(  0, -90, -50 ), //bottom
                new THREE.Vector3(-90, -90,   0 ),
                new THREE.Vector3(-95,   0,  50 ), //left
                new THREE.Vector3(-90,  90,   0 ),
                new THREE.Vector3(  0,  90,  -50 ) //top
        ]);
        var geometry2 = new THREE.Geometry();
        geometry2.vertices = curve2.getPoints( 50 );
        var material2 = new THREE.LineBasicMaterial( { color : 0x7777ff,transparent:true,opacity:1 } );
        onaSeny[3] = new THREE.Line( geometry2, material2 );
        scene.add(onaSeny[3]);
*/

        //cami del crepus/rauxa ---------------------------------
        //ona 1
        var curve = new THREE.SplineCurve( [
                new THREE.Vector3( 0,   0,   0 ),
                new THREE.Vector3( -70,  -10, -80 ),
                new THREE.Vector3( -150, -50, -50),
                new THREE.Vector3( -100, -130, -30),
                new THREE.Vector3( 0,  -170,  0 ),
                new THREE.Vector3( 100, -130, 30),
                new THREE.Vector3( 150, -50, 50),
                new THREE.Vector3( 70, -10, 80),
                new THREE.Vector3( 0,   0,   0 ),
                new THREE.Vector3( -40, 10, -80),
                new THREE.Vector3( -150, 50, -50),
                new THREE.Vector3( -100, 130, -30),
                new THREE.Vector3( 0, 170, 0),
                new THREE.Vector3(  100, 130, 30),
                new THREE.Vector3( 150, 50, 50),
                new THREE.Vector3( 40, 10, 80),
                new THREE.Vector3( 0,   0,   0 )
        ] );
        var geometry = new THREE.BufferGeometry().setFromPoints(curve.getPoints( 100 ));
   //     geometry.vertices = curve.getPoints( 100 );
        var material = new THREE.LineBasicMaterial( { color : 0x00ff00,transparent:true,opacity:0.7 } );
        onaCrepus[0] = new THREE.Line( geometry, material );
        scene.add(onaCrepus[0]);
        // ona 2
        var curve2 = new THREE.SplineCurve( [
                new THREE.Vector3( 0,   0,   0 ),
                new THREE.Vector3( 40,  -10, -80 ),
                new THREE.Vector3( 100, -20, -50),
                new THREE.Vector3( 150, -130, -30),
                new THREE.Vector3( 0,  -130,  0 ),
                new THREE.Vector3( -100, -100, 30),
                new THREE.Vector3( -150, -20, 50),
                new THREE.Vector3( -40, -10, 80),
                new THREE.Vector3( 0,   0,   0 ),
                new THREE.Vector3( 40, 10, -80),
                new THREE.Vector3( 150, 20, -50),
                new THREE.Vector3( 100, 100, -30),
                new THREE.Vector3( 0, 130, 0),
                new THREE.Vector3(  -100, 130, 30),
                new THREE.Vector3( -150, 20, 50),
                new THREE.Vector3( -40, 10, 80),
                new THREE.Vector3( 0,   0,   0 )
        ]);
        var geometry2 = new THREE.BufferGeometry().setFromPoints(curve.getPoints( 100 ));
//        geometry2.vertices = curve2.getPoints( 100 );
        var material2 = new THREE.LineBasicMaterial( { color : 0xffff00,transparent:true,opacity:0.7 } );
        //Create the final Object3d to add to the scene
        onaCrepus[1] = new THREE.Line( geometry2, material2 );
        scene.add(onaCrepus[1]);
}


function addFloor() {
        // FLOOR
      var floorTexture = new THREE.ImageUtils.loadTexture( 'images/checkerboard.jpg' );
        floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping; 
        floorTexture.repeat.set( 10, 10 );
        var floorMaterial = new THREE.MeshBasicMaterial( { map: floorTexture, side: THREE.DoubleSide } );
        var floorGeometry = new THREE.PlaneGeometry(1000, 1000, 10, 10);
        var floor = new THREE.Mesh(floorGeometry, floorMaterial);
        floor.position.y = -0.5;
        floor.rotation.x = Math.PI / 2;
        floor.name = "Checkerboard Floor";
        scene.add(floor);

}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function entre(cat1,cat2) { //situa entre dues categories
	console.log('entre('+cat1+','+cat2+')');

	var cat_dimglob=['TEO','PRA','FEN','NOU','SUB','OBJ']; //,'MON-O','MON-S','MON-F','MON-N','MON-T','MON-P'];
	if (inArray(cat1,cat_dimglob)) {
		x1=globalium[cat1].pos[0];
                y1=globalium[cat1].pos[1];
                z1=globalium[cat1].pos[2];
	} else {
		x1=globalium[cat1].pos[0];
		y1=globalium[cat1].pos[1];
		z1=globalium[cat1].pos[2];
	}

	if (inArray(cat2,cat_dimglob)) {

		x2=globalium[cat2].pos[0];
                y2=globalium[cat2].pos[1];
                z2=globalium[cat2].pos[2];
	} else {
		x2=globalium[cat2].pos[0];
		y2=globalium[cat2].pos[1];
        	z2=globalium[cat2].pos[2];
	}

        //determine x axis;     
        if (globalium['PRA'].pos[0]!=0) var x='PT';
        else if (globalium['FEN'].pos[0]!=0) var x='FN';
        else if (globalium['OBJ'].pos[0]!=0) var x='OS';

        //determine y axis;
        if (globalium['PRA'].pos[1]!=0) var y='PT';
        else if (globalium['FEN'].pos[1]!=0) var y='FN';
        else if (globalium['OBJ'].pos[1]!=0) var y='OS';

        //determine z axis;
        if (globalium['PRA'].pos[2]!=0) var z='PT';
        else if (globalium['FEN'].pos[2]!=0) var z='FN';
        else if (globalium['OBJ'].pos[2]!=0) var z='OS';


	//console.log(x+','+y+','+z);

	mx=(x1-x2)/2;  mx=Math.abs(mx); if (x1>x2) mx=x2+mx; else mx=x1+mx;
        my=(y1-y2)/2;  my=Math.abs(my); if (y1>y2) my=y2+my; else my=y1+my;
        mz=(z1-z2)/2;  mz=Math.abs(mz); if (z1>z2) mz=z2+mz; else mz=z1+mz;


	//console.log("cat1:"+x1+','+y1+','+z1);
	//console.log("cat2:"+x2+','+y2+','+z2);
	//console.log("mig:"+mx+','+my+','+mz);


	var pos=[mx,my,mz];
        return pos;	

}


function posx(percent,relative) {
	var factor=100/percent;
//alert(relative->cat['dim']);
//cat[label][key].data[prop]=data[key].prop
//cat[label][key].data['pos']
//	return (relative.data['pos'][0]/factor);
//console.log(relative.data);
	return (relative.data.pos[0]/factor);

}

function posy(percent,relative) {
        var factor=100/percent;
//      return (relative.data['pos'][1]/factor);
	return (relative.data.pos[1]/factor);
}
function posz(percent,relative) {
        var factor=100/percent;
//     return (relative.data['pos'][2]/factor);
	return (relative.data.pos[2]/factor);

}

function toCir (x,y,z) {
        //transform cartesian to circular
//toCir(posx(25,cat['dim']['PRA']),posy(25,cat['dim']['FEN']),0)
        a=Math.atan(y/x);
        xc = Math.sin(a)*x;
console.log(a);
console.log(x+","+y+","+z);

	var xc = Math.sin(45)*x;
	var yc = Math.sin(45)*y;
	var zc = Math.sin(45)*z;
console.log(xc+","+yc+","+zc);
	return [xc,yc,zc];
}

function degInRad(deg) {
    return deg * Math.PI / 180;
}  

function onDocumentKeyDown ( event ) {
	rot = 0.025; 
	delta = 5;
	var x = camera.position.x,
            y = camera.position.y,
            z = camera.position.z;

//	controls.target.set(0,0,0);
	event = event || window.event;
	
	var keycode = event.keyCode;
	switch(keycode){
	case 37 : //left arrow
        camera.position.x = x * Math.cos(rot) + z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) - x * Math.sin(rot);
	break;
	case 38 : // up arrow
	camera.position.z = camera.position.z - delta;
	break;
	case 39 : // right arrow
//	camera.position.x = camera.position.x + delta;
	camera.position.x = x * Math.cos(rot) - z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) + x * Math.sin(rot);
	break;
	case 40 : //down arrow
	camera.position.z = camera.position.z + delta;
	break;
	}
        camera.lookAt(scene.position);
}

function onDocumentMouseDown( event ) {

    var mouse = new THREE.Vector2();
    mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
    mouse.y = -(event.clientY / window.innerHeight) * 2 + 1; 
    var raycaster = new THREE.Raycaster();
    raycaster.setFromCamera(mouse, camera); //r70!
	
    var intersects = raycaster.intersectObjects(objects);
    if (intersects.length > 0) {
        document.body.style.cursor = 'pointer';
	//load data into info panel
	var obj=intersects[0].object;
	var info="<br>"+obj.data.title+" ("+obj.name+")";//("+obj.position.x+","+obj.position.y+","+obj.position.z+")";
	if (obj.data.type!="Plasmàtica"&&obj.data.type!="Mundana") {add2=")";add="NEUTRAL (";} else{ add=add2="";}
	info+="<br><br>Type: "+add+obj.data.type+add2+"</span>";
	info+="<br><br><span class='colortheme_dark'>"+obj.data.descr+"</span>";
	if (obj.data.related) info+="<br><br><span style='color:#999'><b>Afins:</b><br>"+obj.data.related+"</span>";
	$('#info').html(info);
	setTab('info');
	//points camera to object position
	//controls.target.set( intersects[0].object.position.x,intersects[0].object.position.y,intersects[0].object.position.z );
    } else {
document.body.style.cursor = 'default';

	//click outside
	$('#info').html(info);
	setTab('info');
    }
}

function setTab(panel) {

	$('.tab').hide();
	$('#'+panel).show();
}

function set_visibility(obj,v) {
	obj=obj.substr(2,3); //skip load order prefix from filename
        for (var key in cat[obj]) {
                cat[obj][key].visible=v;
        }
}

function set_visibility_all(v) {
        for (var obj in cat) {
		for (var key in cat[obj]) {
	                cat[obj][key].visible=v;
		}
		$('#chk_'+obj).prop("checked",false);
        }
}

//var particleTexture = THREE.ImageUtils.loadTexture( 'images/particle.png' );
var particleGroup = new THREE.Object3D();
var particleAttributes = { startSize: [], startPosition: [], randomness: [] };
var radiusRange = 140;
	
function loadCategories(label,data,properties) {

console.log('loading categories '+label+' properties:'+properties.fontsize);
//console.log(properties);
	label=label.substr(0,3);
        cat[label]=cat[label] || [];
        for (var key in data) {
		//label
		cat[label][key] = sprite_label = makeTextSprite( label, key, { textcolor:properties.textcolor,borderThickness: properties.borderThickness,fontsize: properties.fontsize, borderColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.borderalpha}, backgroundColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.bgalpha} } ); 
		xlabel=data[key].pos[0]; if (xlabel>0) xlabel++; else if (xlabel<0) xlabel--;
		ylabel=data[key].pos[1]; if (ylabel>0) ylabel++; else if (ylabel<0) ylabel--;
		zlabel=data[key].pos[2]; if (zlabel>0) zlabel++; else if (zlabel<0) zlabel--;

                var scaleFactor = properties.borderThickness*3;
		//console.log(key+' : '+label);console.log(properties);
                sprite_label.scale.set(scaleFactor*1.9, scaleFactor, scaleFactor);
  	        sprite_label.position.set(xlabel,ylabel,zlabel);

		//line
	        //pulsacio pla-mon
		if (data[key].type=='Mundana' && key!='COS' && key!='COV' && key!='INT' && key!='AFI' && key!='EXC' && key!='CMN') {
  		      start = new THREE.Vector3(0, 0, 0);
 		       end = new THREE.Vector3(data[key].pos[0],data[key].pos[1],data[key].pos[2]);
		        var lineGeometry = new THREE.BufferGeometry().setFromPoints([start, end]);
		        var color = Number("0x"+data[key].color[0].toString(16)+data[key].color[1].toString(16)+data[key].color[2].toString(16)+"00");
     			var lineMaterial = new THREE.LineBasicMaterial({ color: color, transparent: true, opacity: 0.2 });
     			var line = new THREE.Line(lineGeometry, lineMaterial);
        		scene.add(line); //pulsacions
		}

		//contains user data!
		cat[label][key].data = {};
		if (typeof data[key]!== 'undefined') { 
			//load parameters
			for (var prop in data[key]) {
			  	cat[label][key].data[prop]=data[key][prop];
			}
		 }

/*                cat[label][key].data = [];
                if (typeof data[key]!== 'undefined') {
                        //load parameters
                        for (var prop in data[key]) {
                                cat[label][key].data[prop]=data[key][prop];
                        }
                 }
*/

                scene.add( sprite_label ); 
		objects.push(sprite_label);

		/*
		//light particle
                var spriteMaterial = new THREE.SpriteMaterial( { map: particleTexture, color: 0xffffff } )
                var sprite = new THREE.Sprite( spriteMaterial );
                sprite.scale.set(3,3, 0.5 ); // imageWidth, imageHeight
                sprite.position.set( data[key][0],data[key][1],data[key][2]*0.2);
		sprite.position.setLength( radiusRange );//* (Math.random() * 0.1 + 0.9) );
		sprite.material.color.setRGB( data[key][3],data[key][4],data[key][5]);
		sprite.material.blending = THREE.AdditiveBlending;
		particleGroup.add( sprite );
		// add variable qualities to arrays, if they need to be accessed later
//		particleAttributes.startPosition.push( sprite.position.clone() );
		//particleAttributes.randomness.push( Math.random() );
		//push into objects
	        objects.push(sprite);

		*/

        }

//	scene.add( particleGroup );
}

function loadCategories2(label,data,properties) {

        label=label.substr(0,3);
        cat2[label]=cat2[label] || [];
        for (var key in data) {
                properties.borderThickness=properties.borderThickness//2;//properties.borderThickness*1.2;
                cat2[label][key] = sprite_label = makeTextSprite( label, key, { textcolor:properties.textcolor,borderThickness: properties.borderThickness,fontsize: properties.fontsize, borderColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.borderalpha}, backgroundColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.bgalpha} } );

                sprite_label.position.set(data[key].pos[0],data[key].pos[1],data[key].pos[2]);
                //contains user data!
                cat2[label][key].data = {};
                if (typeof data[key]!== 'undefined') {
                        //load parameters
                        for (var prop in data[key]) {
                                cat2[label][key].data[prop]=data[key][prop];
                        }
                 }
		var scaleFactor = properties.borderThickness;
		sprite_label.scale.set(scaleFactor, scaleFactor, scaleFactor);

                scene.add( sprite_label );
                objects.push(sprite_label);

        }

}


function makeTextSprite( type, message, parameters )
{
        if ( parameters === undefined ) parameters = {};
       
        var fontface = parameters.hasOwnProperty("fontface") ?  parameters["fontface"] : "arial";
        var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 70;
fontsize=50;
        var borderThickness = parameters.hasOwnProperty("borderThickness") ?  parameters["borderThickness"] : 14;

        var borderColor = parameters.hasOwnProperty("borderColor") ? parameters["borderColor"] : { r:0, g:0, b:0, a:1.0 };
        var backgroundColor = parameters.hasOwnProperty("backgroundColor") ? parameters["backgroundColor"] : { r:255, g:255, b:255, a:1.0 };
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
	
	bgHeightCorr=20;
        context.font = "Bold " + fontsize + "px " + fontface;
//        context.textAlign = 'center';
    
        // get size data (height depends only on font size)
        var metrics = context.measureText( message );
        var textWidth = metrics.width;
	var textHeight = fontsize;
//     	canvas.width=30;//metrics.width;
//	canvas.height=20;

	var offsetX=30;//120;
	var offsetY=0;//60;
	var roundedRadius=80;

        // background color
        context.fillStyle   = "rgba(" + backgroundColor.r + "," + backgroundColor.g + "," + backgroundColor.b + "," + backgroundColor.a + ")";
console.log(type);
	if (type=='neu') context.fillStyle   = "rgba(255,255,255,1)";
	else if (type=='pla') context.fillStyle   = "rgba(255,100,100,1)";
	else if (type=='mun') context.fillStyle   = "rgba(100,100,255,1)";
        // border color
       // context.strokeStyle = "rgba(" + borderColor.r + "," + borderColor.g + "," + borderColor.b + "," + borderColor.a + ")";
       // context.lineWidth = borderThickness;
	
//(ctx, x, y, w, h, r)
	roundRect(context, (borderThickness/2+offsetX)+50, borderThickness/2+offsetY, textWidth + borderThickness + offsetX, canvas.height-bgHeightCorr, roundedRadius);
//	roundRect(context, 0,0,canvas.width,canvas.height,70);
        // 1.4 is extra height factor for text below baseline: g,j,p,q.
        
        // text color

        if (parameters['textcolor']==undefined) parameters['textcolor']="0,0,0";
        context.fillStyle = "rgba("+parameters['textcolor']+", 1.0)";
        context.fillText( message, borderThickness+offsetX+60, fontsize + borderThickness+offsetY+30);

        // canvas contents will be used for a texture
        var texture = new THREE.Texture(canvas)
        texture.needsUpdate = true;



//        var spriteMaterial = new THREE.SpriteMaterial( { map: texture} );

var spriteMaterial = new THREE.SpriteMaterial({
    map: texture,
    depthTest: false, // Disable depth test
    depthWrite: false // Disable depth write
});


        var sprite = new THREE.Sprite( spriteMaterial );
//        sprite.scale.set(60,30,2.0);

	//var radius = 20;
//	var geometry = new THREE.SphereGeometry(radius, 32, 32);
  //	var textureLoader = new THREE.TextureLoader();
//	var texture = textureLoader.load('images/particle.png');
//	var material = new THREE.MeshBasicMaterial({ map: texture });
//	var mesh = new THREE.Mesh(geometry, material);
 //       var sprite = new THREE.Sprite( mesh.material );
//        sprite.scale.set(60,30,2.0);

	sprite.name=message;
	sprite.scale.set(9,5,2.0);
	sprite.position.set(0,0,0);
        return sprite;  
}

// function for drawing rounded rectangles
function roundRect(ctx, x, y, w, h, r) 
{
//forced width circle
	w=135;
    ctx.beginPath();
    ctx.moveTo(x+r, y);
    ctx.lineTo(x+w-r, y);
    ctx.quadraticCurveTo(x+w, y, x+w, y+r);
    ctx.lineTo(x+w, y+h-r);
    ctx.quadraticCurveTo(x+w, y+h, x+w-r, y+h);
    ctx.lineTo(x+r, y+h);
    ctx.quadraticCurveTo(x, y+h, x, y+h-r);
    ctx.lineTo(x, y+r);
    ctx.quadraticCurveTo(x, y, x+r, y);
    ctx.closePath();
    ctx.fill();
   ctx.stroke();   
}

function animate() 
{
   // requestAnimationFrame( animate );

   //covert 60 fps to 30 fps
    setTimeout( function() {
        requestAnimationFrame( animate );

/*
 // Update the raycaster
            raycaster.setFromCamera(mouse, camera);
 // Calculate objects intersecting the raycaster (sprites = labels)
           var intersects = raycaster.intersectObject(objects);
 // Change cursor style based on intersection
            if (intersects.length > 0) {
                document.body.style.cursor = 'pointer';
            } else {
               document.body.style.cursor = 'default';
           }
*/
    }, 1000 / 30 );
//    renderer.render();



        render();               
        update();
}

function update()
{
        controls.update();
//      stats.update();
}

function render() 
{
        renderer.render( scene, camera );
}


