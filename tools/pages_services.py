# ---------------------------------------------------------------- service pages (exec'd inside build.py)
def crumbs3(name):
    return [f'<a href="index.html">{L("الرئيسية","Home")}</a>', "/", f'<span aria-current="page">{name}</span>']

def svc_ld(file, name, desc, faq, title):
    return [ORG, BIZ, {"@type": "Service", "name": name, "serviceType": name, "description": desc, "provider": {"@id": SITE + "/#org"},
                       "areaServed": {"@type": "Country", "name": L("مصر", "Egypt")}, "url": SITE + "/" + file},
            crumb([(L("الرئيسية", "Home"), "index.html"), (name, file)]), faq_ld(faq)]

# ======================================================================= SEO
SEO_FAQ = qa(T(SEO, 318, 357))
DATA[LANG]["seo_faq"] = SEO_FAQ
svc_titles = [67, 80, 90, 101, 113, 122, 131, 141, 149, 157, 167, 176, 184, 194]
svc_ends = svc_titles[1:] + [203]
tabs_btn, tabs_pan = "", ""
SEO_SERVICES = []
for i, (a, b) in enumerate(zip(svc_titles, svc_ends)):
    title = SEO[a - 1]
    body_lines = T(SEO, a + 1, b - 2) if i < 13 else T(SEO, a + 1, 202)
    SEO_SERVICES.append((title, body_lines))
    tabs_btn += f'<button class="tab" role="tab" id="tab-{i}" aria-controls="tp-{i}" aria-selected="false" type="button"><small>{i+1:02d}</small><span class="lat" style="font-weight:700;letter-spacing:0;font-family:var(--font-ar)">{e(title)}</span></button>'
    tabs_pan += f'<div class="tp card" role="tabpanel" id="tp-{i}" aria-labelledby="tab-{i}" tabindex="0"><span class="mono y">{i+1:02d} / 14</span><h3 data-nosplit>{e(title)}</h3>{blocks(body_lines)}</div>'
DATA[LANG]["seo_services"] = SEO_SERVICES

g_stages = [(num(SEO[29]), T(SEO, 31, 32)), (num(SEO[32]), T(SEO, 34, 35)), (num(SEO[35]), T(SEO, 37, 46))]
google = '<div class="stack3">' + "".join(
    f'<article class="card" data-reveal><span class="bignum" aria-hidden="true">{i+1}</span><h3>{e(t)}</h3><div>{blocks(ls)}</div></article>' for i, (t, ls) in enumerate(g_stages)) + "</div>"

seo_proc = [(num(SEO[i - 1]), blocks(T(SEO, i + 1, j))) for i, j in [(207, 208), (209, 210), (211, 218), (219, 220), (221, 222), (223, 224)]]
seo_tools = [(SEO[i - 1], SEO[i]) for i in (229, 231, 233, 235, 237)]
tools_cards = '<div class="tools" style="margin-block-start:1.5rem">' + "".join(
    f'<article class="card tool" data-tilt data-reveal><span class="mono y">{i+1:02d}</span><div class="logo-t">{e(n)}</div><p>{e(d)}</p></article>' for i, (n, d) in enumerate(seo_tools)) + "</div>"
errs = [(num(SEO[i - 1]), SEO[i]) for i in range(300, 315, 2)]
flips_html = '<div class="flips">' + "".join(
    f'<div class="flip" data-reveal tabindex="0" aria-label="{ea(t)}"><div class="flip-in"><div class="f"><span class="bignum" aria-hidden="true">{i+1:02d}</span><h3>{e(t)}</h3><span class="cap mono">{L("مرّر أو المس للقراءة","Hover or tap to read")}</span></div><div class="b"><p style="color:#fff">{e(d)}</p></div></div></div>'
    for i, (t, d) in enumerate(errs)) + "</div>"
chips_ind = '<ul class="chips" data-reveal>' + "".join(f'<li class="chip{" alt" if i % 2 else ""}">{e(strip_dot(x))}</li>' for i, x in enumerate(T(SEO, 255, 267))) + "</ul>"

