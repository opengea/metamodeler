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
var lookat;

var metastructure;
var metacategories;
//global
var torusgeometry;
var torusmaterial;
var torus0,torus1,torus2,torus3;
var geometrySphere;
var materialSphere;
var sphere0, sphere1, sphere2;
var tropic_can;
var tropic_cap;
var equador;
var globalium=[]; // arrays amb objectes

//desplaçament de labels
var desplX=0;
var desplY=0;
var increment=9;

//objects
var objects=[]; //objects to click
var labels=[]; // labels to edit
var cat=[]; //labels in the model
var cat2=[];
var model; //model actual (subtipus)

//Raycaster (for clicking objects)
raycaster = new THREE.Raycaster();

 // Custom TrackballControls class
    class CustomTrackballControls extends THREE.TrackballControls {
        constructor(object, domElement) {
            super(object, domElement);
            this.minPolarAngle = Math.PI / 2; // Prevent moving up 90º in radians
            this.maxPolarAngle = Math.PI / 2; // Prevent moving down 90º in radians
        }

        update() {
            super.update();
            // Restrict the vertical rotation
            const offset = new THREE.Vector3();
            offset.copy(this.object.position).sub(this.target);
            const spherical = new THREE.Spherical();
            spherical.setFromVector3(offset);

            // Clamp polar angle
            spherical.phi = Math.max(this.minPolarAngle, Math.min(this.maxPolarAngle, spherical.phi));

            offset.setFromSpherical(spherical);
            this.object.position.copy(this.target).add(offset);
            this.object.lookAt(this.target);
        }
    }



