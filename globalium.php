<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Globalium</title>
    <style>
        body { margin: 0; }
        canvas { display: block; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700&family=Scope+One&display=swap" rel="stylesheet">
</head>
<body>
    <script src="js/threejs/build/three.js"></script>
    <script src="js/master/examples/js/controls/TrackballControls.js"></script>
    <script src="js/OrbitControls.js"></script>
    <script>
        // Basic setup
        let pointLight;
	let isPaused = false;
	let pauseTimeout;
	let hideValue = 0.1; //Sprites transparency

        const scene = new THREE.Scene();
        const aspect = window.innerWidth / window.innerHeight;
        const frustumSize = 15;

        // CAMERA
  /*      var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
        var VIEW_ANGLE = 20, ASPECT = SCREEN_WIDTH / SCREEN_HEIGHT, NEAR = 0.1, FAR = 1000;
	camera = new THREE.PerspectiveCamera( VIEW_ANGLE, ASPECT, NEAR, FAR);
        camera.zoom=0.4;
        camera.position.set(0,0,0);
        camera.lookAt(scene.position);
*/


const vertexShader = `
    varying vec3 vPosition;
    void main() {
        vPosition = position;
        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
    }
`;

const fragmentShader = `
    uniform vec3 color;
    varying vec3 vPosition;
    void main() {
        // Aplica l'efecte de multiplicació de color
        vec3 multipliedColor = color * vec3(0.7,0.7,0.7); // Adjust the multiplier as needed
        gl_FragColor = vec4(multipliedColor, 0.5); // Adjust the opacity as needed
    }
`;

        const camera = new THREE.OrthographicCamera(
            frustumSize * aspect / -2,
            frustumSize * aspect / 2,
            frustumSize / 2,
            frustumSize / -2,
            0.1,
            1000
        );

	var cameraDirection = new THREE.Vector3();

        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
	renderer.shadowMap.enabled = false; // Enable shadows
//	renderer.shadowMap.type = THREE.PCFSoftShadowMap; // Optionally use soft shadows
        document.body.appendChild(renderer.domElement);

        // Load background image
        const loader = new THREE.TextureLoader();
        let backgroundScene, backgroundCamera;

        loader.load('https://labs.opengea.org/metamodeler/v1/img/8k_stars.jpg', function (texture) {
            const backgroundMesh = new THREE.Mesh(
                new THREE.PlaneGeometry(2, 2),
                new THREE.MeshBasicMaterial({
                    map: texture
                })
            );

            backgroundMesh.material.depthTest = false;
            backgroundMesh.material.depthWrite = false;

            backgroundScene = new THREE.Scene();
            backgroundCamera = new THREE.Camera();
            backgroundScene.add(backgroundCamera);
            backgroundScene.add(backgroundMesh);
        });



        // OrbitControls
        const controls = new THREE.TrackballControls(camera, renderer.domElement);
        controls.rotateSpeed = 4;
        controls.zoomSpeed = 0.1;
        controls.panSpeed = 0.8;
	controls.enableRotate = true;
        controls.enableZoom = true;
        controls.noPan = true;
controls.noZoom = true;
/*        controls.noRotate = false;
        controls.noZoom = false;
        controls.noPan = true;
        controls.staticMoving = false;
        controls.dynamicDampingFactor = 0.3;
        controls.keys = [65, 83, 68]; // A, S, D
*/
controls.keys = [65, 83, 68]; // A, S, D

        // Defineix l'objectiu dels controls
        controls.target.set(0, 0, 0);

        // Touch gestures support
  /*      controls.touches = {
            ONE: THREE.TOUCH.ROTATE,
            TWO: THREE.TOUCH.DOLLY_PAN
        };
*/

        // keys
        window.addEventListener( 'keydown', onDocumentKeyDown, false );

        // Add event listener for resizing the window
        window.addEventListener('resize', onWindowResize, false);

        // Update camera and renderer on window resize
        function onWindowResize() {
            const aspect = window.innerWidth / window.innerHeight;
            camera.left = -frustumSize * aspect / 2;
            camera.right = frustumSize * aspect / 2;
            camera.top = frustumSize / 2;
            camera.bottom = -frustumSize / 2;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);

	    var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
/* 
            camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
            camera.updateProjectionMatrix();
            renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
*/
            if (SCREEN_WIDTH < 400) camera.zoom = 0.7; else camera.zoom = 1;

       }

        // Add event listeners for zoom using touch pinch
        let touchStartDistance = 0;
        let initialZoom = camera.zoom;


