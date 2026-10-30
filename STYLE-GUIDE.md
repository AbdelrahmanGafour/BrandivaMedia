# Brandiva Media — Website Style Guide

> المصدر: اللوجو (`brandivaMedia Logo.png`) + المرجع البصري (Peak Creative Studio). القيم اللونية مقدّرة من اللوجو، تُراجَع بأداة eyedropper قبل الاعتماد النهائي.

## 1. Brand Identity (من اللوجو)
- **الرمز:** حرف B مجسّم من نصفين — نصف بنفسجي (مستطيل مقصوص قطريًا) ونصف أصفر، وبداخله مثلث Play. يعني: Media / Video / Motion + Branding.
- **Wordmark:** "Brandiva" بالأصفر (Bold، Rounded Geometric Sans) + "Media" بالبنفسجي (Light/Regular).
- **الشخصية:** حيوية، واثقة، إبداعية، عصرية، قريبة من العميل. **النبرة:** احترافية بسيطة، نتائج قبل الوعود، بدون مبالغة (متوافقة مع نصوص الملفات: "لا نقدم وعودًا غير واقعية").
- **العناصر المميزة القابلة لإعادة الاستخدام:** الزاوية المقطوعة بميل (diagonal cut) • مثلث Play • نصفا B (أصفر/بنفسجي) كشكل ديكور.

## 2. Color Tokens
| Token | Value | Usage |
|---|---|---|
| `--yellow-500` | `#FDBA0F` | Primary: CTA، تمييز كلمات، أرقام، أيقونات |
| `--yellow-400` | `#FFD24D` | Hover / glow |
| `--yellow-600` | `#E5A000` | Pressed |
| `--purple-400` | `#B79EE8` | Secondary: وسوم، خطوط، highlights، "Media" |
| `--purple-600` | `#7B5CD6` | Gradients / focus ring |
| `--ink-950` | `#07060B` | خلفية الموقع الأساسية (Dark، مثل المرجع) |
| `--ink-900` | `#0F0D16` | أقسام بديلة |
| `--ink-800` | `#171421` | Cards |
| `--ink-700` | `#231F33` | Borders / dividers |
| `--text-100` | `#FFFFFF` | عناوين |
| `--text-300` | `#BDBDBD` | نص ثانوي (مطابق للمرجع) |
| `--text-500` | `#8A8697` | Captions |
| `--light-bg` | `#FAF7F0` | قسم Light واحد للتباين (اختياري) |

- **Gradients:** `linear-gradient(135deg, #FDBA0F, #B79EE8)` للنصوص/الخطوط الرفيعة فقط؛ `radial-gradient(circle, rgba(183,158,232,.25), transparent 60%)` كـ glow خلفي.
- **قاعدة 60/30/10:** 60% داكن، 30% أبيض/رمادي نص، 10% أصفر + بنفسجي. الأصفر هو اللون الوحيد للـ CTA.
- **Contrast:** نص على الأصفر = `--ink-950` (≥ 9:1). لا نص بنفسجي فاتح على أبيض.

## 3. Typography
- **Arabic (عناوين + نص):** `IBM Plex Sans Arabic` أو `Cairo` أو `Tajawal` (Google Fonts) — أوزان 400/500/700/800.
- **Latin (عناوين كبيرة):** `Plus Jakarta Sans` أو `Satoshi`-like (Geometric) — 800/900، tracking سالب (`-0.03em`).
- **Mono (labels / eyebrows / أرقام):** `Space Mono` (مطابق للمرجع) — 0.5px tracking، UPPERCASE للوسوم.
- **Scale (fluid):**
  - Display: `clamp(3rem, 9vw, 8rem)` / line-height 0.95
  - H1: `clamp(2.25rem, 5vw, 4.5rem)`
  - H2: `clamp(1.75rem, 3.5vw, 3rem)`
  - H3: `1.375rem–1.75rem`
  - Body: `1rem–1.125rem` / line-height 1.8 (عربي)
  - Label: `0.75rem` mono uppercase
- العربية: لا letter-spacing سالب على النص العربي، فقط على اللاتيني.

## 4. Layout & Spacing
- **اتجاه:** `<html lang="ar" dir="rtl">`، واستخدم Logical Properties (`margin-inline`, `padding-block`, `inset-inline-start`).
- **Container:** max 1320px، padding-inline `clamp(1rem, 4vw, 3rem)`.
- **Spacing scale (8pt):** 4 • 8 • 16 • 24 • 32 • 48 • 64 • 96 • 128 • 160.
- **Section padding:** `clamp(5rem, 12vw, 10rem)` رأسيًا — مساحات فارغة سخية.
- **Grid:** 12 أعمدة؛ Bento grid للإحصائيات والمميزات.
- **Radius:** Cards 28px • Buttons/Pills 999px • Inputs 16px.
- **Breakpoints:** 480 • 768 • 1024 • 1280 • 1536 (Mobile-first).

