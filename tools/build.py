#!/usr/bin/env python3
"""Brandiva Media — static page generator (authoring tool only; the site itself is plain HTML/CSS/JS).
Reads the extracted docx text in content/*.txt by line number and renders the pages verbatim.
Run:  python3 tools/build.py
"""
import json, re, os, html as H
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SITE = "https://brandivamedia.com"  # TODO: final domain

LANG = "en"
def L(ar, en): return en if LANG == "en" else ar
def load(n):
    base = f"{ROOT}/content/{n}.txt" if LANG == "ar" else f"{ROOT}/content/en/{n}.txt"
    return [l.strip() for l in open(base, encoding="utf-8").read().split("\n")]
def set_lang(l):
    global LANG, SEO, WD, WH
    LANG = l
    SEO, WD, WH = load("seo"), load("web-design"), load("web-hosting")
set_lang("en")
def T(src, a, b=None):
    b = b or a
    return [x for x in src[a - 1:b] if x]
def e(s): return H.escape(s, quote=False)
def ea(s): return H.escape(s, quote=True)
def num(s): return re.sub(r"^\d+\.\s*", "", s)
def clean(s): return re.sub(r"^(?:[✅🚀💡📩]|•)\s*", "", s).strip()
def strip_dot(s): return s.rstrip(" .")

CLOSERS = ("مما يعزز", "وقد تساهم", "وذلك بهدف", "الهدف هو", "وفي نهاية", "بهذه الطريقة")
def parse(lines):
    out, inl = [], False
    explicit = any(l.startswith("• ") for l in lines)
    for l in lines:
        if explicit:
            if l.startswith("• "):
                if not out or out[-1][0] != "ul": out.append(("ul", []))
                out[-1][1].append(l[2:]); continue
            out.append(("lead", l) if l.endswith(":") else ("p", l)); continue
        if inl and len(l) <= 62 and not l.endswith(":") and not l.startswith(CLOSERS):
            out[-1][1].append(l); continue
        inl = False
        if l.endswith(":"):
            out.append(("lead", l)); out.append(("ul", [])); inl = True
        else:
            out.append(("p", l))
    return [o for o in out if not (o[0] == "ul" and not o[1])]
def blocks(lines, cls=""):
    h = ""
    for k, v in parse(lines):
        if k == "p": h += f"<p>{e(v)}</p>"
        elif k == "lead": h += f'<p class="lede">{e(v)}</p>'
        else: h += f'<ul class="ticks {cls}" data-reveal>' + "".join(f"<li>{e(strip_dot(i))}</li>" for i in v) + "</ul>"
    return h
def ticks(items, cls=""):
    return f'<ul class="ticks {cls}" data-reveal>' + "".join(f"<li>{e(strip_dot(clean(i)))}</li>" for i in items) + "</ul>"
def qa(lines):
    return [(num(lines[i]), lines[i + 1]) for i in range(0, len(lines) - 1, 2)]

ARROW = '<svg class="ico" aria-hidden="true"><use href="assets/img/sprite.svg#arrow"/></svg>'
def nav():
    return [("index.html", L("الرئيسية", "Home")), ("web-design.html", L("تصميم مواقع", "Web Design")), ("seo.html", L("خدمات SEO", "SEO")), ("web-hosting.html", L("استضافة مواقع", "Hosting")), ("contact.html", L("تواصل معنا", "Contact"))]
def base(): return SITE + ("" if LANG == "en" else "/ar")

def faq_html(pairs, open_first=False):
    return '<div class="faq" data-reveal>' + "".join(
        f'<details{" open" if open_first and i == 0 else ""}><summary>{e(q)}</summary><div class="ans"><p>{e(a)}</p></div></details>'
        for i, (q, a) in enumerate(pairs)) + "</div>"

def faq_ld(pairs):
    return {"@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": q, "acceptedAnswer": {"@type": "Answer", "text": a}} for q, a in pairs]}

ORG = {"@type": "Organization", "@id": SITE + "/#org", "name": "Brandiva Media", "url": SITE + "/", "logo": SITE + "/assets/img/logo.png",
       "sameAs": []}  # TODO: روابط السوشيال