function init(mobile) 
{

        // SCENE
        scene = new THREE.Scene();
	scene.background = new THREE.Color(0x161111);

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
     //   THREEx.FullScreen.bindKey({ charCode : 'f'.charCodeAt(0) }); // f=fullscreen

        renderer.domElement.addEventListener( 'mousedown', onDocumentMouseDown, false );

	//TROPICS I EQUADOR
	//ecuador_geo = new THREE.RingGeometry(99.7,100,64);
	ecuador_geo = new THREE.CylinderGeometry(100, 100, 0.2, 64, 1, true); // width (top, bottom), height, radial segments
	ecuador_mat = new THREE.MeshLambertMaterial({ color: 0xaaaaaa, wireframe: false, emissive: 0xaaaaaa, emissiveIntensity: 1, side: THREE.DoubleSide });
	ecuador = new THREE.Mesh(ecuador_geo, ecuador_mat);
	ecuador.position.set(0, 0, 0);
	ecuador.rotation.x = Math.PI / 2; // Rotate along the x-axis to make it horizontal
	ecuador.rotation.y = 300.02; // You can adjust this as per your original rotation
	scene.add(ecuador);

        ecuador_geo = new THREE.CylinderGeometry(100, 100, 0.2, 64, 1, true); // width (top, bottom), height, radial segments
	ecuador_mat = new THREE.MeshLambertMaterial({ color: 0xaaaaaa, wireframe: false, emissive: 0xaaaaaa, emissiveIntensity: 1, side: THREE.DoubleSide });
	ecuador = new THREE.Mesh(ecuador_geo, ecuador_mat);
	ecuador.position.set(0, 0, 0);
	ecuador.rotation.x = 0;//-Math.PI / 2; // Rotate along the x-axis to make it horizontal
	ecuador.rotation.y = 0; // You can adjust this as per your original rotation
	scene.add(ecuador);

        ecuador_geo = new THREE.CylinderGeometry(99.7, 100, 0.2, 64, 1, true); // width (top, bottom), height, radial segments
	ecuador_mat = new THREE.MeshLambertMaterial({ color: 0xaaaaaa, wireframe: false, emissive: 0xaaaaaa, emissiveIntensity: 1, side: THREE.DoubleSide });
        ecuador = new THREE.Mesh(ecuador_geo, ecuador_mat);
        ecuador.position.set(0,0,0);
	ecuador.rotation.x = Math.PI / 2;
        ecuador.rotation.y= 0;//300.02;//Math.PI / 2;
	ecuador.rotation.z= Math.PI / 2;
        scene.add(ecuador);

        ecuador_geo = new THREE.RingGeometry(79.7,80,64);
        ecuador_mat = new THREE.MeshBasicMaterial({ color: 0xaaaaaa, wireframe:false, emissive: 0xaaaaaa, emissiveIntensity: 1.5, side: THREE.DoubleSide });
        tropic_cap = new THREE.Mesh(ecuador_geo, ecuador_mat);
        scene.add(tropic_cap);
        tropic_cap.position.set(-60,0,0);
	tropic_cap.rotation.y=300.02;

        ecuador_geo = new THREE.RingGeometry(79.7,80,64);
        tropic_can = new THREE.Mesh(ecuador_geo, ecuador_mat);
        scene.add(tropic_can);
	tropic_can.position.set(60,0,0);
        tropic_can.rotation.y=300.02;
	
	// ROTATE CAM
	window.addEventListener( 'keydown', onDocumentKeyDown, false );

        // CONTROLS
       controls = new THREE.TrackballControls( camera,container );

	// Configura els límits de rotació vertical
//controls.minPolarAngle = Math.PI / 2; // Límits en radians (90 graus)
//controls.maxPolarAngle = Math.PI / 2; // Límits en radians (90 graus)

//	controls = new CustomTrackballControls( camera,container );


        if (mobile) { controls.rotateSpeed = 2.0; } else {  controls.rotateSpeed = 10.0; }
        controls.zoomSpeed = 0.05;
        controls.panSpeed = 0.8;
        controls.noRotate = false;
	controls.noZoom = false;
        controls.noPan = true; //false;

        controls.staticMoving = false;// Permet inèrcia
        controls.dynamicDampingFactor = 0.3;

        controls.keys = [ 65, 83, 68 ]; //A, S, D (http://cherrytree.at/misc/vk.htm)
        controls.addEventListener( 'change', render );

	// Defineix l'objectiu dels controls
	controls.target.set(0, 0, 0); // Objectiu dels controls

	//addStats();
	addLights();
	//addBezier();
	//addSeny();
//	addFloor();

	/* dimensional axis - eixos */

	//PRA
	var geometry = new THREE.BufferGeometry();
	var vertex1 = new THREE.Vector3( -155, 0, 0 ); var vertex2 = new THREE.Vector3( 0, 0, 0 );
	var positions = new Float32Array([
	    vertex1.x, vertex1.y, vertex1.z,
    	    vertex2.x, vertex2.y, vertex2.z,
	]); 
	geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
	var material = new THREE.LineDashedMaterial({ color: 0xff5500, linewidth: 1, scale: 10, dashSize: 2, gapSize: 2, transparent: false, opacity:0.5 });
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
        var material = new THREE.LineDashedMaterial({ color: 0xffaaaa, dashSize: 2, gapSize: 2, transparent: false, opacity:0.7 })
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
        var material = new THREE.LineDashedMaterial({ color: 0x0099ff, dashSize: 2, gapSize: 2, transparent: false, opacity:0.5  });
        var line = new THREE.Line( geometry, material );
        scene.add( line );

	//addSkybox();

	//PLASMA SPHERE
	geometrySphere = new THREE.SphereGeometry( 50 , 100, 100 );
	materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.09});

	var materialSphere = new THREE.MeshLambertMaterial({
	    color: 0xff5555,
	    emissive: 0xff5555,
	    emissiveIntensity: 0.5,
	    wireframe: true,
	    transparent: true,
	    opacity: 0.08,
	    flatShading: false
	});

	sphere0 = new THREE.Mesh( geometrySphere, materialSphere );
	scene.add( sphere0 );

        //NEUTRAL SPHERE
        geometrySphere = new THREE.SphereGeometry( $('#sphere_rad').val(), 126, 126 );
        materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.05});

	var materialSphere = new THREE.MeshLambertMaterial({
	 color: 0xffffff,
	    emissive: 0xcccccc,
	    emissiveIntensity: 0.7,
	    wireframe: true,
	    transparent: true,
	    opacity: 0.08,
	    flatShading: false
	});

        sphere1 = new THREE.Mesh( geometrySphere, materialSphere );
        scene.add( sphere1 );

        //SPHERE MON
        geometrySphere = new THREE.SphereGeometry( 155, 80, 80 );
     //   materialSphere = new THREE.MeshLambertMaterial({wireframe: true, color: 0xffffff,transparent:true,opacity:0.06});

        var materialSphere = new THREE.MeshLambertMaterial({
         color: 0xffffff,
            emissive: 0xaacccc,
            emissiveIntensity: 1,
            wireframe: true,
            transparent: true,
            opacity: 0.08,
            flatShading: false,
	    blending: THREE.NormalBlending 
        });

        sphere2 = new THREE.Mesh( geometrySphere, materialSphere );
        scene.add( sphere2 );
	
        //TORUS mon
	var geo_rad = parseInt($('#geo_rad').val());
	var geo_tub = parseInt($('#geo_tub').val());
	var geo_seg = parseInt($('#geo_seg').val());
	var geo_arc = parseInt($('#geo_arc').val());

	geo_rad = 77;
	geo_tub = 77;
	geo_seg = 128//256;
	geo_arc = 128;//256;
        torusgeometry=new THREE.TorusGeometry(geo_rad,geo_tub,geo_seg,geo_arc);
        torusmaterial=new THREE.MeshLambertMaterial({
		emissive: 0x000000,
		emissiveIntensity: 0,
		wireframe: $('#render_wireframe').is(':checked'), 
		color: 0xffffff,
		transparent:true,
		opacity:0.08,
		flatShading: false,
	});

        torus2 = new THREE.Mesh(torusgeometry,torusmaterial);

        //OBExporter();

        torus2.position.set(0,0,0);
        torus2.rotation.set(1.5,1.5,0);
        torus2.name = "Torus2";
        scene.add(torus2);

