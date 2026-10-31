<?php
defined('ABSPATH') || exit;

/* ---------------------------------------------------------------- language */
function bv_lang() {
	$l = '';
	if (function_exists('pll_current_language')) $l = (string) pll_current_language();
	if (!$l) $l = substr(determine_locale(), 0, 2);
	return $l === 'en' ? 'en' : ($l === 'ar' ? 'ar' : (is_rtl() ? 'ar' : 'en'));
}
function bv_is_ar() { return bv_lang() === 'ar'; }
/** Pick by language: bv_l('عربي','English'). */
function bv_l($ar, $en) { return bv_is_ar() ? $ar : $en; }

/* ---------------------------------------------------------------- fields (safe when SCF is inactive) */
function bv_f($name, $post_id = false, $default = '') {
	if (!function_exists('get_field')) return $default;
	$v = get_field($name, $post_id);
	return ($v === null || $v === false || $v === '') ? $default : $v;
}
/** Boolean field (false is a valid value). */
function bv_fb($name, $post_id = false, $default = true) {
	if (!function_exists('get_field')) return $default;
	$v = get_field($name, $post_id);
	return $v === null ? $default : (bool) $v;
}
/** Global (language-independent) theme option. */
function bv_opt($name, $default = '') { return bv_f($name, 'bv_global', $default); }
function bv_optb($name, $default = true) { return bv_fb($name, 'bv_global', $default); }
/** Translatable UI text (Theme Options → Texts). */
function bv_t($key) {
	$s = bv_strings();
	$def = isset($s[$key]) ? $s[$key][bv_is_ar() ? 1 : 2] : '';
	return bv_f('t_' . str_replace('-', '_', $key), 'bv_texts_' . bv_lang(), $def);
}
function bv_te($key) { echo esc_html(bv_t($key)); }

/* ---------------------------------------------------------------- output helpers */
/** Multi-line text → escaped with <br>. */
function bv_br($s) { return nl2br(esc_html($s), false); }
/** Allow only <em>/<br>/<strong>/<span> in short headings. */
function bv_inline($s) { return wp_kses($s, ['em' => [], 'br' => [], 'strong' => [], 'span' => ['class' => []]]); }

/** WYSIWYG body → theme markup (ul.ticks, reveal hooks). */
function bv_rich($html, $list_style = 'tick') {
	if ($html === '' || $html === null) return '';
	$html = wpautop($html);
	$cls  = $list_style === 'plain' ? 'ticks p' : 'ticks';
	$html = preg_replace('/<ul(\s[^>]*)?>/i', '<ul class="' . $cls . '" data-reveal>', $html);
	return do_shortcode($html);
}

function bv_asset($path) { return get_template_directory_uri() . '/assets/' . ltrim($path, '/'); }

function bv_arrow() { return '<svg class="ico" aria-hidden="true"><use href="' . esc_url(bv_asset('img/sprite.svg#arrow')) . '"/></svg>'; }
function bv_icon($id, $cls = 'fa') { return '<svg class="' . esc_attr($cls) . '" aria-hidden="true" focusable="false"><use href="' . esc_url(bv_asset('img/fa-brands.svg#' . $id)) . '"/></svg>'; }

/** Responsive attachment image. $fallback = theme asset path used when no attachment is set. */
function bv_img($id, $alt = '', $class = '', $sizes = '100vw', $lazy = true, $size = 'large', $fallback = '') {
	if ($id) {
		$a = ['class' => $class, 'sizes' => $sizes, 'decoding' => 'async', 'loading' => $lazy ? 'lazy' : 'eager'];
		if ($alt !== '') $a['alt'] = $alt; else $a['alt'] = '';
		$html = wp_get_attachment_image((int) $id, $size, false, $a);
		if ($html) return $html;
	}
	if ($fallback) return '<img class="' . esc_attr($class) . '" src="' . esc_url(bv_asset($fallback)) . '" alt="' . esc_attr($alt) . '" decoding="async"' . ($lazy ? ' loading="lazy"' : '') . '>';
	return '';
}
function bv_img_url($id, $size = 'full') { $u = $id ? wp_get_attachment_image_url((int) $id, $size) : ''; return $u ?: ''; }

