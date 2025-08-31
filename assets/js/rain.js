// Pluie plein écran qui glisse sur la carte #calendarCard
(function () {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;
  
    const canvas = document.getElementById('rain-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
  
    let w, h, dpr, last = 0;
    function size() {
      dpr = Math.max(1, Math.min(2, window.devicePixelRatio || 1));
      w = canvas.clientWidth;
      h = canvas.clientHeight;
      canvas.width = Math.floor(w * dpr);
      canvas.height = Math.floor(h * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }
    window.addEventListener('resize', size);
    size();
  
    const drops = [];
    const MAX = 160;
    const G = 0.35;
    let wind = 0.25;
  
    function cardRect() {
      const el = document.getElementById('calendarCard');
      if (!el) return null;
      const r = el.getBoundingClientRect();
      return { x: r.left, y: r.top, w: r.width, h: r.height };
    }
  
    function spawn() {
      const speed = 6 + Math.random() * 6;
      drops.push({
        x: Math.random() * w,
        y: -10 - Math.random() * 200,
        vx: wind * (0.6 + Math.random() * 0.8),
        vy: speed,
        len: 8 + Math.random() * 14,
        width: 1 + Math.random() * 1.1,
        state: 'fall', // 'fall' | 'slide'
        side: null
      });
    }
  
    function ensure() { while (drops.length < MAX) spawn(); }
  
    function update(dt) {
      wind += (Math.random() - 0.5) * 0.02;
      wind = Math.max(-0.4, Math.min(0.6, wind));
  
      const rc = cardRect();
  
      for (let i = drops.length - 1; i >= 0; i--) {
        const d = drops[i];
  
        if (d.state === 'fall') {
          d.vy += G * dt;
          d.x += (d.vx + wind) * dt;
          d.y += d.vy * dt;
  
          if (rc) {
            const tipX = d.x;
            const tipY = d.y + d.len;
            const hitTop = tipX >= rc.x && tipX <= rc.x + rc.w && tipY >= rc.y && tipY <= rc.y + 6;
            const hitL = tipY >= rc.y && tipY <= rc.y + rc.h && tipX >= rc.x - 6 && tipX <= rc.x;
            const hitR = tipY >= rc.y && tipY <= rc.y + rc.h && tipX >= rc.x + rc.w && tipX <= rc.x + rc.w + 6;
  
            if (hitTop) { d.state = 'slide'; d.side = 'top'; d.y = rc.y - d.len; d.vx = 0; d.vy = 0; }
            else if (hitL) { d.state = 'slide'; d.side = 'left'; d.x = rc.x - d.width; d.vx = 0; d.vy = 0; }
            else if (hitR) { d.state = 'slide'; d.side = 'right'; d.x = rc.x + rc.w; d.vx = 0; d.vy = 0; }
          }
  
          if (d.y - d.len > h + 40 || d.x < -40 || d.x > w + 40) drops.splice(i, 1);
        } else {
          const rc2 = cardRect();
          if (!rc2) { drops.splice(i, 1); continue; }
  
          // glissade verticale
          d.y += (3 + Math.random() * 2) * dt * 10;
          if (d.side === 'top') d.x += wind * 0.3 * dt * 10;
          if (d.y >= rc2.y + rc2.h) { d.state = 'fall'; d.vy = 2 + Math.random() * 2; d.vx = wind; }
          if (d.y - d.len > h + 40) drops.splice(i, 1);
        }
      }
    }
  
    function draw() {
      ctx.fillStyle = 'rgba(0,0,0,0.12)'; // léger voile pour effet "traînée"
      ctx.fillRect(0, 0, w, h);
      ctx.strokeStyle = 'rgba(173,216,230,0.9)'; // lightblue
      ctx.lineCap = 'round';
      for (const d of drops) {
        ctx.lineWidth = d.width;
        ctx.beginPath();
        ctx.moveTo(d.x, d.y);
        ctx.lineTo(d.x, d.y + d.len);
        ctx.stroke();
      }
    }
  
    function loop(ts) {
      const dt = last ? (ts - last) / 16.67 : 1; // normalisé 60fps
      last = ts;
      ensure();
      update(dt);
      draw();
      requestAnimationFrame(loop);
    }
  
    requestAnimationFrame(loop);
  })();
  