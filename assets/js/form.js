import { CONFIG } from './config.js';
const labels = { 'web-design': 'تصميم مواقع', seo: 'خدمات SEO', hosting: 'استضافة مواقع', other: 'أخرى' };
export function initForms() {
  document.querySelectorAll('[data-form]').forEach(form => {
    const status = form.querySelector('.form-status');
    const btn = form.querySelector('button[type=submit]');
    const rules = {
      name: v => v.trim().length >= 2,
      phone: v => v.replace(/\D/g, '').length >= 8,
      email: v => !v || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)
    };
    const check = f => {
      const r = rules[f.name]; if (!r) return true;
      const ok = r(f.value); f.closest('.field').classList.toggle('invalid', !ok);
      f.setAttribute('aria-invalid', String(!ok)); return ok;
    };
    form.querySelectorAll('input,select,textarea').forEach(f => f.addEventListener('blur', () => check(f)));
    form.addEventListener('submit', e => {
      e.preventDefault();
      const fields = [...form.elements].filter(f => f.name);
      const ok = fields.map(check).every(Boolean);
      status.className = 'form-status';
      if (!ok) { status.classList.add('bad'); status.textContent = 'من فضلك راجع الحقول المميّزة وحاول مرة أخرى.'; form.querySelector('[aria-invalid=true]')?.focus(); return; }
      const d = Object.fromEntries(new FormData(form));
      btn.classList.add('loading'); btn.disabled = true; status.textContent = 'جاري تجهيز طلبك…';
      const msg = `مرحبًا Brandiva، أنا ${d.name}.\nالخدمة: ${labels[d.service] || 'استشارة'}\nالهاتف: ${d.phone}${d.email ? '\nالبريد: ' + d.email : ''}${d.message ? '\nالتفاصيل: ' + d.message : ''}`;
      setTimeout(() => {
        try {
          const w = window.open(`https://wa.me/${CONFIG.waNumber}?text=${encodeURIComponent(msg)}`, '_blank');
          if (!w) throw new Error('blocked');
          w.opener = null;
          status.classList.add('ok'); status.textContent = 'تم تجهيز رسالتك على واتساب — أرسلها وسنردّ عليك في أقرب وقت.';
          form.reset();
        } catch {
          status.classList.add('bad');
          status.innerHTML = `تعذّر فتح واتساب. أرسل طلبك عبر <a class="link-u y" href="mailto:${CONFIG.email}?subject=${encodeURIComponent('طلب استشارة — ' + (labels[d.service] || ''))}&body=${encodeURIComponent(msg)}">البريد الإلكتروني</a>.`;
        }
        btn.classList.remove('loading'); btn.disabled = false;
      }, 600);
    });
  });
}
