#!/usr/bin/env python3
"""Export the static site's content as structured demo data for the WordPress theme.
Run:  python3 tools/export_theme_data.py   →  wp-theme/brandiva/demo/demo-data.json
Reuses tools/build.py's helpers + the extracted docx text so the demo content == the current site.
"""
import json, os, re, html as H
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
src = open(f"{ROOT}/tools/build.py", encoding="utf-8").read().split("DATA = {}")[0]
ns = {"__file__": f"{ROOT}/tools/build.py"}
exec(src, ns)
ns["write"] = lambda *a, **k: None      # don't touch the static HTML
ns["DATA"] = {}

def esc(s): return H.escape(s, quote=False)
def img(k): return {"$img": k}
def pg(k): return {"$page": k}

def rich(lines):
    h = ""
    for k, v in ns["parse"](lines):
        if k == "p": h += f"<p>{esc(v)}</p>"
        elif k == "lead": h += f'<p class="lede">{esc(v)}</p>'
        else: h += "<ul>" + "".join(f"<li>{esc(ns['strip_dot'](i))}</li>" for i in v) + "</ul>"
    return h
def paras(lines): return "".join(f"<p>{esc(x)}</p>" for x in lines)
def ul(items): return "<ul>" + "".join(f"<li>{esc(ns['strip_dot'](ns['clean'](i)))}</li>" for i in items) + "</ul>"

