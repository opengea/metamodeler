<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fibració de Hopf - Three.js</title>
    <style>
        body { margin: 0; }
        canvas { display: block; }
    </style>
</head>
<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    
    <script>
        // Escena, càmera i render
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Controls de la càmera
        const controls = new THREE.OrbitControls(camera, renderer.domElement);

        // Material per als cercles
        const circleMaterial = new THREE.LineBasicMaterial({ color: 0x00ff00 });

        // Funció per crear els cercles que representen la fibració de Hopf
        function createHopfFibrationCircles() {
            const numCircles = 30;
            const angleIncrement = (2 * Math.PI) / numCircles;

            for (let i = 0; i < numCircles; i++) {
                const phi = i * angleIncrement;
                const geometry = new THREE.BufferGeometry();
                const points = [];

                // Ajustant la projecció estereogràfica per a la fibració de Hopf
                for (let t = 0; t <= 2 * Math.PI; t += 0.05) {
                    const a = Math.cos(phi);
                    const b = Math.sin(phi);
                    const x = Math.cos(t);
                    const y = Math.sin(t) * a;
                    const z = Math.sin(t) * b;

                    points.push(new THREE.Vector3(x, y, z));
                }

                geometry.setFromPoints(points);

                const circle = new THREE.Line(geometry, circleMaterial);
                scene.add(circle);
            }
        }

        // Crida la funció per crear els cercles de la fibració de Hopf
        createHopfFibrationCircles();

        // Posicionem la càmera
        camera.position.z = 5;

        // Funció de render
        function animate() {
            requestAnimationFrame(animate);

            // Opcionalment, podem fer que la càmera giri
            controls.update();

            renderer.render(scene, camera);
        }

        animate();

        // Ajustar la finestra de renderització quan es redimensiona la finestra
        window.addEventListener('resize', function() {
            const width = window.innerWidth;
            const height = window.innerHeight;
            renderer.setSize(width, height);
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        });
    </script>
</body>
</html>