// TORUS neutral

	geo_rad = 50;
        geo_tub = 50;
        geo_seg = 128//256;
        geo_arc = 128;//256;
        torusgeometry=new THREE.TorusGeometry(geo_rad,geo_tub,geo_seg,geo_arc);


        torusmaterial=new THREE.MeshLambertMaterial({
                emissive: 0x000000,
                emissiveIntensity: 0,
                wireframe: $('#render_wireframe').is(':checked'),
                color: 0xffffff,
                transparent:true,
                opacity:0.3,
                flatShading: false,
        });

	torus1 = new THREE.Mesh(torusgeometry,torusmaterial);
	torus1.position.set(0,0,0);
        torus1.rotation.set(1.5,1.5,0);
        torus1.name = "Torus1";
        scene.add(torus1);

// TORUS plassmatic

        geo_rad = 25;
        geo_tub = 25;
        geo_seg = 128//256;
        geo_arc = 128;//256;
        torusgeometry=new THREE.TorusGeometry(geo_rad,geo_tub,geo_seg,geo_arc);


        torusmaterial=new THREE.MeshLambertMaterial({
                emissive: 0x000000,
                emissiveIntensity: 0,
                wireframe: $('#render_wireframe').is(':checked'),
                color: 0xffffff,
                transparent:true,
                opacity:0.5,
                flatShading: false,
        });

        torus0= new THREE.Mesh(torusgeometry,torusmaterial);
        torus0.position.set(0,0,0);
        torus0.rotation.set(1.5,1.5,0);
        torus0.name = "Torus0";
        scene.add(torus0);


// Torus universal

        geo_rad = 115;
        geo_tub = 115;
        geo_seg = 128//256;
        geo_arc = 128;//256;
        torusgeometry=new THREE.TorusGeometry(geo_rad,geo_tub,geo_seg,geo_arc);


        torusmaterial=new THREE.MeshLambertMaterial({
                emissive: 0x000000,
                emissiveIntensity: 0,
                wireframe: $('#render_wireframe').is(':checked'),
                color: 0xffffff,
                transparent:true,
                opacity:0.05,
                flatShading: false,
        });

        torus3= new THREE.Mesh(torusgeometry,torusmaterial);
        torus3.position.set(0,0,0);
        torus3.rotation.set(1.5,1.5,0);
        torus3.name = "Torus3";
        scene.add(torus3);


/*
var torusmaterial3 = new THREE.MeshPhysicalMaterial({
    color: 0xffffff,
    roughness: 0.5,
    metalness: 0.5,
    clearcoat: 1.0,       // Add clearcoat to make the surface glossy
    clearcoatRoughness: 0.1,
    wireframe: 0, //$('#render_wireframe').is(':checked'),
    transparent: true,
    opacity: 0.8
});
// Create the wireframe separately using EdgesGeometry and LineBasicMaterial

var torusmaterial2 = new THREE.MeshDepthMaterial({
    wireframe: $('#render_wireframe').is(':checked'),
    transparent: true,
    opacity: 0.05
});

var torusmaterial1 = new THREE.MeshNormalMaterial({
    color: 0xffffff,
     wireframe: true,
    flatShading: true,
    transparent: true,
    opacity: 0.8,
});
*/

//const edges = new THREE.EdgesGeometry(torusgeometry); // Generate wireframe/edges from geometry
//const wireframe = new THREE.LineSegments(edges, torusColormaterial);

	//OBExporter();


/*
wireframe.position.set(0,0,0);
wireframe.rotation.set(1.5,1.5,0);
scene.add(wireframe);
*/

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

function OBJExporter() {

console.log('----------------- OBJExporter-----------------');
var exporter = new THREE.OBJExporter();
var obj_data=exporter.parse( torus1 );
var data = new FormData();
data.append("data" , obj_data);
var xhr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new activeXObject("Microsoft.XMLHTTP");
xhr.open( 'post', '/sources/metamodel/tools/save.php', true );
xhr.send(data);
console.log('-----------------------------------------------');

}

