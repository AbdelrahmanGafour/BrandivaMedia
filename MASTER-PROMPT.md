# MASTER PROMPT — Brandiva Media Website

انسخ كل ما تحت الخط وأرسله كرسالة أولى في جلسة Claude Code جديدة داخل فولدر المشروع (`/Users/abdelrhmangafour/Documents/Brandiva`).

---

## ROLE
أنت Creative Director + Senior Front-End Engineer + SEO Strategist في نفس الوقت. مهمتك بناء موقع **Brandiva Media** (وكالة تسويق رقمي/ميديا مصرية) بمستوى مواقع Awwwards: فخم، ترند، حركات سلسة، تفاعل مستمر مع المستخدم، وسريع وصديق لمحركات البحث. لا تسلّم شيئًا "عاديًا" أو شبيهًا بالقوالب.

## 1) المدخلات الموجودة في الفولدر (اقرأها كلها أولًا)
- `brandivaMedia Logo.png` — الهوية البصرية (B بنفسجي/أصفر + مثلث Play + Wordmark).
- `SEO services.docx` — صفحة خدمة SEO كاملة (H1، مقدمة، ما هو SEO، كيف يعمل Google، 13 خدمة فرعية، 6 خطوات عمل، الأدوات، لماذا Brandiva، القطاعات، النتائج، المدة، 8 أخطاء شائعة، 20 سؤال شائع، CTA).
- `website design.docx` — صفحة `/web-design` (Meta Title/Description جاهزة، 5 أنواع مواقع، 6 مميزات، 7 خطوات تنفيذ، لماذا Brandiva، التسعير، 18 FAQ).
- `web hosting.docx` — صفحة `/web-hosting` (Meta جاهزة، 6 خدمات: WordPress/VPS/Cloud/Domain/SSL/Migration، 10 مميزات، 7 خطوات، 8 FAQ، CTA). ملاحظة: آخر الملف فيه ملاحظة SEO عن استهداف عنقود كلمات (استضافة مواقع، استضافة WordPress، VPS، استضافة مواقع في مصر…) — طبّقها في الـ H1/Meta/النصوص.
- `STYLE-GUIDE.md` — دليل الستايل (ألوان، خطوط، حركة، مكونات). **التزم به بالكامل، ولو لقيت تحسين ممكن عدّله ووثّقه.**

**الخطوة 0:** استخرج نصوص الـ docx (`unzip -p file.docx word/document.xml`) وافهم كل محتوى بعمق، ثم اعمل `content/data.json` أو `content.md` منظم بالأقسام. **ممنوع تخترع أرقامًا أو عملاء أو شهادات أو أسعار**؛ أي بيانات ناقصة (أرقام تواصل، لوجوهات عملاء، إحصائيات، شهادات) اعمل لها placeholders واضحة معلّمة `TODO` واذكرها في تقرير نهائي.

## 2) المرجع (Reference)
موقع: https://peak-creative-template.framer.website — ادخله بالمتصفح المدمج (`mcp__Claude_Browser__*`) وافتحه Desktop + Mobile، واسكرول كامل الصفحة. تحليلي له:
- **البنية بالترتيب:** Hiring/announcement pill → Header (لوجو + زر MENU يفتح overlay) → Hero عملاق بسطرين مع صورة خلفية داكنة (جبل أبيض/أسود) ونقطة حمراء مميزة → **قائمة أعمال (Selected Work)** صفوف: اسم + وسوم الخدمات + السنة + صورة تظهر عند hover → **About** ("Based in" + أعلام + نص + "Get to know us") بكروت Bento (عدد الفريق، تقييم Trustpilot، Behance badges، ساعتان حيتان لمدينتين، خريطة نقاط dotted للعالم) → **Marquee** كلمات عريضة مفصولة بنجمة (RESPONSIBILITY ✦ VISION ✦ …) → **Services accordion** (صف كبير + صورة صغيرة + `+`) → **Testimonials** (3) → **Featured clients** (logo strip) → **CTA ضخم** "LET'S TALK" → **Footer** كبير.
- **الستايل:** أسود كامل، تيبوغرافي Satoshi 900 بحجم 120px و tracking -3.75px، نصوص ثانوية رمادي #BDBDBD، Space Mono للـ labels، كروت داكنة بزوايا كبيرة جدًا، لون تمييز واحد (أحمر) — **استبدله عندنا بأصفر #FDBA0F + بنفسجي #B79EE8**.
- **الحركة:** reveals أثناء السكرول، كروت بتظهر بالتتابع، Marquee مستمر، Hover image reveal، تحميل سلس.
**نفّذ نفس الهيكل والإحساس (Structure + Rhythm) ولكن بمحتوى Brandiva وهويتها، وبدون نسخ أي كود/صور/نصوص من المرجع.**

