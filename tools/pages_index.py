# ---------------------------------------------------------------- index.html  (exec'd per language inside build.py)
IND = [strip_dot(clean(x)) for x in T(SEO, 255, 267)]
SEP = L("، ", ", ")
PROC = [
    (L("فهم النشاط", "Understand the business"), WD[87]),
    (L("تحليل المنافسين", "Competitor analysis"), WD[89]),
    (L("تخطيط", "Planning"), WD[91]),
    (L("تصميم", "Design"), WD[93]),
    (L("تطوير", "Development"), WD[95]),
    (L("اختبار", "Testing"), WD[97] + " " + SEP.join(strip_dot(clean(x)) for x in T(WD, 99, 103)) + "."),
    (L("إطلاق", "Launch"), WD[104]),
    (L("متابعة", "Follow-up"), SEO[221]),
]
HOME_FAQ = [qa(T(SEO, 318, 357))[i] for i in (0, 3, 6)] + [qa(T(WD, 130, 165))[i] for i in (0, 1, 2)] + [qa(T(WH, 92, 107))[i] for i in (1, 3)]
DATA[LANG]["home_faq"] = HOME_FAQ
DATA[LANG]["industries"] = IND
DATA[LANG]["process"] = PROC

services = [
    ("01", L("تصميم مواقع احترافية", "Professional Web Design"), "web-design.html", "svc-web-design",
     L(["مواقع شركات", "متاجر إلكترونية", "عيادات ومحامون"], ["Corporate sites", "E-commerce", "Clinics & law firms"]),
     L("تصميم مواقع احترافية — أوراق تخطيط وواجهات بجانب لابتوب", "Website wireframes on paper next to a laptop")),
    ("02", L("خدمات SEO", "SEO Services"), "seo.html", "svc-seo", L(["SEO تقني", "محتوى", "Local SEO"], ["Technical SEO", "Content", "Local SEO"]),
     L("لوحة تحليلات SEO على شاشة", "SEO analytics dashboard on a screen")),
    ("03", L("استضافة مواقع", "Web Hosting"), "web-hosting.html", "svc-hosting", ["WordPress", "VPS", "SSL"],
     L("خوادم استضافة المواقع داخل مركز بيانات", "Hosting servers inside a data center")),
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

tools = [("Google Search Console", SEO[229]), ("Google Analytics", SEO[231]), ("Google Keyword Planner", SEO[233]),
         ("PageSpeed & Core Web Vitals", SEO[235]), (L("تحليل المنافسين", "Competitor analysis"), SEO[237])]
tools_html = "".join(f'<article class="card tool" data-tilt data-reveal><span class="mono y">{i+1:02d}</span><div class="logo-t">{e(n)}</div><p>{e(d)}</p></article>' for i, (n, d) in enumerate(tools))
DATA[LANG]["tools"] = tools

proc_html = "".join(
    f'<article class="pstep"><div class="n" aria-hidden="true">{i+1:02d}</div><div><h3>{e(t)}</h3><p>{e(d)}</p></div></article>' for i, (t, d) in enumerate(PROC))
dots = "".join("<span></span>" for _ in PROC)

testi = "".join(
    f'''<figure class="card slide" data-todo="real client testimonial"><span class="todo-tag mono">PLACEHOLDER</span>
<blockquote><q>{L("[TODO] هنا يوضع رأي عميل حقيقي بعد الحصول على موافقته. لا توجد آراء مزيّفة على هذا الموقع.", "[TODO] A real client testimonial goes here once approved. There are no fabricated reviews on this site.")}</q></blockquote>
<figcaption class="who"><span class="avatar" aria-hidden="true"></span><span><strong>{L("اسم العميل", "Client name")}</strong><br><span class="cap">{L("النشاط — الشركة", "Industry — Company")}</span></span></figcaption></figure>'''
    for _ in range(3))

home_title = L("Brandiva Media | تصميم مواقع وSEO واستضافة مواقع في مصر", "Brandiva Media | Web Design, SEO & Web Hosting in Egypt")
home_desc = L("Brandiva Media وكالة تسويق رقمي في مصر: تصميم مواقع احترافية، خدمات SEO، واستضافة مواقع سريعة وآمنة لمساعدة نشاطك على الظهور في Google وتحقيق المزيد من العملاء.",
              "Brandiva Media is a digital marketing agency in Egypt: professional web design, SEO services, and fast, secure web hosting to help your business get found on Google and win more customers.")
home_ld = [ORG, BIZ,
           {"@type": "WebSite", "@id": SITE + "/#site", "url": SITE + "/", "name": "Brandiva Media", "inLanguage": ["en", "ar"], "publisher": {"@id": SITE + "/#org"}},
           crumb([(L("الرئيسية", "Home"), "index.html")]), faq_ld(HOME_FAQ)]

play_svg = '''<canvas class="p-canvas" width="480" height="460" data-src="assets/img/svc-web-design-800.webp"></canvas>
<svg viewBox="0 0 400 460" aria-hidden="true" focusable="false">
<defs><linearGradient id="tg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7B5CD6"/><stop offset="1" stop-color="#FDBA0F"/></linearGradient></defs>
<g class="p-fill"><path d="M60 40 L360 230 L60 420Z" fill="url(#tg)" opacity=".4" class="p-tint"/></g>
<path class="p-line two" d="M60 40 L360 230 L60 420Z" transform="translate(-26 -18)"/>
<path class="p-line" d="M60 40 L360 230 L60 420Z"/></svg>'''

pre = '''<div class="preloader" aria-hidden="true" data-logo="assets/img/logo.webp"></div>'''

body = f'''
<section class="hero" aria-labelledby="hero-title">
<canvas class="hero-particles" aria-hidden="true"></canvas><div class="hero-slash" aria-hidden="true"></div><div class="hero-glow" aria-hidden="true"></div>
<div class="hero-play" aria-hidden="true">{play_svg}</div>
<div class="container hero-inner">
<p class="eyebrow mono">{L("BRANDIVA MEDIA — وكالة تسويق رقمي · مصر", "BRANDIVA MEDIA — DIGITAL MARKETING AGENCY · EGYPT")}</p>
<h1 class="display" id="hero-title" data-split="hero"><span class="line">{L("نصمّم حضورك الرقمي…", "We build your digital presence…")}</span><span class="line accent">{L("ونوصّله <em>لعملائك</em>", "and bring it to <em>your customers</em>")}</span></h1>
<p class="lead" data-reveal>{L("تصميم مواقع احترافية، خدمات SEO، واستضافة سريعة وآمنة — كل ما يحتاجه موقعك ليظهر في Google ويجذب عملاء يبحثون بالفعل عن خدماتك.",
 "Professional web design, SEO services, and fast, secure hosting — everything your website needs to show up on Google and attract customers who are already searching for what you offer.")}</p>
<div class="hero-cta" data-reveal><a class="btn btn-yellow btn-lg" href="contact.html" data-magnetic>{L("اطلب استشارة مجانية", "Request a free consultation")} {ARROW}</a><a class="btn btn-ghost btn-lg" href="#services" data-play>{L("اكتشف خدماتنا", "Explore our services")}</a></div>
<ul class="trust mono" data-reveal aria-label="{L("ما يميزنا", "What sets us apart")}"><li>{L("تحليل", "Analysis")}</li><li>{L("استراتيجية", "Strategy")}</li><li>{L("تنفيذ", "Execution")}</li><li>{L("قياس", "Measurement")}</li></ul>
</div>
<a class="scroll-cue mono" href="#services" aria-label="{L("انزل للأسفل", "Scroll down")}"><i></i><span>SCROLL</span></a>
</section>

<section class="section" id="services" aria-labelledby="services-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>{L("الخدمات", "Services")}</p><h2 class="h1" id="services-h" data-split>{L("ثلاث خدمات.<br>موقع واحد ينمو.", "Three services.<br>One website that grows.")}</h2></div><p class="lead" data-reveal>{L("من تصميم الموقع إلى ظهوره في نتائج البحث واستضافته بأداء عالٍ — نبني الأساس الرقمي كاملًا لنشاطك.", "From designing your site to getting it found in search and hosting it for speed — we build your complete digital foundation.")}</p></div>
<ol class="work-list">{rows}</ol>
<div class="work-preview" aria-hidden="true">{previews}</div>
</div></section>

<section class="section slant" id="why" aria-labelledby="why-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>{L("لماذا Brandiva", "Why Brandiva")}</p><h2 class="h1" id="why-h" data-split>{L("مواقع لا تبدو رائعة فقط… بل تحقق نتائج.", "Websites that don't just look great… they deliver results.")}</h2></div>
<div class="bento">
<article class="card b-approach" data-tilt><span class="mono y">{L("نهجنا", "Our approach")}</span><h3>{L("تحليل ← استراتيجية ← تنفيذ ← قياس", "Analyze → Strategize → Execute → Measure")}</h3><p>{L("كل مشروع يبدأ بفهم نشاطك وأهدافك، لا بقالب جاهز.", "Every project starts by understanding your business and goals — not with an off-the-shelf template.")}</p>
<ol class="approach"><li><strong>{L("تحليل", "Analysis")}</strong><span>{L("نفهم نشاطك ومنافسيك", "We study your business and competitors")}</span></li><li><strong>{L("استراتيجية", "Strategy")}</strong><span>{L("خطة بأولويات واضحة", "A clear plan with priorities")}</span></li><li><strong>{L("تنفيذ", "Execution")}</strong><span>{L("تصميم وتطوير وتحسينات", "Design, development and optimization")}</span></li><li><strong>{L("قياس", "Measurement")}</strong><span>{L("تقارير ومتابعة مستمرة", "Reports and continuous follow-up")}</span></li></ol></article>
<article class="card b-metrics"><span class="mono y">{L("نتائج قابلة للقياس", "Measurable results")}</span><h3>{L("نقيس كل خطوة بمؤشرات أداء واضحة", "We measure every step with clear performance indicators")}</h3>
<div class="metrics"><div><b data-count="3">3</b><span>{L("خدمات رئيسية", "Core services")}</span></div><div><b data-count="14">14</b><span>{L("خدمة SEO متخصصة", "Specialized SEO services")}</span></div><div><b data-count="7">7</b><span>{L("خطوات لتنفيذ موقعك", "Steps to launch your site")}</span></div></div></article>
<article class="card b-speed"><span class="mono y">{L("سرعة", "Speed")}</span><h3>{L("سرعة تحميل عالية", "Fast loading times")}</h3><p>{L("نحسّن الصور ونضغط الملفات ونستخدم أحدث تقنيات البرمجة.", "We optimize images, compress files and use modern development techniques.")}</p><div class="speed-bar" aria-hidden="true"><i></i></div><span class="cap">{L("مثال توضيحي", "Illustrative example")}</span></article>
<article class="card b-serp"><span class="mono y">SEO-friendly</span><h3>{L("جاهز لمحركات البحث من اليوم الأول", "Search-ready from day one")}</h3><p>{L("هيكلة عناوين صحيحة، روابط نظيفة، وCore Web Vitals وSchema.", "Proper heading structure, clean URLs, Core Web Vitals and Schema.")}</p>
<div class="serp" aria-hidden="true" dir="{L("rtl","ltr")}"><small>brandivamedia.com › web-design</small><b>{L("تصميم مواقع احترافية | Brandiva", "Professional Web Design | Brandiva")}</b><p>{L("مواقع سريعة ومتوافقة مع محركات البحث ومتجاوبة مع جميع الأجهزة…", "Fast, search-friendly, fully responsive websites…")}</p></div><span class="cap">{L("مثال توضيحي لنتيجة بحث", "Illustrative search result")}</span></article>
<article class="card b-logo" aria-hidden="true"><img src="assets/img/logo.webp" alt="" width="300" height="199" loading="lazy" decoding="async"></article>
<article class="card b-rep"><span class="mono y">{L("تقارير", "Reports")}</span><h3>{L("تقارير دورية", "Regular reports")}</h3><p>{L("تقارير توضح تقدم العمل وأثر التحسينات المنفذة.", "Reports showing progress and the impact of every improvement.")}</p><svg class="spark" viewBox="0 0 200 70" aria-hidden="true"><path d="M2 60 C30 58 40 40 70 44 S110 20 135 26 S175 8 198 6"/></svg></article>
<article class="card b-support"><span class="mono y">{L("دعم", "Support")}</span><h3>{L("دعم فني مستمر", "Ongoing technical support")}</h3><p>{L("نساعدك في حل المشكلات والإجابة عن استفساراتك بعد تسليم الموقع.", "We help solve issues and answer your questions after delivery.")}</p></article>
<article class="card b-time"><span class="mono y">{L("التزام", "Commitment")}</span><h3>{L("التزام بالمواعيد", "On-time delivery")}</h3><p>{L("خطة عمل واضحة لضمان تنفيذ المشروع بأعلى جودة وفي الوقت المحدد.", "A clear work plan to deliver your project at the highest quality and on schedule.")}</p></article>
</div></div></section>

<section class="marquees" aria-label="{L("كلمات مفتاحية", "Keywords")}">
{marquee(L(["تحليل", "استراتيجية", "تصميم", "سرعة", "أمان", "نتائج"], ["Analysis", "Strategy", "Design", "Speed", "Security", "Results"]), d=0)}
{marquee(["ANALYSIS", "STRATEGY", "DESIGN", "SPEED", "SECURITY", "RESULTS"], d=1, outline=True)}
</section>

<section class="section process" id="process" aria-labelledby="process-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>{L("خطوات العمل", "How we work")}</p><h2 class="h1" id="process-h" data-split>{L("من الفكرة… إلى موقع يعمل.", "From idea… to a site that works.")}</h2></div>
</div>
<div class="process-stage"><div class="container"><div class="pbar" aria-hidden="true"><i></i></div><div class="psteps">{proc_html}</div><div class="pdots" aria-hidden="true">{dots}</div></div></div>
</section>

<section class="section slant slant-rev" id="industries" aria-labelledby="ind-h">
<div class="container sec-head split"><div><p class="eyebrow mono" data-reveal>{L("القطاعات", "Industries")}</p><h2 class="h1" id="ind-h" data-split>{L("لكل قطاع استراتيجية مختلفة.", "A different strategy for every sector.")}</h2></div><p class="lead" data-reveal>{e(SEO[267])}</p></div>
<div class="ind" aria-label="{L("القطاعات التي نخدمها", "Industries we serve")}"><div class="ind-row">{chips1}</div><div class="ind-row">{chips2}</div></div>
</section>

<section class="section" id="tools" aria-labelledby="tools-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>{L("الأدوات", "Tools")}</p><h2 class="h1" id="tools-h" data-split>{L("قرارات مبنية على بيانات… لا على التخمين.", "Decisions based on data — not guesswork.")}</h2></div><p class="lead" data-reveal>{e(SEO[226])}</p></div>
<div class="tools">{tools_html}</div>
</div></section>

<section class="section slant" id="testimonials" aria-labelledby="t-h">
<div class="container">
<div class="sec-head"><p class="eyebrow mono" data-reveal>{L("آراء العملاء", "Client reviews")}</p><h2 class="h1" id="t-h" data-split>{L("ماذا يقول عملاؤنا", "What our clients say")}</h2></div>
<div class="slider" data-cursor="drag" tabindex="0" role="region" aria-label="{L("آراء العملاء — اسحب لعرض المزيد", "Client reviews — drag for more")}">{testi}</div>
</div></section>

<section class="section" id="faq" aria-labelledby="faq-h">
<div class="container">
<div class="sec-head split"><div><p class="eyebrow mono" data-reveal>{L("الأسئلة الشائعة", "FAQ")}</p><h2 class="h1" id="faq-h" data-split>{L("أسئلة يسألها كل عميل.", "Questions every client asks.")}</h2></div><p class="lead" data-reveal>{L("إجابات مباشرة بدون وعود غير واقعية. لو سؤالك مش هنا، كلّمنا.", "Straight answers, no unrealistic promises. If your question isn't here, talk to us.")}</p></div>
{faq_html(HOME_FAQ, True)}
</div></section>

<section class="cta-big" id="contact" aria-labelledby="cta-h">
<div class="bg-marq" aria-hidden="true" inert>{marquee(["BRANDIVA", "MEDIA"], d=1, outline=True)}</div>
<div class="container cta-grid">
<div><p class="eyebrow mono" data-reveal>{L("ابدأ الآن", "Get started")}</p><h2 class="display" id="cta-h" data-split>{L("لا تؤجل نمو نشاطك", "Don't put off growing your business")}</h2>
<p class="lead" style="margin-block:1.25rem" data-reveal>{L("تواصل معنا وسندرس موقعك ونفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.", "Get in touch and we'll review your site, understand your goals, and propose a suitable plan — no commitment.")}</p>
<a class="wa link-u" href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب" data-reveal>{L("أو راسلنا مباشرة على واتساب ↗", "Or message us directly on WhatsApp ↗")}</a></div>
<div class="card cta-card" data-reveal>{quick_form("q", compact=True)}</div>
</div></section>
'''
write("index.html", head("index.html", home_title, home_desc, home_ld) + header("index.html", "index.html").replace('<a class="skip"', pre + '<a class="skip"', 1) + f'<main id="main">{body}</main>' + footer())