seo_toc = [(i, SEO[k]) for i, k in [("what", 9), ("invest", 15), ("google", 26), ("need", 50), ("services", 63), ("process", 204), ("tools", 225), ("why", 239), ("industries", 251), ("results", 269), ("time", 283), ("mistakes", 296), ("faq", 316)]]
seo_secs = "".join([
    sec("what", SEO[9], blocks(T(SEO, 11, 14))),
    sec("invest", SEO[15], blocks(T(SEO, 17, 25))),
    sec("google", SEO[26], "".join(f"<p>{e(x)}</p>" for x in T(SEO, 28, 29)) + google +
        f'<div class="note" data-reveal><h3>{e(SEO[46])}</h3><p>{e(SEO[47])}</p></div>'),
    sec("need", SEO[50], blocks(T(SEO, 52, 62))),
    sec("services", SEO[63], f"<p>{e(SEO[64])}</p>" + f'<div class="tabs" data-tabs><div class="tablist" role="tablist" aria-label="{L("خدمات SEO","SEO services")}" aria-orientation="vertical">{tabs_btn}</div><div class="tabpanels">{tabs_pan}</div></div>'),
    sec("process", SEO[204], f"<p>{e(SEO[205])}</p>" + steps(seo_proc, "h")),
    sec("tools", SEO[225], "".join(f"<p>{e(x)}</p>" for x in T(SEO, 227, 228)) + tools_cards),
    sec("why", SEO[239], blocks(T(SEO, 241, 250))),
    sec("industries", SEO[251], blocks(T(SEO, 253, 254)) + chips_ind + f'<p style="margin-block-start:1.25rem">{e(SEO[267])}</p>'),
    sec("results", SEO[269], blocks(T(SEO, 271, 281))),
    sec("time", SEO[283], blocks(T(SEO, 285, 295))),
    sec("mistakes", SEO[296], "".join(f"<p>{e(x)}</p>" for x in T(SEO, 298, 299)) + flips_html),
    sec("faq", SEO[316], faq_html(SEO_FAQ)),
    pcta(SEO[358], T(SEO, 360, 361), [], "seo", extra=f'<p class="lede" style="color:#fff">{e(SEO[361])}</p>' + ticks(T(SEO, 363, 367)) + f'<p>{e(clean(SEO[367]))}</p>'),
])
# fix: pcta text lines (360-361) then lead + list; avoid duplicating 361
seo_secs = seo_secs.replace(f'<p class="lede" style="color:#fff">{e(SEO[361])}</p>', f'<p class="lede" style="color:#fff">{e(SEO[361])}</p>', 1)
seo_title = L("خدمات SEO احترافية | تحسين محركات البحث في مصر | Brandiva", "Professional SEO Services | Search Engine Optimization in Egypt | Brandiva")  # derived (no meta in source doc)
seo_desc = L("خدمات SEO احترافية من Brandiva تعتمد على التحليل والبيانات وأفضل الممارسات: تحليل الموقع، الكلمات المفتاحية، SEO تقني، محتوى وتقارير دورية لزيادة ظهورك في Google.",
             "Professional SEO services from Brandiva, built on analysis, data and best practices: site audits, keyword research, technical SEO, content and regular reports to boost your visibility on Google.")
seo_body = phero(SEO[2], "".join(f"<p>{e(x)}</p>" for x in T(SEO, 5, 8)), crumbs3(L("خدمات SEO","SEO")), ("svc-seo", L("لوحة تحليلات SEO وأداء الموقع على شاشة كمبيوتر","SEO analytics dashboard on a computer screen")), L("SEO — تحسين محركات البحث","SEO — SEARCH ENGINE OPTIMIZATION")) + layout(seo_toc, seo_secs)
write("seo.html", page("seo.html", seo_title, seo_desc, svc_ld("seo.html", L("خدمات SEO","SEO Services"), seo_desc, SEO_FAQ, seo_title), seo_body, "seo.html", "assets/img/svc-seo-1600.webp"))

# ======================================================================= WEB DESIGN
WD_FAQ = qa(T(WD, 130, 165))
DATA[LANG]["wd_faq"] = WD_FAQ
types = [(33, 34, 35, "type-corporate", L("واجهة مكتب شركة عصرية بجدران زجاجية","Modern corporate office with glass walls")), (37, 38, 39, "type-ecommerce", L("متجر إلكتروني على شاشة لابتوب","Online store on a laptop screen")),
         (41, 42, 43, "type-clinic", L("طبيب يستقبل مريضًا في عيادة","Doctor consulting a patient in a clinic")), (45, 46, 47, "type-law", L("مكتبة قانونية كلاسيكية بكتب قديمة","Classic law library with antique books")),
         (49, 50, 51, "type-restaurant", L("ضيوف يتناولون الطعام في مطعم راقٍ","Guests dining in an upscale restaurant"))]
types_html = '<div class="types">' + "".join(
    f'<article class="tc" data-tilt data-reveal><span class="n mono">{i+1:02d}</span>{img(im, alt, "", "(min-width:1100px) 33vw, 100vw", True, 800, 533)}<h3>{e(WD[t-1])}</h3><p>{e(WD[a-1])}</p><p>{e(WD[b-1])}</p></article>'
    for i, (t, a, b, im, alt) in enumerate(types)) + "</div>"