## 3) خريطة الموقع (Multi-page HTML)
```
/index.html            الرئيسية (ملخص كل الخدمات + إثبات + CTA)
/web-design.html       تصميم مواقع احترافية
/seo.html              خدمات SEO
/web-hosting.html      استضافة مواقع
/contact.html          تواصل / طلب استشارة (فورم + واتساب)
/404.html
/assets/{css,js,img,fonts,video}
sitemap.xml · robots.txt · manifest
```
### أقسام الصفحة الرئيسية (بالترتيب)
1. **Preloader** (لوجو B يُرسم ثم ينفتح) + Announcement pill ("استشارة مجانية — ابدأ مشروعك").
2. **Header** زجاجي sticky + Menu overlay بتحريك + زر لغة (AR/EN اختياري) + CTA أصفر.
3. **Hero**: عنوان Display ضخم Split-text (مثال: "نصمّم حضورك الرقمي… ونوصّله لعملائك") + سطر فرعي + CTAs (أصفر/ghost) + عنصر 3D/Particles خفيف بشكل B/Play يتفاعل مع الماوس + مؤشر سكرول + شريط ثقة صغير.
4. **Services as Work-list** (بدل Selected Work): 3 صفوف ضخمة (تصميم مواقع / SEO / استضافة) بأرقام 01-02-03 + وسوم + سهم، وعند hover تظهر صورة تتبع المؤشر، والكليك يفتح الصفحة.
5. **About / Why Brandiva** بـ Bento Grid: كروت (نهجنا: تحليل ← استراتيجية ← تنفيذ ← قياس) + مميزات من الملفات (سرعة، SEO-friendly، تقارير دورية، دعم فني، التزام بالمواعيد) + عدّادات **فقط بأرقام حقيقية يعطيها العميل**، وإلا خلّيها "نتائج قابلة للقياس" كنص.
6. **Marquee** (حركتين متعاكستين): تحليل ✦ استراتيجية ✦ تصميم ✦ سرعة ✦ أمان ✦ نتائج.
7. **Process (Pinned scroll)**: خطوات العمل (فهم النشاط ← تحليل المنافسين ← تخطيط ← تصميم ← تطوير ← اختبار ← إطلاق ← متابعة)، كل خطوة تظهر مع السكرول مع شريط تقدم.
8. **Industries**: القطاعات (شركات، متاجر، عيادات، محاماة، مقاولات، عقارات، سياحة، مطاعم، تعليم، برمجيات، لوجستيات…) كـ chips/Horizontal scroll تفاعلية.
9. **Tools & Tech**: Search Console، Analytics، Keyword Planner، PageSpeed… (شعارات نصية، لا تستخدم شعارات رسمية بدون إذن).
10. **Testimonials**: slider (drag) — placeholders فقط حتى يوفر العميل آراء حقيقية.
11. **FAQ مجمّعة** (أهم 6-8 أسئلة من الملفات) `<details>` + FAQPage Schema.
12. **CTA ضخم** "لا تؤجل نمو نشاطك" + فورم سريع/واتساب + Marquee خلفي.
13. **Footer كبير**: لوجو، روابط الصفحات، الخدمات، تواصل، Social، ساعة حية بتوقيت القاهرة، زر Back-to-top، اسم الوكالة عملاق Outline.

### صفحات الخدمات
كل صفحة: Hero بـ H1 المأخوذ حرفيًا من الملف ← مقدمة ← الأقسام بالترتيب في ملفها ← Sticky TOC جانبي ← Process ← FAQ كامل (من الملف) ← CTA. استخدم عرض مختلف لكل نوع محتوى: خدمات SEO الـ13 كـ Bento/Tabs، أنواع المواقع كـ cards بصور، خطط الاستضافة كـ Cards مقارنة (بدون أسعار — "اطلب عرض سعر")، الأخطاء الشائعة كـ Flip/Stacked cards، الأسئلة كـ Accordion. **النصوص العربية من الملفات تُستخدم كما هي (يمكن تقسيمها/تقصيرها للتصميم لكن بدون تغيير المعنى).**

