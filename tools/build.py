#!/usr/bin/env python3
"""Brandiva Media — static page generator (authoring tool only; the site itself is plain HTML/CSS/JS).
Reads the extracted docx text in content/*.txt by line number and renders the pages verbatim.
Run:  python3 tools/build.py
"""
import json, re, os, html as H
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SITE = "https://brandivamedia.com"  # TODO: final domain

def load(n):
    return [l.strip() for l in open(f"{ROOT}/content/{n}.txt", encoding="utf-8").read().split("\n")]
SEO, WD, WH = load("seo"), load("web-design"), load("web-hosting")
def T(src, a, b=None):
    b = b or a
    return [x for x in src[a - 1:b] if x]
def e(s): return H.escape(s, quote=False)
def ea(s): return H.escape(s, quote=True)
def num(s): return re.sub(r"^\d+\.\s*", "", s)
def clean(s): return re.sub(r"^[✅🚀💡📩]\s*", "", s).strip()
def strip_dot(s): return s.rstrip(" .")

CLOSERS = ("مما يعزز", "وقد تساهم", "وذلك بهدف", "الهدف هو", "وفي نهاية", "بهذه الطريقة")
def parse(lines):
    out, inl = [], False
    for l in lines:
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
NAV = [("index.html", "الرئيسية"), ("web-design.html", "تصميم مواقع"), ("seo.html", "خدمات SEO"), ("web-hosting.html", "استضافة مواقع"), ("contact.html", "تواصل معنا")]

def faq_html(pairs, open_first=False):
    return '<div class="faq" data-reveal>' + "".join(
        f'<details{" open" if open_first and i == 0 else ""}><summary>{e(q)}</summary><div class="ans"><p>{e(a)}</p></div></details>'
        for i, (q, a) in enumerate(pairs)) + "</div>"

def faq_ld(pairs):
    return {"@type": "FAQPage", "mainEntity": [{"@type": "Question", "name": q, "acceptedAnswer": {"@type": "Answer", "text": a}} for q, a in pairs]}

ORG = {"@type": "Organization", "@id": SITE + "/#org", "name": "Brandiva Media", "url": SITE + "/", "logo": SITE + "/assets/img/logo-mark.svg",
       "sameAs": []}  # TODO: روابط السوشيال
BIZ = {"@type": ["ProfessionalService", "LocalBusiness"], "@id": SITE + "/#biz", "name": "Brandiva Media", "url": SITE + "/", "image": SITE + "/assets/img/about-team-1600.webp",
       "areaServed": {"@type": "Country", "name": "مصر"}, "priceRange": "اطلب عرض سعر",
       "telephone": "+20-TODO", "address": {"@type": "PostalAddress", "addressCountry": "EG"}}  # TODO: الهاتف والعنوان

def crumb(items):
    return {"@type": "BreadcrumbList", "itemListElement": [{"@type": "ListItem", "position": i + 1, "name": n, "item": SITE + "/" + u} for i, (n, u) in enumerate(items)]}

def head(file, title, desc, ld, og="assets/img/about-team-1600.webp"):
    url = SITE + "/" + file
    return f'''<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{e(title)}</title>
<meta name="description" content="{ea(desc)}">
<link rel="canonical" href="{url}">
<meta name="theme-color" content="#07060B">
<meta name="color-scheme" content="dark">
<meta property="og:type" content="website"><meta property="og:locale" content="ar_EG"><meta property="og:site_name" content="Brandiva Media">
<meta property="og:title" content="{ea(title)}"><meta property="og:description" content="{ea(desc)}"><meta property="og:url" content="{url}"><meta property="og:image" content="{SITE}/{og}">
<meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="{ea(title)}"><meta name="twitter:description" content="{ea(desc)}"><meta name="twitter:image" content="{SITE}/{og}">
<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="manifest" href="manifest.webmanifest">
<link rel="preload" href="assets/fonts/IBMPlexSansArabic-700-arabic.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="assets/fonts/IBMPlexSansArabic-400-arabic.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="assets/css/site.css">
<link rel="modulepreload" href="assets/js/main.js"><link rel="modulepreload" href="assets/js/config.js"><link rel="modulepreload" href="assets/js/fx.js"><link rel="modulepreload" href="assets/js/hero.js"><link rel="modulepreload" href="assets/js/cursor.js"><link rel="modulepreload" href="assets/js/form.js">
<script>document.documentElement.classList.add('js');setTimeout(function(){{if(!window.__bvOK)document.documentElement.classList.remove('js')}},6000)</script>
<script type="application/ld+json">{json.dumps({"@context": "https://schema.org", "@graph": ld}, ensure_ascii=False)}</script>
</head>
<body>
'''

