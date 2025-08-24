import './styles/background.css';

(function () {
  // Respecte les préférences d’accessibilité
  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReduced) return;

  let canvas, c, w, h, dpr;
  const twoPI = Math.PI * 2;
  let mX, mY, trackmouse = false;
  let per = { x: 0, y: 0, step: 1 };
  let mtn;
  let rafId = null;

  function createCanvasOnce() {
    canvas = document.getElementById('bg-canvas');
    if (!canvas) {
      canvas = document.createElement('canvas');
      canvas.id = 'bg-canvas';
      document.body.prepend(canvas);
    }
    c = canvas.getContext('2d');
    onResize();
  }

  function onResize() {
    dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
    w = Math.max(1, window.innerWidth);
    h = Math.max(1, window.innerHeight);

    canvas.style.width = w + 'px';
    canvas.style.height = h + 'px';
    canvas.width = Math.floor(w * dpr);
    canvas.height = Math.floor(h * dpr);
    c.setTransform(dpr, 0, 0, dpr, 0, 0);

    // (re)centrer la souris virtuelle
    mX = w / 2;
    mY = h / 2;
    per = { x: w / 2, y: h / 2, step: per.step || 1 };

    // (re)générer les montagnes
    mtn = new Mountains(300, '10');
  }

  function newGradient(gradient) {
    let grad;
    if (gradient.type === 'radial') {
      grad = c.createRadialGradient(gradient.x1, gradient.y1, gradient.r1, gradient.x1, gradient.y1, gradient.r2);
    } else {
      grad = c.createLinearGradient(gradient.x1, gradient.y1, gradient.x2, gradient.y2);
    }
    for (let i = 0; i < gradient.stops.length; i++) {
      grad.addColorStop(gradient.stops[i].s, gradient.stops[i].c);
    }
    return grad;
  }

  function animate() {
    if (!trackmouse) {
      per.x = mX = w / 2 + Math.round(Math.cos(per.step) * w / 2);
      per.y = mY = h / 2 + Math.round(Math.sin(per.step) * h / 2);
      per.step += 0.03;
      if (per.step > twoPI) per.step = 0;
    }

    c.save();
    c.globalCompositeOperation = 'source-over';
    c.fillStyle = newGradient({
      type: 'radial',
      x1: mX,
      y1: mY,
      r1: 0,
      r2: Math.max(w, h),
      stops: [
        { s: 0,    c: `rgba(${h - mY}, ${h - mY}, ${h - mY}, 0.5)` },
        { s: 0.05, c: `rgba(${h - mY}, ${h - mY - 128}, 128, 0.5)` },
        { s: 1,    c: `rgba(0, ${h - mY - 128}, ${h - mY}, 0.5)` },
      ],
    });
    c.fillRect(0, 0, w, h);
    mtn.draw();
    c.restore();

    rafId = requestAnimationFrame(animate);
  }

  function Mountains(peaks /* , seed */) {
    const points = [];
    this.init = function () {
      const step = w / peaks;
      let y = 0;
      points.push({ x: 0, y });
      for (let i = 1; i <= peaks; i++) {
        y = y + (Math.random() * 20) - 10;
        points.push({ x: i * step, y });
      }
    };
    this.draw = function () {
      c.save();
      // Ombre montagne
      c.fillStyle = newGradient({
        type: 'linear', x1: 0, y1: 0, x2: 0, y2: h,
        stops: [{ s: 1, c: 'rgba(0,0,0,1)' }, { s: 0, c: 'rgba(80,80,80,1)' }]
      });
      c.beginPath();
      c.moveTo(points[0].x, h / 2 - points[0].y);
      for (let p = 1; p < points.length; p++) {
        c.lineTo(points[p].x, h / 2 - points[p].y);
      }
      c.lineTo(w, h);
      c.lineTo(0, h);
      c.closePath();
      c.fill();
      c.restore();

      c.globalCompositeOperation = 'lighter';
      c.fillStyle = `rgba(${h - mY}, ${h - mY}, ${h - mY}, 0.03)`;
      for (let p = 0; p < points.length - 1; p++) {
        const va1 = Math.atan2(h / 2 - points[p].y - per.y, points[p].x - per.x);
        const va2 = Math.atan2(h / 2 - points[p + 1].y - per.y, points[p + 1].x - per.x);
        c.beginPath();
        c.moveTo(points[p].x, h / 2 - points[p].y);
        c.lineTo(points[p + 1].x, h / 2 - points[p + 1].y);
        c.lineTo(points[p + 1].x + Math.cos(va2) * w, h / 2 - points[p + 1].y + Math.sin(va2) * w);
        c.lineTo(points[p].x + Math.cos(va1) * w, h / 2 - points[p].y + Math.sin(va1) * w);
        c.closePath();
        c.fill();
      }
    };
    this.init();
  }

  // init
  createCanvasOnce();

  // interactivité
  window.addEventListener('resize', onResize);
  canvas.addEventListener('mousemove', (e) => {
    trackmouse = true;
    const rect = canvas.getBoundingClientRect();
    mX = e.clientX - rect.left;
    mY = e.clientY - rect.top;
    per = { x: mX, y: mY, step: per.step || 1 };
  });

  // animation
  rafId = requestAnimationFrame(animate);

  // nettoyage si besoin (SPA)
  window.addEventListener('beforeunload', () => {
    if (rafId) cancelAnimationFrame(rafId);
  });
})();