function addSkybox() {

      var skyBoxGeometry = new THREE.CubeGeometry( 10000, 10000, 10000 );
        var skyBoxMaterial = new THREE.MeshBasicMaterial( { color: 0x9999ff, side: THREE.BackSide } );
        var skyBox = new THREE.Mesh( skyBoxGeometry, skyBoxMaterial );
        scene.add(skyBox);

}

function addStats() {

        // STATS
        stats = new Stats();
        stats.domElement.style.position = 'absolute';
        stats.domElement.style.bottom = '0px';
        stats.domElement.style.zIndex = 100;
        container.appendChild( stats.domElement );

}

function addLights() {

// x es y,  y es z, z es x
	var intensity = 5; //0-1 normal values, 1-10 : brighter
	var decay = 2; //realistic

        //yelow center 
        var light = new THREE.PointLight(0xffff00,intensity,500,decay*0);
        var x=0;var y=0;var z=0;
        light.position.set(x,y,z);
        //scene.add(light);

         // object
         var light = new THREE.PointLight(0x00aaff,intensity,500,decay);
        var x=0;var y=0;var z=-500;
        light.position.set(x,y,z);
        scene.add(light);

        // subject
        var light = new THREE.PointLight(0xcf70c0,intensity,500,decay);
        var x=0;var y=0;var z=500;
        light.position.set(x,y,z);
        scene.add(light);

        // teo 
         var light = new THREE.PointLight(0x3333cc,intensity*2,1500,decay*4);
        var x=500;var y=0;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

        // pra
         var light = new THREE.PointLight(0xff0000,intensity,1500,decay*4);
        var x=-500;var y=0;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

        // fen 
         var light = new THREE.PointLight(0x44ee42,intensity,500,decay);
        var x=0;var y=-500;var z=0;
        light.position.set(x,y,z);
        scene.add(light);

        // nou
        var light = new THREE.PointLight(0x6611aa,intensity,500,decay);
        var x=0;var y=500;var z=0;
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
	onaSeny=onaCrepus= [];
        //geometry.vertices = curve.getPoints( 50 );
/*
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

*/
        //ona 3
        var curve = new THREE.SplineCurve( [
                new THREE.Vector3( 0,  -80,  0 ), //top
                new THREE.Vector3( -80,  -80,   80 ),
             /*   new THREE.Vector3( 95,   0, -50 ), //right
                new THREE.Vector3( 80, -80,   0 ),
                new THREE.Vector3(  0, -90,  50 ), //bottom
                new THREE.Vector3(-80, -80,   0 ),
                new THREE.Vector3(-95,   0, -50 ), //left
                new THREE.Vector3(-80,  80,   0 ),
                new THREE.Vector3(  0,  90,  50 ) //top
*/
        ] );

      //  var geometry = new THREE.Geometry();
	 var geometry = new THREE.BufferGeometry().setFromPoints(curve.getPoints( 50 ));   
     geometry.vertices = curve.getPoints( 50 );
        var material = new THREE.LineBasicMaterial( { color : 0xff0000,transparent:true,opacity:1 } );
        onaSeny[2] = new THREE.Line( geometry, material );
        scene.add(onaSeny[2]);
/*
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
        //var geometry2 = new THREE.Geometry();
 var geometry2 = new THREE.BufferGeometry().setFromPoints(curve2.getPoints( 50 ));
        geometry2.vertices = curve2.getPoints( 50 );
        var material2 = new THREE.LineBasicMaterial( { color : 0x7777ff,transparent:true,opacity:1 } );
        onaSeny[3] = new THREE.Line( geometry2, material2 );
        scene.add(onaSeny[3]);
*/
/*
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
*/
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
	return (relative.data.pos[0]/factor);
}

function posy(percent,relative) {
        var factor=100/percent;
	return (relative.data.pos[1]/factor);
}
function posz(percent,relative) {
        var factor=100/percent;
	return (relative.data.pos[2]/factor);
}

