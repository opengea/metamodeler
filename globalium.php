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
       <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <script src="js/threejs/build/three.js"></script>
    <script src="js/OrbitControls.js"></script>
    <script>
        // Basic setup
        const scene = new THREE.Scene();
        const aspect = window.innerWidth / window.innerHeight;
        const frustumSize = 10;
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
        const material = new THREE.ShaderMaterial({
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

        const sphere = new THREE.Mesh(geometry, material);
        scene.add(sphere);

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
        color: 0x000000,
        linewidth: 2,
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
            context.lineWidth = 2; // Border thickness

            // Draw border
            context.strokeStyle = 'gray';
            context.strokeText(text, 10, 10); // Slightly offset the text to leave space for the border

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
createOrbitingCircle(4.1, 1.5, 2.7, 64);
createOrbitingCircle(4.1, 1.5, 8.5, 64);

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
            controls.update();
            updateLabelVisibility();
            render();
        }

        animate();
    </script>
</body>
</html>

