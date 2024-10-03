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
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Scope+One&display=swap" rel="stylesheet">
</head>
<body>
    <script src="js/threejs/build/three.js"></script>
    <script src="js/master/examples/js/controls/TrackballControls.js"></script>
    <script src="js/OrbitControls.js"></script>
    <script>
        // Basic setup
	let pointLight;
        const scene = new THREE.Scene();
        const aspect = window.innerWidth / window.innerHeight;
        const frustumSize = 12;
        const camera = new THREE.OrthographicCamera(
            frustumSize * aspect / -2, 
            frustumSize * aspect / 2, 
            frustumSize / 2, 
            frustumSize / -2, 
            0.1, 
            1000
        );

        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // OrbitControls
        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableZoom = true;  // Enable zoom
        controls.zoomSpeed = 1.2;    // Adjust the zoom speed if needed
        controls.enableDamping = true;
        controls.dampingFactor = 0.25;

        // Touch gestures support
        controls.touches = {
            ONE: THREE.TOUCH.ROTATE,
            TWO: THREE.TOUCH.DOLLY_PAN
        };

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
        }

// Add event listener for zoom using mouse wheel
window.addEventListener('wheel', function(event) {
    if (event.deltaY < 0) {
        camera.zoom *= 1.1;
    } else if (event.deltaY > 0) {
        camera.zoom /= 1.1;
    }
    camera.updateProjectionMatrix();
});


        // Inicial setup
        onWindowResize();

        // Define colors for the six spots
        const colors = [
            new THREE.Color(0x00FFFF), // North (Cyan)
            new THREE.Color(0xFF0000), // South (Red)
            new THREE.Color(0xFF00FF), // East (Blue)
            new THREE.Color(0xFFFF00), // West (Green)
            new THREE.Color(0x0000FF), // Front (Yellow)
            new THREE.Color(0x00FF00)  // Rear (Magenta)
        ];

        // Sphere geometry and material
        const geometry = new THREE.SphereGeometry(5, 64, 64);


// Crea una textura de color personalitzada
const size = 64; // Mida de la textura
const data = new Uint8Array(size * size * size * 3);
//const texture = createColorTexture(colors, size); //new THREE.DataTexture3D(data, size, size, size);
//const texture = new THREE.DataTexture3D(data, size, size, size);

        // Carrega la textura
        const textureLoader = new THREE.TextureLoader();
        const texture=textureLoader.load('https://labs.opengea.org/metamodeler/v1/img/8k_moon.jpg');

// Crear el material MeshPhongMaterial
const material = new THREE.MeshPhongMaterial({
    map: texture,
    side: THREE.DoubleSide,
    transparent: false, // Desactivar la transparència
    wireframe: false    // Desactivar el mode wireframe
});


function createColorTexture(colors, size) {
    const canvas = document.createElement('canvas');
    canvas.width = size;
    canvas.height = size;
    const context = canvas.getContext('2d');

    const imageData = context.createImageData(size, size);
    const data = imageData.data;

    for (let y = 0; y < size; y++) {
        for (let x = 0; x < size; x++) {
            const index = (y * size + x) * 4;
            const p = new THREE.Vector3(x / size - 0.5, y / size - 0.5, 0).normalize();

            const northWeight = Math.max(0, p.y);
            const southWeight = Math.max(0, -p.y);
            const eastWeight = Math.max(0, p.x);
            const westWeight = Math.max(0, -p.x);
            const frontWeight = Math.max(0, p.z);
            const rearWeight = Math.max(0, -p.z);

            const color = new THREE.Color(0x000000);
            color.r = colors[0].r * northWeight + colors[1].r * southWeight +
                      colors[2].r * eastWeight + colors[3].r * westWeight +
                      colors[4].r * frontWeight + colors[5].r * rearWeight;
            color.g = colors[0].g * northWeight + colors[1].g * southWeight +
                      colors[2].g * eastWeight + colors[3].g * westWeight +
                      colors[4].g * frontWeight + colors[5].g * rearWeight;
            color.b = colors[0].b * northWeight + colors[1].b * southWeight +
                      colors[2].b * eastWeight + colors[3].b * westWeight +
                      colors[4].b * frontWeight + colors[5].b * rearWeight;

            data[index] = color.r * 255;
            data[index + 1] = color.g * 255;
            data[index + 2] = color.b * 255;
            data[index + 3] = 255; // alpha
        }
    }

    context.putImageData(imageData, 0, 0);

    return new THREE.CanvasTexture(canvas);
}

        const sphere = new THREE.Mesh(geometry, material);
        scene.add(sphere);


// Lights
            // Crear una llum ambiental com a objecte mòbil
            ambientLight = new THREE.AmbientLight(0x444444); // Llum suau
          scene.add(ambientLight);

            // Afegir llum direccional fixa
            const directionalLight = new THREE.DirectionalLight(0xff0000, 1);
           directionalLight.position.set(6, 0, 0); // Posició fixa de la llum
     // scene.add(directionalLight);