function toCir (x,y,z) {
        //transform cartesian to circular
        a=Math.atan(y/x);
        xc = Math.sin(a)*x;
	//console.log(a);
	//console.log(x+","+y+","+z);
	var xc = Math.sin(45)*x;
	var yc = Math.sin(45)*y;
	var zc = Math.sin(45)*z;
	//console.log(xc+","+yc+","+zc);
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
       // camera.position.x = x * Math.cos(rot) + z * Math.sin(rot);
      //  camera.position.z = z * Math.cos(rot) - x * Math.sin(rot);
	break;
	case 38 : // up arrow
//	camera.position.z = camera.position.z - delta;
	break;
	case 39 : // right arrow
//	camera.position.x = x * Math.cos(rot) - z * Math.sin(rot);
 //       camera.position.z = z * Math.cos(rot) + x * Math.sin(rot);
	break;
	case 40 : //down arrow
//	camera.position.z = camera.position.z + delta;
	break;
	}
        camera.lookAt(scene.position);
}

 function decodeHtml(html) {
    return $('<textarea/>').html(html).text();
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
	var preinfo="<br>"+obj.data.title+" ("+obj.name+")";//("+obj.position.x+","+obj.position.y+","+obj.position.z+")";
	//console.log('click on '+obj.name);
	if (obj.data.type!="Plasmàtica"&&obj.data.type!="Mundana") {add2=")";add="NEUTRAL (";} else{ add=add2="";}
	preinfo+="<br><br>Type: "+add+obj.data.type+add2+"<br><br>";
	var info=obj.data.descr;
	if (obj.data.related) preinfo+="<br><br><span style='color:#999'><b>Afins:</b><br>"+obj.data.related+"</span>";
	if (mode=="edit") var postinfo="<br><br><a href='#' onclick=\"if (meta_flag<2) meta_flag++;metacat("+obj.object_id+")\">((Expandir))</a><br>";

 	if (!$('#editform').length) {
    		var add="<div id='preinfo'></div><div id='readonly' class='colortheme_dark' style='display:none'></div><div id='edit'  style='display:none'><form id='editform'><textarea class='textarea_edit' id='textarea'></textarea><br><input type='submit' value='submit'></input></form></div><div id='postinfo'></div></div>";
		//    info = "Disciplines<br><br>Teoria del coneixement, epistemologia. Materies d'estudi.";
    		$('#info').html(add);
	}
	if (mode=="edit") {
                $('#readonly').css('display','none');
                $('#edit').css('display','block');
        } else {
                $('#readonly').css('display','block');
                $('#edit').css('display','none');
        }	
	$('#preinfo').html(preinfo);
        $('#postinfo').html(postinfo);
	$('#readonly').html(info);
        $('#textarea').val(decodeHtml(info).replace(/<br\s*\/?>/gi, '\n'));
	//enable submit button
	$('#editform').off('submit');
	//meta model
	$('#editform').on('submit',function(event) {
		event.preventDefault();
      		var value = $('#textarea').val();
		console.log('crida set_metacat_descr amb meta_flag='+meta_flag+', object_id='+obj.object_id);//+', valor='+value);
		set_metacat_descr(obj.object_id, value); //meta_flag, obj.object_id, value);
		// obre la categoria
		openNav();
		setTab('info');
	});
	//points camera to object position
	//controls.target.set( intersects[0].object.position.x,intersects[0].object.position.y,intersects[0].object.position.z );
	  openNav();
	  setTab('info');
    } else {
	document.body.style.cursor = 'default';
	//var obj=intersects[0].object;
//	 var info=obj.data.descr;
	//click outside
//	$('#info').html(info);

	openNav();
	setTab('info');
    }

    var intersects2 = raycaster.intersectObjects(labels);

   if (intersects2.length > 0 && mode=='edit') {
        // Display input box over the sprite
 	  var sprite = intersects2[0].object;
	removeAllInputs();
       const input = document.createElement('input');
       const charWidth = 10;
	input.type = 'text';
        input.style.position = 'absolute';
	const inputWidth =  Math.max(sprite.name.length * charWidth, 100);
	input.style.width = inputWidth + "px";
	input.style.fontSize='16px';
        input.style.left = event.clientX + 'px';
        input.style.top = event.clientY + 'px';
	input.style.border = '1px solid #67d5a6';
	input.style.lineHeight = '30px';
	input.style.backgroundColor = 'rgba(0,0,0,0.9)';
	input.style.padding = '4px';
	input.style.textAlign = 'center';
	input.style.color = '#67d5a6';
	input.style.outline = 'none';
	input.value = sprite.name;
	input.original_value = sprite.name;
	input.label = sprite.label;
        	
        document.body.appendChild(input);

        // On pressing Enter, update the sprite text
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                updateTextSprite(sprite, input.original_value, input.value);
                document.body.removeChild(input);
            }
        });

        input.focus();
    }

}

function removeAllInputs() {
    // Select all input elements in the document
    const inputs = document.querySelectorAll('input[type=text]');

    // Loop through each input and remove it from the DOM
    inputs.forEach(input => {
        input.remove();  // Remove the input element
    });
}

