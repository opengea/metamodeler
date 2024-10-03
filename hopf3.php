<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hopf Fibration of S³</title>
  <style>
    body { margin: 0; }
    canvas { display: block; }
  </style>
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
  <script>
    // Escena, càmera i renderitzador
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    // Controls per moure la càmera
    const controls = new THREE.OrbitControls(camera, renderer.domElement);

    // Fons negre per a la visualització
    scene.background = new THREE.Color(0x000000);

    // Funció per generar una línia fina tridimensional per a cada fibra
    function createFiber(color, points) {
      const geometry = new THREE.BufferGeometry().setFromPoints(points);
      const material = new THREE.LineBasicMaterial({ color: color, linewidth: 1 });
      const line = new THREE.Line(geometry, material);
      return line;
    }

    // Funció de projecció estereogràfica per a S^3
    // Projecció d'un punt (x1, x2, x3, x4) a l'espai tridimensional
    function stereographicProjection(x1, x2, x3, x4) {
      const denom = 1 - x4;  // Factor de projecció
      return new THREE.Vector3(x1 / denom, x2 / denom, x3 / denom);
    }

    // Genera fibres entrellaçades projectades des de la 3-esfera (S^3)
    function generateHopfFibration(numFibres) {
      const colors = [0xff0000, 0x00ff00, 0x0000ff, 0xffff00, 0xff00ff, 0x00ffff, 0xffffff, 0xff8000];  // 8 colors diferents

      for (let i = 0; i < numFibres; i++) {
        const theta = (i / numFibres) * Math.PI * 2;  // Distribueix les fibres de manera uniforme al voltant de la 3-esfera
        
        const points = [];
        for (let t = 0; t <= Math.PI * 2; t += 0.05) {
          // Parametritza un cercle a la 3-esfera
          const x1 = Math.cos(t);
          const x2 = Math.sin(t);
          const x3 = Math.sin(theta) * Math.cos(t);
          const x4 = Math.cos(theta);

          // Projecció estereogràfica a 3D
          const projectedPoint = stereographicProjection(x1, x2, x3, x4);
          points.push(projectedPoint);
        }

        // Crea una fibra com una línia que connecta els punts projectats
        const fiber = createFiber(colors[i % colors.length], points);
        scene.add(fiber);
      }
    }

    // Crea la fibració de Hopf amb 12 fibres projectades des de S³
    generateHopfFibration(12);

    // Posició inicial de la càmera
    camera.position.z = 5;

    // Funció d'animació
    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }

    animate();

    // Ajusta la mida del canvas si la finestra canvia de mida
    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });
  </script>
</body>
</html>