BIZ = {"@type": ["ProfessionalService", "LocalBusiness"], "@id": SITE + "/#biz", "name": "Brandiva Media", "url": SITE + "/", "image": SITE + "/assets/img/about-team-1600.webp",
       "areaServed": {"@type": "Country", "name": "Egypt"}, "priceRange": "Request a quote",
       "telephone": "+20-TODO", "address": {"@type": "PostalAddress", "addressCountry": "EG"}}  # TODO: الهاتف والعنوان

def crumb(items):
    return {"@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": i + 1, "name": n, "item": SITE + "/" + u} for i, (n, u) in enumerate(items)]}

def head(file, title, desc, ld, og="assets/img/about-team-1600.webp"):
    url = base() + "/" + file
    alts = (f'<link rel="alternate" hreflang="en" href="{SITE}/{file}"><link rel="alternate" hreflang="ar" href="{SITE}/ar/{file}"><link rel="alternate" hreflang="x-default" href="{SITE}/{file}">'
            if file != "404.html" else "")
    fonts = ('<link rel="preload" href="assets/fonts/IBMPlexSansArabic-700-arabic.woff2" as="font" type="font/woff2" crossorigin><link rel="preload" href="assets/fonts/IBMPlexSansArabic-400-arabic.woff2" as="font" type="font/woff2" crossorigin>'
             if LANG == "ar" else '<link rel="preload" href="assets/fonts/PlusJakartaSans-800-latin.woff2" as="font" type="font/woff2" crossorigin><link rel="preload" href="assets/fonts/IBMPlexSansArabic-400-latin.woff2" as="font" type="font/woff2" crossorigin>')
    return f'''<!doctype html>
<html lang="{L("ar","en")}" dir="{L("rtl","ltr")}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{e(title)}</title>
<meta name="description" content="{ea(desc)}">
<link rel="canonical" href="{url}">
{alts}
<meta name="theme-color" content="#07060B">
<meta name="color-scheme" content="dark">
<meta property="og:type" content="website"><meta property="og:locale" content="{L("ar_EG","en_US")}"><meta property="og:site_name" content="Brandiva Media">
<meta property="og:title" content="{ea(title)}"><meta property="og:description" content="{ea(desc)}"><meta property="og:url" content="{url}"><meta property="og:image" content="{SITE}/{og}">
<meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="{ea(title)}"><meta name="twitter:description" content="{ea(desc)}"><meta name="twitter:image" content="{SITE}/{og}">
<link rel="icon" href="assets/img/favicon-32.png" type="image/png" sizes="32x32"><link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="manifest" href="manifest.webmanifest">
{fonts}
<link rel="stylesheet" href="assets/css/site.css">
<link rel="modulepreload" href="assets/js/main.js"><link rel="modulepreload" href="assets/js/config.js"><link rel="modulepreload" href="assets/js/fx.js"><link rel="modulepreload" href="assets/js/hero.js"><link rel="modulepreload" href="assets/js/cursor.js"><link rel="modulepreload" href="assets/js/form.js">
<script>document.documentElement.classList.add('js');setTimeout(function(){{if(!window.__bvOK)document.documentElement.classList.remove('js')}},6000)</script>
<script type="application/ld+json">{json.dumps({"@context": "https://schema.org", "@graph": ld}, ensure_ascii=False)}</script>
</head>
<body>
'''