function updateTextSprite(sprite, original, value) {

	console.log('updating '+original+' ('+sprite.object_id+') with value ' +value);
	set_metacat(sprite.object_id,value);

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

	console.log('loading categories '+label);
	label=label.substr(0,3);
        cat[label]=cat[label] || [];
	var textcolor;
	var label2_properties;

        for (var key in data) {

	if (label==data[key].type.slice(0,3).toLowerCase()) { //carreguem nomes les categories que corresponen al lebel demanat (num, neu o pla)

//	console.log(label+" "+key);

	var r = data[key].color[0];
	var g = data[key].color[1];
	var b = data[key].color[2];

	if (label=='pla') { r=g=b=0; properties.textcolor='255,255,255' }
	else if (label=='neu') { r=g=b=255; } 
	else if (label=='mun')  { r=g=b=0; properties.textcolor='255,255,255';  }
//console.log(data[key]);
	cat[label][key] = sprite_label = makeTextSprite( label, key, { textcolor:properties.textcolor,borderThickness: properties.borderThickness,fontsize: properties.fontsize, borderColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.borderalpha}, backgroundColor: {r:r, g:g, b:b, a:properties.bgalpha} }, data[key] );

	xlabel=data[key].pos[0]; if (xlabel>0) xlabel++; else if (xlabel<0) xlabel--;
	ylabel=data[key].pos[1]; if (ylabel>0) ylabel++; else if (ylabel<0) ylabel--;
	zlabel=data[key].pos[2]; if (zlabel>0) zlabel++; else if (zlabel<0) zlabel--;
        var scaleFactor = properties.borderThickness*3;
	//console.log(key+' : '+label);console.log(properties);
        sprite_label.scale.set(scaleFactor*1.9, scaleFactor, scaleFactor);
	sprite_label.position.set(xlabel,ylabel,zlabel);

	//label2
	if (label=='mun') label2_properties={textcolor:'116,147,185',borderThinkness:0,fontsize:70,borderalpha:1.0,bgalpha:0.8} 
	else if (label=='pla') label2_properties={textcolor:'166,77,121',borderThinkness:0,fontsize:60,borderalpha:1.0,bgalpha:0.8}
	else label2_properties={textcolor:'255,255,255',borderThinkness:0,fontsize:80,borderalpha:1.0,bgalpha:1}

        sprite_label2 = makeTextSprite2 ( label, data[key].label, data[key].title, label2_properties, data[key]);

	// desplaçament vertical i horitzontal dels labels

        const targetDirection = new THREE.Vector3().subVectors(controls.target, camera.position).normalize();
        var canvi = ajustaDespl(targetDirection);
//console.log('ajusta -> '+desplX+","+desplY);
//	 if (label!='neu') { desplY-=2; } // desplX=0; } else { desplY=7; desplX=0; } 
	if (label!="neu") increment=7; else increment=9;
 
     //   sprite_label2.scale.set(scaleFactor*1.9, scaleFactor, scaleFactor);
  
	sprite_label2.scale.set(20,4.4,1); // vegades que s'escala en x, y i z (z és 1 perquè els sprites son plans)
 
	//labels[key].position.set(labels[key].position.x-desplX,labels[key].position.y-desplY,labels[key].position.z);
	sprite_label2.position.set(xlabel-desplX,ylabel-desplY,zlabel);
	//line
	//pulsacio pla-mon
	if (data[key].type=='Mundana' && key!='COS' && key!='COV' && key!='INT' && key!='AFI' && key!='EXC' && key!='CMN') {
  		      start = new THREE.Vector3(0, 0, 0);
 		       end = new THREE.Vector3(data[key].pos[0],data[key].pos[1],data[key].pos[2]);
		        var lineGeometry = new THREE.BufferGeometry().setFromPoints([start, end]);
		        var color = Number("0x"+data[key].color[0].toString(16)+data[key].color[1].toString(16)+data[key].color[2].toString(16)+"00");
     			var lineMaterial = new THREE.LineBasicMaterial({ color: color, transparent: true, opacity: 0.8 });
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
        scene.add( sprite_label );
        objects.push(sprite_label);
        //paraula a sota
        scene.add( sprite_label2 );
        labels.push(sprite_label2);
	// categoria MON (per cada mundana)

	if (label=='mun') {
		// extemsio MON
		r=g=b=0; properties.textcolor='255,255,255';
                start = new THREE.Vector3(data[key].pos[0],data[key].pos[1],data[key].pos[2]);
                end =  new THREE.Vector3(data[key].pos[0]*1.5,data[key].pos[1]*1.5,data[key].pos[2]*1.5);
                lineGeometry = new THREE.BufferGeometry().setFromPoints([start, end]);
                color = Number("0x44444444");
                lineMaterial = new THREE.LineDashedMaterial({ color: color, transparent: true, opacity: 0.8, dashSize: 3, gapSize: 1 });
                line = new THREE.Line(lineGeometry, lineMaterial);
                line.computeLineDistances();
                scene.add(line);

		//label mon
		sprite_label = makeTextSprite( "mun", "MON", { textcolor:properties.textcolor,borderThickness: properties.borderThickness,fontsize: properties.fontsize, borderColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.borderalpha}, backgroundColor: {r:r, g:g, b:b, a:properties.bgalpha} }, data[key] );

                xlabel=data[key].pos[0]*1.5;// if (xlabel>0) xlabel++; else if (xlabel<0) xlabel--;
                ylabel=data[key].pos[1]*1.5; //if (ylabel>0) ylabel++; else if (ylabel<0) ylabel--;
                zlabel=data[key].pos[2]*1.5; //if (zlabel>0) zlabel++; else if (zlabel<0) zlabel--;
                var scaleFactor = properties.borderThickness*3;
                //console.log(key+' : '+label);console.log(properties);
                sprite_label.scale.set(scaleFactor*1.9, scaleFactor, scaleFactor);
                sprite_label.position.set(xlabel,ylabel,zlabel);
		scene.add( sprite_label );
 //               objects.push(sprite_label); 		// no l'afegim a objectes i aixi no l'eliminem
	}
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
	}
//	scene.add( particleGroup );
}

