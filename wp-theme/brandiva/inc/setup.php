<?php
defined('ABSPATH') || exit;

add_action('after_setup_theme', function () {
	load_theme_textdomain('brandiva', get_template_directory() . '/languages');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	add_theme_support('responsive-embeds');
	register_nav_menus([
		'primary'         => __('Primary menu (header + mobile)', 'brandiva'),
		'footer_pages'    => __('Footer — pages', 'brandiva'),
		'footer_services' => __('Footer — services', 'brandiva'),
	]);
	add_image_size('bv-card', 800, 533, true);
});

/* Disable features the theme doesn't use */
add_action('init', function () {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'wp_site_icon', 99);
	remove_action('wp_head', 'wp_generator');
});
add_filter('wpcf7_load_css', '__return_false'); // the theme styles CF7 itself (assets/css/src/07-cf7.css)

/* Gutenberg front-end styles are not needed by the custom templates */
add_action('wp_enqueue_scripts', function () {
	wp_dequeue_style('wp-block-library'); wp_dequeue_style('wp-block-library-theme'); wp_dequeue_style('global-styles'); wp_dequeue_style('classic-theme-styles');
}, 100);

/* ---------------------------------------------------------------- assets */
function bv_ver($rel) { $f = get_template_directory() . '/assets/' . $rel; return file_exists($f) ? filemtime($f) : wp_get_theme()->get('Version'); }

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_style('bv-site', bv_asset('css/site.css'), [], bv_ver('css/site.css'));

	// Google Fonts (only if a non-bundled family is chosen and allowed)
	if (bv_optb('font_google', true)) {
		$cat = bv_font_catalog(); $fam = [];
		foreach (['ar' => bv_opt('font_ar', 'plex'), 'lat' => bv_opt('font_lat', 'jakarta'), 'mono' => bv_opt('font_mono', 'space')] as $k => $v) {
			if (isset($cat[$k][$v]) && $cat[$k][$v][2]) $fam[] = 'family=' . $cat[$k][$v][2];
		}
		if ($fam) wp_enqueue_style('bv-google-fonts', 'https://fonts.googleapis.com/css2?' . implode('&', $fam) . '&display=swap', [], null);
	}

	$v = ['gsap' => 'js/vendor/gsap.min.js', 'st' => 'js/vendor/ScrollTrigger.min.js', 'split' => 'js/vendor/split-type.min.js', 'lenis' => 'js/vendor/lenis.min.js'];
	$prev = [];
	foreach ($v as $h => $p) {
		wp_enqueue_script('bv-' . $h, bv_asset($p), $prev, bv_ver($p), ['in_footer' => true, 'strategy' => 'defer']);
		$prev = ['bv-' . $h];
	}
	wp_enqueue_script('bv-main', bv_asset('js/main.js'), ['bv-lenis'], bv_ver('js/main.js'), ['in_footer' => true]);

	$S = function ($k) { return bv_t($k); };
	$cfg = [
		'waNumber' => preg_replace('/\D/', '', (string) bv_opt('c_whatsapp')),
		'email'    => (string) bv_opt('c_email'),
		'site'     => home_url('/'),
		'tz'       => (string) bv_opt('c_timezone', 'Africa/Cairo'),
		'fx'       => [
			'animations' => bv_optb('fx_animations'), 'preloader' => bv_optb('fx_preloader'), 'cursor' => bv_optb('fx_cursor'), 'smooth' => bv_optb('fx_smooth'),
		],
		'form'     => ['ok' => $S('form_ok'), 'bad' => $S('form_bad'), 'fail' => $S('form_fail'), 'template' => $S('form_wa_template')],
	];
	wp_add_inline_script('bv-gsap', 'window.BV=' . wp_json_encode($cfg) . ';', 'before');
}, 20);

add_filter('script_loader_tag', function ($tag, $handle, $src) {
	if ($handle === 'bv-main') return '<script type="module" src="' . esc_url($src) . '" id="bv-main-js"></script>' . "\n";
	return $tag;
}, 10, 3);

