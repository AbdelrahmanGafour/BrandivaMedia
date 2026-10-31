<?php
/** Built-in SEO: title, description, Open Graph, JSON-LD. Disabled via Theme Options → SEO & tracking. */
defined('ABSPATH') || exit;

function bv_seo_on() { return bv_optb('seo_builtin', true); }

add_filter('pre_get_document_title', function ($t) {
	if (!bv_seo_on() || !is_singular('page')) return $t;
	$v = bv_f('seo_title', get_the_ID());
	return $v ?: $t;
});

add_action('wp_head', function () {
	if (!bv_seo_on()) return;
	$name = bv_opt('site_name', 'Brandiva Media');
	if (is_404()) {
		echo '<meta name="robots" content="noindex">' . "\n";
		return;
	}
	if (!is_singular('page')) return;
	$id = get_the_ID(); $url = get_permalink($id);
	$title = bv_f('seo_title', $id) ?: get_the_title($id);
	$desc  = bv_f('seo_desc', $id);
	$img   = bv_img_url(bv_f('seo_image', $id) ?: bv_opt('seo_og_default'), 'large');
	if (bv_f('seo_noindex', $id)) echo '<meta name="robots" content="noindex,follow">' . "\n";
	if ($desc) echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
	echo '<meta property="og:type" content="website"><meta property="og:locale" content="' . esc_attr(get_locale()) . '"><meta property="og:site_name" content="' . esc_attr($name) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr($title) . '"><meta property="og:url" content="' . esc_url($url) . '">';
	if ($desc) echo '<meta property="og:description" content="' . esc_attr($desc) . '">';
	if ($img) echo '<meta property="og:image" content="' . esc_url($img) . '">';
	echo '<meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="' . esc_attr($title) . '">';
	if ($desc) echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">';
	if ($img) echo '<meta name="twitter:image" content="' . esc_url($img) . '">';
	echo "\n";

	/* ---- JSON-LD */
	$home = bv_home_url(); $same = [];
	foreach ((array) bv_opt('social_links', []) as $s) if (!empty($s['enabled']) && !empty($s['url'])) $same[] = $s['url'];
	$logo = bv_img_url(bv_opt('logo'));
	$org  = ['@type' => 'Organization', '@id' => $home . '#org', 'name' => $name, 'url' => $home, 'sameAs' => $same];
	if ($logo) $org['logo'] = $logo;
	$biz = ['@type' => ['ProfessionalService', 'LocalBusiness'], '@id' => $home . '#biz', 'name' => $name, 'url' => $home, 'areaServed' => ['@type' => 'Country', 'name' => bv_opt('seo_country', 'Egypt')], 'priceRange' => bv_opt('seo_price_range', 'Request a quote')];
	if ($img) $biz['image'] = $img;
	if (bv_opt('c_phone')) $biz['telephone'] = bv_opt('c_phone');
	$addr = ['@type' => 'PostalAddress']; if (bv_opt('c_address')) $addr['streetAddress'] = bv_opt('c_address'); $biz['address'] = $addr;
	$graph = [$org, $biz];

	$tpl = get_page_template_slug($id);
	$crumbs = [['name' => bv_t('crumb_home'), 'url' => $home]];
	if (!is_front_page()) $crumbs[] = ['name' => bv_f('sp_name', $id) ?: get_the_title($id), 'url' => $url];
	$graph[] = ['@type' => 'BreadcrumbList', 'itemListElement' => array_map(function ($c, $i) { return ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['url']]; }, $crumbs, array_keys($crumbs))];

	$faq = [];
	if ($tpl === 'templates/page-home.php') {
		$graph[] = ['@type' => 'WebSite', '@id' => $home . '#site', 'url' => $home, 'name' => $name, 'inLanguage' => bv_lang(), 'publisher' => ['@id' => $home . '#org']];
		$faq = (array) bv_f('fq_items', $id, []);
	} elseif ($tpl === 'templates/page-service.php') {
		$sn = bv_f('sp_name', $id) ?: get_the_title($id);
		$graph[] = ['@type' => 'Service', 'name' => $sn, 'serviceType' => $sn, 'description' => $desc, 'provider' => ['@id' => $home . '#org'], 'areaServed' => ['@type' => 'Country', 'name' => bv_opt('seo_country', 'Egypt')], 'url' => $url];
		foreach ((array) bv_f('sp_sections', $id, []) as $r) if (($r['acf_fc_layout'] ?? '') === 'faq') $faq = array_merge($faq, (array) ($r['items'] ?? []));
	} elseif ($tpl === 'templates/page-contact.php') {
		$graph[] = ['@type' => 'ContactPage', 'name' => $title, 'url' => $url];
	}
	if ($faq) $graph[] = ['@type' => 'FAQPage', 'mainEntity' => array_values(array_map(function ($q) { return ['@type' => 'Question', 'name' => $q['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $q['a']]]; }, array_filter($faq, function ($q) { return !empty($q['q']); })))];
	echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@graph' => $graph], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}, 5);
