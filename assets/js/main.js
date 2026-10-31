import { reduce } from './config.js';
import { initCursor } from './cursor.js';
import { initHero } from './hero.js';
import { initFx } from './fx.js';
import { initForms } from './form.js';

window.__bvOK = true;
const root = document.documentElement;
const $ = (s, r = document) => [...r.querySelectorAll(s)];
let lenis = null;

function smooth() {
  if (reduce || !window.Lenis) return;
  lenis = new Lenis({ duration: 1.15, easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)), smoothWheel: true });
  window.__lenis = lenis;
  if (window.gsap && window.ScrollTrigger) {
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(t => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
  } else { (function r(t) { lenis.raf(t); requestAnimationFrame(r); })(0); }
  $('a[href^="#"]').forEach(a => a.addEventListener('click', e => {
    const id = a.getAttribute('href'); if (id.length < 2) return;
    const t = document.querySelector(id); if (!t) return;
    e.preventDefault(); lenis.scrollTo(t, { offset: -80 });
    history.replaceState(null, '', id);
  }));
}

function header() {
  const h = document.querySelector('.site-header'); if (!h) return;
  const on = () => h.classList.toggle('is-stuck', scrollY > 24);
  on(); addEventListener('scroll', on, { passive: true });
  const btn = document.querySelector('.menu-btn'), menu = document.getElementById('menu');
  if (!btn || !menu) return;
  const set = open => {
    btn.setAttribute('aria-expanded', open); btn.querySelector('span').textContent = open ? btn.dataset.close : btn.dataset.open;
    menu.classList.toggle('is-open', open);
    open ? lenis?.stop() : lenis?.start();
    document.body.style.overflow = open ? 'hidden' : '';
    if (open && window.gsap && !reduce) gsap.from($('.menu-links a, .menu-foot > *', menu), { y: 60, opacity: 0, duration: .9, ease: 'expo.out', stagger: .06 });
    if (open) menu.querySelector('a').focus(); else btn.focus();
  };
  btn.addEventListener('click', () => set(btn.getAttribute('aria-expanded') !== 'true'));
  addEventListener('keydown', e => { if (e.key === 'Escape' && menu.classList.contains('is-open')) set(false); });
  $('a', menu).forEach(a => a.addEventListener('click', () => set(false)));
}

function transitions() {
  if (CSS.supports?.('selector(:target)') && 'CSSViewTransitionRule' in window) return; // native cross-document VT
  const wipe = document.createElement('div'); wipe.className = 'wipe'; wipe.setAttribute('aria-hidden', 'true'); document.body.append(wipe);
  addEventListener('pageshow', () => wipe.classList.remove('in'));
  document.addEventListener('click', e => {
    const a = e.target.closest('a'); if (!a || e.defaultPrevented || e.metaKey || e.ctrlKey || e.shiftKey || a.target) return;
    const u = new URL(a.href, location.href);
    if (u.origin !== location.origin || u.pathname === location.pathname || a.hasAttribute('download')) return;
    e.preventDefault(); wipe.classList.add('in'); setTimeout(() => location.href = u.href, 450);
  });
}

function preloader() {
  const pre = document.querySelector('.preloader');
  const ready = () => { root.classList.add('is-ready'); dispatchEvent(new Event('brandiva:ready')); };
  if (!pre) return ready();
  const seen = sessionStorage.getItem('bv-seen');
  if (seen || reduce || !window.gsap) { pre.remove(); return ready(); }
  sessionStorage.setItem('bv-seen', '1');
  document.body.style.overflow = 'hidden';
  const logo = new Image(); logo.src = pre.dataset.logo; logo.alt = ''; logo.width = 300; logo.height = 199; pre.append(logo);
  const done = () => { document.body.style.overflow = ''; pre.remove(); ready(); };
  const tl = gsap.timeline({ onComplete: done });
  tl.from(logo, { scale: .86, y: 12, duration: .9, ease: 'expo.out' })
    .to(logo, { scale: 1.08, duration: .5, ease: 'power2.in' }, '+=.25')
    .to(pre, { clipPath: 'polygon(0 0,100% 0,100% 0,0 0)', duration: .9, ease: 'expo.inOut' }, '-=.05');
  setTimeout(() => { if (document.body.contains(pre)) { tl.progress(1); } }, 4500);
}

root.classList.add('js');
header();
smooth();
initCursor();
initFx(lenis);
initHero();
initForms();
transitions();
preloader();
addEventListener('load', () => window.ScrollTrigger?.refresh());