## 4) الهوية والستايل
طبّق `STYLE-GUIDE.md`: Dark-first (`#07060B`)، أصفر `#FDBA0F` للأكشن، بنفسجي `#B79EE8` للتمييز الثانوي، RTL، خط عربي (IBM Plex Sans Arabic/Cairo) + Plus Jakarta Sans للعناوين اللاتينية + Space Mono للـ labels. استخدم عناصر اللوجو (القطع القطري + مثلث Play + نصفا B) كلغة تصميم متكررة (فواصل أقسام مائلة، cursor، ديكور خلفي). حوّل اللوجو لـ SVG نظيف (trace يدوي) للـ preloader والـ favicon.

## 5) الحركة والتفاعل (Motion & Interaction)
- **المكتبات (CDN بنسخ ثابتة):** GSAP + ScrollTrigger + SplitText-alternative (SplitType) + Lenis (smooth scroll). Three.js/OGL **فقط** لو الـ hero يستفيد منه وبحجم خفيف (أو Canvas 2D particles).
- Split-text reveals للعناوين • Image clip-path reveal • Parallax • Pinned sections • Horizontal scroll • Marquee بسرعة تتغير مع سرعة السكرول • عدّادات • Magnetic buttons • Custom cursor بحالات (hover/drag/view) • Cards spotlight/tilt • Scroll progress • Menu overlay بتحريك • Page transitions بين الصفحات (View Transitions API مع fallback) • Hover image-follow في قائمة الخدمات • Easter egg بسيط (مثلث Play ينبض).
- **منع الملل:** كل سكرولة (≈ 100vh) فيها لحظة حركة أو تفاعل مختلفة، ومفيش قسمين متتاليين بنفس الـ layout. إيقاع: كبير ← هادي ← تفاعلي ← بصري.
- **الأداء والإتاحة:** transform/opacity فقط، `will-change` بحذر، `prefers-reduced-motion` يوقف الحركات الثقيلة، الكيرسر المخصص desktop فقط، الموقع يشتغل كامل بدون JS (محتوى ظاهر + progressive enhancement).

## 6) الصور والأصول
- ابحث على الإنترنت عن صور **عالية الجودة (≥ 1600px عرض)** وفيديوهات قصيرة مرخصة للاستخدام التجاري من **Unsplash / Pexels / Pixabay** (لا تستخدم أي صورة من Google Images أو مواقع بدون ترخيص). ثبّت المصدر والمصوّر والرخصة في `assets/CREDITS.md`.
- مواضيع البحث: digital marketing team workshop، SEO analytics dashboard، Google search results، website design wireframe، UI/UX designer workspace، responsive devices mockup، data center servers، cloud hosting، cybersecurity SSL، code on screen dark، ecommerce online store، clinic/law office/restaurant (للقطاعات)، Arab/Middle-East business people (بتنوع واقعي)، تجريد gradient أصفر/بنفسجي.
- نزّلها بـ `curl` داخل `assets/img/raw/` (كل ملف في مكان جديد)، حوّلها لـ WebP/AVIF بأحجام متعددة، طبّق عليها grade موحد (Duotone/dark overlay + لمسة هوية) وتأكد إنها مرتبطة فعلًا بمحتوى القسم. أي صورة ضعيفة أو غير متصلة بالموضوع تُرفض.
- ولّد خلفيات/تجريدات بـ CSS/SVG/Canvas حيث يناسب بدل الصور.

