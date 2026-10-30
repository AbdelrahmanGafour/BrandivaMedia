import { fine, reduce } from './config.js';
export function initCursor() {
  if (!fine || reduce) return;
  const el = document.querySelector('.cursor');
  if (!el) return;
  document.documentElement.classList.add('has-cursor');
  let x = innerWidth / 2, y = innerHeight / 2, cx = x, cy = y;
  addEventListener('pointermove', e => { x = e.clientX; y = e.clientY; el.classList.remove('is-hidden'); }, { passive: true });
  document.addEventListener('pointerleave', () => el.classList.add('is-hidden'));
  (function loop() { cx += (x - cx) * .22; cy += (y - cy) * .22; el.style.transform = `translate3d(${cx}px,${cy}px,0)`; requestAnimationFrame(loop); })();
  const set = (t) => {
    el.className = 'cursor';
    el.textContent = '';
    if (!t) return;
    const c = t.closest('[data-cursor]');
    if (c) { const k = c.dataset.cursor; el.classList.add('is-' + k); el.textContent = k === 'view' ? 'شاهد' : k === 'drag' ? 'اسحب' : ''; if (k === 'hover') el.classList.add('is-hover'); return; }
    if (t.closest('a,button,summary,input,select,textarea,[role=tab]')) el.classList.add('is-hover');
  };
  document.addEventListener('pointerover', e => set(e.target));
}