def header(cur, file="index.html"):
    NAV = nav()
    sw_href = (f"ar/{file}" if LANG == "en" else f"../{file}")
    sw = L("EN", "العربية")
    sw_lang = L("en", "ar")
    links = "".join(f'<a href="{u}"{" aria-current=\"page\"" if u == cur else ""}>{t}</a>' for u, t in NAV)
    menu = "".join(f'<a href="{u}"><small>0{i+1}</small>{t}</a>' for i, (u, t) in enumerate(NAV))
    return f'''<a class="skip" href="#main">{L("تخطَّ إلى المحتوى الرئيسي","Skip to main content")}</a>
<div class="cursor" aria-hidden="true"></div>
<div class="progress" aria-hidden="true"></div>
<div class="announce"><a class="pill" href="contact.html"><span class="dot"></span><span class="mono">{L("استشارة مجانية","Free consultation")}</span><span>{L("— ابدأ مشروعك","— start your project")}</span></a></div>
<header class="site-header">
  <a class="brand" href="index.html"><picture><source srcset="assets/img/logo.webp" type="image/webp"><img class="logo" src="assets/img/logo.png" alt="Brandiva Media" width="87" height="58" decoding="async"></picture></a>
  <nav class="nav-desktop" aria-label="{L("التنقل الرئيسي","Main navigation")}">{links}</nav>
  <a class="lang-sw mono" href="{sw_href}" hreflang="{sw_lang}" lang="{sw_lang}" aria-label="{L("Switch to English","التبديل إلى العربية")}">{sw}</a>
  <a class="btn btn-yellow btn-sm" href="contact.html" data-magnetic>{L("اطلب استشارة","Get a quote")} {ARROW}</a>
  <button class="menu-btn" type="button" aria-expanded="false" aria-controls="menu" data-open="{L("القائمة","Menu")}" data-close="{L("إغلاق","Close")}"><span>{L("القائمة","Menu")}</span><i aria-hidden="true"></i></button>
</header>
<div class="menu" id="menu" role="dialog" aria-label="{L("القائمة الرئيسية","Main menu")}">
  <nav class="menu-links" aria-label="{L("القائمة","Menu")}">{menu}</nav>
  <div class="menu-foot"><a class="link-u" href="contact.html">{L("اطلب استشارة مجانية","Request a free consultation")}</a><a class="link-u" href="{sw_href}" hreflang="{sw_lang}" lang="{sw_lang}">{sw}</a><span class="mono">BRANDIVA MEDIA — EGYPT</span>{social_icons()}</div>
</div>
'''

def fa(i, cls="fa"):
    return f'<svg class="{cls}" aria-hidden="true" focusable="false"><use href="assets/img/fa-brands.svg#{i}"/></svg>'

def social_icons():
    items = [("fb", "Facebook"), ("ig", "Instagram"), ("in", "LinkedIn"), ("yt", "YouTube"), ("tt", "TikTok"), ("x", "X")]
    a = "".join(f'<a href="#" data-todo="{n} URL" rel="noopener" target="_blank">{fa(i)}<span class="sr-only">{n}</span></a>' for i, n in items)
    return f'<div class="socials" aria-label="{L("حسابات التواصل الاجتماعي","Social media")}">{a}</div>'

def footer():
    NAV = nav()
    ln = "".join(f'<li><a href="{u}">{t}</a></li>' for u, t in NAV)
    sv = f'<li><a href="web-design.html">{L("تصميم مواقع احترافية","Professional Web Design")}</a></li><li><a href="seo.html">{L("خدمات SEO","SEO Services")}</a></li><li><a href="web-hosting.html">{L("استضافة مواقع","Web Hosting")}</a></li><li><a href="web-hosting.html#wp">{L("استضافة WordPress","WordPress Hosting")}</a></li>'
    return f'''<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand easter" href="index.html"><picture><source srcset="assets/img/logo.webp" type="image/webp"><img class="logo logo-lg" src="assets/img/logo.png" alt="Brandiva Media" width="150" height="100" decoding="async"></picture></a>
        <p style="margin-block:1rem 1.25rem;max-width:34ch">{L("نصمّم حضورك الرقمي… ونوصّله لعملائك. تصميم مواقع، SEO، واستضافة سريعة وآمنة.","We build your digital presence and bring it to your customers. Web design, SEO and fast, secure hosting.")}</p>
        {social_icons()}
      </div>
      <nav aria-label="{L("روابط الصفحات","Pages")}"><h2>{L("الصفحات","Pages")}</h2><ul>{ln}</ul></nav>
      <nav aria-label="{L("الخدمات","Services")}"><h2>{L("الخدمات","Services")}</h2><ul>{sv}</ul></nav>
      <div><h2>{L("تواصل","Contact")}</h2><ul>
        <li><a href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب">{fa("wa","fa fa-inline")} WhatsApp</a></li>
        <li><a href="mailto:info@brandivamedia.com" data-todo="البريد الرسمي">info@brandivamedia.com</a></li>
        <li><a href="contact.html">{L("نموذج طلب استشارة","Consultation request form")}</a></li>
        <li><span class="mono" style="color:var(--text-500)">{L("القاهرة","Cairo")}</span> <span class="clock" aria-label="{L("الساعة الآن بتوقيت القاهرة","Current time in Cairo")}">--:--:--</span></li></ul></div>
    </div>
    <div class="footer-bar"><span>© <span id="yr">2026</span> Brandiva Media. {L("جميع الحقوق محفوظة.","All rights reserved.")}</span><a class="to-top" href="#top" aria-label="{L("العودة لأعلى الصفحة","Back to top")}">↑ {L("للأعلى","Top")}</a></div>
  </div>
  <div class="giant" aria-hidden="true">BRANDIVA</div>
</footer>
<script src="assets/js/vendor/gsap.min.js" defer></script>
<script src="assets/js/vendor/ScrollTrigger.min.js" defer></script>
<script src="assets/js/vendor/split-type.min.js" defer></script>
<script src="assets/js/vendor/lenis.min.js" defer></script>
<script type="module" src="assets/js/main.js"></script>
</body>
</html>
'''

