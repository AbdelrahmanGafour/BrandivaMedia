import { reduce, fine } from './config.js';
export function initHero() {
  const hero = document.querySelector('.hero');
  if (!hero) return;
  const cv = hero.querySelector('.hero-particles');
  const play = hero.querySelector('.hero-play');
  const pc = hero.querySelector('.p-canvas');
  if (pc) {
    // drawn on <canvas> after load: decorative image never becomes the LCP candidate
    const load = () => { const im = new Image(); im.onload = () => { const c = pc.getContext('2d'), W = pc.width, H = pc.height, k = Math.max(W / im.width, H / im.height); c.drawImage(im, (W - im.width * k) / 2, (H - im.height * k) / 2, im.width * k, im.height * k); pc.classList.add('on'); }; im.src = pc.dataset.src; };
    document.readyState === 'complete' ? setTimeout(load, 300) : addEventListener('load', () => setTimeout(load, 300), { once: true });
  }
  const mouse = { x: -999, y: -999 };
  hero.addEventListener('pointermove', e => {
    const r = hero.getBoundingClientRect();
    mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    if (play && !reduce && window.gsap) {
      const nx = (e.clientX / innerWidth - .5), ny = (e.clientY / innerHeight - .5);
      gsap.to([play.querySelector('.p-fill'), pc].filter(Boolean), { x: nx * -30, y: ny * -24, duration: 1, ease: 'power3.out', overwrite: 'auto' });
      gsap.to(play.querySelector('.p-line:not(.two)'), { x: nx * 18, y: ny * 14, duration: 1.2, ease: 'power3.out', overwrite: 'auto' });
      gsap.to(play.querySelector('.p-line.two'), { x: nx * 46, y: ny * 36, duration: 1.4, ease: 'power3.out', overwrite: 'auto' });
    }
  }, { passive: true });
  hero.addEventListener('pointerleave', () => { mouse.x = mouse.y = -999; });
  if (play) {
    play.classList.add('pulse');
    const btn = hero.querySelector('[data-play]');
    btn && btn.addEventListener('click', () => { play.classList.remove('pulse'); void play.offsetWidth; play.classList.add('pulse'); });
  }
  if (!cv || reduce) return;
  const ctx = cv.getContext('2d');
  let w, h, dpr, parts = [], run = true;
  const N = () => (innerWidth < 768 ? 34 : 78);
  function size() {
    dpr = Math.min(devicePixelRatio || 1, 1.75);
    w = hero.clientWidth; h = hero.clientHeight;
    cv.width = w * dpr; cv.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    parts = Array.from({ length: N() }, (_, i) => ({
      x: Math.random() * w, y: Math.random() * h, vx: (Math.random() - .5) * .3, vy: (Math.random() - .5) * .3,
      r: Math.random() * 2 + 1, c: i % 3 ? '183,158,232' : '253,186,15', t: Math.random() > .82
    }));
  }
  size();
  let rt; addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(size, 200); });
  new IntersectionObserver(([e]) => { run = e.isIntersecting; if (run) loop(); }).observe(hero);
  function loop() {
    if (!run || document.hidden) return;
    ctx.clearRect(0, 0, w, h);
    for (const p of parts) {
      const dx = p.x - mouse.x, dy = p.y - mouse.y, d = dx * dx + dy * dy;
      if (d < 22000) { const f = (1 - d / 22000) * 2.2, m = Math.sqrt(d) || 1; p.vx += dx / m * f * .06; p.vy += dy / m * f * .06; }
      p.vx *= .985; p.vy *= .985;
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0) p.x = w; if (p.x > w) p.x = 0; if (p.y < 0) p.y = h; if (p.y > h) p.y = 0;
      ctx.fillStyle = `rgba(${p.c},.75)`;
      if (p.t) { ctx.beginPath(); ctx.moveTo(p.x, p.y - 5); ctx.lineTo(p.x + 7, p.y); ctx.lineTo(p.x, p.y + 5); ctx.fill(); }
      else { ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, 6.283); ctx.fill(); }
    }
    ctx.lineWidth = .6;
    for (let i = 0; i < parts.length; i++) for (let j = i + 1; j < parts.length; j++) {
      const a = parts[i], b = parts[j], dx = a.x - b.x, dy = a.y - b.y, d = dx * dx + dy * dy;
      if (d < 9000) { ctx.strokeStyle = `rgba(183,158,232,${.16 * (1 - d / 9000)})`; ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke(); }
    }
    requestAnimationFrame(loop);
  }
  document.addEventListener('visibilitychange', () => { if (!document.hidden) loop(); });
  loop();
}
