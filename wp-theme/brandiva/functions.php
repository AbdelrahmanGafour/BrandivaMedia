<?php
/**
 * Brandiva Media theme
 * Content: Secure Custom Fields · Forms: Contact Form 7 · Languages: Polylang
 */
defined('ABSPATH') || exit;

$bv_inc = get_template_directory() . '/inc/';
foreach (['strings', 'helpers', 'fields-api', 'options', 'fields-pages', 'setup', 'walker', 'render', 'seo', 'plugins', 'cf7'] as $f) require_once $bv_inc . $f . '.php';
if (is_admin()) require_once $bv_inc . 'demo-import.php';