function pauseRotation() {
    isPaused = true;
    clearTimeout(pauseTimeout);
    pauseTimeout = setTimeout(() => {
        isPaused = false;
    }, 10000); // 10 segons
}

// Function to update rotateSpeed based on camera direction
function updateRotateSpeed() {
    // Get the direction the camera is facing
    camera.getWorldDirection(cameraDirection);

    // Convert the direction to spherical coordinates
    var spherical = new THREE.Spherical().setFromVector3(cameraDirection);

    // Calculate latitude from the spherical coordinates
    var latitude = THREE.MathUtils.radToDeg(spherical.phi) - 90;

    // Adjust rotateSpeed based on latitude
    if (Math.abs(latitude) > 85) {
        // Near the poles
        rotateSpeed = 1;
	updateLabelVisibility(0);
    } else {
        // Near the equator
        rotateSpeed = 4;
	updateLabelVisibility(0.1);
    }

    // Update the controls rotation speed
    controls.rotateSpeed = rotateSpeed;
}


        function getTouchDistance(event) {
            const dx = event.touches[0].pageX - event.touches[1].pageX;
            const dy = event.touches[0].pageY - event.touches[1].pageY;
            return Math.sqrt(dx * dx + dy * dy);
        }

	window.addEventListener('click', pauseRotation, false);

        window.addEventListener('touchstart', function(event) {
            if (event.touches.length === 2) {
                touchStartDistance = getTouchDistance(event);
                initialZoom = camera.zoom;
            }
		pauseRotation();
        });

        window.addEventListener('touchmove', function(event) {
            if (event.touches.length === 2) {
                const touchMoveDistance = getTouchDistance(event);
                camera.zoom = initialZoom * (touchMoveDistance / touchStartDistance);
                camera.updateProjectionMatrix();
            }
        });

        // Add event listener for zoom using mouse wheel
        window.addEventListener('wheel', function (event) {
            if (event.deltaY < 0) {
                camera.zoom *= 1.1;
            } else if (event.deltaY > 0) {
                camera.zoom /= 1.1;
            }

console.log(camera.zoom);
            camera.updateProjectionMatrix();
        });

        // Inicial setup
        onWindowResize();

        // Define colors for the six spots
        const colors = [
            new THREE.Color(0x00FFFF), // North (Cyan)
            new THREE.Color(0xFF0000), // South (Red)
            new THREE.Color(0x0000FF), // East (Blue)
            new THREE.Color(0x00FF00), // West (Green)
            new THREE.Color(0xFFFF00), // Front (Yellow)
            new THREE.Color(0xFF00FF)  // Rear (Magenta)
        ];

        // Sphere geometry and material
        const geometry = new THREE.SphereGeometry(5, 64, 64);

	// textured material
	const textureLoader = new THREE.TextureLoader();