feats = [(55, 56, 56), (57, 58, 59), (60, 61, 65), (66, 67, 74), (75, 76, 81), (82, 83, 83)]
feat_html = '<div class="feat">' + "".join(
    f'<article class="card{" wide" if i in (0, 3) else ""}" data-tilt data-reveal><span class="mono y">{i+1:02d}</span><h3>{e(WD[t-1])}</h3>{blocks(T(WD, a, b))}</article>'
    for i, (t, a, b) in enumerate(feats)) + "</div>"
wd_steps = [(num(WD[t - 1]), blocks(T(WD, a, b))) for t, a, b in [(87, 88, 88), (89, 90, 90), (91, 92, 92), (93, 94, 94), (95, 96, 96), (97, 98, 103), (104, 105, 105)]]
price = (blocks(T(WD, 120, 128)) + '<div class="note" data-reveal><h3>' + L("اطلب عرض سعر مخصّص","Request a custom quote") + '</h3><p>' + L("نقدم عرض سعر واضحًا يوضح جميع التفاصيل دون أي تكاليف غير متوقعة.","We provide a clear quote with every detail and no unexpected costs.") + '</p>'
         '<a class="btn btn-yellow" href="contact.html?service=web-design" style="margin-block-start:1rem">' + L("اطلب عرض سعر","Request a quote") + ' ' + ARROW + '</a></div>')
wd_toc = [(i, WD[k]) for i, k in [("why-site", 16), ("types", 29), ("features", 52), ("process", 84), ("why", 106), ("pricing", 118), ("faq", 128)]]
wd_secs = "".join([
    sec("why-site", WD[16], blocks(T(WD, 18, 28))),
    sec("types", WD[29], f"<p>{e(WD[30])}</p>" + types_html),
    sec("features", WD[52], f"<p>{e(WD[53])}</p>" + feat_html),
    sec("process", WD[84], f"<p>{e(WD[85])}</p>" + steps(wd_steps)),
    sec("why", WD[106], blocks(T(WD, 108, 109)) + ticks(T(WD, 110, 117), "p")),
    sec("pricing", WD[118], price),
    sec("faq", WD[128], faq_html(WD_FAQ)),
    pcta(L("جاهز لموقع يحقق لك نتائج حقيقية؟","Ready for a website that delivers real results?"), [L("تواصل معنا وسنفهم نشاطك ونقترح عليك أنسب حل مع عرض سعر واضح.","Get in touch and we'll understand your business and recommend the right solution with a clear quote.")],
         T(WD, 110, 113), "web-design"),
])
wd_title = WD[5]; wd_desc = WD[8]
if LANG == "ar":
    pass
wd_body = phero(WD[10], "".join(f"<p>{e(x)}</p>" for x in T(WD, 13, 15)), crumbs3(L("تصميم مواقع","Web Design")), ("svc-web-design", L("تصميم موقع احترافي: أوراق تخطيط بجانب لابتوب","Website wireframes on paper next to a laptop")), L("WEB DESIGN — تصميم مواقع احترافية","WEB DESIGN — PROFESSIONAL WEBSITES")) + layout(wd_toc, wd_secs)
write("web-design.html", page("web-design.html", wd_title, wd_desc, svc_ld("web-design.html", L("تصميم مواقع احترافية","Professional Web Design"), wd_desc, WD_FAQ, wd_title), wd_body, "web-design.html", "assets/img/svc-web-design-1600.webp"))

# ======================================================================= HOSTING
WH_FAQ = qa(T(WH, 92, 107))
DATA[LANG]["wh_faq"] = WH_FAQ
plans = [("WordPress", 29, 30, "wp", L(["سرعة تحميل عالية", "حماية متقدمة", "تحديثات مستمرة"], ["High loading speed", "Advanced protection", "Continuous updates"]), False),
         ("VPS", 32, 33, "vps", L(["موارد أكبر", "تحكم أعلى في الخادم", "مناسبة للمواقع كثيرة الزيارات"], ["More resources", "Greater server control", "Suited to high-traffic sites"]), True),
         ("Cloud Hosting", 35, 36, "cloud", L(["مرونة عالية", "استقرار ممتاز", "توزيع الموارد على أكثر من خادم"], ["High flexibility", "Excellent stability", "Resources spread across several servers"]), False)]