def img(name, alt, cls="", sizes="(min-width:1000px) 40vw, 100vw", lazy=True, w=1600, h=1067):
    return (f'<img class="{cls}" src="assets/img/{name}-800.webp" srcset="assets/img/{name}-800.webp 800w, assets/img/{name}-1600.webp 1600w" sizes="{sizes}" '
            f'alt="{ea(alt)}" width="{w}" height="{h}" {"loading=\"lazy\" " if lazy else ""}decoding="async">')

def page(file, title, desc, ld, body, cur, og=None):
    return head(file, title, desc, ld, og or "assets/img/about-team-1600.webp") + header(cur, file) + f'<main id="main">{body}</main>' + footer()

def write(name, content):
    if LANG == "ar":
        content = re.sub(r'(["\s,(])(assets/|manifest\.webmanifest)', r'\1../\2', content)
        os.makedirs(f"{ROOT}/ar", exist_ok=True)
        name = "ar/" + name
    open(f"{ROOT}/{name}", "w", encoding="utf-8").write(content)

# --------------------------------------------------------------------------- shared sections
def marquee(words, cls="", d=0, outline=False):
    sep = '<span class="sep" aria-hidden="true"></span>'
    one = "".join(f'<span{" class=\"o\"" if (outline and i % 2) else ""}>{w}</span>{sep}' for i, w in enumerate(words))
    return (f'<div class="marquee {cls}" data-dir="{d}" aria-hidden="true"><div class="marquee-track">{one}</div><div class="marquee-track">{one}</div></div>')

def quick_form(prefix, service="", compact=False):
    opts = [("", L("اختر الخدمة","Choose a service")), ("web-design", L("تصميم مواقع","Web design")), ("seo", L("خدمات SEO","SEO services")), ("hosting", L("استضافة مواقع","Web hosting")), ("other", L("أخرى","Other"))]
    sel = "".join(f'<option value="{v}"{" selected" if v == service else ""}>{t}</option>' for v, t in opts)
    extra = "" if compact else f'''<div class="field"><label for="{prefix}-email">{L("البريد الإلكتروني","Email")} <span class="cap">{L("(اختياري)","(optional)")}</span></label><input id="{prefix}-email" name="email" type="email" autocomplete="email" dir="ltr" inputmode="email"><span class="err">{L("اكتب بريدًا إلكترونيًا صحيحًا.","Enter a valid email address.")}</span></div>
<div class="field"><label for="{prefix}-msg">{L("تفاصيل مشروعك","Project details")} <span class="cap">{L("(اختياري)","(optional)")}</span></label><textarea id="{prefix}-msg" name="message" rows="4"></textarea></div>'''
    return f'''<form class="form" data-form novalidate>
<div class="row2">
<div class="field"><label for="{prefix}-name">{L("الاسم","Name")}</label><input id="{prefix}-name" name="name" autocomplete="name" required aria-required="true"><span class="err">{L("اكتب اسمك (حرفان على الأقل).","Enter your name (at least 2 characters).")}</span></div>
<div class="field"><label for="{prefix}-phone">{L("رقم الهاتف / واتساب","Phone / WhatsApp")}</label><input id="{prefix}-phone" name="phone" type="tel" dir="ltr" inputmode="tel" autocomplete="tel" required aria-required="true"><span class="err">{L("اكتب رقم هاتف صحيح.","Enter a valid phone number.")}</span></div>
</div>
<div class="field"><label for="{prefix}-svc">{L("الخدمة المطلوبة","Service needed")}</label><select id="{prefix}-svc" name="service">{sel}</select></div>
{extra}
<button class="btn btn-yellow btn-lg" type="submit"><span class="spin" aria-hidden="true"></span>{fa("wa","fa")}<span>{L("أرسل الطلب عبر واتساب","Send request via WhatsApp")}</span></button>
<p class="form-status" role="status" aria-live="polite"></p>
<p class="cap">{L("بالضغط على إرسال سيفتح واتساب برسالة جاهزة بطلبك. لا نشارك بياناتك مع أي طرف.","Submitting opens WhatsApp with your request pre-filled. We never share your details with third parties.")}</p>
</form>'''

