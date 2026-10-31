import { CONFIG } from './config.js';
const AR = document.documentElement.lang === 'ar';
const labels = AR ? { 'web-design': 'تصميم مواقع', seo: 'خدمات SEO', hosting: 'استضافة مواقع', other: 'أخرى' } : { 'web-design': 'Web design', seo: 'SEO services', hosting: 'Web hosting', other: 'Other' };
const T = AR ? {
  bad: 'من فضلك راجع الحقول المميّزة وحاول مرة أخرى.', busy: 'جاري تجهيز طلبك…',
  msg: d => `مرحبًا Brandiva، أنا ${d.name}.\nالخدمة: ${labels[d.service] || 'استشارة'}\nالهاتف: ${d.phone}${d.email ? '\nالبريد: ' + d.email : ''}${d.message ? '\nالتفاصيل: ' + d.message : ''}`,
  ok: 'تم تجهيز رسالتك على واتساب — أرسلها وسنردّ عليك في أقرب وقت.', fail: 'تعذّر فتح واتساب. أرسل طلبك عبر', mail: 'البريد الإلكتروني', subj: 'طلب استشارة — '
} : {
  bad: 'Please check the highlighted fields and try again.', busy: 'Preparing your request…',
  msg: d => `Hello Brandiva, I'm ${d.name}.\nService: ${labels[d.service] || 'Consultation'}\nPhone: ${d.phone}${d.email ? '\nEmail: ' + d.email : ''}${d.message ? '\nDetails: ' + d.message : ''}`,
  ok: 'Your message is ready on WhatsApp — send it and we will reply shortly.', fail: 'Could not open WhatsApp. Send your request by', mail: 'email', subj: 'Consultation request — '
};
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
      if (!ok) { status.classList.add('bad'); status.textContent = T.bad; form.querySelector('[aria-invalid=true]')?.focus(); return; }
      const d = Object.fromEntries(new FormData(form));
      btn.classList.add('loading'); btn.disabled = true; status.textContent = T.busy;
      const msg = T.msg(d);
      setTimeout(() => {
        try {
          const w = window.open(`https://wa.me/${CONFIG.waNumber}?text=${encodeURIComponent(msg)}`, '_blank');
          if (!w) throw new Error('blocked');
          w.opener = null;
          status.classList.add('ok'); status.textContent = T.ok;
          form.reset();
        } catch {
          status.classList.add('bad');
          status.innerHTML = `${T.fail} <a class="link-u y" href="mailto:${CONFIG.email}?subject=${encodeURIComponent(T.subj + (labels[d.service] || ''))}&body=${encodeURIComponent(msg)}">${T.mail}</a>.`;
        }
        btn.classList.remove('loading'); btn.disabled = false;
      }, 600);
    });
  });
}