/* Modulepreload hints, theme-color, color-scheme, manifest, favicon */
add_action('wp_head', function () {
	echo '<script>document.documentElement.classList.add("js");setTimeout(function(){if(!window.__bvOK)document.documentElement.classList.remove("js")},6000)</script>' . "\n";
	echo '<meta name="theme-color" content="' . esc_attr(bv_opt('theme_color', '#07060B')) . '"><meta name="color-scheme" content="dark">' . "\n";
	foreach (['main', 'config', 'fx', 'hero', 'cursor', 'form'] as $m) echo '<link rel="modulepreload" href="' . esc_url(bv_asset("js/$m.js")) . '">';
	echo "\n";
	// preload bundled fonts used above the fold
	$ar = bv_opt('font_ar', 'plex'); $lat = bv_opt('font_lat', 'jakarta');
	$pl = function ($f) { echo '<link rel="preload" href="' . esc_url(bv_asset('fonts/' . $f)) . '" as="font" type="font/woff2" crossorigin>'; };
	if ($ar === 'plex') { if (bv_is_ar()) { $pl('IBMPlexSansArabic-700-arabic.woff2'); $pl('IBMPlexSansArabic-400-arabic.woff2'); } else $pl('IBMPlexSansArabic-400-latin.woff2'); }
	if (!bv_is_ar() && $lat === 'jakarta') $pl('PlusJakartaSans-800-latin.woff2');
	echo "\n";
	$fav = bv_opt('favicon'); $apple = bv_opt('apple_icon') ?: $fav;
	if ($fav) {
		echo '<link rel="icon" href="' . esc_url(bv_img_url($fav, 'thumbnail')) . '" sizes="32x32" type="image/png"><link rel="icon" href="' . esc_url(bv_img_url($fav)) . '" sizes="512x512" type="image/png">';
		echo '<link rel="apple-touch-icon" href="' . esc_url(bv_img_url($apple)) . '">';
	} else {
		echo '<link rel="icon" href="' . esc_url(bv_asset('img/favicon-32.png')) . '" type="image/png" sizes="32x32"><link rel="apple-touch-icon" href="' . esc_url(bv_asset('img/apple-touch-icon.png')) . '">';
	}
	echo '<link rel="manifest" href="' . esc_url(home_url('/?bv-manifest=1')) . '">' . "\n";
}, 2);

/* Dynamic design tokens from Theme Options (printed after site.css) */
function bv_hex_rgb($hex) { $h = ltrim((string) $hex, '#'); if (strlen($h) === 3) $h = $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2]; if (!preg_match('/^[0-9a-f]{6}$/i', $h)) return ''; return hexdec(substr($h, 0, 2)) . ',' . hexdec(substr($h, 2, 2)) . ',' . hexdec(substr($h, 4, 2)); }

add_action('wp_head', function () {
	$vars = [];
	foreach (bv_color_defaults() as $k => $c) {
		$val = bv_opt($k, $c[1]);
		if (preg_match('/^#[0-9a-f]{3,8}$/i', $val)) $vars[$c[2]] = $val;
	}
	$rgb = ['col_accent' => '--yellow-rgb', 'col_second' => '--purple-rgb', 'col_second_dark' => '--purple6-rgb', 'col_bg' => '--ink-rgb'];
	$def = bv_color_defaults();
	foreach ($rgb as $k => $var) { $r = bv_hex_rgb(bv_opt($k, $def[$k][1])); if ($r) $vars[$var] = $r; }
	$cat = bv_font_catalog();
	foreach (['ar' => ['font_ar', 'plex', '--font-ar'], 'lat' => ['font_lat', 'jakarta', '--font-lat'], 'mono' => ['font_mono', 'space', '--font-mono']] as $k => $o) {
		$id = bv_opt($o[0], $o[1]);
		if (isset($cat[$k][$id])) $vars[$o[2]] = $cat[$k][$id][1];
	}
	$vars['--r-card']  = (int) bv_opt('st_radius_card', 28) . 'px';
	$vars['--r-input'] = (int) bv_opt('st_radius_input', 16) . 'px';
	$vars['--container'] = (int) bv_opt('st_container', 1320) . 'px';
	$vars['--header-h'] = (int) bv_opt('st_header_h', 84) . 'px';
	$sp = ['compact' => 'clamp(3.5rem,8vw,7rem)', 'normal' => 'clamp(5rem,12vw,10rem)', 'spacious' => 'clamp(6.5rem,15vw,13rem)'];
	$vars['--sec'] = $sp[bv_opt('st_section_space', 'normal')] ?? $sp['normal'];
	$css = ':root{'; foreach ($vars as $k => $v) $css .= $k . ':' . $v . ';'; $css .= '}';
	$custom = (string) bv_opt('code_css');
	echo '<style id="bv-dynamic">' . $css . ($custom ? wp_strip_all_tags($custom) : '') . '</style>' . "\n";
	$head = (string) bv_opt('code_head');
	if ($head) echo $head . "\n"; // trusted: only administrators can edit Theme Options
	$ga = trim((string) bv_opt('seo_ga'));
	if (preg_match('/^G-[A-Z0-9]+$/i', $ga)) {
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr($ga) . '"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag("js",new Date());gtag("config","' . esc_js($ga) . '");</script>' . "\n";
	}
}, 30);

