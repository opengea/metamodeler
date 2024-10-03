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

	document.addEventListener('DOMContentLoaded', () => {

        // Basic setup
        const scene = new THREE.Scene();
        const aspect = window.innerWidth / window.innerHeight;

        // CAMERA
        var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
        var VIEW_ANGLE = 20, ASPECT = SCREEN_WIDTH / SCREEN_HEIGHT, NEAR = 0.1, FAR = 1000;
        camera = new THREE.PerspectiveCamera( VIEW_ANGLE, ASPECT, NEAR, FAR);
	camera.zoom=0.4;
        camera.position.set(0,0,0);
        camera.lookAt(scene.position);

	const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(window.devicePixelRatio);  // Millora la resolució
        document.body.appendChild(renderer.domElement);

        // OrbitControls
        const controls = new THREE.TrackballControls(camera, renderer.domElement);
        controls.rotateSpeed = 4;
        controls.zoomSpeed = 0.1;
        controls.panSpeed = 0.8;
        controls.noRotate = false;
        controls.noZoom = false;
        controls.noPan = true;
        controls.staticMoving = false;
        controls.dynamicDampingFactor = 0.3;
        controls.keys = [65, 83, 68]; // A, S, D

        // Defineix l'objectiu dels controls
        controls.target.set(0, 0, 0);

        // Add event listener for resizing the window
        window.addEventListener('resize', onWindowResize, false);

        function onWindowResize() {
		var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
		camera.aspect = SCREEN_WIDTH / SCREEN_HEIGHT;
    		camera.updateProjectionMatrix();
    		renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
		if (SCREEN_WIDTH<400) camera.zoom=0.29; else camera.zoom=0.4;
        }

        // Add event listeners for zoom using touch pinch
        let touchStartDistance = 0;
        let initialZoom = camera.zoom;

        function getTouchDistance(event) {
            const dx = event.touches[0].pageX - event.touches[1].pageX;
            const dy = event.touches[0].pageY - event.touches[1].pageY;
            return Math.sqrt(dx * dx + dy * dy);
        }

        window.addEventListener('touchstart', function(event) {
            if (event.touches.length === 2) {
                touchStartDistance = getTouchDistance(event);
                initialZoom = camera.zoom;
            }
        });

        window.addEventListener('touchmove', function(event) {
            if (event.touches.length === 2) {
                const touchMoveDistance = getTouchDistance(event);
                camera.zoom = initialZoom * (touchMoveDistance / touchStartDistance);
                camera.updateProjectionMatrix();
            }
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


    // Carrega la textura
    var textureLoader = new THREE.TextureLoader();
    textureLoader.load(
        'https://labs.opengea.org/metamodeler/v1/img/2k_earth_nightmap.jpg',  // Canvia això per la ruta de la teva textura
        function(texture) {


const uniforms = {
    ambientLightColor: { value: new THREE.Color(0x404040) },
    directionalLightColor: { value: new THREE.Color(0xffffff) },
    directionalLightPosition: { value: new THREE.Vector3(10, 10, 10) },
    pointLightColor: { value: new THREE.Color(0xffffff) },
    pointLightPosition: { value: new THREE.Vector3(5, 5, 5) },
    colors: { value: colors },
    type: 't',
    value: texture
};

            // Crea el material amb la textura utilitzant ShaderMaterial
            var material = new THREE.ShaderMaterial({
                uniforms: uniforms,
                vertexShader: `
        varying vec3 vNormal;
        varying vec3 vPosition;
                  //  varying vec2 vUv;
                    void main() {
                   //     vUv = uv;
            vNormal = normalize(normalMatrix * normal);
            vPosition = vec3(modelViewMatrix * vec4(position, 1.0));
                        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                    }
                `,
                fragmentShader: `
        uniform vec3 ambientLightColor;
        uniform vec3 directionalLightColor;
        uniform vec3 directionalLightPosition;
        uniform vec3 pointLightColor;
        uniform vec3 pointLightPosition;
        uniform vec3 colors[6];
        varying vec3 vNormal;
        varying vec3 vPosition;
                //    uniform sampler2D texture1;
                  // varying vec2 vUv;
                    void main() {
			vec3 lightDirection = normalize(directionalLightPosition - vPosition);
            float directionalLightWeighting = max(dot(vNormal, lightDirection), 0.0);
            vec3 lightWeighting = ambientLightColor + directionalLightColor * directionalLightWeighting;

            vec3 pointLightDirection = normalize(pointLightPosition - vPosition);
            float pointLightWeighting = max(dot(vNormal, pointLightDirection), 0.0);
            lightWeighting += pointLightColor * pointLightWeighting;

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

            gl_FragColor = vec4(color * lightWeighting, 1.0); // Set alpha to 1 for no transparency

                 //       vec4 color = texture2D(texture1, vUv);
                        //gl_FragColor = color;
//			gl_FragColor = vec4(color.rgb, 1); // Ajusta el valor alpha
                    }
                `,
                side: THREE.DoubleSide,
                transparent: false,
                wireframe: false
            });

            // Sphere geometry and material
            var geometry = new THREE.SphereGeometry(5, 64, 64);
            var sphere = new THREE.Mesh(geometry, material);
            scene.add(sphere);



// Afegir llum puntual
const pointLight = new THREE.PointLight(0x00ff00, 1, 100);
pointLight.position.set(5, 5, 5); // Posició de la llum
scene.add(pointLight);


        },
        undefined,
        function(err) {
            // On Texture Load Error
            console.error('An error occurred loading the texture:', err);
        }



    );


/*
	// Carrega la textura
var textureLoader = new THREE.TextureLoader();
var texture = textureLoader.load('https://labs.opengea.org/metamodeler/v1/img/2k_earth_nightmap.jpg');

        // Sphere geometry and material
        const geometry = new THREE.SphereGeometry(5, 64, 64);
        const material = new THREE.ShaderMaterial({
            uniforms: {
		texture1: { type: 't', value: texture },
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

                    gl_FragColor = vec4(color, 0.9); // Set alpha to 0.9 for slight transparency
                }
            `,
            side: THREE.DoubleSide,
            transparent: false, // Disable transparency
            wireframe: false    // Disable wireframe mode
        });

        const sphere = new THREE.Mesh(geometry, material);
        scene.add(sphere);
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
        createOrbit(5.5, 64, new THREE.Vector3(0, Math.PI / 2, 0)); // Vertical

        // Helper function to create a circle orbiting the north pole and slightly above the sphere
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

        // Create additional labels
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

        // Create orbiting circles
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

            // Mantenir eix vertical del model (TEO-PRA)
            const up = new THREE.Vector3(0, 1, 0);
            const targetDirection = new THREE.Vector3().subVectors(controls.target, camera.position).normalize();
            const right = new THREE.Vector3().crossVectors(up, targetDirection).normalize();
            const cameraUp = new THREE.Vector3().crossVectors(targetDirection, right);
            camera.up.copy(cameraUp);
            camera.lookAt(controls.target);

            controls.update();
            updateLabelVisibility();
            render();
        }

        animate();

});

    </script>
</body>
</html>

