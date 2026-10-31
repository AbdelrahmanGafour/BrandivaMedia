<?php
/**
 * Demo content importer — Appearance → Demo Import.
 * Creates (or refreshes) the Arabic + English pages, SCF field values, Contact Form 7 forms,
 * media, menus, Theme Options and reading settings from demo/demo-data.json.
 * Safe to run more than once: demo items are tracked with the _bv_demo_key / _bv_demo_asset meta.
 */
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
	add_theme_page('Demo Import', 'Demo Import', 'manage_options', 'bv-demo', 'bv_demo_screen');
});

add_action('admin_post_bv_import_demo', function () {
	check_admin_referer('bv_import_demo');
	if (!current_user_can('manage_options')) wp_die('Forbidden');
	@set_time_limit(600);
	@ini_set('memory_limit', '512M');
	$log = bv_demo_run();
	set_transient('bv_demo_log', $log, HOUR_IN_SECONDS);
	wp_safe_redirect(admin_url('themes.php?page=bv-demo&done=1')); exit;
});

function bv_demo_screen() {
	$missing = bv_missing_plugins();
	$log = get_transient('bv_demo_log');
	echo '<div class="wrap"><h1>Brandiva — Demo Import</h1>';
	echo '<p>Imports the full Arabic (default) and English site: 5 pages per language, all section content as Secure Custom Fields, Contact Form 7 forms, images, menus and Theme Options.</p>';
	echo '<h2>Required plugins</h2><ul style="list-style:disc;margin-inline-start:1.5rem">';
	foreach (bv_required_plugins() as $p) echo '<li>' . esc_html($p['name']) . ' — ' . (bv_plugin_active($p['file']) ? '<span style="color:#1a7f37">active</span>' : '<span style="color:#b32d2e">not active</span>') . '</li>';
	echo '</ul>';
	if ($missing) {
		$url = wp_nonce_url(admin_url('admin-post.php?action=bv_install_plugins'), 'bv_install_plugins');
		echo '<p><a class="button button-primary" href="' . esc_url($url) . '">Install &amp; activate required plugins</a></p>';
		$pl = get_option('bv_plugins_log'); if ($pl) { echo '<p><strong>Last attempt:</strong> '; foreach ($pl as $k => $v) echo esc_html("$k: $v; "); echo '</p>'; }
	} else {
		echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '"><input type="hidden" name="action" value="bv_import_demo">';
		wp_nonce_field('bv_import_demo');
		echo '<p class="description">Re-running refreshes the demo pages’ fields (your own pages are not touched).</p>';
		submit_button('Import demo content', 'primary large', 'submit', true, ['onclick' => "this.value='Importing… please wait';"]);
		echo '</form>';
	}
	if ($log) {
		echo '<h2>Import log</h2><pre style="background:#fff;padding:1rem;border:1px solid #ccd0d4;max-height:420px;overflow:auto;direction:ltr;text-align:left">' . esc_html(implode("\n", $log)) . '</pre>';
	}
	echo '</div>';
}

