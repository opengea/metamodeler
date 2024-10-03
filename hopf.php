<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hopf Fibration Visualization</title>
  <style>
    body { margin: 0; }
    canvas { display: block; }
  </style>
</head>
<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>

  <script type="module">

    // Escena, càmera i renderitzador
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    // Controls d'òrbita per permetre la interacció
    const controls = new THREE.OrbitControls(camera, renderer.domElement);

    // Fons negre per a la visualització
    scene.background = new THREE.Color(0x000000);

    // Funció per generar un cercle tridimensional
    function createCircle(radius, color) {
      const geometry = new THREE.TorusGeometry(radius, 0.02, 16, 100);
      const material = new THREE.MeshBasicMaterial({ color: color });
      const circle = new THREE.Mesh(geometry, material);
      return circle;
    }

    // Funció de projecció estereogràfica per a S^3
    function stereographicProjection(x1, x2, x3, x4) {
      const denom = 1 - x4;
      return new THREE.Vector3(x1 / denom, x2 / denom, x3 / denom);
    }

    // Genera els cercles (fibres de Hopf)
    function generateHopfFibration(numFibres) {
      const colors = [0xff0000, 0x00ff00, 0x0000ff, 0xffff00];  // Diferents colors per les fibres
      for (let i = 0; i < numFibres; i++) {
        const theta = (i / numFibres) * Math.PI * 2;
        
        // Parametritza un punt en S^3
        const x1 = Math.cos(theta);
        const x2 = Math.sin(theta);
        const x3 = 0;
        const x4 = 0;

        // Projecció estereogràfica a 3D
        const projectedPoint = stereographicProjection(x1, x2, x3, x4);

        // Genera un cercle al voltant del punt projectat
        const circle = createCircle(0.3, colors[i % colors.length]);
        circle.position.set(projectedPoint.x, projectedPoint.y, projectedPoint.z);
        circle.rotation.x = Math.random() * Math.PI;  // Rotació aleatòria per diversificar l'orientació
        circle.rotation.y = Math.random() * Math.PI;
        scene.add(circle);
      }
    }

    // Crea la fibració amb N fibres
    generateHopfFibration(30);

    // Posició inicial de la càmera
    camera.position.z = 5;

    // Funció d'animació
    function animate() {
      requestAnimationFrame(animate);
      controls.update();
      renderer.render(scene, camera);
    }

    animate();
  </script>
</body>
</html>