## 5. Components
- **Button Primary:** خلفية `--yellow-500`، نص `--ink-950`، pill، سهم يدور 45° عند hover، **magnetic hover** + ripple.
- **Button Ghost:** حد `--ink-700`، hover يملأ بنفسجي من الاتجاه.
- **Pill/Badge:** خلفية `rgba(255,255,255,.06)`، حد 1px، نقطة نابضة صفراء + نص mono (مثل "WE'RE HIRING" في المرجع).
- **Card:** `--ink-800`، حد 1px `--ink-700`، radius 28px، spotlight يتبع المؤشر (radial-gradient عند mouse).
- **Service Row (Accordion):** صف كبير: أيقونة/صورة صغيرة + عنوان + `+` يتحول `×`، يفتح بـ height animation.
- **Marquee:** كلمات عريضة مفصولة بنجمة/مثلث Play أصفر (مثل RESPONSIBILITY ✦ VISION).
- **FAQ:** `<details>` دلالي + animation.
- **Nav:** شفاف → blur glass عند السكرول، Hamburger يفتح Fullscreen overlay.
- **Cursor:** دائرة مخصصة تكبر على العناصر التفاعلية (desktop فقط) وتتحول لنص "View/شاهد" على الصور.

## 6. Motion System
- **Easing:** `cubic-bezier(.22, 1, .36, 1)` (expo-out) افتراضي؛ `cubic-bezier(.65, 0, .35, 1)` للانتقالات.
- **Durations:** micro 150–250ms • reveal 700–1000ms • page 600ms • stagger 60–90ms.
- **Reveals:** Split-text (سطر/كلمة) يطلع من mask، Fade-up 40px، Clip-path image reveal، Counter يعدّ للرقم.
- **Scroll:** smooth scroll (Lenis)، parallax على الصور، Pinned section للـ "خطوات العمل" بحيث كل خطوة تظهر وقت السكرول، horizontal scroll للـ Showcase، scroll-progress bar أصفر.
- **Hero:** عنوان ضخم Split + عنصر 3D/Particles خفيف (B + Play) يتفاعل مع الماوس.
- **Hover:** Image reveal يتبع المؤشر على صفوف الخدمات، tilt للكروت، underline draw.
- **Preloader:** لوجو B يُرسم (SVG stroke) ثم نصفاه ينفتحان ويكشفان الموقع.
- **Accessibility:** احترام `prefers-reduced-motion` (تعطيل parallax/marquee/cursor)، كل الحركة `transform`/`opacity` فقط، 60fps.

## 7. Imagery & Iconography
- **الصور:** Duotone/ dark-graded بحيث تتماشى مع الأسود + لمسة أصفر/بنفسجي (overlay `mix-blend-mode`)، نسب 4:5 و16:9، فريق/مكاتب/شاشات/لوحات تحليلات/سيرفرات.
- **أيقونات:** Lucide/Phosphor stroke 1.5px، بزوايا مدورة.
- **ممنوع:** Stock مبتذل (مصافحة، لمبة)، إيموجي كأيقونات، ألوان خارج الـ tokens.

## 8. Accessibility & Quality
- WCAG 2.2 AA، Focus ring أصفر 3px، Skip link، `alt` وصفي بالعربية، Landmarks (`header/nav/main/section/article/aside/footer`)، ترتيب Headings سليم (H1 واحد لكل صفحة).
- Lighthouse هدف ≥ 95 (Performance/SEO/Accessibility/Best Practices)، LCP < 2.5s، CLS < 0.1، INP < 200ms.
- صور WebP/AVIF + `loading="lazy"` + `srcset`، خطوط `font-display: swap`.


## 9. تعديلات التنفيذ (موثّقة)
- **Mono للعربي:** `letter-spacing:0` على `.mono` (التباعد يكسر اتصال الحروف العربية). خط الـ fallback IBM Plex Sans Arabic.
- **Split-text:** بالكلمات فقط (بدون حروف) لنفس السبب؛ عناوين الـ Hero وصفحات الخدمات تدخل بـ CSS فقط لحماية LCP.
- **صورة الـ Hero:** تُرسم على `<canvas>` بعد التحميل حتى لا تصبح عنصر LCP.
- **الخطوط:** مستضافة محليًا (arabic + latin subsets فقط) بدل Google Fonts CDN.
- **المكتبات:** نسخ محلية مثبّتة بدل CDN (أسرع، تعمل offline).
- **الألوان:** الأصفر يبقى اللون الوحيد للـ CTA؛ البنفسجي للتمييز الثانوي والخطوط والـ glow.
