<?php
/**
 * Required plugins — installed from WordPress.org and activated automatically
 * when the theme is activated (and retried from the admin notice if needed).
 */
defined('ABSPATH') || exit;

function bv_required_plugins() {
	return [
		'secure-custom-fields' => ['name' => 'Secure Custom Fields', 'file' => 'secure-custom-fields/secure-custom-fields.php'],
		'contact-form-7'       => ['name' => 'Contact Form 7',       'file' => 'contact-form-7/wp-contact-form-7.php'],
		'polylang'             => ['name' => 'Polylang',             'file' => 'polylang/polylang.php'],
	];
}
function bv_plugin_installed($file) { return file_exists(WP_PLUGIN_DIR . '/' . $file); }
function bv_plugin_active($file) { include_once ABSPATH . 'wp-admin/includes/plugin.php'; return is_plugin_active($file); }
function bv_missing_plugins() {
	$m = [];
	foreach (bv_required_plugins() as $slug => $p) if (!bv_plugin_active($p['file'])) $m[$slug] = $p;
	return $m;
}

/** Install + activate everything that's missing. Returns [slug => message]. */
function bv_install_plugins() {
	if (!current_user_can('install_plugins') || !current_user_can('activate_plugins')) return ['_' => 'insufficient permissions'];
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/misc.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	$log = [];
	foreach (bv_required_plugins() as $slug => $p) {
		if (!bv_plugin_installed($p['file'])) {
			$api = plugins_api('plugin_information', ['slug' => $slug, 'fields' => ['sections' => false]]);
			if (is_wp_error($api)) { $log[$slug] = $api->get_error_message(); continue; }
			$up = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
			$r  = $up->install($api->download_link);
			if (is_wp_error($r) || !$r) { $log[$slug] = is_wp_error($r) ? $r->get_error_message() : 'install failed'; continue; }
		}
		if (!is_plugin_active($p['file'])) {
			$a = activate_plugin($p['file']);
			$log[$slug] = is_wp_error($a) ? $a->get_error_message() : 'ok';
		} else $log[$slug] = 'ok';
	}
	return $log;
}

/* Automatic: right after the theme is activated */
add_action('after_switch_theme', function () {
	$log = bv_install_plugins();
	update_option('bv_plugins_log', $log, false);
	update_option('bv_plugins_checked', time(), false);
});

/* Admin notice with a one-click retry + link to the demo importer */
add_action('admin_post_bv_install_plugins', function () {
	check_admin_referer('bv_install_plugins');
	update_option('bv_plugins_log', bv_install_plugins(), false);
	wp_safe_redirect(admin_url('themes.php?page=bv-demo')); exit;
});
add_action('admin_notices', function () {
	if (!current_user_can('install_plugins')) return;
	$m = bv_missing_plugins();
	if (!$m) return;
	$url = wp_nonce_url(admin_url('admin-post.php?action=bv_install_plugins'), 'bv_install_plugins');
	echo '<div class="notice notice-warning"><p><strong>Brandiva theme:</strong> required plugins are not active — ' . esc_html(implode(', ', wp_list_pluck($m, 'name'))) . '. <a class="button button-primary" href="' . esc_url($url) . '">Install &amp; activate now</a></p></div>';
});
