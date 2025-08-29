// assets/background.js
console.log('[bg] entry loaded');

// Si un canvas #bg-canvas est présent dans la page, on le remplit d’un léger dégradé (inoffensif)
(function () {
  const canvas = document.getElementById('bg-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  function resize() {
    canvas.width = canvas.offsetWidth || window.innerWidth;
    canvas.height = canvas.offsetHeight || 300;
    draw();
  }

  function draw() {
    const w = canvas.width, h = canvas.height;
    const grd = ctx.createLinearGradient(0, 0, 0, h);
    grd.addColorStop(0, 'rgba(0,0,0,.25)');
    grd.addColorStop(1, 'rgba(0,0,0,.55)');
    ctx.fillStyle = grd;
    ctx.fillRect(0, 0, w, h);
  }

  window.addEventListener('resize', resize);
  resize();
})();
