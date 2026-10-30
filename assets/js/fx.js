import { reduce, fine } from './config.js';
const $ = (s, r = document) => [...r.querySelectorAll(s)];

export function initFx(lenis) {
  const g = window.gsap, ST = window.ScrollTrigger;
  // ---------- always-on (no GSAP needed) ----------
  clock(); tabs(); flips(); slider(); easter(); spotlight();
  if (!g || !ST || reduce) { $('[data-split]').forEach(e => e.classList.add('sp-ready')); if (g && !ST) $('[data-count]').forEach(e => e.textContent = e.dataset.count); document.documentElement.classList.remove('js-pin'); return; }
  g.registerPlugin(ST);

  // progress bar
  const bar = document.querySelector('.progress');
  if (bar) g.to(bar, { scaleX: 1, ease: 'none', scrollTrigger: { start: 0, end: 'max', scrub: .2 } });

  // split text (hero immediately on ready; the rest lazily when near the viewport)
  const splitEl = el => {
    if (!window.SplitType) { el.classList.add('sp-ready'); return null; }
    const targets = el.querySelector('.line') ? $('.line', el) : [el];
    const words = [];
    targets.forEach(t => {
      new SplitType(t, { types: 'words', tagName: 'span', wordClass: 'wi' });
      $('.wi', t).forEach(w => { const m = document.createElement('span'); m.className = 'w'; w.before(m); m.append(w); words.push(w); });
    });
    g.set(words, { yPercent: 110 });
    el.classList.add('sp-ready');
    return () => g.to(words, { yPercent: 0, duration: 1.1, ease: 'expo.out', stagger: .07, clearProps: 'transform' });
  };
  $('[data-split]').forEach(el => {
    if (el.dataset.split === 'hero') {
      const go = splitEl(el); if (go) addEventListener('brandiva:ready', go, { once: true });
    } else {
      ST.create({ trigger: el, start: 'top 95%', once: true, onEnter: () => { const go = splitEl(el); go && go(); } });
    }
  });

  // reveals (batched, staggered)
  ST.batch('[data-reveal]:not(.hero *):not(.phero *)', {
    start: 'top 90%', once: true,
    onEnter: b => {
      const above = b.filter(el => el.getBoundingClientRect().bottom < 0), seen = b.filter(el => !above.includes(el));
      if (above.length) g.set(above, { opacity: 1, y: 0 });
      if (seen.length) g.to(seen, { opacity: 1, y: 0, duration: 1, ease: 'expo.out', stagger: { amount: Math.min(.7, seen.length * .09) }, overwrite: true });
    }
  });
  $('[data-clip]').forEach(el => ST.create({ trigger: el, start: 'top 85%', once: true, onEnter: () => g.to(el, { clipPath: 'inset(0 0 0% 0)', duration: 1.3, ease: 'expo.inOut' }) }));
  $('[data-parallax]').forEach(el => g.fromTo(el, { yPercent: -8 }, { yPercent: 8, ease: 'none', scrollTrigger: { trigger: el.parentElement, scrub: true } }));

  // counters
  $('[data-count]').forEach(el => {
    const end = +el.dataset.count, o = { v: 0 };
    ST.create({ trigger: el, start: 'top 90%', once: true, onEnter: () => g.to(o, { v: end, duration: 1.8, ease: 'power3.out', onUpdate: () => el.textContent = Math.round(o.v) }) });
  });

  // bento children cascade
  $('.bento').forEach(b => g.from(b.children, { opacity: 0, y: 50, scale: .96, duration: 1, ease: 'expo.out', stagger: .1, scrollTrigger: { trigger: b, start: 'top 82%', once: true } }));
  $('.speed-bar i').forEach(i => g.from(i, { scaleX: 0, duration: 1.6, ease: 'expo.out', scrollTrigger: { trigger: i, start: 'top 92%', once: true } }));
  $('.spark path').forEach(p => { const L = p.getTotalLength(); g.fromTo(p, { strokeDasharray: L, strokeDashoffset: L }, { strokeDashoffset: 0, duration: 2, ease: 'power2.out', scrollTrigger: { trigger: p, start: 'top 92%', once: true } }); });

  // marquee: speed follows scroll velocity
  const marqs = $('.marquee-track');
  if (marqs.length) {
    let boost = 0;
    ST.create({ onUpdate: s => { boost = g.utils.clamp(-6, 6, s.getVelocity() / 250); } });
    g.ticker.add(() => {
      boost *= .92;
      marqs.forEach(t => t.getAnimations().forEach(a => { a.playbackRate = 1 + Math.abs(boost); }));
    });
  }

  // hero slash + title parallax on scroll
  const slash = document.querySelector('.hero-slash');
  if (slash) g.to(slash, { yPercent: 12, xPercent: -4, ease: 'none', scrollTrigger: { trigger: '.hero', scrub: true, start: 'top top', end: 'bottom top' } });

  // magnetic buttons
  if (fine) $('[data-magnetic],.btn-lg').forEach(b => {
    const qx = g.quickTo(b, 'x', { duration: .6, ease: 'power3.out' }), qy = g.quickTo(b, 'y', { duration: .6, ease: 'power3.out' });
    b.addEventListener('pointermove', e => { const r = b.getBoundingClientRect(); qx((e.clientX - r.left - r.width / 2) * .3); qy((e.clientY - r.top - r.height / 2) * .4); });
    b.addEventListener('pointerleave', () => { qx(0); qy(0); });
  });
  // tilt
  if (fine) $('[data-tilt]').forEach(c => {
    const rx = g.quickTo(c, 'rotationX', { duration: .6 }), ry = g.quickTo(c, 'rotationY', { duration: .6 });
    g.set(c, { transformPerspective: 900 });
    c.addEventListener('pointermove', e => { const r = c.getBoundingClientRect(); ry(((e.clientX - r.left) / r.width - .5) * 8); rx(-((e.clientY - r.top) / r.height - .5) * 8); });
    c.addEventListener('pointerleave', () => { rx(0); ry(0); });
  });

  workList(g);
  process(g, ST);
  industries(g, ST);
  toc();
}