def header(cur):
    links = "".join(f'<a href="{u}"{" aria-current=\"page\"" if u == cur else ""}>{t}</a>' for u, t in NAV)
    menu = "".join(f'<a href="{u}"><small>0{i+1}</small>{t}</a>' for i, (u, t) in enumerate(NAV))
    return f'''<a class="skip" href="#main">تخطَّ إلى المحتوى الرئيسي</a>
<div class="cursor" aria-hidden="true"></div>
<div class="progress" aria-hidden="true"></div>
<div class="announce"><a class="pill" href="contact.html"><span class="dot"></span><span class="mono">استشارة مجانية</span><span>— ابدأ مشروعك</span></a></div>
<header class="site-header">
  <a class="brand" href="index.html"><svg class="mark" width="30" height="34" aria-hidden="true"><use href="assets/img/sprite.svg#mark"/></svg><span class="wm"><b>Brandiva</b><i>Media</i></span></a>
  <nav class="nav-desktop" aria-label="التنقل الرئيسي">{links}</nav>
  <a class="btn btn-yellow btn-sm" href="contact.html" data-magnetic>اطلب استشارة {ARROW}</a>
  <button class="menu-btn" type="button" aria-expanded="false" aria-controls="menu"><span>القائمة</span><i aria-hidden="true"></i></button>
</header>
<div class="menu" id="menu" role="dialog" aria-label="القائمة الرئيسية">
  <nav class="menu-links" aria-label="القائمة">{menu}</nav>
  <div class="menu-foot"><a class="link-u" href="contact.html">اطلب استشارة مجانية</a><span class="mono">BRANDIVA MEDIA — EGYPT</span></div>
</div>
'''

def footer():
    ln = "".join(f'<li><a href="{u}">{t}</a></li>' for u, t in NAV)
    sv = '<li><a href="web-design.html">تصميم مواقع احترافية</a></li><li><a href="seo.html">خدمات SEO</a></li><li><a href="web-hosting.html">استضافة مواقع</a></li><li><a href="web-hosting.html#wp">استضافة WordPress</a></li>'
    return f'''<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand easter" href="index.html"><svg class="mark" width="40" height="45" aria-hidden="true"><use href="assets/img/sprite.svg#mark"/></svg><span class="wm" style="font-size:1.6rem"><b>Brandiva</b><i>Media</i></span></a>
        <p style="margin-block:1rem 1.25rem;max-width:34ch">نصمّم حضورك الرقمي… ونوصّله لعملائك. تصميم مواقع، SEO، واستضافة سريعة وآمنة.</p>
        <div class="socials" aria-label="حسابات التواصل الاجتماعي">
          <a href="#" data-todo="رابط فيسبوك"><span aria-hidden="true">FB</span><span class="sr-only">Facebook</span></a><a href="#" data-todo="رابط انستجرام"><span aria-hidden="true">IG</span><span class="sr-only">Instagram</span></a><a href="#" data-todo="رابط لينكدإن"><span aria-hidden="true">IN</span><span class="sr-only">LinkedIn</span></a><a href="#" data-todo="رابط يوتيوب"><span aria-hidden="true">YT</span><span class="sr-only">YouTube</span></a>
        </div>
      </div>
      <nav aria-label="روابط الصفحات"><h2>الصفحات</h2><ul>{ln}</ul></nav>
      <nav aria-label="الخدمات"><h2>الخدمات</h2><ul>{sv}</ul></nav>
      <div><h2>تواصل</h2><ul>
        <li><a href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب">واتساب</a></li>
        <li><a href="mailto:info@brandivamedia.com" data-todo="البريد الرسمي">info@brandivamedia.com</a></li>
        <li><a href="contact.html">نموذج طلب استشارة</a></li>
        <li><span class="mono" style="color:var(--text-500)">القاهرة</span> <span class="clock" aria-label="الساعة الآن بتوقيت القاهرة">--:--:--</span></li></ul></div>
    </div>
    <div class="footer-bar"><span>© <span id="yr">2026</span> Brandiva Media. جميع الحقوق محفوظة.</span><a class="to-top" href="#top" aria-label="العودة لأعلى الصفحة">↑ للأعلى</a></div>
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
    return head(file, title, desc, ld, og or "assets/img/about-team-1600.webp") + header(cur) + f'<main id="main">{body}</main>' + footer()

def write(name, content):
    open(f"{ROOT}/{name}", "w", encoding="utf-8").write(content)

# --------------------------------------------------------------------------- shared sections
def marquee(words, cls="", d=0, outline=False):
    sep = '<span class="sep" aria-hidden="true"></span>'
    one = "".join(f'<span{" class=\"o\"" if (outline and i % 2) else ""}>{w}</span>{sep}' for i, w in enumerate(words))
    return (f'<div class="marquee {cls}" data-dir="{d}" aria-hidden="true"><div class="marquee-track">{one}</div><div class="marquee-track">{one}</div></div>')

def quick_form(prefix, service="", compact=False):
    opts = [("", "اختر الخدمة"), ("web-design", "تصميم مواقع"), ("seo", "خدمات SEO"), ("hosting", "استضافة مواقع"), ("other", "أخرى")]
    sel = "".join(f'<option value="{v}"{" selected" if v == service else ""}>{t}</option>' for v, t in opts)
    extra = "" if compact else f'''<div class="field"><label for="{prefix}-email">البريد الإلكتروني <span class="cap">(اختياري)</span></label><input id="{prefix}-email" name="email" type="email" autocomplete="email" dir="ltr" inputmode="email"><span class="err">اكتب بريدًا إلكترونيًا صحيحًا.</span></div>
