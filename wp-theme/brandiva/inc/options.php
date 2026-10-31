<?php
/** Theme Options (Secure Custom Fields options pages) */
defined('ABSPATH') || exit;

function bv_font_catalog() {
	return [
		'ar'   => [
			'plex'    => ['IBM Plex Sans Arabic (bundled)', '"IBM Plex Sans Arabic","Cairo",system-ui,sans-serif', ''],
			'cairo'   => ['Cairo (Google Fonts)', '"Cairo","IBM Plex Sans Arabic",system-ui,sans-serif', 'Cairo:wght@400;500;700;800'],
			'tajawal' => ['Tajawal (Google Fonts)', '"Tajawal","IBM Plex Sans Arabic",system-ui,sans-serif', 'Tajawal:wght@400;500;700;800'],
			'almarai' => ['Almarai (Google Fonts)', '"Almarai","IBM Plex Sans Arabic",system-ui,sans-serif', 'Almarai:wght@400;700;800'],
			'kufi'    => ['Noto Kufi Arabic (Google Fonts)', '"Noto Kufi Arabic","IBM Plex Sans Arabic",system-ui,sans-serif', 'Noto+Kufi+Arabic:wght@400;500;700;800'],
			'system'  => ['System UI', 'system-ui,-apple-system,"Segoe UI",Tahoma,sans-serif', ''],
		],
		'lat'  => [
			'jakarta'    => ['Plus Jakarta Sans (bundled)', '"Plus Jakarta Sans","IBM Plex Sans Arabic",system-ui,sans-serif', ''],
			'poppins'    => ['Poppins (Google Fonts)', '"Poppins","IBM Plex Sans Arabic",system-ui,sans-serif', 'Poppins:wght@600;700;800'],
			'inter'      => ['Inter (Google Fonts)', '"Inter","IBM Plex Sans Arabic",system-ui,sans-serif', 'Inter:wght@500;700;800'],
			'montserrat' => ['Montserrat (Google Fonts)', '"Montserrat","IBM Plex Sans Arabic",system-ui,sans-serif', 'Montserrat:wght@600;700;800'],
			'manrope'    => ['Manrope (Google Fonts)', '"Manrope","IBM Plex Sans Arabic",system-ui,sans-serif', 'Manrope:wght@600;700;800'],
			'system'     => ['System UI', 'system-ui,-apple-system,"Segoe UI",sans-serif', ''],
		],
		'mono' => [
			'space'     => ['Space Mono (bundled)', '"Space Mono","IBM Plex Sans Arabic",ui-monospace,monospace', ''],
			'jetbrains' => ['JetBrains Mono (Google Fonts)', '"JetBrains Mono","IBM Plex Sans Arabic",ui-monospace,monospace', 'JetBrains+Mono:wght@400;700'],
			'ibmmono'   => ['IBM Plex Mono (Google Fonts)', '"IBM Plex Mono","IBM Plex Sans Arabic",ui-monospace,monospace', 'IBM+Plex+Mono:wght@400;700'],
			'system'    => ['System monospace', 'ui-monospace,SFMono-Regular,Menlo,monospace', ''],
		],
	];
}
function bv_font_choices($k) { $o = []; foreach (bv_font_catalog()[$k] as $id => $f) $o[$id] = $f[0]; return $o; }

function bv_color_defaults() {
	return [
		'col_accent'      => ['Accent (yellow) — buttons, highlights', '#FDBA0F', '--yellow-500'],
		'col_accent_light'=> ['Accent — light (hover)', '#FFD24D', '--yellow-400'],
		'col_accent_dark' => ['Accent — dark', '#E5A000', '--yellow-600'],
		'col_second'      => ['Secondary (purple) — light', '#B79EE8', '--purple-400'],
		'col_second_dark' => ['Secondary (purple) — dark', '#7B5CD6', '--purple-600'],
		'col_bg'          => ['Background — darkest', '#07060B', '--ink-950'],
		'col_bg2'         => ['Background — section', '#0F0D16', '--ink-900'],
		'col_bg3'         => ['Background — card', '#171421', '--ink-800'],
		'col_line'        => ['Borders / lines', '#231F33', '--ink-700'],
		'col_text'        => ['Text — primary', '#FFFFFF', '--text-100'],
		'col_text2'       => ['Text — secondary', '#BDBDBD', '--text-300'],
		'col_text3'       => ['Text — muted', '#8A8697', '--text-500'],
		'col_light'       => ['Light background', '#FAF7F0', '--light-bg'],
	];
}