function workList(g) {
  const list = document.querySelector('.work-list'), pv = document.querySelector('.work-preview');
  if (!list || !pv || !fine) return;
  const imgs = $('img', pv);
  let x = 0, y = 0, cx = 0, cy = 0, on = false;
  addEventListener('pointermove', e => { x = e.clientX; y = e.clientY; }, { passive: true });
  g.ticker.add(() => { if (!on) return; cx += (x - cx) * .14; cy += (y - cy) * .14; pv.style.transform = `translate3d(${cx + 28}px,${cy - 150}px,0) rotate(${(x - cx) * .02}deg)`; });
  $('.work-row', list).forEach((row, i) => {
    row.addEventListener('pointerenter', () => { imgs.forEach((im, k) => im.classList.toggle('on', k === i)); pv.classList.add('on'); on = true; cx = x; cy = y; });
    row.addEventListener('pointerleave', () => { pv.classList.remove('on'); on = false; });
  });
}

function process(g, ST) {
  const sec = document.querySelector('.process-stage');
  if (!sec) return;
  const steps = $('.pstep', sec), dots = $('.pdots span', sec), fill = sec.querySelector('.pbar i');
  const mm = g.matchMedia();
  mm.add('(min-width:700px)', () => {
    document.documentElement.classList.add('js-pin');
    ST.refresh();
    const n = steps.length;
    g.set(steps, { opacity: 0, y: 40 }); g.set(steps[0], { opacity: 1, y: 0 });
    const tl = g.timeline({ scrollTrigger: { trigger: sec, start: 'top top', end: () => '+=' + innerHeight * (n * .7), pin: true, scrub: .6, anticipatePin: 1,
      onUpdate: self => { const i = Math.min(n - 1, Math.floor(self.progress * n * .9999)); dots.forEach((d, k) => d.classList.toggle('on', k <= i)); } } });
    tl.to(fill, { scaleX: 1, duration: n - 1, ease: 'none' }, 0);
    for (let i = 1; i < n; i++) {
      tl.to(steps[i - 1], { opacity: 0, y: -40, duration: .4 }, i - .45).fromTo(steps[i], { opacity: 0, y: 40 }, { opacity: 1, y: 0, duration: .4 }, i - .1);
    }
    return () => { document.documentElement.classList.remove('js-pin'); g.set(steps, { clearProps: 'all' }); };
  });
  mm.add('(max-width:699px)', () => {
    steps.forEach(s => g.from(s, { opacity: 0, y: 40, duration: .9, ease: 'expo.out', scrollTrigger: { trigger: s, start: 'top 88%', once: true } }));
    g.to(fill, { scaleX: 1, ease: 'none', scrollTrigger: { trigger: '.psteps', start: 'top 70%', end: 'bottom 70%', scrub: true } });
  });
}