add_action('wp_footer', function () {
	$c = (string) bv_opt('code_body');
	if ($c) echo $c . "\n";
}, 99);

/* manifest.webmanifest generated from options */
add_action('template_redirect', function () {
	if (!isset($_GET['bv-manifest'])) return;
	$icon = bv_opt('favicon'); $name = bv_opt('site_name', 'Brandiva Media');
	$icons = $icon ? [['src' => bv_img_url($icon), 'sizes' => '512x512', 'type' => 'image/png']] : [['src' => bv_asset('img/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png']];
	nocache_headers(); header('Content-Type: application/manifest+json; charset=utf-8');
	echo wp_json_encode(['name' => $name, 'short_name' => $name, 'start_url' => bv_home_url(), 'display' => 'standalone', 'background_color' => bv_opt('col_bg', '#07060B'), 'theme_color' => bv_opt('theme_color', '#07060B'), 'lang' => bv_lang(), 'dir' => is_rtl() ? 'rtl' : 'ltr', 'icons' => $icons], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	exit;
});

/* body classes */
add_filter('body_class', function ($c) { $c[] = 'bv-' . bv_lang(); return $c; });

/* Optional attributes controlled by Effects options */
function bv_fxa($name) { // data-magnetic / data-tilt
	$on = $name === 'magnetic' ? bv_optb('fx_magnetic') : ($name === 'tilt' ? bv_optb('fx_tilt') : true);
	return $on ? ' data-' . $name : '';
}

/* ---------------------------------------------------------------- language switcher */
function bv_other_lang() {
	if (!function_exists('pll_the_languages')) return null;
	$langs = pll_the_languages(['raw' => 1, 'hide_if_no_translation' => 0]);
	if (!is_array($langs)) return null;
	foreach ($langs as $slug => $l) if (empty($l['current_lang']) && !empty($l['url'])) return ['slug' => $slug, 'url' => $l['url'], 'name' => $l['name']];
	return null;
}

/* ---------------------------------------------------------------- logo markup */
function bv_logo($which = 'header') {
	$id = bv_opt('logo'); $w = (int) bv_opt($which === 'footer' ? 'footer_logo_w' : 'logo_w', $which === 'footer' ? 150 : 87);
	$cls = $which === 'footer' ? 'logo logo-lg' : 'logo';
	$name = bv_opt('site_name', 'Brandiva Media');
	if (!$id) return '<img class="' . $cls . '" src="' . esc_url(bv_asset('img/logo.png')) . '" alt="' . esc_attr($name) . '" width="' . $w . '" height="' . round($w * 0.667) . '" decoding="async">';
	$m = wp_get_attachment_metadata($id); $ratio = (!empty($m['width']) && !empty($m['height'])) ? $m['height'] / $m['width'] : 0.667;
	$img = '<img class="' . $cls . '" src="' . esc_url(wp_get_attachment_url($id)) . '" alt="' . esc_attr($name) . '" width="' . $w . '" height="' . round($w * $ratio) . '" decoding="async">';
	$webp = bv_opt('logo_webp');
	return '<picture>' . ($webp ? '<source srcset="' . esc_url(wp_get_attachment_url($webp)) . '" type="image/webp">' : '') . $img . '</picture>';
}
