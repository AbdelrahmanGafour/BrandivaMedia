<?php
/** Contact Form 7 helpers: default form templates used by the demo importer. */
defined('ABSPATH') || exit;

/** Form markup that reuses the theme's .form/.field classes. $compact = home CTA variant (no email/message). */
function bv_cf7_markup($lang, $compact = false) {
	$ar = $lang === 'ar';
	$t = function ($a, $e) use ($ar) { return $ar ? $a : $e; };
	$opts = '"' . $t('اختر الخدمة', 'Choose a service') . '" "' . $t('تصميم مواقع', 'Web design') . '" "' . $t('خدمات SEO', 'SEO services') . '" "' . $t('استضافة مواقع', 'Web hosting') . '" "' . $t('أخرى', 'Other') . '"';
	$p = $compact ? 'q' : 'c';
	$h  = "<div class=\"form\">\n<div class=\"row2\">\n";
	$h .= '<div class="field"><label for="' . $p . '-name">' . $t('الاسم', 'Name') . "</label>[text* your-name id:$p-name autocomplete:name minlength:2]</div>\n";
	$h .= '<div class="field"><label for="' . $p . '-phone">' . $t('رقم الهاتف / واتساب', 'Phone / WhatsApp') . "</label>[tel* your-phone id:$p-phone autocomplete:tel minlength:8]</div>\n</div>\n";
	$h .= '<div class="field"><label for="' . $p . '-svc">' . $t('الخدمة المطلوبة', 'Service needed') . "</label>[select service id:$p-svc first_as_label $opts]</div>\n";
	if (!$compact) {
		$h .= '<div class="field"><label for="c-email">' . $t('البريد الإلكتروني', 'Email') . ' <span class="cap">' . $t('(اختياري)', '(optional)') . "</span></label>[email your-email id:c-email autocomplete:email]</div>\n";
		$h .= '<div class="field"><label for="c-msg">' . $t('تفاصيل مشروعك', 'Project details') . ' <span class="cap">' . $t('(اختياري)', '(optional)') . "</span></label>[textarea your-message id:c-msg 40x4]</div>\n";
	}
	$h .= '<button class="btn btn-yellow btn-lg" type="submit"><span class="spin" aria-hidden="true"></span><svg class="fa" aria-hidden="true" focusable="false"><use href="' . get_template_directory_uri() . '/assets/img/fa-brands.svg#wa"/></svg><span>' . $t('أرسل الطلب', 'Send request') . "</span></button>\n";
	$h .= '<p class="cap">' . $t('لن نشارك بياناتك مع أي طرف. سنردّ عليك في أقرب وقت.', 'We never share your details with third parties. We will reply as soon as possible.') . "</p>\n</div>";
	return $h;
}

function bv_cf7_mail($lang) {
	$ar = $lang === 'ar';
	return [
		'active' => true, 'recipient' => '[_site_admin_email]', 'sender' => '[_site_title] <wordpress@' . wp_parse_url(home_url(), PHP_URL_HOST) . '>',
		'subject' => ($ar ? 'طلب استشارة جديد من [your-name]' : 'New consultation request from [your-name]'),
		'additional_headers' => 'Reply-To: [your-email]',
		'body' => ($ar ? "الاسم: [your-name]\nالهاتف: [your-phone]\nالخدمة: [service]\nالبريد: [your-email]\n\nالتفاصيل:\n[your-message]\n\n-- \nأُرسل من [_url]" : "Name: [your-name]\nPhone: [your-phone]\nService: [service]\nEmail: [your-email]\n\nDetails:\n[your-message]\n\n-- \nSent from [_url]"),
		'use_html' => false, 'exclude_blank' => true,
	];
}
function bv_cf7_messages($lang) {
	return $lang === 'ar' ? [
		'mail_sent_ok' => 'تم إرسال طلبك بنجاح. سنرد عليك في أقرب وقت.', 'mail_sent_ng' => 'تعذّر إرسال الرسالة. حاول مرة أخرى أو تواصل معنا عبر واتساب.',
		'validation_error' => 'من فضلك راجع الحقول المميّزة وحاول مرة أخرى.', 'invalid_required' => 'هذا الحقل مطلوب.', 'invalid_too_short' => 'القيمة قصيرة جدًا.',
		'invalid_email' => 'اكتب بريدًا إلكترونيًا صحيحًا.', 'invalid_tel' => 'اكتب رقم هاتف صحيح.', 'spam' => 'تعذّر إرسال الرسالة. حاول مرة أخرى.',
	] : [
		'mail_sent_ok' => 'Your request was sent. We will reply shortly.', 'mail_sent_ng' => 'The message could not be sent. Please try again or contact us on WhatsApp.',
		'validation_error' => 'Please check the highlighted fields and try again.', 'invalid_required' => 'This field is required.', 'invalid_too_short' => 'This value is too short.',
		'invalid_email' => 'Enter a valid email address.', 'invalid_tel' => 'Enter a valid phone number.', 'spam' => 'The message could not be sent. Please try again.',
	];
}