/* ------------------------------------------------------------------ helpers */
function bv_demo_media($key, $rel, $alt = '') {
	$q = get_posts(['post_type' => 'attachment', 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_bv_demo_asset', 'meta_value' => $key, 'lang' => '', 'suppress_filters' => true]);
	if ($q) return (int) $q[0];
	require_once ABSPATH . 'wp-admin/includes/file.php'; require_once ABSPATH . 'wp-admin/includes/media.php'; require_once ABSPATH . 'wp-admin/includes/image.php';
	$src = get_template_directory() . '/assets/' . $rel;
	if (!file_exists($src)) return 0;
	$tmp = wp_tempnam(basename($src)); copy($src, $tmp);
	$id = media_handle_sideload(['name' => basename($src), 'tmp_name' => $tmp], 0, $alt ?: $key);
	if (is_wp_error($id)) { @unlink($tmp); return 0; }
	update_post_meta($id, '_bv_demo_asset', $key);
	if ($alt) update_post_meta($id, '_wp_attachment_image_alt', $alt);
	return (int) $id;
}

function bv_demo_find($meta_value, $post_type = 'page') {
	$q = get_posts(['post_type' => $post_type, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids', 'meta_key' => '_bv_demo_key', 'meta_value' => $meta_value, 'lang' => '', 'suppress_filters' => true]);
	return $q ? (int) $q[0] : 0;
}

/** Replace {"$img":..}/{"$page":..} markers recursively. */
function bv_demo_resolve($v, $lang, $media, $pages) {
	if (is_array($v)) {
		if (isset($v['$img'])) return $media[$v['$img']] ?? 0;
		if (isset($v['$page'])) return $pages[$lang][$v['$page']] ?? 0;
		$o = [];
		foreach ($v as $k => $x) $o[$k] = bv_demo_resolve($x, $lang, $media, $pages);
		return $o;
	}
	return $v;
}

function bv_demo_languages(&$log) {
	if (!function_exists('PLL') || !PLL()) { $log[] = '! Polylang API not available'; return; }
	$model = PLL()->model;
	$have = (array) $model->get_languages_list(['fields' => 'slug']);
	$want = [
		'ar' => ['name' => 'العربية', 'slug' => 'ar', 'locale' => 'ar', 'rtl' => 1, 'term_group' => 1, 'flag' => 'eg'],
		'en' => ['name' => 'English', 'slug' => 'en', 'locale' => 'en_US', 'rtl' => 0, 'term_group' => 2, 'flag' => 'us'],
	];
	foreach ($want as $slug => $args) {
		if (in_array($slug, $have, true)) { $log[] = "= language $slug exists"; continue; }
		$r = $model->add_language($args);
		$log[] = is_wp_error($r) ? "! language $slug: " . $r->get_error_message() : "+ language $slug added";
	}
	$opts = get_option('polylang', []);
	$opts['default_lang'] = 'ar'; $opts['hide_default'] = 1; $opts['force_lang'] = 1; $opts['rewrite'] = 1;
	update_option('polylang', $opts);
	if (method_exists($model, 'clean_languages_cache')) $model->clean_languages_cache();
}

function bv_demo_cf7_form($key, $lang, $title, $compact, &$log) {
	if (!class_exists('WPCF7_ContactForm')) return 0;
	$id = bv_demo_find($key, 'wpcf7_contact_form');
	$cf = $id ? WPCF7_ContactForm::get_instance($id) : WPCF7_ContactForm::get_template(['locale' => $lang === 'ar' ? 'ar' : 'en_US', 'title' => $title]);
	if (!$cf) return 0;
	$p = $cf->get_properties();
	$p['form'] = bv_cf7_markup($lang, $compact); $p['mail'] = array_merge((array) $p['mail'], bv_cf7_mail($lang)); $p['messages'] = array_merge((array) $p['messages'], bv_cf7_messages($lang));
	$cf->set_title($title); $cf->set_properties($p);
	$id = $cf->save();
	update_post_meta($id, '_bv_demo_key', $key);
	$log[] = ($id ? '+' : '!') . " form “$title” (#$id)";
	return (int) $id;
}

/* ------------------------------------------------------------------ main */
function bv_demo_run() {
	$log = [];
	foreach (['update_field' => 'Secure Custom Fields', 'pll_set_post_language' => 'Polylang', 'wpcf7' => 'Contact Form 7'] as $fn => $name) {
		if (($fn === 'wpcf7' ? !class_exists('WPCF7_ContactForm') : !function_exists($fn))) { $log[] = "! $name is not active — aborting."; return $log; }
	}
	$data = json_decode(file_get_contents(get_template_directory() . '/demo/demo-data.json'), true);
	if (!$data) { $log[] = '! demo-data.json missing or invalid'; return $log; }

	/* 1 — languages */
	bv_demo_languages($log);

	/* 2 — media */
	$media = [];
	$assets = [
		'logo' => ['img/logo.png', 'Brandiva Media logo'], 'logo-webp' => ['img/logo.webp', 'Brandiva Media logo (WebP)'], 'favicon' => ['img/icon-512.png', 'Brandiva favicon'], 'apple' => ['img/apple-touch-icon.png', 'Apple touch icon'],
		'about-team' => ['img/about-team-1600.webp', 'Brandiva team'],
		'svc-web-design' => ['img/svc-web-design-1600.webp', 'Web design'], 'svc-seo' => ['img/svc-seo-1600.webp', 'SEO'], 'svc-hosting' => ['img/svc-hosting-1600.webp', 'Hosting'],
		'type-corporate' => ['img/type-corporate-1600.webp', 'Corporate'], 'type-ecommerce' => ['img/type-ecommerce-1600.webp', 'E-commerce'], 'type-clinic' => ['img/type-clinic-1600.webp', 'Clinic'],
		'type-law' => ['img/type-law-1600.webp', 'Law'], 'type-restaurant' => ['img/type-restaurant-1600.webp', 'Restaurant'],
	];
	foreach ($assets as $k => $a) { $media[$k] = bv_demo_media($k, $a[0], $a[1]); $log[] = ($media[$k] ? '+' : '!') . " media $k"; }

	/* 3 — forms */
	$forms = [];
	foreach (['ar', 'en'] as $lang) {
		$forms[$lang]['quote']   = bv_demo_cf7_form("form:$lang:quote", $lang, $lang === 'ar' ? 'نموذج سريع (الرئيسية)' : 'Quick form (home)', true, $log);
		$forms[$lang]['contact'] = bv_demo_cf7_form("form:$lang:contact", $lang, $lang === 'ar' ? 'نموذج صفحة التواصل' : 'Contact page form', false, $log);
	}

	/* 4 — page shells */
	$pages = ['ar' => [], 'en' => []]; $order = 0;
	foreach ($data['templates'] as $key => $tpl) {
		$order += 10;
		foreach (['ar', 'en'] as $lang) {
			$d = $data['langs'][$lang][$key];
			$id = bv_demo_find("$lang:$key");
			$arr = ['post_type' => 'page', 'post_status' => 'publish', 'post_title' => $d['title'], 'post_name' => $d['slug'], 'menu_order' => $order, 'post_content' => ''];
			if ($id) { $arr['ID'] = $id; wp_update_post($arr); } else { $id = wp_insert_post($arr); }
			update_post_meta($id, '_bv_demo_key', "$lang:$key");
			update_post_meta($id, '_wp_page_template', $tpl);
			pll_set_post_language($id, $lang);
			if (get_post_field('post_name', $id) !== $d['slug']) wp_update_post(['ID' => $id, 'post_name' => $d['slug']]);
			$pages[$lang][$key] = $id;
		}
		pll_save_post_translations(['ar' => $pages['ar'][$key], 'en' => $pages['en'][$key]]);
		$log[] = "+ page “$key” (ar #{$pages['ar'][$key]}, en #{$pages['en'][$key]})";
	}

	/* 5 — fields */
	$group = ['templates/page-home.php' => 'home', 'templates/page-service.php' => 'svc', 'templates/page-contact.php' => 'contact'];
	foreach (['ar', 'en'] as $lang) {
		foreach ($data['templates'] as $key => $tpl) {
			$id = $pages[$lang][$key]; $d = $data['langs'][$lang][$key]; $g = $group[$tpl];
			foreach ($d['fields'] as $name => $val) update_field(bv_key($g, $name), bv_demo_resolve($val, $lang, $media, $pages), $id);
			foreach ($d['seo'] as $name => $val) update_field(bv_key('seo', $name), $val, $id);
			update_field(bv_key('seo', 'seo_image'), $media['about-team'], $id);
		}
		$log[] = "+ fields ($lang)";
	}

	/* 6 — Theme Options */
	$opt = [
		'logo' => $media['logo'], 'logo_webp' => $media['logo-webp'], 'logo_w' => 87, 'footer_logo_w' => 150, 'favicon' => $media['favicon'], 'apple_icon' => $media['apple'],
		'site_name' => 'Brandiva Media', 'theme_color' => '#07060B',
		'c_whatsapp' => '201000000000', 'c_email' => 'info@brandivamedia.com', 'c_phone' => '+20 10 0000 0000', 'c_address' => '', 'c_timezone' => 'Africa/Cairo', 'c_show_clock' => 1,
		'social_links' => array_map(function ($n) { return ['network' => $n, 'url' => '', 'enabled' => 0]; }, array_keys(bv_socials_all())),
		'font_ar' => 'plex', 'font_lat' => 'jakarta', 'font_mono' => 'space', 'font_google' => 1,
		'st_radius_card' => 28, 'st_radius_input' => 16, 'st_container' => 1320, 'st_header_h' => 84, 'st_section_space' => 'normal', 'st_announce' => 1,
		'fx_animations' => 1, 'fx_preloader' => 1, 'fx_cursor' => 1, 'fx_smooth' => 1, 'fx_particles' => 1, 'fx_hero_play' => 1, 'fx_magnetic' => 1, 'fx_tilt' => 1,
		'form_mode' => 'both', 'seo_builtin' => 1, 'seo_og_default' => $media['about-team'], 'seo_price_range' => 'Request a quote', 'seo_country' => 'Egypt',
	];
	foreach (bv_color_defaults() as $k => $c) $opt[$k] = $c[1];
	foreach ($opt as $name => $val) update_field(bv_key('opt', $name), $val, 'bv_global');
	foreach (['ar', 'en'] as $lang) {
		$pid = 'bv_texts_' . $lang;
		update_field(bv_key('texts_' . $lang, 'f_quote_form'), $forms[$lang]['quote'], $pid);
		update_field(bv_key('texts_' . $lang, 'f_contact_form'), $forms[$lang]['contact'], $pid);
		update_field(bv_key('texts_' . $lang, 'h_cta_link'), ['title' => $lang === 'ar' ? 'اطلب استشارة' : 'Get a quote', 'url' => get_permalink($pages[$lang]['contact']), 'target' => ''], $pid);
	}
	$log[] = '+ Theme Options';

	/* 7 — menus */
	$loc = [];
	foreach (['ar', 'en'] as $lang) {
		$suffix = strtoupper($lang);
		$spec = $data['langs'][$lang]['_menu'];
		$make = function ($name, $items) use ($pages, $lang) {
			$m = wp_get_nav_menu_object($name); $mid = $m ? $m->term_id : wp_create_nav_menu($name);
			foreach ((array) wp_get_nav_menu_items($mid) as $it) wp_delete_post($it->ID, true);
			foreach ($items as $i => [$pk, $label]) {
				[$base, $frag] = array_pad(explode('#', $pk, 2), 2, '');
				$a = ['menu-item-title' => $label, 'menu-item-status' => 'publish', 'menu-item-position' => $i + 1];
				if ($frag) { $a += ['menu-item-type' => 'custom', 'menu-item-url' => get_permalink($pages[$lang][$base]) . '#' . $frag]; }
				else { $a += ['menu-item-type' => 'post_type', 'menu-item-object' => 'page', 'menu-item-object-id' => $pages[$lang][$base]]; }
				wp_update_nav_menu_item($mid, 0, $a);
			}
			return $mid;
		};
		$loc[$lang] = [
			'primary'         => $make("Primary ($suffix)", $spec['primary']),
			'footer_pages'    => $make("Footer pages ($suffix)", $spec['primary']),
			'footer_services' => $make("Footer services ($suffix)", $spec['footer_services']),
		];
	}
	$theme = get_option('stylesheet');
	$pl = get_option('polylang', []);
	foreach (['primary', 'footer_pages', 'footer_services'] as $l) foreach (['ar', 'en'] as $lang) $pl['nav_menus'][$theme][$l][$lang] = $loc[$lang][$l];
	update_option('polylang', $pl);
	set_theme_mod('nav_menu_locations', $loc['ar']);
	$log[] = '+ menus (assigned per language)';

	/* 8 — reading + permalinks */
	update_option('show_on_front', 'page');
	update_option('page_on_front', $pages['ar']['home']);
	update_option('blogname', 'Brandiva Media');
	if (!get_option('permalink_structure')) update_option('permalink_structure', '/%postname%/');
	flush_rewrite_rules();
	update_option('bv_demo_imported', time());
	$log[] = '✔ Done. Open the site: ' . home_url('/') . ' (English: ' . home_url('/en/') . ')';
	return $log;
}