function industries(g, ST) {
  const rows = $('.ind-row');
  rows.forEach((r, i) => {
    const dist = () => Math.max(0, r.scrollWidth - innerWidth + 80);
    const dir = i % 2 ? -1 : 1; // RTL: positive x moves toward start-end edge
    g.fromTo(r, { x: () => dir > 0 ? 0 : dist() }, { x: () => dir > 0 ? dist() : 0, ease: 'none',
      scrollTrigger: { trigger: r.parentElement, start: 'top bottom', end: 'bottom top', scrub: .8, invalidateOnRefresh: true } });
  });
}

function toc() {
  const links = $('.toc a');
  if (!links.length) return;
  const map = new Map(links.map(a => [a.getAttribute('href').slice(1), a]));
  const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { links.forEach(l => l.classList.remove('on')); map.get(e.target.id)?.classList.add('on'); } }), { rootMargin: '-30% 0px -60% 0px' });
  map.forEach((_, id) => { const s = document.getElementById(id); s && io.observe(s); });
}

function clock() {
  const c = $('.clock'); if (!c.length) return;
  const f = new Intl.DateTimeFormat('en-GB', { timeZone: 'Africa/Cairo', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
  const t = () => c.forEach(e => e.textContent = f.format(new Date()) + ' CAIRO');
  t(); setInterval(t, 1000);
}

function tabs() {
  $('[data-tabs]').forEach(root => {
    const tabs = $('[role=tab]', root), panels = $('[role=tabpanel]', root);
    const sel = (i, focus) => tabs.forEach((t, k) => { const on = k === i; t.setAttribute('aria-selected', on); t.tabIndex = on ? 0 : -1; panels[k].hidden = !on; if (on && focus) t.focus(); });
    tabs.forEach((t, i) => {
      t.addEventListener('click', () => sel(i));
      t.addEventListener('keydown', e => {
        const k = { ArrowDown: 1, ArrowLeft: 1, ArrowUp: -1, ArrowRight: -1 }[e.key];
        if (k) { e.preventDefault(); sel((i + k + tabs.length) % tabs.length, true); }
      });
    });
    sel(0);
  });
}

function flips() {
  $('.flip').forEach(f => f.addEventListener('click', () => { if (!fine) f.classList.toggle('on'); }));
}

function slider() {
  $('.slider').forEach(s => {
    let down = false, sx = 0, sl = 0, moved = 0;
    s.addEventListener('pointerdown', e => { if (e.pointerType !== 'mouse') return; down = true; moved = 0; sx = e.clientX; sl = s.scrollLeft; s.classList.add('drag'); });
    addEventListener('pointermove', e => { if (!down) return; const d = e.clientX - sx; moved = Math.abs(d); s.scrollLeft = sl - d; });
    addEventListener('pointerup', () => { if (!down) return; down = false; s.classList.remove('drag'); });
  });
}

function spotlight() {
  if (!fine) return;
  $('.card').forEach(c => c.addEventListener('pointermove', e => { const r = c.getBoundingClientRect(); c.style.setProperty('--mx', e.clientX - r.left + 'px'); c.style.setProperty('--my', e.clientY - r.top + 'px'); }));
}

function easter() {
  $('.easter').forEach(el => el.addEventListener('click', () => {
    el.classList.remove('boom'); void el.offsetWidth; el.classList.add('boom');
    const r = el.getBoundingClientRect();
    for (let i = 0; i < 14; i++) {
      const t = document.createElement('i');
      Object.assign(t.style, { position: 'fixed', left: r.left + r.width / 2 + 'px', top: r.top + r.height / 2 + 'px', width: '14px', height: '14px', background: i % 2 ? '#FDBA0F' : '#B79EE8', clipPath: 'polygon(0 0,100% 50%,0 100%)', zIndex: 999, pointerEvents: 'none', transition: 'transform 1s cubic-bezier(.22,1,.36,1),opacity 1s' });
      document.body.append(t);
      const a = Math.random() * 6.283, d = 80 + Math.random() * 140;
      requestAnimationFrame(() => { t.style.transform = `translate(${Math.cos(a) * d}px,${Math.sin(a) * d - 60}px) rotate(${Math.random() * 360}deg)`; t.style.opacity = 0; });
      setTimeout(() => t.remove(), 1100);
    }
  }));
}
