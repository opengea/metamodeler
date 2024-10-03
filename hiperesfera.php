<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three.js Toroides amb Distribució Esfèrica i Centres Coincidents</title>
    <style>
        body { margin: 0; }
        canvas { display: block; }
        #controls {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="controls">
        <label for="torusCount">Nombre de torus:</label>
        <input type="number" id="torusCount" value="6" min="1" max="13"><br><br>

        <label for="radialSegments">Segments Radials:</label>
        <input type="number" id="radialSegments" value="32" min="3"><br><br>

        <label for="tubularSegments">Segments Tubulars:</label>
        <input type="number" id="tubularSegments" value="100" min="3"><br><br>

        <button id="updateScene">Actualitzar</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
    <script>
        // Crear l'escena
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Afegir controls per rotar l'escena
        const controls = new THREE.OrbitControls(camera, renderer.domElement);

        // Llum
        const light = new THREE.PointLight(0xffffff, 1, 100);
        light.position.set(10, 10, 10);
        scene.add(light);

        // Funció per crear un gradient continu de color
        function lerpColor(color1, color2, t) {
            const r = (1 - t) * (color1 >> 16 & 0xff) + t * (color2 >> 16 & 0xff);
            const g = (1 - t) * (color1 >> 8 & 0xff) + t * (color2 >> 8 & 0xff);
            const b = (1 - t) * (color1 & 0xff) + t * (color2 & 0xff);
            return (r << 16) + (g << 8) + b;
        }

        // Funció per crear un torus en wireframe amb color i transparència
        function createTorusWireframe(radius, tube, radialSegments, tubularSegments, color) {
            const geometry = new THREE.TorusGeometry(radius, tube, radialSegments, tubularSegments);
            const material = new THREE.MeshBasicMaterial({ color: color, wireframe: true, transparent: true, opacity: 0.6 });
            return new THREE.Mesh(geometry, material);
        }

        // Paràmetres inicials
        let torusCount = parseInt(document.getElementById('torusCount').value);
        let radialSegments = parseInt(document.getElementById('radialSegments').value);
        let tubularSegments = parseInt(document.getElementById('tubularSegments').value);

        const radius = 0.3; // Radi intern del torus
        const tube = 0.3; // Gruix del torus

        let toruses = [];

        // Funció per crear i afegir els torus a l'escena
        function createTorusScene() {
            // Neteja els toruses anteriors de l'escena
            toruses.forEach(torus => scene.remove(torus));
            toruses = [];

            for (let i = 0; i < torusCount; i++) {
                // Gradient de color continu entre vermell i blau
                const color = lerpColor(0xff0000, 0x0000ff, i / (torusCount - 1));

                // Coordenades esfèriques
                const phi = Math.acos(1 - 2 * (i + 0.5) / torusCount); // Angle polar
                const theta = Math.PI * (1 + Math.sqrt(5)) * i; // Angle azimutal (basat en la constant d'or)

                // Crear el torus amb un color diferent i una mica de transparència
                const torus = createTorusWireframe(radius, tube, radialSegments, tubularSegments, color);

                // Posicionar el torus en el centre però rotat
                torus.position.set(0, 0, 0);

                // Orientar el torus perquè els seus forats es distribueixin esfèricament
                const quaternion = new THREE.Quaternion();
                quaternion.setFromAxisAngle(new THREE.Vector3(Math.sin(phi) * Math.cos(theta), Math.sin(phi) * Math.sin(theta), Math.cos(phi)), Math.PI / 2);
                torus.setRotationFromQuaternion(quaternion);

                toruses.push(torus);
                scene.add(torus);
            }
        }

        // Posició inicial de la càmera
        camera.position.z = 5;

        // Bucle d'animació
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }

        animate();

        // Funció per actualitzar l'escena quan es canvien els inputs
        document.getElementById('updateScene').addEventListener('click', function() {
            torusCount = parseInt(document.getElementById('torusCount').value);
            radialSegments = parseInt(document.getElementById('radialSegments').value);
            tubularSegments = parseInt(document.getElementById('tubularSegments').value);
            createTorusScene();
        });

        // Inicialitzar l'escena amb els paràmetres inicials
        createTorusScene();

        // Actualitzar l'escena en cas de redimensionar la finestra
        window.addEventListener('resize', () => {
            renderer.setSize(window.innerWidth, window.innerHeight);
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
        });
    </script>
</body>
</html>
