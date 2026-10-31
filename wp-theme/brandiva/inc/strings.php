<?php
/**
 * Editable UI strings. Every entry becomes a field in Theme Options → Texts (AR / EN).
 * Format: key => [ label, arabic default, english default, type (text|textarea) ]
 */
defined('ABSPATH') || exit;

function bv_strings() {
	static $s = null;
	if ($s !== null) return $s;
	$s = [
		// Header / menu
		'skip'            => ['Skip link', 'تخطَّ إلى المحتوى الرئيسي', 'Skip to main content'],
		'announce_label'  => ['Announcement – label', 'استشارة مجانية', 'Free consultation'],
		'announce_text'   => ['Announcement – text', '— ابدأ مشروعك', '— start your project'],
		'nav_aria'        => ['Main navigation (aria)', 'التنقل الرئيسي', 'Main navigation'],
		'header_cta'      => ['Header button label', 'اطلب استشارة', 'Get a quote'],
		'menu_open'       => ['Menu button – open', 'القائمة', 'Menu'],
		'menu_close'      => ['Menu button – close', 'إغلاق', 'Close'],
		'menu_aria'       => ['Mobile menu (aria)', 'القائمة الرئيسية', 'Main menu'],
		'menu_foot_cta'   => ['Mobile menu – CTA link', 'اطلب استشارة مجانية', 'Request a free consultation'],
		'menu_tagline'    => ['Mobile menu – tagline', 'BRANDIVA MEDIA — EGYPT', 'BRANDIVA MEDIA — EGYPT'],
		'switch_label'    => ['Language switch label', 'EN', 'العربية'],
		'switch_aria'     => ['Language switch (aria)', 'Switch to English', 'التبديل إلى العربية'],
		'social_aria'     => ['Social icons (aria)', 'حسابات التواصل الاجتماعي', 'Social media'],
		// Footer
		'footer_blurb'    => ['Footer – blurb', 'نصمّم حضورك الرقمي… ونوصّله لعملائك. تصميم مواقع، SEO، واستضافة سريعة وآمنة.', 'We build your digital presence and bring it to your customers. Web design, SEO and fast, secure hosting.', 'textarea'],
		'footer_pages'    => ['Footer – pages heading', 'الصفحات', 'Pages'],
		'footer_pages_aria' => ['Footer – pages (aria)', 'روابط الصفحات', 'Pages'],
		'footer_services' => ['Footer – services heading', 'الخدمات', 'Services'],
		'footer_contact'  => ['Footer – contact heading', 'تواصل', 'Contact'],
		'footer_form'     => ['Footer – form link label', 'نموذج طلب استشارة', 'Consultation request form'],
		'footer_city'     => ['Footer – city label', 'القاهرة', 'Cairo'],
		'clock_aria'      => ['Clock (aria)', 'الساعة الآن بتوقيت القاهرة', 'Current time in Cairo'],
		'copyright'       => ['Copyright text', 'جميع الحقوق محفوظة.', 'All rights reserved.'],
		'to_top'          => ['Back to top – label', 'للأعلى', 'Top'],
		'to_top_aria'     => ['Back to top (aria)', 'العودة لأعلى الصفحة', 'Back to top'],
		// Breadcrumb / service pages
		'crumb_home'      => ['Breadcrumb – home', 'الرئيسية', 'Home'],
		'crumbs_aria'     => ['Breadcrumb (aria)', 'مسار التنقل', 'Breadcrumb'],
		'toc_title'       => ['Table of contents title', 'محتويات الصفحة', 'On this page'],
		'toc_aria'        => ['Table of contents (aria)', 'فهرس الصفحة', 'Page index'],
		'svc_cta_primary' => ['Service hero – primary button', 'اطلب استشارة مجانية', 'Request a free consultation'],
		'svc_cta_browse'  => ['Service hero – browse button', 'تصفّح الصفحة', 'Browse this page'],
		'flip_hint'       => ['Flip card hint', 'مرّر أو المس للقراءة', 'Hover or tap to read'],
		'quote_btn'       => ['Default quote button', 'اطلب عرض سعر', 'Request a quote'],
		// Home
		'scroll_cue'      => ['Hero – scroll cue', 'انزل', 'SCROLL'],
		'scroll_aria'     => ['Hero – scroll cue (aria)', 'انزل للأسفل', 'Scroll down'],
		'trust_aria'      => ['Hero – trust list (aria)', 'ما يميزنا', 'What sets us apart'],
		'placeholder_tag' => ['Testimonial placeholder tag', 'مكان مخصص', 'PLACEHOLDER'],
		'slider_aria'     => ['Testimonials (aria)', 'آراء العملاء — اسحب لعرض المزيد', 'Client reviews — drag for more'],
		'marquee_aria'    => ['Keywords marquee (aria)', 'كلمات مفتاحية', 'Keywords'],
		'industries_aria' => ['Industries (aria)', 'القطاعات التي نخدمها', 'Industries we serve'],
		'illustrative'    => ['Caption – illustrative example', 'مثال توضيحي', 'Illustrative example'],
		'illustrative_serp' => ['Caption – illustrative search result', 'مثال توضيحي لنتيجة بحث', 'Illustrative search result'],
		// Contact
		'ct_whatsapp'     => ['Contact – WhatsApp label', 'واتساب', 'WhatsApp'],
		'ct_email'        => ['Contact – email label', 'البريد الإلكتروني', 'Email'],
		'ct_aria'         => ['Contact form section (aria)', 'نموذج التواصل', 'Contact form'],
		'ct_follow'       => ['Contact – follow us', 'تابعنا', 'Follow us'],
		'ct_clock'        => ['Contact – clock label', 'القاهرة الآن', 'Cairo time now'],
		// Forms
		'form_ok'         => ['Form – WhatsApp ready message', 'تم تجهيز رسالتك على واتساب — أرسلها وسنردّ عليك في أقرب وقت.', 'Your message is ready on WhatsApp — send it and we will reply shortly.'],
		'form_bad'        => ['Form – validation message', 'من فضلك راجع الحقول المميّزة وحاول مرة أخرى.', 'Please check the highlighted fields and try again.'],
		'form_fail'       => ['Form – WhatsApp failed message', 'تعذّر فتح واتساب. أرسل طلبك عبر البريد الإلكتروني.', 'Could not open WhatsApp. Send your request by email.'],
		'form_wa_template'=> ['Form – WhatsApp message template ({name} {phone} {service} {email} {message})',
			"مرحبًا Brandiva، أنا {name}.\nالخدمة: {service}\nالهاتف: {phone}\nالبريد: {email}\nالتفاصيل: {message}",
			"Hello Brandiva, I'm {name}.\nService: {service}\nPhone: {phone}\nEmail: {email}\nDetails: {message}", 'textarea'],
		'svc_opt_choose'  => ['Service dropdown – placeholder', 'اختر الخدمة', 'Choose a service'],
		'svc_opt_web-design' => ['Service dropdown – web design', 'تصميم مواقع', 'Web design'],
		'svc_opt_seo'     => ['Service dropdown – SEO', 'خدمات SEO', 'SEO services'],
		'svc_opt_hosting' => ['Service dropdown – hosting', 'استضافة مواقع', 'Web hosting'],
		'svc_opt_other'   => ['Service dropdown – other', 'أخرى', 'Other'],
		// 404
		'e404_title'      => ['404 – heading', 'الصفحة غير موجودة', 'Page not found'],
		'e404_text'       => ['404 – text', 'يبدو أن الرابط غير صحيح أو أن الصفحة نُقلت. ارجع للرئيسية أو اطلب استشارة.', 'The link may be wrong or the page may have moved. Head back home or request a consultation.', 'textarea'],
		'e404_home'       => ['404 – home button', 'العودة للرئيسية', 'Back to home'],
		'e404_contact'    => ['404 – contact button', 'تواصل معنا', 'Contact us'],
		'e404_meta_title' => ['404 – browser title', 'الصفحة غير موجودة | Brandiva Media', 'Page Not Found | Brandiva Media'],
		// Generic page.php
		'no_results'      => ['Generic – nothing found', 'لا يوجد محتوى.', 'Nothing found.'],
	];
	return $s;
}