def pcta(title, text, items, service, btn=None, extra=""):
    btn = btn or L("اطلب استشارة مجانية الآن", "Request a free consultation now")
    lst = ticks(items) if items else ""
    return f'''<section class="pcta" id="cta" aria-labelledby="cta-h" data-reveal>
<h2 class="h2" id="cta-h">{e(title)}</h2>
{"".join(f"<p>{e(t)}</p>" for t in text)}
{lst}{extra}
<a class="btn btn-yellow btn-lg" href="contact.html?service={service}" data-magnetic>{e(btn)} {ARROW}</a>
</section>'''

def phero(h1, intro, crumbs_, image, eyebrow, cta_label=None):
    cta_label = cta_label or L("اطلب استشارة مجانية", "Request a free consultation")
    cr = "".join(f'<li>{c}</li>' for c in crumbs_)
    return f'''<section class="phero" aria-labelledby="h1">
<div class="container phero-grid">
<div>
<ol class="crumbs mono" aria-label="{L("مسار التنقل","Breadcrumb")}">{cr}</ol>
<p class="eyebrow mono" style="margin-block-start:1rem">{eyebrow}</p>
<h1 class="h1 rise" id="h1">{e(h1)}</h1>
<div class="intro lead" data-reveal>{intro}</div>
<div class="hero-cta" style="margin-block-start:1.75rem"><a class="btn btn-yellow btn-lg" href="#cta" data-magnetic>{cta_label} {ARROW}</a><a class="btn btn-ghost btn-lg" href="#toc-start">{L("تصفّح الصفحة","Browse this page")}</a></div>
</div>
<figure class="phero-fig" data-clip>{img(image[0], image[1], "", "(min-width:1000px) 38vw, 100vw", True, 1600, 1067)}<figcaption class="sr-only">{ea(image[1])}</figcaption></figure>
</div></section>'''

def layout(toc, sections):
    t = "".join(f'<li><a href="#{i}">{e(n)}</a></li>' for i, n in toc)
    return f'''<div class="container page-grid" id="toc-start">
<aside class="toc" aria-label="{L("محتويات الصفحة","On this page")}"><span class="toc-title mono">{L("محتويات الصفحة","On this page")}</span><nav aria-label="{L("فهرس الصفحة","Page index")}"><ol>{t}</ol></nav></aside>
<div class="page-body">{sections}</div></div>'''

def sec(id_, title, body, cls=""):
    return f'<section class="psec {cls}" id="{id_}" aria-labelledby="{id_}-h"><h2 class="h2" id="{id_}-h" data-reveal>{e(title)}</h2>{body}</section>'

def steps(items, cls=""):
    return f'<ol class="steps {cls}">' + "".join(
        f'<li class="step card" data-reveal><span class="sn" aria-hidden="true">{i+1:02d}</span><h3>{e(t)}</h3>{d}</li>' for i, (t, d) in enumerate(items)) + "</ol>"

DATA = {}
for _lang in ("en", "ar"):
    set_lang(_lang)
    DATA[_lang] = {}
    _d = DATA
    for _f in ("pages_index", "pages_services", "pages_misc"):
        exec(open(f"{ROOT}/tools/{_f}.py", encoding="utf-8").read())
os.makedirs(f"{ROOT}/content", exist_ok=True)
json.dump(DATA, open(f"{ROOT}/content/data.json", "w", encoding="utf-8"), ensure_ascii=False, indent=1)
print("built")