plans_html = '<div class="plans">' + "".join(
    f'<article class="card plan{" feat-plan" if f else ""}" id="{pid}" data-tilt data-reveal><span class="tag mono ptag">{L("تحكم أعلى","More control") if f else L("خطة استضافة","Hosting plan")}</span><h3 class="lat">{n}</h3><p>{e(WH[d-1])}</p>{ticks(its)}'
    f'<a class="btn {"btn-yellow" if f else "btn-ghost"}" href="contact.html?service=hosting">{L("اطلب عرض سعر","Request a quote")} {ARROW}</a></article>'
    for n, t, d, pid, its, f in plans) + "</div><p class=\"cap\" style=\"margin-block-start:.75rem\">" + L("الأسعار حسب الخطة وحجم الموارد — اطلب عرض سعر وسنرشّح لك الأنسب.","Pricing depends on the plan and resources — request a quote and we'll recommend the best fit.") + "</p>"
addons = '<div class="addons">' + "".join(
    f'<article class="card" data-reveal><h3>{e(WH[t-1])}</h3><p>{e(WH[d-1])}</p></article>' for t, d in [(38, 39), (41, 42), (44, 45)]) + "</div>"
wh_steps = [(num(x).rstrip("."), "") for x in T(WH, 70, 76)]
wh_steps_html = '<ol class="steps">' + "".join(f'<li class="step card" data-reveal><span class="sn" aria-hidden="true">{i+1:02d}</span><h3>{e(t)}</h3></li>' for i, (t, _) in enumerate(wh_steps)) + "</ol>"
wh_toc = [("why-host", WH[15]), ("plans", L("خطط الاستضافة","Hosting plans")), ("addons", L("الدومين وSSL ونقل المواقع","Domains, SSL & migration")), ("features", WH[46]),
          ("choose", WH[59]), ("process", WH[68]), ("why", WH[77]), ("pricing", WH[86]), ("faq", WH[90])]
wh_secs = "".join([
    sec("why-host", WH[15], blocks(T(WH, 17, 25))),
    sec("plans", L("خطط استضافة مواقع تناسب كل مشروع","Web hosting plans for every project"), plans_html +
        '<p class="kw" data-reveal>' + L("نخدم الشركات والمتاجر والمدوّنات: استضافة مواقع في مصر، استضافة مواقع سريعة، استضافة WordPress، واستضافة VPS — مع إعداد الموقع كاملًا.","We host company sites, stores and blogs: web hosting in Egypt, fast web hosting, WordPress hosting and VPS hosting — with full site setup.") + '</p>'),
    sec("addons", L("خدمات الدومين وSSL ونقل المواقع","Domains, SSL & site migration"), addons),
    sec("features", WH[46], f"<p>{e(WH[47])}</p>" + ticks(T(WH, 49, 58))),
    sec("choose", WH[59], blocks(T(WH, 61, 67))),
    sec("process", WH[68], wh_steps_html),
    sec("why", WH[77], ticks(T(WH, 79, 85), "p")),
    sec("pricing", WH[86], f"<p>{e(WH[87])}</p><p>{e(WH[88])}</p>" +
        '<div class="note" data-reveal><h3>' + L("اشترِ استضافة مواقع مناسبة لمشروعك","Get web hosting that fits your project") + '</h3><p>' + L("اطلب عرض سعر وسنرشّح لك الخطة الأنسب دون تكاليف غير ضرورية.","Request a quote and we'll recommend the best plan — no unnecessary costs.") + '</p><a class="btn btn-yellow" href="contact.html?service=hosting" style="margin-block-start:1rem">' + L("اطلب عرض سعر","Request a quote") + ' ' + ARROW + '</a></div>'),
    sec("faq", WH[90], faq_html(WH_FAQ)),
    pcta(WH[108], [WH[109]], [], "hosting", clean(WH[110])),
])
wh_title = L("استضافة مواقع سريعة وآمنة في مصر | استضافة WordPress وVPS | Brandiva", WH[6])  # AR: source title + 'في مصر' (keyword cluster)
wh_desc = L("احصل على استضافة مواقع سريعة وآمنة في مصر مع Brandiva. نقدم استضافة WordPress وVPS وشهادات SSL وحجز الدومينات مع أداء عالٍ ودعم فني مستمر.", WH[8])
wh_body = phero(WH[10], "".join(f"<p>{e(x)}</p>" for x in T(WH, 13, 14)), crumbs3(L("استضافة مواقع","Hosting")), ("svc-hosting", L("خوادم استضافة المواقع داخل مركز بيانات","Hosting servers inside a data center")), L("WEB HOSTING — استضافة مواقع في مصر","WEB HOSTING — HOSTING IN EGYPT")) + layout(wh_toc, wh_secs)
write("web-hosting.html", page("web-hosting.html", wh_title, wh_desc, svc_ld("web-hosting.html", L("استضافة مواقع","Web Hosting"), wh_desc, WH_FAQ, wh_title), wh_body, "web-hosting.html", "assets/img/svc-hosting-1600.webp"))