<div class="field"><label for="{prefix}-msg">تفاصيل مشروعك <span class="cap">(اختياري)</span></label><textarea id="{prefix}-msg" name="message" rows="4"></textarea></div>'''
    return f'''<form class="form" data-form novalidate>
<div class="row2">
<div class="field"><label for="{prefix}-name">الاسم</label><input id="{prefix}-name" name="name" autocomplete="name" required aria-required="true"><span class="err">اكتب اسمك (حرفان على الأقل).</span></div>
<div class="field"><label for="{prefix}-phone">رقم الهاتف / واتساب</label><input id="{prefix}-phone" name="phone" type="tel" dir="ltr" inputmode="tel" autocomplete="tel" required aria-required="true"><span class="err">اكتب رقم هاتف صحيح.</span></div>
</div>
<div class="field"><label for="{prefix}-svc">الخدمة المطلوبة</label><select id="{prefix}-svc" name="service">{sel}</select></div>
{extra}
<button class="btn btn-yellow btn-lg" type="submit"><span class="spin" aria-hidden="true"></span><span>أرسل الطلب عبر واتساب</span>{ARROW}</button>
<p class="form-status" role="status" aria-live="polite"></p>
<p class="cap">بالضغط على إرسال سيفتح واتساب برسالة جاهزة بطلبك. لا نشارك بياناتك مع أي طرف.</p>
</form>'''

def pcta(title, text, items, service, btn="اطلب استشارة مجانية الآن", extra=""):
    lst = ticks(items) if items else ""
    return f'''<section class="pcta" id="cta" aria-labelledby="cta-h" data-reveal>
<h2 class="h2" id="cta-h">{e(title)}</h2>
{"".join(f"<p>{e(t)}</p>" for t in text)}
{lst}{extra}
<a class="btn btn-yellow btn-lg" href="contact.html?service={service}" data-magnetic>{e(btn)} {ARROW}</a>
</section>'''

def phero(h1, intro, crumbs_, image, eyebrow, cta_label="اطلب استشارة مجانية"):
    cr = "".join(f'<li>{c}</li>' for c in crumbs_)
    return f'''<section class="phero" aria-labelledby="h1">
<div class="container phero-grid">
<div>
<ol class="crumbs mono" aria-label="مسار التنقل">{cr}</ol>
<p class="eyebrow mono" style="margin-block-start:1rem">{eyebrow}</p>
<h1 class="h1 rise" id="h1">{e(h1)}</h1>
<div class="intro lead" data-reveal>{intro}</div>
<div class="hero-cta" style="margin-block-start:1.75rem"><a class="btn btn-yellow btn-lg" href="#cta" data-magnetic>{cta_label} {ARROW}</a><a class="btn btn-ghost btn-lg" href="#toc-start">تصفّح الصفحة</a></div>
</div>
<figure class="phero-fig" data-clip>{img(image[0], image[1], "", "(min-width:1000px) 38vw, 100vw", True, 1600, 1067)}<figcaption class="sr-only">{ea(image[1])}</figcaption></figure>
</div></section>'''

def layout(toc, sections):
    t = "".join(f'<li><a href="#{i}">{e(n)}</a></li>' for i, n in toc)
    return f'''<div class="container page-grid" id="toc-start">
<aside class="toc" aria-label="محتويات الصفحة"><span class="toc-title mono">محتويات الصفحة</span><nav aria-label="فهرس الصفحة"><ol>{t}</ol></nav></aside>
<div class="page-body">{sections}</div></div>'''

def sec(id_, title, body, cls=""):
    return f'<section class="psec {cls}" id="{id_}" aria-labelledby="{id_}-h"><h2 class="h2" id="{id_}-h" data-reveal>{e(title)}</h2>{body}</section>'

def steps(items, cls=""):
    return f'<ol class="steps {cls}">' + "".join(
        f'<li class="step card" data-reveal><span class="sn" aria-hidden="true">{i+1:02d}</span><h3>{e(t)}</h3>{d}</li>' for i, (t, d) in enumerate(items)) + "</ol>"

DATA = {}
exec(open(f"{ROOT}/tools/pages_index.py", encoding="utf-8").read())
exec(open(f"{ROOT}/tools/pages_services.py", encoding="utf-8").read())
exec(open(f"{ROOT}/tools/pages_misc.py", encoding="utf-8").read())
os.makedirs(f"{ROOT}/content", exist_ok=True)
json.dump(DATA, open(f"{ROOT}/content/data.json", "w", encoding="utf-8"), ensure_ascii=False, indent=1)
print("built")
