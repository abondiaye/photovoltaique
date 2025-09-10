document.addEventListener("mousemove", (e) => {
    const sun = document.querySelector(".sun");
    if (!sun) return;
  
    const { innerWidth, innerHeight } = window;
    const x = (e.clientX / innerWidth - 0.5) * 40; // mouvement max 40px
    const y = (e.clientY / innerHeight - 0.5) * 40;
  
    sun.style.transform = `translate(-50%, -50%) translate(${x}px, ${y}px) scale(1.1)`;
  });