<?
// Especifica el directori que vols llegir
$directory = './img';
$filesAndFolders = scandir($directory);
$files = array_diff($filesAndFolders, array('.', '..'));
$files = array_values($files);
$material=1;
if (isset($_GET['b'])) { $pic=$files[$_GET['b']]; $material=2; }
?>

        const texture=textureLoader.load('https://labs.opengea.org/metamodeler/v1/img/<?=$pic?>');

	const material2 = new THREE.MeshPhongMaterial({
	    map: texture,
	    side: THREE.DoubleSide,
	    transparent: false, // Desactivar la transparència
	    wireframe: false    // Desactivar el mode wireframe
	});

	// color material
        const material1 = new THREE.ShaderMaterial({
            uniforms: {
                colors: { value: colors }
            },
            vertexShader: `
                varying vec3 vPosition;
                void main() {
                    vPosition = position;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
            fragmentShader: `
                uniform vec3 colors[6];
                varying vec3 vPosition;
                void main() {
                    vec3 p = normalize(vPosition);
                    float northWeight = clamp(p.y, 0.0, 1.0);
                    float southWeight = clamp(-p.y, 0.0, 1.0);
                    float eastWeight = clamp(p.x, 0.0, 1.0);
                    float westWeight = clamp(-p.x, 0.0, 1.0);
                    float frontWeight = clamp(p.z, 0.0, 1.0);
                    float rearWeight = clamp(-p.z, 0.0, 1.0);

                    vec3 color = 
                        northWeight * colors[0] + 
                        southWeight * colors[1] + 
                        eastWeight * colors[2] +
                        westWeight * colors[3] +
                        frontWeight * colors[4] +
                        rearWeight * colors[5]; 

                    gl_FragColor = vec4(color, 1); // Set alpha to 1 for no transparency
                }
            `,
            side: THREE.DoubleSide,
            transparent: false, // Disable transparency
            wireframe: false    // Disable wireframe mode
        });

        const sphere = new THREE.Mesh(geometry, material<?=$material?>);
	sphere.castShadow = false; // Sphere casts shadow
	sphere.receiveShadow = false; // Sphere receives shadow
	sphere.renderOrder = 1;
        scene.add(sphere);

        // Lights
        ambientLight = new THREE.AmbientLight(0xcccccc); // Llum suau
	 ambientLight.castShadow = true; 
       scene.add(ambientLight);


        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLight.position.set(10, 10, 10); // Posició fixa de la llum
	directionalLight.castShadow = true; // Light casts shadow
	scene.add(directionalLight);


     pointLight = new THREE.PointLight(0xffffff, 3, 20);
        pointLight.position.set(5, 5, 5); // Posició fixa de la llum puntual
	pointLight.castShadow = true; 
  //     scene.add(pointLight);

/*
// Afegir un llum omnidireccional al centre de l'esfera
const pointLightCenter = new THREE.PointLight(0xffffff, 1, 100);
pointLightCenter.position.set(0, 0, 0);
scene.add(pointLightCenter);
*/
        camera.position.set(0, 0, 15);
        camera.lookAt(0, 0, 0);

        // Helper function to create an orbit
        function createOrbit(radius, segments, axis) {
            const geometry = new THREE.BufferGeometry();
            const positions = [];

            for (let i = 0; i <= segments; i++) {
                const theta = (i / segments) * Math.PI * 2;
                const x = radius * Math.cos(theta);
                const y = radius * Math.sin(theta);
                positions.push(x, y, 0);
            }

            geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));


      //      const material = new THREE.LineBasicMaterial({ color: 0x555544, linewidth: 2 });

     var color;
//const color='0x555555';
    const material = new THREE.ShaderMaterial({
        uniforms: {
            color: { value: new THREE.Color(color) }
        },
        vertexShader: vertexShader,
        fragmentShader: fragmentShader,
        transparent: true, // Enable transparency
        depthTest: false // Allow lines to be visible through other objects
    });
            const orbit = new THREE.LineLoop(geometry, material);
            orbit.rotation.set(axis.x, axis.y, axis.z);
            scene.add(orbit);
        }

        // Create the orbits with more segments for smoother lines
<? if ($material==1) { ?>
        createOrbit(5.5, 128, new THREE.Vector3(Math.PI / 2, 0, 0)); // Equador
        createOrbit(5.5, 128, new THREE.Vector3(0, 0, 0)); // Transversal
        createOrbit(5.5, 128, new THREE.Vector3(0, Math.PI / 2, 0)); // Vertical
<? } ?>
        // Paral·lels - tropics / Helper function to create a circle orbiting the north pole and slightly above the sphere
        function createOrbitingCircle(radius, distanceFromSurface, verticalDistance, segments) {
            const positions = [];

            for (let i = 0; i <= segments; i++) {
                const theta = (i / segments) * Math.PI * 2;
                const x = radius * Math.cos(theta);
                const y = radius * Math.sin(theta);
                positions.push(x, y, verticalDistance);
            }

            const geometry = new THREE.BufferGeometry();
            geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));

/*            const material = new THREE.LineDashedMaterial({
                color: 0x444444,
                linewidth: 5,
                dashSize: 0.1,
                gapSize: 0.1
            });
*/
var color;
//const color='0x555555';
    const material = new THREE.ShaderMaterial({
        uniforms: {
            color: { value: new THREE.Color(color) }
        },
        vertexShader: vertexShader,
        fragmentShader: fragmentShader,
        transparent: true, // Enable transparency
        depthTest: false // Allow lines to be visible through other objects
    });

            const circle = new THREE.LineLoop(geometry, material);
            circle.position.set(0, radius + distanceFromSurface, 0); // Position it slightly above the sphere
            circle.rotation.x = Math.PI / 2; // Rotate to be parallel to the x-z plane
            circle.computeLineDistances(); // Necessary for dashed lines

            scene.add(circle);
        }
<? if ($material==1) { ?>

        // Create an orbiting circle with a specific distance from the sphere surface
        createOrbitingCircle(4.2, 1.5, 2.7, 64);
        createOrbitingCircle(4.2, 1.4, 8.5, 64);
<? } ?>
        // Create canvas-based text labels
function createTextLabel(text, position, color, fontSize = '26px', distanceFromSurface = 0) {
    // Create and position the white circle at the same location
    createSpot(position, 0.2, 0.05, distanceFromSurface); // Adjust the radius and distance as needed
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    context.font = `${fontSize} 'Roboto'`;
    const textWidth = context.measureText(text).width;
    canvas.width = textWidth + 20; // Add some padding
    canvas.height = parseInt(fontSize) + 20; // Font size + some padding
    context.font = `bold ${fontSize} 'Roboto'`;
    context.textBaseline = 'top';
    context.lineJoin = 'round';
    context.lineWidth = 0; // Border thickness

    // Draw text
    context.fillStyle = color;
    context.fillText(text, 10, 10); // Slightly offset the text to leave space for the border

    const texture = new THREE.CanvasTexture(canvas);
    const spriteMaterial = new THREE.SpriteMaterial({
        map: texture,
        depthTest: false,  // Disable depth test for sprites
        transparent: true  // Enable transparency
    });
    const sprite = new THREE.Sprite(spriteMaterial);
    sprite.scale.set(canvas.width / 100, canvas.height / 100, 1); // Adjust the scale

var topCat    = ['TEO'];
 var bottomCat = ['PRA'];
    if (topCat.includes(text)) position.z+=0.4; else if (bottomCat.includes(text)) position.z-=0.4;  else position.y-=0.4;
    sprite.position.copy(position);
    if (topCat.includes(text)) position.z-=0.4; else if (bottomCat.includes(text)) position.z+=0.4; else position.y+=0.4;
    sprite.userData = { text: text, originalPosition: position.clone(), originalOpacity: 1 }; // Store original opacity
    sprite.renderOrder = 3; // Ensure sprites are drawn after circles and sphere
    scene.add(sprite);

    return sprite;
}

        // Add main labels
        const radius = 5;
        const posTEO = new THREE.Vector3(0, radius, 0);    // North
        const posPRA = new THREE.Vector3(0, -radius, 0);   // South
        const posOBJ = new THREE.Vector3(radius, 0, 0);    // East
        const posSUB = new THREE.Vector3(-radius, 0, 0);   // West
        const posFEN = new THREE.Vector3(0, 0, radius);    // Front
        const posNOU = new THREE.Vector3(0, 0, -radius);   // Rear

        const labels = [];
        labels.push(createTextLabel('TEO', posTEO, 'black'));
        labels.push(createTextLabel('PRA', posPRA, 'black'));
        labels.push(createTextLabel('OBJ', posOBJ, 'black'));
        labels.push(createTextLabel('SUB', posSUB, 'black'));
        labels.push(createTextLabel('FEN', posFEN, 'black'));
        labels.push(createTextLabel('NOU', posNOU, 'black'));

        // Add extra labels in 36px
        const posSTT = midpointOnSurface(posSUB, posTEO, radius);
        const posSTM = midpointOnSurface(posSUB, posPRA, radius);
        const posSGE = midpointOnSurface(posOBJ, posPRA, radius);
        const posSGT = midpointOnSurface(posOBJ, posTEO, radius);
        const posANA = midpointOnSurface(posFEN, posTEO, radius);
        const posSIN = midpointOnSurface(posNOU, posTEO, radius);
        const posEXP = midpointOnSurface(posFEN, posPRA, radius);
        const posAMO = midpointOnSurface(posNOU, posPRA, radius);
        const posCIE = midpointOnSurface(posFEN, posOBJ, radius);
        const posART = midpointOnSurface(posFEN, posSUB, radius);
        const posMTF = midpointOnSurface(posNOU, posOBJ, radius);
        const posMTP = midpointOnSurface(posNOU, posSUB, radius);

        labels.push(createTextLabel('STT', posSTT, 'black', '26px'));
        labels.push(createTextLabel('STM', posSTM, 'black', '26px'));
        labels.push(createTextLabel('SGE', posSGE, 'black', '26px'));
        labels.push(createTextLabel('SGT', posSGT, 'black', '26px'));
        labels.push(createTextLabel('ANA', posANA, 'black', '26px'));
        labels.push(createTextLabel('SIN', posSIN, 'black', '26px'));
        labels.push(createTextLabel('EXP', posEXP, 'black', '26px'));
        labels.push(createTextLabel('AMO', posAMO, 'black', '26px'));
        labels.push(createTextLabel('CIE', posCIE, 'black', '26px'));
        labels.push(createTextLabel('ART', posART, 'black', '26px'));
        labels.push(createTextLabel('MTF', posMTF, 'black', '26px'));
        labels.push(createTextLabel('MTP', posMTP, 'black', '26px'));

        // Add complex labels
        const posLOG = midpoint3OnSurface(posTEO, posOBJ, posFEN, radius);
        const posEST = midpoint3OnSurface(posTEO, posSUB, posFEN, radius);
        const posMIT = midpoint3OnSurface(posTEO, posSUB, posNOU, radius);
        const posIDE = midpoint3OnSurface(posTEO, posOBJ, posNOU, radius);
        const posTEC = midpoint3OnSurface(posPRA, posOBJ, posFEN, radius);
        const posPSI = midpoint3OnSurface(posPRA, posSUB, posFEN, radius);
        const posMIS = midpoint3OnSurface(posPRA, posSUB, posNOU, radius);
        const posETI = midpoint3OnSurface(posPRA, posOBJ, posNOU, radius);

        labels.push(createTextLabel('LOG', posLOG, 'black', '26px'));
        labels.push(createTextLabel('EST', posEST, 'black', '26px'));
        labels.push(createTextLabel('MIT', posMIT, 'black', '26px'));
        labels.push(createTextLabel('IDE', posIDE, 'black', '26px'));
        labels.push(createTextLabel('TEC', posTEC, 'black', '26px'));
        labels.push(createTextLabel('PSI', posPSI, 'black', '26px'));
        labels.push(createTextLabel('MIS', posMIS, 'black', '26px'));
        labels.push(createTextLabel('ETI', posETI, 'black', '26px'));

function updateLabelVisibility(visibility) {
	hideValue = visibility;
    labels.forEach(sprite => {
        // Obtenim la direcció cap a la càmera
        const spriteWorldPosition = new THREE.Vector3().setFromMatrixPosition(sprite.matrixWorld);
        const directionToCamera = camera.position.clone().sub(spriteWorldPosition).normalize();
        const directionToSprite = spriteWorldPosition.clone().normalize();

        // Calculem el punt escalar
        const dot = directionToCamera.dot(directionToSprite);
        // Ajustem l'opacitat basant-nos en la visibilitat

        sprite.material.opacity = dot < -0.2 ? hideValue : sprite.userData.originalOpacity;
        sprite.material.needsUpdate = true; // Assegurem que l'actualització del material s'aplica

    });
}

        // Sort and render scene
        function render() {
            if (backgroundScene && backgroundCamera) {
                renderer.autoClear = false; // Ensure renderer does not clear automatically
                renderer.clear(); // Clear manually
                renderer.render(backgroundScene, backgroundCamera); // Render background scene
            }
    
            // Sort the scene by distance from the camera
            scene.children.sort((a, b) => {
                const distanceA = camera.position.distanceTo(a.position);
                const distanceB = camera.position.distanceTo(b.position);
                return distanceB - distanceA;
            });

            renderer.render(scene, camera); // Render main scene
        }

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            updateRotateSpeed();

            // Mantenir eix vertical del model (TEO-PRA)
            const up = new THREE.Vector3(0, 1, 0);
            const targetDirection = new THREE.Vector3().subVectors(controls.target, camera.position).normalize();
            const right = new THREE.Vector3().crossVectors(up, targetDirection).normalize();
            const cameraUp = new THREE.Vector3().crossVectors(targetDirection, right);
            camera.up.copy(cameraUp);

            camera.lookAt(controls.target);

            controls.update();
            updateLabelVisibility(hideValue);
	   updateTextLabelPositions(hideValue);
  	    //updateSpotsOrientation();
            // Rotar només si no està pausat
            if (!isPaused) {
                scene.rotation.y += 0.001; // Ajusta la velocitat de rotació segons sigui necessari
	    } 

	
  // Calcular la direcció contrària a la rotació de l'escena
   // const lightDistance = 10; // Ajusta la distància del punt de llum

    // Desplaçar el pointLight en la direcció contrària a la rotació
 //   pointLight.position.x -= 0.02;
//	 pointLight.position.y = 0;
//	 pointLight.position.z=20;
	    // Actualitza la posició del pointLight per ser igual a la de la càmera
//	    pointLight.position.copy(camera.position);
	
    
            render();
        }

        animate();

        // Helper functions
        function midpointOnSurface(p1, p2, radius) {
            const midpoint = new THREE.Vector3(
                (p1.x + p2.x) / 2,
                (p1.y + p2.y) / 2.7,   //distancia rectificada per facilitat d'us
                (p1.z + p2.z) / 2
            );
            return midpoint.normalize().multiplyScalar(radius);
        }

        function midpoint3OnSurface(p1, p2, p3, radius) {
            const midpoint = new THREE.Vector3(
                (p1.x + p2.x + p3.x) / 3,
                (p1.y + p2.y + p3.y) / 3,
                (p1.z + p2.z + p3.z) / 3
            );
            return midpoint.normalize().multiplyScalar(radius);
        }


function updateSpotsOrientation() {
    scene.traverse(function (object) {
        if (object.userData.originalPosition) {

            // Mantenir la posició adherida a la superfície de l'esfera
            const sphereRadius = 5;
            const distanceFromSurface = 0.01;
            const updatedPosition = object.userData.originalPosition.clone().normalize().multiplyScalar(sphereRadius + distanceFromSurface);
            object.position.copy(updatedPosition);

            // Determinar si el `Spot` està davant o darrere de l'esfera
            const cameraDirection = new THREE.Vector3();
            camera.getWorldDirection(cameraDirection);
            const dotProduct = cameraDirection.dot(updatedPosition.normalize());

            if (dotProduct > 0) {
                // Si el `Spot` està davant de la càmera, orientar cap a fora de l'esfera
                const lookAtVector = updatedPosition.clone();
                object.lookAt(object.position.clone().add(lookAtVector));
            } else {
                // Si el `Spot` està darrere de la càmera, orientar cap a dins de l'esfera
                const lookAtVector = updatedPosition.clone().multiplyScalar(-1);
                object.lookAt(lookAtVector);
            }
        }
    });
}

function updateTextLabelPositions(hideValue) {
    labels.forEach(sprite => {
        const text = sprite.userData.text;
        const position = sprite.userData.originalPosition.clone();
        
        let topCat, bottomCat;
        if (hideValue == 0) {
            topCat = ['TEO', 'ANA', 'SIN', 'STT', 'SGT', 'IDE', 'MIT', 'EST', 'LOG'];
            bottomCat = ['PRA', 'AMO', 'EXP', 'STM', 'SGE', 'ETI', 'MIS', 'PSI', 'TEC'];
        } else {
            topCat = ['TEO'];
            bottomCat = ['PRA'];
        }

        if (topCat.includes(text)) position.z += 0.4;
        else if (bottomCat.includes(text)) position.z -= 0.4;
        else position.y -= 0.4;
        
        sprite.position.copy(position);

        if (topCat.includes(text)) position.z -= 0.4;
        else if (bottomCat.includes(text)) position.z += 0.4;
        else position.y += 0.4;
    });
}

/*
        function updateSpotsOrientation() {

 scene.traverse(function (object) {
        if (object.userData.surfaceNormal) {

    // Align the circle and its border to the surface normal
    const lookAtVector = normalizedPosition.clone().multiplyScalar(-1);
    circle.lookAt(lookAtVector);
    borderCircumference.lookAt(lookAtVector);
    circle.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface
    borderCircumference.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface

        }
    });
        }

*/

/*
function updateSpotsOrientation() {
    scene.traverse(function (object) {
        if (object.userData.surfaceNormal) {
            object.lookAt(camera.position);
        }
    });


}
*/
function createSpot(position, radius = 0.001, borderThickness = 0.001, distanceFromSurface = 0.01) {
    // Crear la geometria i material per al cercle principal
    const geometry = new THREE.CircleGeometry(0.14, 32);
    const material = new THREE.MeshBasicMaterial({
        color: 0xffffff,
        transparent: true,
        opacity: 0.9,
        depthTest: false, // Ensure circles are always visible
        depthWrite: false // Prevent circles from affecting the depth buffer
    });

    const circle = new THREE.Mesh(geometry, material);

    // Crear la geometria i material per a la circumferència de la vora
    const borderGeometry = new THREE.RingGeometry(0.18, 0.2, 32);
    const borderMaterial = new THREE.MeshBasicMaterial({
        color: 0xffffff, // Color de la vora (blanc en aquest cas)
        transparent: true,
        opacity: 0.9,
        depthTest: false, // Ensure circles are always visible
        depthWrite: false // Prevent circles from affecting the depth buffer
    });

    const borderCircumference = new THREE.Mesh(borderGeometry, borderMaterial);

    // Normalize the position to adjust it to the surface of the sphere
    const sphereRadius = 5;
    const normalizedPosition = position.clone().normalize().multiplyScalar(sphereRadius + distanceFromSurface); // Adjust distance from surface

    // Copiar la posició normalitzada al cercle i a la seva vora
    circle.position.copy(normalizedPosition);
    borderCircumference.position.copy(normalizedPosition);

    // Align the circle and its border to the surface normal
    const lookAtVector = normalizedPosition.clone();
    circle.lookAt(circle.position.clone().add(lookAtVector));
    borderCircumference.lookAt(borderCircumference.position.clone().add(lookAtVector));
    circle.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface
    borderCircumference.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface

    // Assign originalPosition to userData for both circle and borderCircumference
    circle.userData.originalPosition = normalizedPosition.clone();
    borderCircumference.userData.originalPosition = normalizedPosition.clone();

    // Afegir el cercle i la seva vora a l'escena
    scene.add(borderCircumference);
    scene.add(circle);
}


function onDocumentKeyDown ( event ) {
	pauseRotation();

        rot = 0.005;
        delta = 5;
        var x = camera.position.x,
            y = camera.position.y,
            z = camera.position.z;

//      controls.target.set(0,0,0);
        event = event || window.event;

        var keycode = event.keyCode;
        switch(keycode){
        case 37 : //left arrow
        camera.position.x = x * Math.cos(rot) + z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) - x * Math.sin(rot);
        break;
        case 38 : // up arrow
	camera.position.y = y * Math.cos(rot) + z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) - y * Math.sin(rot);
        break;
        case 39 : // right arrow
        camera.position.x = x * Math.cos(rot) - z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) + x * Math.sin(rot);
        break;
        case 40 : //down arrow
        camera.position.y = y * Math.cos(rot) - z * Math.sin(rot);
        camera.position.z = z * Math.cos(rot) + y * Math.sin(rot);
        break;
        }
        camera.lookAt(scene.position);
}

        // Inicial setup
        onWindowResize();
    </script>
</body>
</html>

