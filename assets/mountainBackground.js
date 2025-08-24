let canvas, c, w, h,
    twoPI = Math.PI * 2,
    mX, mY,
    resize = true,
    mousemove = true,
    per = { x: 0, y: 0 },
    mtn, trackmouse = false;

document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("mountain-bg");
  if (!container) return;

  canvas = document.createElement('canvas');
  canvas.style.width = "100%";
  canvas.style.height = "100%";
  canvas.style.position = "absolute";
  canvas.style.top = "0";
  canvas.style.left = "0";
  canvas.style.zIndex = "-1";
  canvas.style.borderRadius = "28px";

  container.style.position = "relative";
  container.appendChild(canvas);

  w = canvas.width = container.offsetWidth;
  h = canvas.height = container.offsetHeight;
  c = canvas.getContext('2d');

  if (resize) {
    window.addEventListener('resize', () => {
      w = canvas.width = container.offsetWidth;
      h = canvas.height = container.offsetHeight;
    });
  }

  if (mousemove) {
    canvas.addEventListener('mousemove', function(e) {
      trackmouse = true;
      const rect = canvas.getBoundingClientRect();
      mX = e.clientX - rect.left;
      mY = e.clientY - rect.top;
      per = { x: mX, y: mY };
    });
  }

  mX = w/2;
  mY = h/2;
  per = { x: mX, y: mY, step: 1 };

  mtn = new Mountains(300,"10");
  setInterval(animate,17);
});

function newGradient(gradient){
  let grad;
  switch(gradient.type){
    case "radial":
      grad = c.createRadialGradient(gradient.x1, gradient.y1, gradient.r1, gradient.x1, gradient.y1, gradient.r2);
      break;
    case "linear":
      grad = c.createLinearGradient(gradient.x1, gradient.y1, gradient.x2, gradient.y2);
      break;
  }
  gradient.stops.forEach(stop => grad.addColorStop(stop.s, stop.c));
  return grad;
}

function animate(){
  if(!trackmouse){
    per.x = mX = w/2 + Math.cos(per.step)*w/2;
    per.y = mY = h/2 + Math.sin(per.step)*h/2;
    per.step += 0.03;
    if(per.step > twoPI) per.step = 0;
  }

  c.save();
  c.globalCompositeOperation = "source-over";
  c.fillStyle = newGradient({
    type: "radial",
    x1: mX, y1: mY, r1: 0, r2: w,
    stops: [
      { s: 0, c: `rgba(${h-mY},${h-mY},${h-mY},0.5)` },
      { s: 0.05, c: `rgba(${h-mY},${h-mY-128},128,0.5)` },
      { s: 1, c: `rgba(0,${h-mY-128},${h-mY},0.5)` }
    ]
  });
  c.fillRect(0, 0, w, h);
  mtn.draw();
}

function Mountains(peaks){
  const points = [];
  const step = w / peaks;
  let y = 0;

  points.push({x: 0, y});
  for (let i = 1; i <= peaks; i++) {
    y += (Math.random() * 20) - 10;
    points.push({x: i * step, y});
  }

  this.draw = function(){
    c.save();
    c.fillStyle = newGradient({type:"linear", x1: 0, y1: 0, x2: 0, y2: h, stops: [{s:1, c:"rgba(0,0,0,1)"},{s:0, c:"rgba(80,80,80,1)"}]});
    c.beginPath();
    c.moveTo(points[0].x, h/2 - points[0].y);
    for (let p = 1; p < points.length; p++) {
      c.lineTo(points[p].x, h/2 - points[p].y);
    }
    c.lineTo(w, h);
    c.lineTo(0, h);
    c.closePath();
    c.fill();
    c.restore();

    c.globalCompositeOperation = "lighter";  						
    c.fillStyle = `rgba(${h-mY},${h-mY},${h-mY},0.03)`;
    for (let p = 0; p < points.length - 1; p++) {
      const va1 = Math.atan2(h/2 - points[p].y - per.y, points[p].x - per.x),
            va2 = Math.atan2(h/2 - points[p+1].y - per.y, points[p+1].x - per.x);

      c.beginPath();
      c.moveTo(points[p].x, h/2 - points[p].y);
      c.lineTo(points[p+1].x, h/2 - points[p+1].y);
      c.lineTo(points[p+1].x + Math.cos(va2)*w, h/2 - points[p+1].y + Math.sin(va2)*w);
      c.lineTo(points[p].x + Math.cos(va1)*w, h/2 - points[p].y + Math.sin(va1)*w);
      c.closePath();
      c.fill();
    }
  };
}