function loadCategories2(label,data,properties) {

        label=label.substr(0,3);
        cat2[label]=cat2[label] || [];
        for (var key in data) {
                properties.borderThickness=properties.borderThickness//2;//properties.borderThickness*1.2;
                cat2[label][key] = sprite_label = makeTextSprite( label, key, { textcolor:properties.textcolor,borderThickness: properties.borderThickness,fontsize: properties.fontsize, borderColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.borderalpha}, backgroundColor: {r:data[key].color[0], g:data[key].color[1], b:data[key].color[2], a:properties.bgalpha} }, data[key] );

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

function makeTextSprite( type, message, parameters, object )
{
	// categories abreviades en esferes
//console.log(object);
        if ( parameters === undefined ) parameters = {};
       
        var fontface = parameters.hasOwnProperty("fontface") ?  parameters["fontface"] : "arial";
        var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 50;
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


/*	if (type=='neu') context.fillStyle   = "rgba(255,255,255,1)";
	else if (type=='pla') context.fillStyle   = "rgba(255,100,100,1)";
	else if (type=='mun') context.fillStyle   = "rgba(100,100,255,1)";
*/ 
	if (object.generacio==1) context.fillStyle   = "rgba(255,255,255,1)";
	else if (object.generacio==1.5) context.fillStyle   = "rgba(190,255,255,1)";
	else if (object.generacio==1.75) context.fillStyle   = "rgba(255,255,190,1)";
       // border color
       // context.strokeStyle = "rgba(" + borderColor.r + "," + borderColor.g + "," + borderColor.b + "," + borderColor.a + ")";
       // context.lineWidth = borderThickness;
	
	var x = (borderThickness/2+offsetX)+50;
	var y = borderThickness/2+offsetY;
	var w = (textWidth + borderThickness + offsetX)/2;
	var h = canvas.height-bgHeightCorr;
	w = 100;
	h = w*1.25;
	roundRect(context, x, y, w, h, roundedRadius);

        // text color
        if (parameters['textcolor']==undefined) parameters['textcolor']="0,0,0";
        context.fillStyle = "rgba("+parameters['textcolor']+", 1.0)";
        context.fillText( message, borderThickness+offsetX+60, fontsize + borderThickness+offsetY+30);

        // canvas contents will be used for a texture
        var texture = new THREE.Texture(canvas)
        texture.needsUpdate = true;

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
	//console.log(message+","+object.title+" "+object.id);
	sprite.object_id=object.id; // id
	sprite.name=message;
	sprite.scale.set(9,5,2.0);
	sprite.position.set(0,0,0);
        return sprite;  
}

 // Function to wrap text
    function wrapText(context, text, x, y, maxWidth, lineHeight) {
        var words = text.split(' ');
        var line = '';
        var lines = [];
        
        for (var i = 0; i < words.length; i++) {
            var testLine = line + words[i] + ' ';
            var metrics = context.measureText(testLine);
            var testWidth = metrics.width;
            if (testWidth > maxWidth && i > 0) {
                lines.push(line);
                line = words[i] + ' ';
            } else {
                line = testLine;
            }
        }
        lines.push(line);

        for (var j = 0; j < lines.length; j++) {
            context.fillText(lines[j], x, y + j * lineHeight);
        }

        return lines.length * lineHeight;  // Return total height of all lines
    }

function makeTextSprite2( type, label, message, parameters, object )
{
	//noms de categories a sota
        if ( parameters === undefined ) parameters = {};

        var fontface = parameters.hasOwnProperty("fontface") ?  parameters["fontface"] : "arial";
        var fontsize = parameters.hasOwnProperty("fontsize") ? parameters["fontsize"] : 50;
	borderThickness=0;
        var backgroundColor = parameters.hasOwnProperty("backgroundColor") ? parameters["backgroundColor"] : { r:255, g:255, b:255, a:1.0 };
        var canvas = document.createElement('canvas');
	canvas.width = 512;//1724;//;  // Example: 512 pixels wide
	canvas.height =100;//65;//256;//786;//512;//256;  // Example: 256 pixels tal

        var context = canvas.getContext('2d');

        context.font = "Bold " + fontsize + "px " + fontface;
	
       // get size data (height depends only on font size)
        var metrics = context.measureText( message );
        var textWidth = metrics.width;

    	// Neteja el canvas (important si canvies la mida del canvas)
	context.clearRect(0, 0, canvas.width, canvas.height);
        // background color
        if (object.generacio==1) context.fillStyle   = "rgba(255,255,255,1)";
        else if (object.generacio==1.5) context.fillStyle   = "rgba(190,255,255,1)";
        else if (object.generacio==1.75) context.fillStyle   = "rgba(255,255,190,1)";
/*
// border 
var borderWidth = 5;
var borderColor = 'green';
context.lineWidth = borderWidth;  // Set the width of the border
context.strokeStyle = borderColor; // Set the color of the border
context.strokeRect(0, 0, canvas.width, canvas.height);
*/
        //context.fillStyle   = "rgba(" + backgroundColor.r + "," + backgroundColor.g + "," + backgroundColor.b + "," + backgroundColor.a + ")";

        // text color
        if (parameters['textcolor']==undefined) parameters['textcolor']="0,0,0";
        context.fillStyle = "rgba("+parameters['textcolor']+", 1.0)";

        // Calculate position to center the text horizontally
        var x = (canvas.width - textWidth) / 2;
        var y = fontsize + borderThickness; // Vertical padding for the text
        context.fillText( message, x, y);

        // canvas contents will be used for a texture
        var texture = new THREE.Texture(canvas)
        texture.needsUpdate = true;

        var spriteMaterial = new THREE.SpriteMaterial({
            map: texture,
            depthTest: false, // Disable depth test
            depthWrite: false // Disable depth write
        });

        var sprite = new THREE.Sprite( spriteMaterial );

	sprite.object_id=object.id; //per si cal
        sprite.name=message;
	sprite.label=label;
	sprite.scale.set(5000,5,1);
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

function ajustaDespl(targetDirection) {
        //console.log(targetDirection);
        var canvi=false;
        if (targetDirection.x<-0.5 && lookat!='up') { // vista pol nord
                lookat='up'; canvi=true;
                desplY=increment; desplX=0;
        }  else if (targetDirection.x>0.5 && lookat!='down') {
                lookat='down'; canvi=true;
                desplY=-increment; desplX=0;
        }  else if (targetDirection.x>=-0.5 && targetDirection.x<=0.5 && lookat!='normal') {
                if (lookat=='down') desplY=increment;
                else if (lookat=='up') desplY=-increment;
                lookat='normal'; canvi=true;
		if (desplX==0 && desplY==0) { desplX=increment; desplY=0; }
        }
	console.log(desplX+","+desplY);
        return canvi;
}

function animate() 
{

   //covert 60 fps to 30 fps
  //  setTimeout( function() {

        requestAnimationFrame( animate );

        // Evita el "roll" (gir d'eix Y sobre Z) reajustant la càmera
        const up = new THREE.Vector3(1, 0, 0);
        const targetDirection = new THREE.Vector3().subVectors(controls.target, camera.position).normalize();
        const right = new THREE.Vector3().crossVectors(up, targetDirection).normalize();
        const cameraUp = new THREE.Vector3().crossVectors(targetDirection, right);
        camera.up.copy(cameraUp);  // manté els eixos verticals

	var canvi = ajustaDespl(targetDirection);
	if (canvi) {
		console.log(lookat);
		//actualitzem posicions de labels
		Object.keys(labels).forEach(key => {
		    if (labels[key] instanceof THREE.Sprite) {
		        labels[key].position.set(labels[key].position.x-desplX,labels[key].position.y-desplY,labels[key].position.z);
		    }
		});
	}

        camera.lookAt(controls.target);

        update();
        render();         
	
//    }, 1000 / 30 );
}

function update()
{
        controls.update();
//      stats.update();
}

function render() 
{
        renderer.render(scene, camera);
}


