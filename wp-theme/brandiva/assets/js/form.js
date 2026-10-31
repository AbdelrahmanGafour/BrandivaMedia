// Contact Form 7 integration: keeps the original WhatsApp hand-off (optional) on top of CF7's own AJAX mail.
// Delivery mode (Theme Options → Forms): email | whatsapp | both — exposed as data-mode on .bv-form.
import { CONFIG } from './config.js';

const S = window.BV?.form || {};
const clean = v => (v || '').toString().trim();

function waText(form, tpl) {
  const get = n => clean(form.querySelector(`[name="${n}"]`)?.value);
  const svcSel = form.querySelector('[name="service"]');
  const svc = svcSel ? clean(svcSel.options[svcSel.selectedIndex]?.text) : '';
  const map = { name: get('your-name'), phone: get('your-phone'), service: svc, email: get('your-email'), message: get('your-message') };
  let out = tpl.replace(/\{(\w+)\}/g, (_, k) => map[k] || '');
  // drop lines whose value is empty (e.g. "Email: ")
  return out.split('\n').filter(l => !/[:：]\s*$/.test(l.trim())).join('\n').trim();
}

export function initForms() {
  document.querySelectorAll('.bv-form').forEach(wrap => {
    const mode = wrap.dataset.mode || 'email';
    const form = wrap.querySelector('form');
    if (!form) return;
    const tpl = wrap.dataset.tpl || S.template || '';
    const url = text => `https://wa.me/${CONFIG.waNumber}?text=${encodeURIComponent(text)}`;
    let popup = null;

    // ?service=hosting on the contact page pre-selects the dropdown
    const q = new URLSearchParams(location.search).get('service');
    const sel = form.querySelector('select[name="service"]');
    const order = { 'web-design': 1, seo: 2, hosting: 3, other: 4 }; // option order in the CF7 form (0 = placeholder)
    if (q && sel && order[q] && sel.options[order[q]]) sel.selectedIndex = order[q];

    form.addEventListener('submit', e => {
      form.classList.add('submitting');
      if (mode === 'whatsapp') {
        // WhatsApp-only: don't let CF7 send mail
        e.preventDefault(); e.stopImmediatePropagation();
        const name = form.querySelector('[name="your-name"]'), phone = form.querySelector('[name="your-phone"]');
        const ok = clean(name?.value).length >= 2 && clean(phone?.value).replace(/\D/g, '').length >= 8;
        const out = form.querySelector('.wpcf7-response-output');
        form.classList.remove('submitting');
        if (!ok) { if (out) { out.textContent = S.bad || ''; form.className = form.className.replace(/\b(sent|init)\b/g, '') + ' invalid'; } return; }
        const w = window.open(url(waText(form, tpl)), '_blank');
        if (w) w.opener = null;
        if (out) { out.textContent = w ? (S.ok || '') : (S.fail || ''); form.classList.remove('invalid'); form.classList.add('sent'); }
        if (w) form.reset();
        return;
      }
      if (mode === 'both') { popup = window.open('about:blank', '_blank'); } // open synchronously to avoid popup blockers
    }, true);

    wrap.addEventListener('wpcf7mailsent', () => {
      form.classList.remove('submitting');
      if (mode === 'both' && CONFIG.waNumber) {
        const text = waText(form, tpl);
        if (popup && !popup.closed) { popup.location.href = url(text); popup.opener = null; }
        else window.open(url(text), '_blank');
      }
      popup = null;
    });
    ['wpcf7invalid', 'wpcf7spam', 'wpcf7mailfailed', 'wpcf7submit'].forEach(ev => wrap.addEventListener(ev, e => {
      form.classList.remove('submitting');
      if (ev !== 'wpcf7submit' && popup && !popup.closed) { popup.close(); popup = null; }
      if (ev === 'wpcf7submit' && e.detail?.status !== 'mail_sent' && popup && !popup.closed) { popup.close(); popup = null; }
    }));
  });
}