## 7) المهارات (Skills) المطلوب استخدامها — استدعِها بالترتيب
1. `ui-ux-pro-max:ui-ux-pro-max` + `ui-ux-pro-max:design-system` — بنية التصميم والـ tokens.
2. `taste-skill:design-taste-frontend` + `taste-skill:high-end-visual-design` + `example-skills:frontend-design` — جودة بصرية عالية وتجنب شكل "AI العادي".
3. `modern-web-design:modern-web-design` — الترندات (Bento، scrollytelling، micro-interactions).
4. `gsap-skills:gsap-core` / `gsap-timeline` / `gsap-scrolltrigger` / `gsap-performance` — الحركة والسكرول.
5. `locomotive-scroll:locomotive-scroll` أو Lenis للسكرول الناعم؛ `scroll-reveal-libraries` كبديل خفيف.
6. `lightweight-3d-effects` / `threejs-webgl` — عنصر الـ hero (لو أضاف قيمة).
7. `searchfit-seo:on-page-seo` + `technical-seo` + `schema-markup` + `seo-audit` — الـ Meta، Schema (Organization، Service، FAQPage، BreadcrumbList، LocalBusiness)، sitemap، robots، canonical، hreflang لو في EN.
8. `design:accessibility-review` + `design:ux-copy` — الإتاحة وكتابة الـ microcopy.
9. `example-skills:webapp-testing` — اختبار فعلي بالمتصفح (Desktop/Tablet/Mobile، console بدون أخطاء، سكرول، فورم).
10. `taste-skill:image-to-code` — اختياري: ولّد مرجع بصري للـ hero أولًا ثم نفّذه.
لو في Skill ناقصة أو محتاج Plugin، ابحث بـ `SearchPlugins`/`SearchSkills` وثبّته. وزّع الشغل على Subagents متوازية (حركة/محتوى SEO/صور) لما ينفع.

## 8) المتطلبات التقنية
- **HTML5 دلالي + CSS3 + Vanilla JS (ES modules)** فقط؛ بدون React/Tailwind/Build step (CDN للمكتبات بنسخ ثابتة + نسخة محلية احتياطية).
- Semantic: `header, nav, main, section[aria-labelledby], article, aside, figure/figcaption, footer`، H1 واحد/صفحة، تسلسل Headings سليم، `lang="ar" dir="rtl"`، Skip link، ARIA بحذر.
- CSS: Custom properties، Logical properties، `clamp()`، Grid/Flex، `@layer`، Container Queries، `:has()`، `@media (prefers-color-scheme / reduced-motion)`.
- Responsive Mobile-first (360 → 1920+)، تجربة موبايل مصممة خصيصًا (مش مجرد تصغير): hamburger، Hero مختصر، horizontal scroll بالسحب، Touch targets ≥ 44px، سيناريو بدون cursor.
- SEO داخل الكود: Title/Meta/OG/Twitter لكل صفحة (استخدم النصوص الجاهزة في الملفات)، Schema JSON-LD، روابط داخلية بين الصفحات، `alt` عربي وصفي، URL slugs نظيفة، Canonical.
- أداء: Lighthouse ≥ 95 موبايل، LCP < 2.5s، CLS < 0.1، تحميل الخطوط بـ preload + swap، Lazy-load، فيديو مضغوط < 2MB مع poster.
- فورم التواصل: Validation + حالات (loading/success/error) + رابط واتساب جاهز (رقم placeholder) + `mailto` بديل.

## 9) خطوات التنفيذ
1. Analysis: اقرأ كل الملفات + المرجع → اكتب `ANALYSIS.md` (محتوى، جمهور، pain points، رسائل التسويق، فجوات المحتوى، Site map، Wireframe نصي لكل قسم، قائمة الحركات).
2. Design System: اعتمد/حسّن `STYLE-GUIDE.md` وحوّله لـ `tokens.css`.
3. Assets: لوجو SVG + صور مرخصة محسّنة + `CREDITS.md`.
4. Build: ابدأ بـ index (Hero أولًا) ثم باقي الأقسام ثم صفحات الخدمات.
5. Motion pass: أضف الحركات بعد ما الـ layout يثبت.
6. SEO + Accessibility pass.
7. QA بالمتصفح (Desktop 1440 / Tablet 768 / Mobile 375) + Lighthouse + فحص console + اختبار reduced-motion؛ صلّح قبل التسليم.
8. اعرض لي قبل البدء: **خطة قصيرة + 3 اتجاهات للـ Hero** لاختيار واحد، وبعد كده نفّذ بدون توقف.

## 10) معايير القبول (Definition of Done)
- كل نص الملفات الثلاثة مستخدم في صفحته الصحيحة، بدون اختراع معلومات.
- الشكل يوحي "وكالة مميزة"، متسق مع اللوجو، ولا يشبه قالب جاهز ولا المرجع حرفيًا.
- 0 أخطاء Console، 0 overflow أفقي في أي عرض، كل الروابط تعمل.
- تقرير نهائي يشمل: ملخص ما بُني، قائمة TODO (بيانات التواصل، شهادات، أرقام، لوجوهات عملاء)، مصادر الصور، نتائج Lighthouse، وطريقة تشغيل الموقع محليًا (`python3 -m http.server`).
