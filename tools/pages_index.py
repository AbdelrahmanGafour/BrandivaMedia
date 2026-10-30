# ---------------------------------------------------------------- index.html  (exec'd inside build.py)
IND = [strip_dot(x) for x in T(SEO, 255, 267)]
# 8 process steps (text taken verbatim from the three source files)
PROC = [
    ("فهم النشاط", WD[87], "web-design"),
    ("تحليل المنافسين", WD[89], ""),
    ("تخطيط", WD[91], ""),
    ("تصميم", WD[93], ""),
    ("تطوير", WD[95], ""),
    ("اختبار", " ".join(T(WD, 98, 98)) + " " + "، ".join(strip_dot(x) for x in T(WD, 99, 103)) + ".", ""),
    ("إطلاق", WD[104], ""),
    ("متابعة", SEO[221], ""),
]
PROC = [(t, d) for t, d, _ in PROC]
HOME_FAQ = [qa(T(SEO, 318, 357))[i] for i in (0, 3, 6)] + [qa(T(WD, 130, 165))[i] for i in (0, 1, 2)] + [qa(T(WH, 92, 107))[i] for i in (1, 3)]
DATA["home_faq"] = HOME_FAQ
DATA["industries"] = IND
DATA["process"] = PROC

services = [
    ("01", "تصميم مواقع احترافية", "web-design.html", "svc-web-design", ["مواقع شركات", "متاجر إلكترونية", "عيادات ومحامون"], "تصميم مواقع احترافية — أوراق تخطيط وواجهات بجانب لابتوب"),
    ("02", "خدمات SEO", "seo.html", "svc-seo", ["SEO تقني", "محتوى", "Local SEO"], "لوحة تحليلات SEO على شاشة"),
    ("03", "استضافة مواقع", "web-hosting.html", "svc-hosting", ["WordPress", "VPS", "SSL"], "خوادم استضافة المواقع داخل مركز بيانات"),
]
rows = "".join(
    f'<li><a class="work-row" href="{u}" data-cursor="view"><span class="num mono">{n}</span><h3 data-split>{e(t)}</h3>'
    f'<ul class="tags">{"".join(f"<li class=\"tag mono\">{e(g)}</li>" for g in tg)}</ul>'
    f'<span class="go" aria-hidden="true"><svg><use href="assets/img/sprite.svg#arrow"/></svg></span>'
    f'{img(im, alt, "row-thumb", "100vw", True, 800, 450)}</a></li>'
    for n, t, u, im, tg, alt in services)
previews = "".join(f'<img src="assets/img/{im}-800.webp" alt="" width="800" height="533">' for _, _, _, im, _, _ in services)

chips1 = "".join(f'<span class="chip{" alt" if i % 2 else ""}"><small>{i+1:02d}</small>{e(x)}</span>' for i, x in enumerate(IND[:7]))
chips2 = "".join(f'<span class="chip{" alt" if i % 2 else ""}"><small>{i+8:02d}</small>{e(x)}</span>' for i, x in enumerate(IND[7:]))

tools = [("Google Search Console", SEO[229]), ("Google Analytics", SEO[231]), ("Google Keyword Planner", SEO[233]), ("PageSpeed & Core Web Vitals", SEO[235]), ("تحليل المنافسين", SEO[237])]
tools_html = "".join(f'<article class="card tool" data-tilt data-reveal><span class="mono y">{i+1:02d}</span><div class="logo-t">{e(n)}</div><p>{e(d)}</p></article>' for i, (n, d) in enumerate(tools))
DATA["tools"] = tools

proc_html = "".join(
    f'<article class="pstep"><div class="n" aria-hidden="true">{i+1:02d}</div><div><h3>{e(t)}</h3><p>{e(d)}</p></div></article>' for i, (t, d) in enumerate(PROC))
dots = "".join("<span></span>" for _ in PROC)

testi = "".join(
    f'''<figure class="card slide" data-todo="رأي عميل حقيقي"><span class="todo-tag mono">PLACEHOLDER</span>
<blockquote><q>[TODO] هنا يوضع رأي عميل حقيقي بعد الحصول على موافقته. لا توجد آراء مزيّفة على هذا الموقع.</q></blockquote>
<figcaption class="who"><span class="avatar" aria-hidden="true"></span><span><strong>اسم العميل</strong><br><span class="cap">النشاط — الشركة</span></span></figcaption></figure>'''
    for _ in range(3))

home_ld = [ORG, BIZ,
           {"@type": "WebSite", "@id": SITE + "/#site", "url": SITE + "/", "name": "Brandiva Media", "inLanguage": "ar", "publisher": {"@id": SITE + "/#org"}},
           crumb([("الرئيسية", "index.html")]), faq_ld(HOME_FAQ)]

