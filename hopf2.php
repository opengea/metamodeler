<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hopf Fibration - Simple Visualization</title>
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

    // Funció per crear un cercle 3D
    function createCircle(radius, color, position, rotation) {
      const geometry = new THREE.TorusGeometry(radius, 0.02, 16, 100);  // Radi del cercle, gruix, segments
      const material = new THREE.MeshBasicMaterial({ color: color });
      const circle = new THREE.Mesh(geometry, material);
      circle.position.set(position.x, position.y, position.z);
      circle.rotation.set(rotation.x, rotation.y, rotation.z);
      return circle;
    }

    // Funció per generar les fibres de la fibració de Hopf
    function generateHopfFibration(numFibres) {
      const radius = 2;  // Radi del cercle major
      for (let i = 0; i < numFibres; i++) {
        const angle = (i / numFibres) * Math.PI * 2;  // Angle per distribuir els cercles
        const color = new THREE.Color(`hsl(${(i / numFibres) * 360}, 100%, 50%)`);  // Assignar colors diferents a cada cercle

        // Posició i rotació dels cercles
        const x = radius * Math.cos(angle);  // Posició en l'eix X
        const y = radius * Math.sin(angle);  // Posició en l'eix Y
        const position = new THREE.Vector3(x, y, 0);
        
        // Rotació: cada cercle ha d'estar rotat segons l'angle
        const rotation = new THREE.Vector3(Math.random() * Math.PI, Math.random() * Math.PI, angle);

        // Crea el cercle i afegeix-lo a l'escena
        const circle = createCircle(1, color, position, rotation);
        scene.add(circle);
      }
    }

    // Crea la fibració de Hopf amb 8 fibres
    generateHopfFibration(8);

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
