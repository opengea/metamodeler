<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three.js Torus Orientation</title>
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
        <input type="number" id="torusCount" value="13" min="1" max="13"><br><br>

        <label for="radialSegments">Segments Radials:</label>
        <input type="number" id="radialSegments" value="80" min="3"><br><br>

        <label for="tubularSegments">Segments Tubulars:</label>
        <input type="number" id="tubularSegments" value="80" min="3" max="600"><br><br>

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

        // Funció per convertir HSL a RGB
        function hslToRgb(h, s, l) {
            let r, g, b;

            if (s == 0) {
                r = g = b = l; // Greyscale
            } else {
                const hue2rgb = function(p, q, t) {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1 / 6) return p + (q - p) * 6 * t;
                    if (t < 1 / 3) return q;
                    if (t < 1 / 2) return p + (q - p) * (2 / 3 - t) * 6;
                    return p;
                };

                const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                const p = 2 * l - q;
                r = hue2rgb(p, q, h + 1 / 3);
                g = hue2rgb(p, q, h);
                b = hue2rgb(p, q, h - 1 / 3);
            }

            return (Math.round(r * 255) << 16) + (Math.round(g * 255) << 8) + Math.round(b * 255);
        }

        // Funció per crear un torus en wireframe amb color i transparència
        function createTorusWireframe(radius, tube, radialSegments, tubularSegments, color) {
            const geometry = new THREE.TorusGeometry(radius, tube, radialSegments, tubularSegments);
            const material = new THREE.MeshBasicMaterial({ color: color, wireframe: true, transparent: true, opacity: 0.6 });
            return new THREE.Mesh(geometry, material);
        }

        // Paràmetres inicials
        const torusOrientations = [
            { x: 0, y: Math.PI / 2, z: 0 }, // TEO
            { x: Math.PI / 2, y: 0, z: 0 }, // FEN
            { x: 0, y: 0, z: Math.PI / 2 }, // SUB
            { x: Math.PI / 4, y: 0, z: Math.PI / 4 }, // ART
            { x: Math.PI / 2, y: Math.PI / 4, z: 0 }, // ANA
            { x: Math.PI / 4, y: Math.PI / 2, z: 0 }, // STT
            { x: Math.PI / 6, y: Math.PI / 6, z: Math.PI / 6 }, // EST
            { x: 0, y: Math.PI / 4, z: Math.PI / 2 }, // SGT
            { x: Math.PI / 2, y: Math.PI / 4, z: Math.PI }, // SIN
            { x: Math.PI / 6, y: Math.PI / 6, z: -Math.PI / 6 }, // MIT
            { x: 0, y: 0, z: Math.PI }, // MTP
            { x: -Math.PI / 6, y: Math.PI / 4, z: Math.PI / 2 }, // IDE
            { x: Math.PI / 6, y: -Math.PI / 6, z: Math.PI / 4 } // LOG
        ];


        function createTorusScene() {
            // Netejar els toruses anteriors de l'escena
            while (scene.children.length > 0) {
                scene.remove(scene.children[0]);
            }

            const radius = 0.1;
            const tube = 0.1;
            const radialSegments = parseInt(document.getElementById('radialSegments').value);
            const tubularSegments = parseInt(document.getElementById('tubularSegments').value);

//	    let torusCount = torusOrientations.lenght;

	    const torusCount = parseInt(document.getElementById('torusCount').value);
		

            for (let i = 0; i < torusCount; i++) {
                const hue = i / torusCount;
                const color = hslToRgb(hue, 1, 0.5);

                const torus = createTorusWireframe(radius, tube, radialSegments, tubularSegments, color);
                
                // Centrar el torus en (0, 0, 0) però mantenir l'orientació
                torus.rotation.set(torusOrientations[i].x, torusOrientations[i].y, torusOrientations[i].z);
                
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
        document.getElementById('updateScene').addEventListener('click', createTorusScene);

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