add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) return;

	acf_add_options_page(['page_title' => 'Theme Options', 'menu_title' => 'Theme Options', 'menu_slug' => 'bv-options', 'post_id' => 'bv_global', 'capability' => 'manage_options', 'icon_url' => 'dashicons-art', 'position' => 59, 'redirect' => false, 'updated_message' => 'Theme options saved']);
	acf_add_options_sub_page(['page_title' => 'Texts — العربية', 'menu_title' => 'Texts — العربية', 'parent_slug' => 'bv-options', 'menu_slug' => 'bv-texts-ar', 'post_id' => 'bv_texts_ar', 'capability' => 'manage_options']);
	acf_add_options_sub_page(['page_title' => 'Texts — English', 'menu_title' => 'Texts — English', 'parent_slug' => 'bv-options', 'menu_slug' => 'bv-texts-en', 'post_id' => 'bv_texts_en', 'capability' => 'manage_options']);

	/* ------------------------------------------------------------ Global options */
	$tz = ['Africa/Cairo' => 'Cairo (Africa/Cairo)', 'Asia/Riyadh' => 'Riyadh', 'Asia/Dubai' => 'Dubai', 'Asia/Kuwait' => 'Kuwait', 'Asia/Amman' => 'Amman', 'Europe/London' => 'London', 'UTC' => 'UTC'];
	$net = bv_socials_all();
	$f = [
		bv_tab('Branding'),
		bv_img_f('logo', 'Logo (PNG/SVG)', ['instructions' => 'Used in header, footer and preloader.']),
		bv_img_f('logo_webp', 'Logo — WebP version (optional)', ['instructions' => 'Served first to modern browsers.']),
		bv_num('logo_w', 'Header logo width (px)', 87, ['min' => 40, 'max' => 400]),
		bv_num('footer_logo_w', 'Footer logo width (px)', 150, ['min' => 60, 'max' => 500]),
		bv_img_f('preloader_logo', 'Preloader logo (optional)', ['instructions' => 'Falls back to the main logo.']),
		bv_img_f('favicon', 'Favicon (PNG, 512×512 recommended)'),
		bv_img_f('apple_icon', 'Apple touch icon (180×180, optional)'),
		bv_text('site_name', 'Brand name', ['default_value' => 'Brandiva Media']),
		bv_color('theme_color', 'Browser theme color', '#07060B'),

		bv_tab('Contacts'),
		bv_text('c_whatsapp', 'WhatsApp number', ['instructions' => 'International format, digits only (e.g. 201000000000).', 'placeholder' => '201000000000']),
		bv_fld('email', 'c_email', 'Email', ['placeholder' => 'info@brandivamedia.com']),
		bv_text('c_phone', 'Phone (display)', ['placeholder' => '+20 10 0000 0000']),
		bv_area('c_address', 'Address (for schema)', ['rows' => 2]),
		bv_sel('c_timezone', 'Clock time zone', $tz, 'Africa/Cairo'),
		bv_bool('c_show_clock', 'Show live clock (footer / contact page)', 1),

		bv_tab('Social links'),
		bv_rep('social_links', 'Social accounts', [
			bv_sel('network', 'Network', $net, 'fb'),
			bv_fld('url', 'url', 'Profile URL', []),
			bv_bool('enabled', 'Show', 1),
		], ['layout' => 'table', 'button_label' => 'Add account']),

		bv_tab('Colors'),
	];
	foreach (bv_color_defaults() as $k => $c) $f[] = bv_color($k, $c[0], $c[1]);

	$f[] = bv_tab('Fonts');
	$f[] = bv_sel('font_ar', 'Arabic / body font', bv_font_choices('ar'), 'plex');
	$f[] = bv_sel('font_lat', 'Latin headings font (English pages)', bv_font_choices('lat'), 'jakarta');
	$f[] = bv_sel('font_mono', 'Label / mono font', bv_font_choices('mono'), 'space');
	$f[] = bv_bool('font_google', 'Allow loading Google Fonts', 1, ['instructions' => 'Only used when a “Google Fonts” family is selected above.']);

	$f[] = bv_tab('Style & layout');
	$f[] = bv_range('st_radius_card', 'Card corner radius', 28, 0, 48);
	$f[] = bv_range('st_radius_input', 'Input / button corner radius', 16, 0, 32);
	$f[] = bv_range('st_container', 'Container max width', 1320, 1000, 1700, 10);
	$f[] = bv_range('st_header_h', 'Header height', 84, 60, 120);
	$f[] = bv_sel('st_section_space', 'Section spacing', ['compact' => 'Compact', 'normal' => 'Normal', 'spacious' => 'Spacious'], 'normal');
	$f[] = bv_bool('st_announce', 'Show announcement bar above the header', 1);

	$f[] = bv_tab('Effects');
	$f[] = bv_bool('fx_animations', 'Animations (master switch)', 1, ['instructions' => 'Off = behaves like “reduced motion” for everyone.']);
	$f[] = bv_bool('fx_preloader', 'Preloader (logo intro, first visit)', 1);
	$f[] = bv_bool('fx_cursor', 'Custom cursor (desktop)', 1);
	$f[] = bv_bool('fx_smooth', 'Smooth scrolling (Lenis)', 1);
	$f[] = bv_bool('fx_particles', 'Hero particles', 1);
	$f[] = bv_bool('fx_hero_play', 'Hero “play” shape', 1);
	$f[] = bv_bool('fx_magnetic', 'Magnetic buttons', 1);
	$f[] = bv_bool('fx_tilt', 'Card tilt on hover', 1);

	$f[] = bv_tab('Forms');
	$f[] = bv_sel('form_mode', 'After a form is submitted', ['both' => 'Send email (Contact Form 7) and open WhatsApp', 'email' => 'Send email only (Contact Form 7)', 'whatsapp' => 'Open WhatsApp only (no email)'], 'both');

	$f[] = bv_tab('SEO & tracking');
	$f[] = bv_bool('seo_builtin', 'Output built-in SEO meta, Open Graph and JSON-LD', 1, ['instructions' => 'Turn off if you use Yoast / Rank Math.']);
	$f[] = bv_img_f('seo_og_default', 'Default social share image');
	$f[] = bv_text('seo_price_range', 'Schema price range', ['default_value' => 'Request a quote']);
	$f[] = bv_text('seo_country', 'Service country (schema)', ['default_value' => 'Egypt']);
	$f[] = bv_text('seo_ga', 'Google Analytics 4 ID', ['placeholder' => 'G-XXXXXXXXXX']);

	$f[] = bv_tab('Custom code');
	$f[] = bv_area('code_css', 'Custom CSS', ['rows' => 8]);
	$f[] = bv_area('code_head', 'Code before </head>', ['rows' => 5]);
	$f[] = bv_area('code_body', 'Code before </body>', ['rows' => 5]);

	bv_group('opt', 'Theme Options', $f, [[['param' => 'options_page', 'operator' => '==', 'value' => 'bv-options']]], ['hide_on_screen' => []]);

	/* ------------------------------------------------------------ Texts (per language) */
	foreach (['ar' => 'bv-texts-ar', 'en' => 'bv-texts-en'] as $lang => $slug) {
		$t = [bv_tab('Forms'),
			bv_fld('post_object', 'f_quote_form', 'Quick form (home CTA section)', ['post_type' => ['wpcf7_contact_form'], 'return_format' => 'id', 'ui' => 1, 'allow_null' => 1]),
			bv_fld('post_object', 'f_contact_form', 'Contact page form', ['post_type' => ['wpcf7_contact_form'], 'return_format' => 'id', 'ui' => 1, 'allow_null' => 1]),
			bv_link('h_cta_link', 'Header button link', ['instructions' => 'Leave empty to link to the Contact page.']),
			bv_tab('Interface texts'),
		];
		foreach (bv_strings() as $key => $s) {
			$type = $s[3] ?? 'text';
			$t[] = $type === 'textarea' ? bv_area('t_' . str_replace('-', '_', $key), $s[0], ['rows' => 3]) : bv_text('t_' . str_replace('-', '_', $key), $s[0], ['placeholder' => $s[$lang === 'ar' ? 1 : 2]]);
		}
		bv_group('texts_' . $lang, 'Texts — ' . strtoupper($lang), $t, [[['param' => 'options_page', 'operator' => '==', 'value' => $slug]]], ['hide_on_screen' => []]);
	}
});
