// TODO: استبدل القيم دي ببيانات Brandiva الحقيقية
export const CONFIG = {
  waNumber: '201000000000',        // TODO: رقم واتساب (بصيغة دولية بدون +)
  email: 'info@brandivamedia.com', // TODO: البريد الرسمي
  site: 'https://brandivamedia.com' // TODO: الدومين النهائي
};
export const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
export const fine = matchMedia('(hover:hover) and (pointer:fine)').matches;
