# ---------------------------------------------------------------- contact + 404 (exec'd inside build.py)
contact_body = f'''
<section class="phero" aria-labelledby="h1">
<div class="container">
<ol class="crumbs mono" aria-label="{L("مسار التنقل","Breadcrumb")}"><li><a href="index.html">{L("الرئيسية","Home")}</a></li><li>/</li><li><span aria-current="page">{L("تواصل معنا","Contact")}</span></li></ol>
<p class="eyebrow mono" style="margin-block-start:1rem">{L("CONTACT — طلب استشارة","CONTACT — REQUEST A CONSULTATION")}</p>
<h1 class="h1 rise" id="h1">{L("لنبدأ مشروعك… اطلب استشارتك المجانية","Let's start your project… request your free consultation")}</h1>
<p class="lead" data-reveal>{L("كل ما عليك هو التواصل معنا، وسنقوم بدراسة موقعك، وفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.","All you need to do is get in touch. We'll review your site, understand your goals, and propose a suitable plan — no commitment.")}</p>
</div></section>
<section class="container contact-grid" style="padding-block-end:var(--sec)" aria-label="{L("نموذج التواصل","Contact form")}">
<div class="card rise">{quick_form("c")}</div>
<div class="ci rise">
<a class="row" href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب"><span class="ic" aria-hidden="true">W</span><span><strong>WhatsApp</strong><br><span class="cap" dir="ltr">+20 10 0000 0000 (TODO)</span></span></a>
<a class="row" href="mailto:info@brandivamedia.com" data-todo="البريد الرسمي"><span class="ic" aria-hidden="true">@</span><span><strong>{L("البريد الإلكتروني","Email")}</strong><br><span class="cap" dir="ltr">info@brandivamedia.com (TODO)</span></span></a>
<div class="card"><h2 style="font-size:var(--fs-h3);margin-block-end:.6rem">{L("ماذا يحدث بعد الإرسال؟","What happens after you submit?")}</h2>{ticks(L(["دراسة موقعك وفهم أهدافك", "خطة عمل تناسب نشاطك", "عرض سعر واضح دون تكاليف غير متوقعة"], ["We review your site and understand your goals", "A plan that fits your business", "A clear quote with no unexpected costs"]), "p")}</div>
<div class="card"><span class="mono y">{L("القاهرة الآن","Cairo time now")}</span><p class="clock" style="font-size:2rem;margin-block-start:.4rem">--:--:--</p></div>
</div></section>'''
contact_ld = [ORG, BIZ, crumb([(L("الرئيسية","Home"), "index.html"), (L("تواصل معنا","Contact"), "contact.html")]), {"@type": "ContactPage", "name": L("تواصل مع Brandiva Media","Contact Brandiva Media"), "url": SITE + "/contact.html"}]
cp = page("contact.html", L("تواصل معنا | اطلب استشارة مجانية | Brandiva Media","Contact Us | Request a Free Consultation | Brandiva Media"),
          L("تواصل مع Brandiva Media واطلب استشارة مجانية لتصميم موقعك أو تحسين SEO أو اختيار استضافة مناسبة. نرد عليك في أقرب وقت.","Contact Brandiva Media and request a free consultation on web design, SEO or choosing the right hosting. We reply as soon as possible."), contact_ld, contact_body, "contact.html")
cp = cp.replace("</body>", """<script type="module">const s=new URLSearchParams(location.search).get('service');const el=document.getElementById('c-svc');if(s&&el)el.value=s;</script></body>""")
write("contact.html", cp)

err = f'''<section class="err404"><div class="container"><div class="giant" aria-hidden="true">404</div>
<h1 class="h1" style="margin-block:1rem">{L("الصفحة غير موجودة","Page not found")}</h1><p class="lead" style="margin-inline:auto;margin-block-end:2rem">{L("يبدو أن الرابط غير صحيح أو أن الصفحة نُقلت. ارجع للرئيسية أو اطلب استشارة.","The link may be wrong or the page may have moved. Head back home or request a consultation.")}</p>
<div class="hero-cta" style="justify-content:center"><a class="btn btn-yellow btn-lg" href="index.html">{L("العودة للرئيسية","Back to home")}</a><a class="btn btn-ghost btn-lg" href="contact.html">{L("تواصل معنا","Contact us")}</a></div></div></section>'''
write("404.html", page("404.html", L("الصفحة غير موجودة | Brandiva Media","Page Not Found | Brandiva Media"), L("الصفحة المطلوبة غير موجودة. ارجع إلى الصفحة الرئيسية لـ Brandiva Media.","The page you requested doesn't exist. Return to the Brandiva Media home page."), [ORG], err, "").replace('<meta name="description"', '<meta name="robots" content="noindex"><meta name="description"', 1))