play_svg = '''<canvas class="p-canvas" width="480" height="460" data-src="assets/img/svc-web-design-800.webp"></canvas>
<svg viewBox="0 0 400 460" aria-hidden="true" focusable="false">
<defs><linearGradient id="tg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7B5CD6"/><stop offset="1" stop-color="#FDBA0F"/></linearGradient></defs>
<g class="p-fill"><path d="M60 40 L360 230 L60 420Z" fill="url(#tg)" opacity=".4" class="p-tint"/></g>
<path class="p-line two" d="M60 40 L360 230 L60 420Z" transform="translate(-26 -18)"/>
<path class="p-line" d="M60 40 L360 230 L60 420Z"/></svg>'''

pre = '''<div class="preloader" aria-hidden="true"><svg viewBox="-20 -20 260 290"><polygon class="st" points="0,0 70,0 70,170 38,248 0,212" stroke="#B79EE8"/><polygon class="fl" points="0,0 70,0 70,170 38,248 0,212" fill="#B79EE8"/>
<path class="st" d="M88 17H136C173 17 197 38 197 66C197 88 192 102 188 112C204 122 208 142 208 168C208 204 182 233 140 233H52L88 166Z" stroke="#FDBA0F" style="stroke-width:34"/>
<polygon class="st fl" points="114,76 172,124 114,172" stroke="#FDBA0F" style="fill:#FDBA0F;stroke-width:6"/></svg></div>'''