var decay=20;
            // Afegir llum puntual fixa
            pointLight = new THREE.PointLight(0xffffff, 2, 100, 5);
            pointLight.position.set(5, 5, 5); // Posició fixa de la llum puntual
         scene.add(pointLight);

            pointLight2 = new THREE.PointLight(0x0000ff, 1, 100, decay);
            pointLight2.position.set(9, 0, 0); // Posició fixa de la llum puntual
           scene.add(pointLight2);

         pointLight2 = new THREE.PointLight(0xff00ff, 1, 100, decay);
            pointLight2.position.set(0, 0, -9); // Posició fixa de la llum puntual
           scene.add(pointLight2);


         pointLight2 = new THREE.PointLight(0x00ff00, 1, 100, decay);
            pointLight2.position.set(-9, 0, 0); // Posició fixa de la llum puntual
           scene.add(pointLight2);

         pointLight2 = new THREE.PointLight(0xffff00, 2, 100, decay);
            pointLight2.position.set(0, 0, 9); // Posició fixa de la llum puntual
           scene.add(pointLight2);

         pointLight2 = new THREE.PointLight(0x00ffff, 2, 100, decay);
            pointLight2.position.set(0, 9, 0); // Posició fixa de la llum puntual
           scene.add(pointLight2);

         pointLight2 = new THREE.PointLight(0xff0000, 3, 100, decay);
            pointLight2.position.set(0, -22, 0); // Posició fixa de la llum puntual
           scene.add(pointLight2);

        camera.position.set(0, 0, 15);
        camera.lookAt(0, 0, 0);

	//orbits

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
       //     const material = new THREE.LineBasicMaterial({ color: 0x000000, linewidth: 5 });
	    const material = new THREE.LineDashedMaterial({ 
	        color: 0x444444, 
	        linewidth: 5, 
	        dashSize: 0.1, 
	        gapSize: 0.1 
	    });

            const orbit = new THREE.LineLoop(geometry, material);
            orbit.rotation.set(axis.x, axis.y, axis.z);
	 orbit.computeLineDistances();
            scene.add(orbit);
        }

        // Create the orbits
        createOrbit(5.5, 64, new THREE.Vector3(Math.PI / 2, 0, 0)); // Equador
        createOrbit(5.5, 64, new THREE.Vector3(0, 0, 0)); // Transversal
        createOrbit(5.5, 64, new THREE.Vector3(0, Math.PI / 2,0)); // Vertical


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

    const material = new THREE.LineDashedMaterial({
        color: 0x444444,
        linewidth: 5,
        dashSize: 0.1,
        gapSize: 0.1
    });

    const circle = new THREE.LineLoop(geometry, material);
    circle.position.set(0, radius + distanceFromSurface, 0); // Position it slightly above the sphere
    circle.rotation.x = Math.PI / 2; // Rotate to be parallel to the x-z plane
    circle.computeLineDistances(); // Necessary for dashed lines

    scene.add(circle);
}

        // Helper function to find the midpoint on the surface
        function midpointOnSurface(p1, p2, radius) {
            const midpoint = new THREE.Vector3(
                (p1.x + p2.x) / 2,
                (p1.y + p2.y) / 2.7,   //distancia rectificada per facilitat d'us
                (p1.z + p2.z) / 2
            );
            return midpoint.normalize().multiplyScalar(radius);
        }

        // Helper function to find the midpoint between 3 points on the surface
        function midpoint3OnSurface(p1, p2, p3, radius) {
            const midpoint = new THREE.Vector3(
                (p1.x + p2.x + p3.x) / 3,
                (p1.y + p2.y + p3.y) / 3,
                (p1.z + p2.z + p3.z) / 3
            );
            return midpoint.normalize().multiplyScalar(radius);
        }

        // Create canvas-based text labels
        function createTextLabel(text, position, color, fontSize = '36px') {
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

            // Draw border
        //    context.strokeStyle = 'gray';
          //  context.strokeText(text, 10, 10); // Slightly offset the text to leave space for the border

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
            sprite.position.copy(position);
            sprite.userData = { originalOpacity: 1 }; // Store original opacity
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

        //
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

	//Orbites

// Create an orbiting circle with a specific distance from the sphere surface
createOrbitingCircle(4.2, 1.5, 2.7, 64);
createOrbitingCircle(4.2, 1.4, 8.5, 64);

        // Adjust opacity based on visibility
        function updateLabelVisibility() {
            labels.forEach(sprite => {
                const directionToCamera = camera.position.clone().sub(sprite.position).normalize();
                const directionToSprite = sprite.position.clone().normalize();
                const dot = directionToCamera.dot(directionToSprite);
                sprite.material.opacity = dot < 0 ? 0.2 : sprite.userData.originalOpacity;
                sprite.material.needsUpdate = true; // Ensure the material update is applied
            });
        }

        // Sort and render scene
        function render() {
            // Sort the scene by distance from the camera
            scene.children.sort((a, b) => {
                const distanceA = camera.position.distanceTo(a.position);
                const distanceB = camera.position.distanceTo(b.position);
                return distanceB - distanceA;
            });

            renderer.render(scene, camera);
        }

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
                pointLight.position.copy(camera.position);

            controls.update();
            updateLabelVisibility();
            render();
        }

        animate();

// Inicial setup
onWindowResize();


    </script>

</body>
</html>