def snapshot(lang):
    ns["set_lang"](lang); ns["DATA"][lang] = {}
    for f in ("pages_index", "pages_services", "pages_misc"):
        exec(open(f"{ROOT}/tools/{f}.py", encoding="utf-8").read(), ns)
    g = ns; L = lambda a, e: e if lang == "en" else a
    T, SEO, WD, WH, num, clean, sd = g["T"], g["SEO"], g["WD"], g["WH"], g["num"], g["clean"], g["strip_dot"]
    qa = lambda pairs: [{"q": q, "a": a} for q, a in pairs]
    ARROW_BTN = None

    # ---------------------------------------------------------------- HOME
    home = {
        "sections": ["services", "why", "marquee", "process", "industries", "tools", "testimonials", "faq", "cta"],
        "hero_eyebrow": L("BRANDIVA MEDIA — وكالة تسويق رقمي · مصر", "BRANDIVA MEDIA — DIGITAL MARKETING AGENCY · EGYPT"),
        "hero_line1": L("نصمّم حضورك الرقمي…", "We build your digital presence…"),
        "hero_line2_pre": L("ونوصّله", "and bring it to"), "hero_line2_em": L("لعملائك", "your customers"),
        "hero_lead": L("تصميم مواقع احترافية، خدمات SEO، واستضافة سريعة وآمنة — كل ما يحتاجه موقعك ليظهر في Google ويجذب عملاء يبحثون بالفعل عن خدماتك.",
                       "Professional web design, SEO services, and fast, secure hosting — everything your website needs to show up on Google and attract customers who are already searching for what you offer."),
        "hero_cta1_label": L("اطلب استشارة مجانية", "Request a free consultation"), "hero_cta1_service": "",
        "hero_cta2_label": L("اكتشف خدماتنا", "Explore our services"), "hero_cta2_anchor": "services",
        "hero_trust": [{"text": x} for x in L(["تحليل", "استراتيجية", "تنفيذ", "قياس"], ["Analysis", "Strategy", "Execution", "Measurement"])],
        "hero_canvas_img": img("svc-web-design"),
        "svc_eyebrow": L("الخدمات", "Services"),
        "svc_title": L("ثلاث خدمات.\nموقع واحد ينمو.", "Three services.\nOne website that grows."),
        "svc_lead": L("من تصميم الموقع إلى ظهوره في نتائج البحث واستضافته بأداء عالٍ — نبني الأساس الرقمي كاملًا لنشاطك.", "From designing your site to getting it found in search and hosting it for speed — we build your complete digital foundation."),
        "svc_items": [{"title": t, "page": pg(p), "image": img(im), "alt": alt, "tags": [{"tag": x} for x in tg]} for t, p, im, tg, alt in [
            (L("تصميم مواقع احترافية", "Professional Web Design"), "web-design", "svc-web-design", L(["مواقع شركات", "متاجر إلكترونية", "عيادات ومحامون"], ["Corporate sites", "E-commerce", "Clinics & law firms"]), L("تصميم مواقع احترافية — أوراق تخطيط وواجهات بجانب لابتوب", "Website wireframes on paper next to a laptop")),
            (L("خدمات SEO", "SEO Services"), "seo", "svc-seo", L(["SEO تقني", "محتوى", "Local SEO"], ["Technical SEO", "Content", "Local SEO"]), L("لوحة تحليلات SEO على شاشة", "SEO analytics dashboard on a screen")),
            (L("استضافة مواقع", "Web Hosting"), "web-hosting", "svc-hosting", ["WordPress", "VPS", "SSL"], L("خوادم استضافة المواقع داخل مركز بيانات", "Hosting servers inside a data center"))]],
        "why_eyebrow": L("لماذا Brandiva", "Why Brandiva"),
        "why_title": L("مواقع لا تبدو رائعة فقط… بل تحقق نتائج.", "Websites that don't just look great… they deliver results."),
        "why_approach": {"label": L("نهجنا", "Our approach"), "title": L("تحليل ← استراتيجية ← تنفيذ ← قياس", "Analyze → Strategize → Execute → Measure"),
            "text": L("كل مشروع يبدأ بفهم نشاطك وأهدافك، لا بقالب جاهز.", "Every project starts by understanding your business and goals — not with an off-the-shelf template."),
            "steps": [{"title": a, "text": b} for a, b in [(L("تحليل", "Analysis"), L("نفهم نشاطك ومنافسيك", "We study your business and competitors")), (L("استراتيجية", "Strategy"), L("خطة بأولويات واضحة", "A clear plan with priorities")),
                (L("تنفيذ", "Execution"), L("تصميم وتطوير وتحسينات", "Design, development and optimization")), (L("قياس", "Measurement"), L("تقارير ومتابعة مستمرة", "Reports and continuous follow-up"))]]},
        "why_metrics": {"label": L("نتائج قابلة للقياس", "Measurable results"), "title": L("نقيس كل خطوة بمؤشرات أداء واضحة", "We measure every step with clear performance indicators"),
            "items": [{"number": n, "label": l} for n, l in [(3, L("خدمات رئيسية", "Core services")), (14, L("خدمة SEO متخصصة", "Specialized SEO services")), (7, L("خطوات لتنفيذ موقعك", "Steps to launch your site"))]]},
        "why_speed": {"label": L("سرعة", "Speed"), "title": L("سرعة تحميل عالية", "Fast loading times"), "text": L("نحسّن الصور ونضغط الملفات ونستخدم أحدث تقنيات البرمجة.", "We optimize images, compress files and use modern development techniques."), "note": L("مثال توضيحي", "Illustrative example")},
        "why_serp": {"label": L("صديق لمحركات البحث", "SEO-friendly"), "title": L("جاهز لمحركات البحث من اليوم الأول", "Search-ready from day one"), "text": L("هيكلة عناوين صحيحة، روابط نظيفة، وCore Web Vitals وSchema.", "Proper heading structure, clean URLs, Core Web Vitals and Schema."),
            "url": "brandivamedia.com › web-design", "snip_title": L("تصميم مواقع احترافية | Brandiva", "Professional Web Design | Brandiva"), "snip_text": L("مواقع سريعة ومتوافقة مع محركات البحث ومتجاوبة مع جميع الأجهزة…", "Fast, search-friendly, fully responsive websites…"), "note": L("مثال توضيحي لنتيجة بحث", "Illustrative search result")},
        "why_logo": 1,
        "why_reports": {"label": L("تقارير", "Reports"), "title": L("تقارير دورية", "Regular reports"), "text": L("تقارير توضح تقدم العمل وأثر التحسينات المنفذة.", "Reports showing progress and the impact of every improvement.")},
        "why_support": {"label": L("دعم", "Support"), "title": L("دعم فني مستمر", "Ongoing technical support"), "text": L("نساعدك في حل المشكلات والإجابة عن استفساراتك بعد تسليم الموقع.", "We help solve issues and answer your questions after delivery.")},
        "why_time": {"label": L("التزام", "Commitment"), "title": L("التزام بالمواعيد", "On-time delivery"), "text": L("خطة عمل واضحة لضمان تنفيذ المشروع بأعلى جودة وفي الوقت المحدد.", "A clear work plan to deliver your project at the highest quality and on schedule.")},
        "mq_words": [{"word": w} for w in L(["تحليل", "استراتيجية", "تصميم", "سرعة", "أمان", "نتائج"], ["Analysis", "Strategy", "Design", "Speed", "Security", "Results"])],
        "pr_eyebrow": L("خطوات العمل", "How we work"), "pr_title": L("من الفكرة… إلى موقع يعمل.", "From idea… to a site that works."),
        "pr_steps": [{"title": t, "text": d} for t, d in g["PROC"]],
        "in_eyebrow": L("القطاعات", "Industries"), "in_title": L("لكل قطاع استراتيجية مختلفة.", "A different strategy for every sector."), "in_lead": SEO[267],
        "in_items": [{"text": x} for x in g["IND"]],
        "tl_eyebrow": L("الأدوات", "Tools"), "tl_title": L("قرارات مبنية على بيانات… لا على التخمين.", "Decisions based on data — not guesswork."), "tl_lead": SEO[226],
        "tl_items": [{"name": n, "text": d} for n, d in g["tools"]],
        "ts_eyebrow": L("آراء العملاء", "Client reviews"), "ts_title": L("ماذا يقول عملاؤنا", "What our clients say"),
        "ts_items": [{"quote": L("[TODO] هنا يوضع رأي عميل حقيقي بعد الحصول على موافقته. لا توجد آراء مزيّفة على هذا الموقع.", "[TODO] A real client testimonial goes here once approved. There are no fabricated reviews on this site."),
                      "name": L("اسم العميل", "Client name"), "role": L("النشاط — الشركة", "Industry — Company"), "placeholder": 1} for _ in range(3)],
        "fq_eyebrow": L("الأسئلة الشائعة", "FAQ"), "fq_title": L("أسئلة يسألها كل عميل.", "Questions every client asks."),
        "fq_lead": L("إجابات مباشرة بدون وعود غير واقعية. لو سؤالك مش هنا، كلّمنا.", "Straight answers, no unrealistic promises. If your question isn't here, talk to us."),
        "fq_items": qa(g["HOME_FAQ"]),
        "cta_eyebrow": L("ابدأ الآن", "Get started"), "cta_title": L("لا تؤجل نمو نشاطك", "Don't put off growing your business"),
        "cta_text": L("تواصل معنا وسندرس موقعك ونفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.", "Get in touch and we'll review your site, understand your goals, and propose a suitable plan — no commitment."),
        "cta_wa_show": 1, "cta_wa_label": L("أو راسلنا مباشرة على واتساب", "Or message us directly on WhatsApp"),
    }

    # ---------------------------------------------------------------- SERVICE: SEO
    def note(t, x, btn="", svc=""): return {"title": t, "text": x, "btn": btn, "btn_service": svc}
    seo_rows = [
        {"acf_fc_layout": "rich", "anchor": "what", "title": SEO[9], "body": rich(T(SEO, 11, 14)), "list_style": "tick"},
        {"acf_fc_layout": "rich", "anchor": "invest", "title": SEO[15], "body": rich(T(SEO, 17, 25)), "list_style": "tick"},
        {"acf_fc_layout": "cards_stack", "anchor": "google", "title": SEO[26], "intro": paras(T(SEO, 28, 29)), "items": [{"title": t, "body": rich(ls)} for t, ls in g["g_stages"]], "note": note(SEO[46], SEO[47])},
        {"acf_fc_layout": "rich", "anchor": "need", "title": SEO[50], "body": rich(T(SEO, 52, 62)), "list_style": "tick"},
        {"acf_fc_layout": "tabs", "anchor": "services", "title": SEO[63], "intro": paras([SEO[64]]), "items": [{"title": t, "body": rich(ls)} for t, ls in g["SEO_SERVICES"]]},
        {"acf_fc_layout": "steps", "anchor": "process", "title": SEO[204], "intro": paras([SEO[205]]), "style": "h", "items": [{"title": t, "body": b} for t, b in g["seo_proc"]]},
        {"acf_fc_layout": "tools", "anchor": "tools", "title": SEO[225], "intro": paras(T(SEO, 227, 228)), "items": [{"name": n, "text": d} for n, d in g["seo_tools"]]},
        {"acf_fc_layout": "rich", "anchor": "why", "title": SEO[239], "body": rich(T(SEO, 241, 250)), "list_style": "tick"},
        {"acf_fc_layout": "chips", "anchor": "industries", "title": SEO[251], "intro": rich(T(SEO, 253, 254)), "items": [{"text": sd(x)} for x in T(SEO, 255, 267)], "after": paras([SEO[267]])},
        {"acf_fc_layout": "rich", "anchor": "results", "title": SEO[269], "body": rich(T(SEO, 271, 281)), "list_style": "tick"},
        {"acf_fc_layout": "rich", "anchor": "time", "title": SEO[283], "body": rich(T(SEO, 285, 295)), "list_style": "tick"},
        {"acf_fc_layout": "flips", "anchor": "mistakes", "title": SEO[296], "intro": paras(T(SEO, 298, 299)), "items": [{"title": t, "text": d} for t, d in g["errs"]]},
        {"acf_fc_layout": "faq", "anchor": "faq", "title": SEO[316], "open_first": 0, "items": qa(g["SEO_FAQ"])},
    ]
    seo_page = {"sp_eyebrow": L("SEO — تحسين محركات البحث", "SEO — SEARCH ENGINE OPTIMIZATION"), "sp_h1": SEO[2], "sp_intro": paras(T(SEO, 5, 8)), "sp_image": img("svc-seo"),
        "sp_image_alt": L("لوحة تحليلات SEO وأداء الموقع على شاشة كمبيوتر", "SEO analytics dashboard on a computer screen"), "sp_name": L("خدمات SEO", "SEO"), "sp_sections": seo_rows,
        "sp_cta": {"title": SEO[358], "text": paras(T(SEO, 360, 361)) + f'<p class="lede">{esc(SEO[361])}</p>', "list": ul(T(SEO, 363, 367)) + f"<p>{esc(clean(SEO[367]))}</p>", "btn": "", "service": "seo"}}

    # ---------------------------------------------------------------- SERVICE: WEB DESIGN
    wd_rows = [
        {"acf_fc_layout": "rich", "anchor": "why-site", "title": WD[16], "body": rich(T(WD, 18, 28)), "list_style": "tick"},
        {"acf_fc_layout": "types", "anchor": "types", "title": WD[29], "intro": paras([WD[30]]), "items": [{"image": img(im), "alt": alt, "title": WD[t - 1], "text1": WD[a - 1], "text2": WD[b - 1]} for t, a, b, im, alt in g["types"]]},
        {"acf_fc_layout": "features", "anchor": "features", "title": WD[52], "intro": paras([WD[53]]), "items": [{"title": WD[t - 1], "body": rich(T(WD, a, b)), "wide": 1 if i in (0, 3) else 0} for i, (t, a, b) in enumerate(g["feats"])]},
        {"acf_fc_layout": "steps", "anchor": "process", "title": WD[84], "intro": paras([WD[85]]), "style": "", "items": [{"title": t, "body": b} for t, b in g["wd_steps"]]},
        {"acf_fc_layout": "rich", "anchor": "why", "title": WD[106], "body": paras(T(WD, 108, 109)) + ul(T(WD, 110, 117)), "list_style": "plain"},
        {"acf_fc_layout": "rich", "anchor": "pricing", "title": WD[118], "body": rich(T(WD, 120, 128)), "list_style": "tick",
         "note": note(L("اطلب عرض سعر مخصّص", "Request a custom quote"), L("نقدم عرض سعر واضحًا يوضح جميع التفاصيل دون أي تكاليف غير متوقعة.", "We provide a clear quote with every detail and no unexpected costs."), L("اطلب عرض سعر", "Request a quote"), "web-design")},
        {"acf_fc_layout": "faq", "anchor": "faq", "title": WD[128], "open_first": 0, "items": qa(g["WD_FAQ"])},
    ]
    wd_page = {"sp_eyebrow": L("WEB DESIGN — تصميم مواقع احترافية", "WEB DESIGN — PROFESSIONAL WEBSITES"), "sp_h1": WD[10], "sp_intro": paras(T(WD, 13, 15)), "sp_image": img("svc-web-design"),
        "sp_image_alt": L("تصميم موقع احترافي: أوراق تخطيط بجانب لابتوب", "Website wireframes on paper next to a laptop"), "sp_name": L("تصميم مواقع", "Web Design"), "sp_sections": wd_rows,
        "sp_cta": {"title": L("جاهز لموقع يحقق لك نتائج حقيقية؟", "Ready for a website that delivers real results?"), "text": paras([L("تواصل معنا وسنفهم نشاطك ونقترح عليك أنسب حل مع عرض سعر واضح.", "Get in touch and we'll understand your business and recommend the right solution with a clear quote.")]),
                   "list": ul(T(WD, 110, 113)), "btn": "", "service": "web-design"}}

    # ---------------------------------------------------------------- SERVICE: HOSTING
    wh_rows = [
        {"acf_fc_layout": "rich", "anchor": "why-host", "title": WH[15], "body": rich(T(WH, 17, 25)), "list_style": "tick"},
        {"acf_fc_layout": "plans", "anchor": "plans", "title": L("خطط استضافة مواقع تناسب كل مشروع", "Web hosting plans for every project"), "toc": L("خطط الاستضافة", "Hosting plans"),
         "items": [{"id": pid, "name": n, "tag": (L("تحكم أعلى", "More control") if f else L("خطة استضافة", "Hosting plan")), "text": WH[d - 1], "features": [{"text": x} for x in its], "featured": 1 if f else 0, "btn": L("اطلب عرض سعر", "Request a quote"), "btn_service": "hosting"}
                   for n, t, d, pid, its, f in g["plans"]],
         "footnote": L("الأسعار حسب الخطة وحجم الموارد — اطلب عرض سعر وسنرشّح لك الأنسب.", "Pricing depends on the plan and resources — request a quote and we'll recommend the best fit."),
         "after": paras([L("نخدم الشركات والمتاجر والمدوّنات: استضافة مواقع في مصر، استضافة مواقع سريعة، استضافة WordPress، واستضافة VPS — مع إعداد الموقع كاملًا.", "We host company sites, stores and blogs: web hosting in Egypt, fast web hosting, WordPress hosting and VPS hosting — with full site setup.")])},
        {"acf_fc_layout": "addons", "anchor": "addons", "title": L("خدمات الدومين وSSL ونقل المواقع", "Domains, SSL & site migration"), "toc": L("الدومين وSSL ونقل المواقع", "Domains, SSL & migration"), "items": [{"title": WH[t - 1], "text": WH[d - 1]} for t, d in [(38, 39), (41, 42), (44, 45)]]},
        {"acf_fc_layout": "rich", "anchor": "features", "title": WH[46], "intro": paras([WH[47]]), "body": ul(T(WH, 49, 58)), "list_style": "tick"},
        {"acf_fc_layout": "rich", "anchor": "choose", "title": WH[59], "body": rich(T(WH, 61, 67)), "list_style": "tick"},
        {"acf_fc_layout": "steps", "anchor": "process", "title": WH[68], "style": "", "items": [{"title": num(x).rstrip("."), "body": ""} for x in T(WH, 70, 76)]},
        {"acf_fc_layout": "rich", "anchor": "why", "title": WH[77], "body": ul(T(WH, 79, 85)), "list_style": "plain"},
        {"acf_fc_layout": "rich", "anchor": "pricing", "title": WH[86], "body": paras([WH[87], WH[88]]), "list_style": "tick",
         "note": note(L("اشترِ استضافة مواقع مناسبة لمشروعك", "Get web hosting that fits your project"), L("اطلب عرض سعر وسنرشّح لك الخطة الأنسب دون تكاليف غير ضرورية.", "Request a quote and we'll recommend the best plan — no unnecessary costs."), L("اطلب عرض سعر", "Request a quote"), "hosting")},
        {"acf_fc_layout": "faq", "anchor": "faq", "title": WH[90], "open_first": 0, "items": qa(g["WH_FAQ"])},
    ]
    wh_page = {"sp_eyebrow": L("WEB HOSTING — استضافة مواقع في مصر", "WEB HOSTING — HOSTING IN EGYPT"), "sp_h1": WH[10], "sp_intro": paras(T(WH, 13, 14)), "sp_image": img("svc-hosting"),
        "sp_image_alt": L("خوادم استضافة المواقع داخل مركز بيانات", "Hosting servers inside a data center"), "sp_name": L("استضافة مواقع", "Hosting"), "sp_sections": wh_rows,
        "sp_cta": {"title": WH[108], "text": paras([WH[109]]), "list": "", "btn": clean(WH[110]), "service": "hosting"}}

    # ---------------------------------------------------------------- CONTACT
    contact = {"cp_eyebrow": L("CONTACT — طلب استشارة", "CONTACT — REQUEST A CONSULTATION"), "cp_h1": L("لنبدأ مشروعك… اطلب استشارتك المجانية", "Let's start your project… request your free consultation"),
        "cp_lead": L("كل ما عليك هو التواصل معنا، وسنقوم بدراسة موقعك، وفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.", "All you need to do is get in touch. We'll review your site, understand your goals, and propose a suitable plan — no commitment."),
        "cp_show_wa": 1, "cp_show_email": 1, "cp_next_title": L("ماذا يحدث بعد الإرسال؟", "What happens after you submit?"),
        "cp_next": [{"text": x} for x in L(["دراسة موقعك وفهم أهدافك", "خطة عمل تناسب نشاطك", "عرض سعر واضح دون تكاليف غير متوقعة"], ["We review your site and understand your goals", "A plan that fits your business", "A clear quote with no unexpected costs"])],
        "cp_show_social": 1, "cp_show_clock": 1}

    return {
        "home": {"title": L("الرئيسية", "Home"), "slug": "home", "fields": home, "seo": {"seo_title": g["home_title"], "seo_desc": g["home_desc"]}},
        "web-design": {"title": L("تصميم مواقع", "Web Design"), "slug": "web-design", "fields": wd_page, "seo": {"seo_title": g["wd_title"], "seo_desc": g["wd_desc"]}},
        "seo": {"title": L("خدمات SEO", "SEO Services"), "slug": "seo", "fields": seo_page, "seo": {"seo_title": g["seo_title"], "seo_desc": g["seo_desc"]}},
        "web-hosting": {"title": L("استضافة مواقع", "Web Hosting"), "slug": "web-hosting", "fields": wh_page, "seo": {"seo_title": g["wh_title"], "seo_desc": g["wh_desc"]}},
        "contact": {"title": L("تواصل معنا", "Contact"), "slug": "contact", "fields": contact,
                    "seo": {"seo_title": L("تواصل معنا | اطلب استشارة مجانية | Brandiva Media", "Contact Us | Request a Free Consultation | Brandiva Media"),
                            "seo_desc": L("تواصل مع Brandiva Media واطلب استشارة مجانية لتصميم موقعك أو تحسين SEO أو اختيار استضافة مناسبة. نرد عليك في أقرب وقت.", "Contact Brandiva Media and request a free consultation on web design, SEO or choosing the right hosting. We reply as soon as possible.")}},
        "_menu": {
            "primary": [("home", L("الرئيسية", "Home")), ("web-design", L("تصميم مواقع", "Web Design")), ("seo", L("خدمات SEO", "SEO")), ("web-hosting", L("استضافة مواقع", "Hosting")), ("contact", L("تواصل معنا", "Contact"))],
            "footer_services": [("web-design", L("تصميم مواقع احترافية", "Professional Web Design")), ("seo", L("خدمات SEO", "SEO Services")), ("web-hosting", L("استضافة مواقع", "Web Hosting")), ("web-hosting#wp", L("استضافة WordPress", "WordPress Hosting"))],
        },
    }

out = {"ar": snapshot("ar"), "en": snapshot("en")}
tpl = {"home": "templates/page-home.php", "web-design": "templates/page-service.php", "seo": "templates/page-service.php", "web-hosting": "templates/page-service.php", "contact": "templates/page-contact.php"}
final = {"templates": tpl, "langs": {l: out[l] for l in out}}
dst = f"{ROOT}/wp-theme/brandiva/demo/demo-data.json"
os.makedirs(os.path.dirname(dst), exist_ok=True)
json.dump(final, open(dst, "w", encoding="utf-8"), ensure_ascii=False, indent=1)
print("exported", os.path.getsize(dst), "bytes")
