# ---------------------------------------------------------------- contact + 404 (exec'd inside build.py)
contact_body = f'''
<section class="phero" aria-labelledby="h1">
<div class="container">
<ol class="crumbs mono" aria-label="مسار التنقل"><li><a href="index.html">الرئيسية</a></li><li>/</li><li><span aria-current="page">تواصل معنا</span></li></ol>
<p class="eyebrow mono" style="margin-block-start:1rem">CONTACT — طلب استشارة</p>
<h1 class="h1 rise" id="h1">لنبدأ مشروعك… اطلب استشارتك المجانية</h1>
<p class="lead" data-reveal>كل ما عليك هو التواصل معنا، وسنقوم بدراسة موقعك، وفهم أهدافك، ثم نقترح خطة عمل مناسبة دون التزام مسبق.</p>
</div></section>
<section class="container contact-grid" style="padding-block-end:var(--sec)" aria-label="نموذج التواصل">
<div class="card rise">{quick_form("c")}</div>
<div class="ci rise">
<a class="row" href="https://wa.me/201000000000" rel="noopener" data-todo="رقم واتساب"><span class="ic" aria-hidden="true">W</span><span><strong>واتساب</strong><br><span class="cap" dir="ltr">+20 10 0000 0000 (TODO)</span></span></a>
<a class="row" href="mailto:info@brandivamedia.com" data-todo="البريد الرسمي"><span class="ic" aria-hidden="true">@</span><span><strong>البريد الإلكتروني</strong><br><span class="cap" dir="ltr">info@brandivamedia.com (TODO)</span></span></a>
<div class="card"><h2 style="font-size:var(--fs-h3);margin-block-end:.6rem">ماذا يحدث بعد الإرسال؟</h2>{ticks(["دراسة موقعك وفهم أهدافك", "خطة عمل تناسب نشاطك", "عرض سعر واضح دون تكاليف غير متوقعة"], "p")}</div>
<div class="card"><span class="mono y">القاهرة الآن</span><p class="clock" style="font-size:2rem;margin-block-start:.4rem">--:--:--</p></div>
</div></section>'''
contact_ld = [ORG, BIZ, crumb([("الرئيسية", "index.html"), ("تواصل معنا", "contact.html")]), {"@type": "ContactPage", "name": "تواصل مع Brandiva Media", "url": SITE + "/contact.html"}]
cp = page("contact.html", "تواصل معنا | اطلب استشارة مجانية | Brandiva Media",
          "تواصل مع Brandiva Media واطلب استشارة مجانية لتصميم موقعك أو تحسين SEO أو اختيار استضافة مناسبة. نرد عليك في أقرب وقت.", contact_ld, contact_body, "contact.html")
cp = cp.replace("</body>", """<script type="module">const s=new URLSearchParams(location.search).get('service');const el=document.getElementById('c-svc');if(s&&el)el.value=s;</script></body>""")
write("contact.html", cp)

err = '''<section class="err404"><div class="container"><div class="giant" aria-hidden="true">404</div>
<h1 class="h1" style="margin-block:1rem">الصفحة غير موجودة</h1><p class="lead" style="margin-inline:auto;margin-block-end:2rem">يبدو أن الرابط غير صحيح أو أن الصفحة نُقلت. ارجع للرئيسية أو اطلب استشارة.</p>
<div class="hero-cta" style="justify-content:center"><a class="btn btn-yellow btn-lg" href="index.html">العودة للرئيسية</a><a class="btn btn-ghost btn-lg" href="contact.html">تواصل معنا</a></div></div></section>'''
write("404.html", page("404.html", "الصفحة غير موجودة | Brandiva Media", "الصفحة المطلوبة غير موجودة. ارجع إلى الصفحة الرئيسية لـ Brandiva Media.", [ORG], err, "").replace('<meta name="description"', '<meta name="robots" content="noindex"><meta name="description"', 1))