body = f'''
<section class="hero" aria-labelledby="hero-title">
<canvas class="hero-particles" aria-hidden="true"></canvas><div class="hero-slash" aria-hidden="true"></div><div class="hero-glow" aria-hidden="true"></div>
<div class="hero-play" aria-hidden="true">{play_svg}</div>
<div class="container hero-inner">
<p class="eyebrow mono">BRANDIVA MEDIA — وكالة تسويق رقمي · مصر</p>
<h1 class="display" id="hero-title" data-split="hero"><span class="line">نصمّم حضورك الرقمي…</span><span class="line accent">ونوصّله <em>لعملائك</em></span></h1>
<p class="lead" data-reveal>تصميم مواقع احترافية، خدمات SEO، واستضافة سريعة وآمنة — كل ما يحتاجه موقعك ليظهر في Google ويجذب عملاء يبحثون بالفعل عن خدماتك.</p>
<div class="hero-cta" data-reveal><a class="btn btn-yellow btn-lg" href="contact.html" data-magnetic>اطلب استشارة مجانية {ARROW}</a><a class="btn btn-ghost btn-lg" href="#services" data-play>اكتشف خدماتنا</a></div>
<ul class="trust mono" data-reveal aria-label="ما يميزنا"><li>تحليل</li><li>استراتيجية</li><li>تنفيذ</li><li>قياس</li></ul>
</div>
<a class="scroll-cue mono" href="#services" aria-label="انزل للأسفل"><i></i><span>SCROLL</span></a>
</section>

<section class="section" id="services" aria-labelledby="services-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>الخدمات</p><h2 class="h1" id="services-h" data-split>ثلاث خدمات.<br>موقع واحد ينمو.</h2></div><p class="lead" data-reveal>من تصميم الموقع إلى ظهوره في نتائج البحث واستضافته بأداء عالٍ — نبني الأساس الرقمي كاملًا لنشاطك.</p></div>
<ol class="work-list">{rows}</ol>
<div class="work-preview" aria-hidden="true">{previews}</div>
</div></section>

<section class="section slant" id="why" aria-labelledby="why-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>لماذا Brandiva</p><h2 class="h1" id="why-h" data-split>مواقع لا تبدو رائعة فقط… بل تحقق نتائج.</h2></div>
<div class="bento">
<article class="card b-approach" data-tilt><span class="mono y">نهجنا</span><h3>تحليل ← استراتيجية ← تنفيذ ← قياس</h3><p>كل مشروع يبدأ بفهم نشاطك وأهدافك، لا بقالب جاهز.</p>
<ol class="approach"><li><strong>تحليل</strong><span>نفهم نشاطك ومنافسيك</span></li><li><strong>استراتيجية</strong><span>خطة بأولويات واضحة</span></li><li><strong>تنفيذ</strong><span>تصميم وتطوير وتحسينات</span></li><li><strong>قياس</strong><span>تقارير ومتابعة مستمرة</span></li></ol></article>
<article class="card b-metrics"><span class="mono y">نتائج قابلة للقياس</span><h3>نقيس كل خطوة بمؤشرات أداء واضحة</h3>
<div class="metrics"><div><b data-count="3">3</b><span>خدمات رئيسية</span></div><div><b data-count="14">14</b><span>خدمة SEO متخصصة</span></div><div><b data-count="7">7</b><span>خطوات لتنفيذ موقعك</span></div></div></article>
<article class="card b-speed"><span class="mono y">سرعة</span><h3>سرعة تحميل عالية</h3><p>نحسّن الصور ونضغط الملفات ونستخدم أحدث تقنيات البرمجة.</p><div class="speed-bar" aria-hidden="true"><i></i></div><span class="cap">مثال توضيحي</span></article>
<article class="card b-serp"><span class="mono y">SEO-friendly</span><h3>جاهز لمحركات البحث من اليوم الأول</h3><p>هيكلة عناوين صحيحة، روابط نظيفة، وCore Web Vitals وSchema.</p>
<div class="serp" aria-hidden="true"><small>brandivamedia.com › web-design</small><b>تصميم مواقع احترافية | Brandiva</b><p>مواقع سريعة ومتوافقة مع محركات البحث ومتجاوبة مع جميع الأجهزة…</p></div><span class="cap">مثال توضيحي لنتيجة بحث</span></article>
<article class="card b-logo" aria-hidden="true"><svg viewBox="0 0 220 250"><use href="assets/img/sprite.svg#mark"/></svg></article>
<article class="card b-rep"><span class="mono y">تقارير</span><h3>تقارير دورية</h3><p>تقارير توضح تقدم العمل وأثر التحسينات المنفذة.</p><svg class="spark" viewBox="0 0 200 70" aria-hidden="true"><path d="M2 60 C30 58 40 40 70 44 S110 20 135 26 S175 8 198 6"/></svg></article>
<article class="card b-support"><span class="mono y">دعم</span><h3>دعم فني مستمر</h3><p>نساعدك في حل المشكلات والإجابة عن استفساراتك بعد تسليم الموقع.</p></article>
<article class="card b-time"><span class="mono y">التزام</span><h3>التزام بالمواعيد</h3><p>خطة عمل واضحة لضمان تنفيذ المشروع بأعلى جودة وفي الوقت المحدد.</p></article>
</div></div></section>

<section class="marquees" aria-label="كلمات مفتاحية">
{marquee(["تحليل", "استراتيجية", "تصميم", "سرعة", "أمان", "نتائج"], d=0)}
{marquee(["ANALYSIS", "STRATEGY", "DESIGN", "SPEED", "SECURITY", "RESULTS"], d=1, outline=True)}
</section>

<section class="section process" id="process" aria-labelledby="process-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>خطوات العمل</p><h2 class="h1" id="process-h" data-split>من الفكرة… إلى موقع يعمل.</h2></div>
</div>
<div class="process-stage"><div class="container"><div class="pbar" aria-hidden="true"><i></i></div><div class="psteps">{proc_html}</div><div class="pdots" aria-hidden="true">{dots}</div></div></div>
</section>

<section class="section slant slant-rev" id="industries" aria-labelledby="ind-h">
<div class="container sec-head split"><div><p class="eyebrow mono" data-reveal>القطاعات</p><h2 class="h1" id="ind-h" data-split>لكل قطاع استراتيجية مختلفة.</h2></div><p class="lead" data-reveal>{e(SEO[267])}</p></div>
<div class="ind" aria-label="القطاعات التي نخدمها"><div class="ind-row">{chips1}</div><div class="ind-row">{chips2}</div></div>
</section>

<section class="section" id="tools" aria-labelledby="tools-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>الأدوات</p><h2 class="h1" id="tools-h" data-split>قرارات مبنية على بيانات… لا على التخمين.</h2></div><p class="lead" data-reveal>{e(SEO[226])}</p></div>
<div class="tools">{tools_html}</div>
</div></section>

<section class="section slant" id="testimonials" aria-labelledby="t-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>آراء العملاء</p><h2 class="h1" id="t-h" data-split>ماذا يقول عملاؤنا</h2></div>
<div class="slider" data-cursor="drag" tabindex="0" role="region" aria-label="آراء العملاء — اسحب لعرض المزيد">{testi}</div>
</div></section>

<section class="section" id="faq" aria-labelledby="faq-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>الأسئلة الشائعة</p><h2 class="h1" id="faq-h" data-split>أسئلة يسألها كل عميل.</h2></div><p class="lead" data-reveal>إجابات مباشرة بدون وعود غير واقعية. لو سؤالك مش هنا، كلّمنا.</p></div>
{faq_html(HOME_FAQ, True)}
</div></section>

<section class="cta-big" id="contact" aria-labelledby="cta-h">
<div class="bg-marq" aria-hidden="true">{marquee(["BRANDIVA", "MEDIA"], d=1, outline=True)}</div>
<div class="container cta-grid">
<div><p class="eyebrow mono" data-reveal>ابدأ الآن</p><h2 class="display" id="cta-h" data-split>لا تؤجل نمو نشاطك</h2>
<p class="lead" style="margin-block:1.25rem" data-reveal>تواصل معنا وسندرس موقعك ونفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.</p>
<a class="wa link-u" href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب" data-reveal>أو راسلنا مباشرة على واتساب ↗</a></div>
<div class="card cta-card" data-reveal>{quick_form("q", compact=True)}</div>
</div></section>
'''
write("index.html", head("index.html", "Brandiva Media | تصميم مواقع وSEO واستضافة مواقع في مصر",
      "Brandiva Media وكالة تسويق رقمي في مصر: تصميم مواقع احترافية، خدمات SEO، واستضافة مواقع سريعة وآمنة لمساعدة نشاطك على الظهور في Google وتحقيق المزيد من العملاء.", home_ld)
      + header("index.html").replace('<a class="skip"', pre + '<a class="skip"', 1) + f'<main id="main">{body}</main>' + footer())
