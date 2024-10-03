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
    <script>
        const scene = new THREE.Scene();
        const aspect = window.innerWidth / window.innerHeight;
        const frustumSize = 15;

        const camera = new THREE.OrthographicCamera(
            frustumSize * aspect / -2,
            frustumSize * aspect / 2,
            frustumSize / 2,
            frustumSize / -2,
            0.1,
            1000
        );

        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        const controls = new THREE.TrackballControls(camera, renderer.domElement);
        controls.rotateSpeed = 4;
        controls.zoomSpeed = 0.1;
        controls.panSpeed = 0.8;
        controls.enableRotate = true;
        controls.enableZoom = true;
        controls.noPan = true;
        controls.noZoom = true;
        controls.keys = [65, 83, 68]; // A, S, D
        controls.target.set(0, 0, 0);

        window.addEventListener('resize', onWindowResize, false);

        function onWindowResize() {
            const aspect = window.innerWidth / window.innerHeight;
            camera.left = -frustumSize * aspect / 2;
            camera.right = frustumSize * aspect / 2;
            camera.top = frustumSize / 2;
            camera.bottom = -frustumSize / 2;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);

            var SCREEN_WIDTH = window.innerWidth, SCREEN_HEIGHT = window.innerHeight;
            if (SCREEN_WIDTH < 400) camera.zoom = 0.7; else camera.zoom = 1;
        }

        camera.position.set(0, 0, 15);
        camera.lookAt(0, 0, 0);

        function createSpot(position, radius = 0.001, borderThickness = 0.001, distanceFromSurface = 0.01) {
            const geometry = new THREE.CircleGeometry(0.14, 32);
            const material = new THREE.MeshBasicMaterial({
                color: 0xffffff,
                transparent: true,
                opacity: 0.9,
                depthTest: false, // Ensure circles are always visible
                depthWrite: false // Prevent circles from affecting the depth buffer
            });

            const circle = new THREE.Mesh(geometry, material);

            const borderGeometry = new THREE.RingGeometry(0.18, 0.2, 32);
            const borderMaterial = new THREE.MeshBasicMaterial({
                color: 0xffffff, // Color de la vora (negre en aquest cas)
                transparent: true,
                opacity: 0.9,
                depthTest: false, // Ensure circles are always visible
                depthWrite: false // Prevent circles from affecting the depth buffer
            });

            const borderCircumference = new THREE.Mesh(borderGeometry, borderMaterial);

            const sphereRadius = 5;
            const normalizedPosition = position.clone().normalize().multiplyScalar(sphereRadius + distanceFromSurface); // Adjust distance from surface

            circle.position.copy(normalizedPosition);
            borderCircumference.position.copy(normalizedPosition);

            const lookAtVector = normalizedPosition.clone().multiplyScalar(-1);
            circle.lookAt(lookAtVector);
            borderCircumference.lookAt(lookAtVector);
            circle.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface
            borderCircumference.rotateZ(Math.PI / 2); // Rotate to be parallel to the surface

            scene.add(borderCircumference);
            scene.add(circle);

            // Ensuring the spot faces the correct direction
            circle.userData.surfaceNormal = normalizedPosition.clone().normalize();
            borderCircumference.userData.surfaceNormal = normalizedPosition.clone().normalize();

            return { circle, borderCircumference };
        }

        function createSpots() {
            const positions = [
                new THREE.Vector3(0, 5, 0),   // North
                new THREE.Vector3(0, -5, 0),  // South
                new THREE.Vector3(5, 0, 0),   // East
                new THREE.Vector3(-5, 0, 0),  // West
                new THREE.Vector3(0, 0, 5),   // Front
                new THREE.Vector3(0, 0, -5)   // Rear
            ];

            positions.forEach(position => {
                createSpot(position);
            });
        }

        createSpots();

        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            updateSpotsOrientation();
            render();
        }

        function render() {
            renderer.render(scene, camera); // Render main scene
        }

        function updateSpotsOrientation() {
            scene.traverse(function (object) {
                if (object.userData.surfaceNormal) {
                    const lookAtVector = object.position.clone().add(object.userData.surfaceNormal);
                    object.lookAt(lookAtVector);
                }
            });
        }

        animate();
    </script>
</body>
</html>