/** Page-link / link field → URL. */
function bv_url($v) {
	if (is_array($v)) return !empty($v['url']) ? $v['url'] : '';
	if (is_numeric($v)) return get_permalink((int) $v) ?: '';
	return (string) $v;
}
/** URL of the page assigned to a template, in the current language. */
function bv_page_by_template($tpl) {
	$q = get_posts(['post_type' => 'page', 'posts_per_page' => 1, 'post_status' => 'publish', 'meta_key' => '_wp_page_template', 'meta_value' => $tpl, 'lang' => bv_lang(), 'suppress_filters' => false, 'fields' => 'ids']);
	return $q ? (int) $q[0] : 0;
}
function bv_contact_url($service = '') {
	$id  = bv_page_by_template('templates/page-contact.php');
	$url = $id ? get_permalink($id) : home_url('/');
	return $service ? add_query_arg('service', $service, $url) : $url;
}
function bv_home_url() { return function_exists('pll_home_url') ? pll_home_url() : home_url('/'); }

function bv_wa_url($msg = '') {
	$n = preg_replace('/\D/', '', (string) bv_opt('c_whatsapp'));
	return $n ? 'https://wa.me/' . $n . ($msg ? '?text=' . rawurlencode($msg) : '') : '';
}

function bv_service_choices() {
	return ['web-design' => bv_t('svc_opt_web-design'), 'seo' => bv_t('svc_opt_seo'), 'hosting' => bv_t('svc_opt_hosting'), 'other' => bv_t('svc_opt_other')];
}

/** Social networks supported by assets/img/fa-brands.svg */
function bv_socials_all() { return ['fb' => 'Facebook', 'ig' => 'Instagram', 'in' => 'LinkedIn', 'yt' => 'YouTube', 'tt' => 'TikTok', 'x' => 'X']; }
function bv_social_icons() {
	$rows = bv_opt('social_links', []);
	if (!is_array($rows) || !$rows) return '';
	$all = bv_socials_all(); $h = '';
	foreach ($rows as $r) {
		if (empty($r['enabled']) || empty($r['url']) || empty($r['network']) || !isset($all[$r['network']])) continue;
		$h .= '<a href="' . esc_url($r['url']) . '" rel="noopener" target="_blank">' . bv_icon($r['network']) . '<span class="sr-only">' . esc_html($all[$r['network']]) . '</span></a>';
	}
	return $h ? '<div class="socials" aria-label="' . esc_attr(bv_t('social_aria')) . '">' . $h . '</div>' : '';
}

/** Marquee row. */
function bv_marquee($words, $dir = 0, $outline = false, $cls = '') {
	$sep = '<span class="sep" aria-hidden="true"></span>'; $one = '';
	foreach (array_values($words) as $i => $w) $one .= '<span' . ($outline && $i % 2 ? ' class="o"' : '') . '>' . esc_html($w) . '</span>' . $sep;
	return '<div class="marquee ' . esc_attr($cls) . '" data-dir="' . (int) $dir . '" aria-hidden="true"><div class="marquee-track">' . $one . '</div><div class="marquee-track">' . $one . '</div></div>';
}

/** Render a Contact Form 7 form wrapped with delivery settings. */
function bv_form($form_id, $service = '') {
	if (!$form_id) return '<p class="cap">' . esc_html__('Select a Contact Form 7 form in Theme Options → Texts.', 'brandiva') . '</p>';
	if (!shortcode_exists('contact-form-7')) return '';
	$mode = bv_opt('form_mode', 'both');
	$tpl  = bv_t('form_wa_template');
	return '<div class="bv-form" data-mode="' . esc_attr($mode) . '" data-tpl="' . esc_attr($tpl) . '" data-service="' . esc_attr($service) . '">' . do_shortcode('[contact-form-7 id="' . (int) $form_id . '"]') . '</div>';
}

/** ID of a Contact Form 7 form chosen in Texts (field "f_quote_form" / "f_contact_form"). */
function bv_form_id($which) { $v = bv_f('f_' . $which . '_form', 'bv_texts_' . bv_lang(), 0); return is_object($v) ? $v->ID : (int) $v; }
