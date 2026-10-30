# Brandiva Media — الموقع

HTML5 + CSS3 + Vanilla JS (ES modules). لا React ولا Tailwind ولا Build step للموقع نفسه.

## التشغيل محليًا
```bash
python3 -m http.server 8000
# افتح http://localhost:8000
```

## الصفحات
`index.html` · `web-design.html` · `seo.html` · `web-hosting.html` · `contact.html` · `404.html`
+ `sitemap.xml` · `robots.txt` · `manifest.webmanifest`

## تعديل المحتوى / إعادة التوليد (أداة تأليف فقط)
نصوص الصفحات مأخوذة حرفيًا من ملفات الـ docx (مستخرجة في `content/*.txt`).
الصفحات HTML ثابتة، ويتم توليدها بسكربت Python اختياري:
```bash
python3 tools/bundle_css.py   # يدمج assets/css/fonts.css + assets/css/src/*.css في site.css
python3 tools/build.py        # يعيد توليد كل صفحات HTML + content/data.json
```
الأرقام/الروابط الناقصة (واتساب، بريد، دومين، سوشيال) في `assets/js/config.js` و`tools/build.py` معلّمة `TODO`.

## الهيكل
- `assets/css/src/` — tokens · base · components · pages · motion (باستخدام `@layer`)
- `assets/js/` — `main.js` (boot/Lenis/menu/preloader/transitions) · `fx.js` (GSAP/ScrollTrigger) · `hero.js` · `cursor.js` · `form.js`
- `assets/js/vendor/` — GSAP 3.12.5 · ScrollTrigger · SplitType 0.3.4 · Lenis 1.1.13 (نسخ محلية مثبّتة)
- `assets/img/` — صور WebP (800/1600) + `raw/` الأصول الأصلية + `CREDITS.md`
