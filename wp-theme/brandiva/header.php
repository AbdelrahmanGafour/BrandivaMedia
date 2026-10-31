<?php defined('ABSPATH') || exit;
$cta = bv_f('h_cta_link', 'bv_texts_' . bv_lang(), []);
$cta_url = bv_url($cta) ?: bv_contact_url();
$sw = bv_other_lang();
$logo_pre = bv_opt('preloader_logo') ?: bv_opt('logo');
$logo_pre_url = $logo_pre ? wp_get_attachment_url($logo_pre) : bv_asset('img/logo.webp');
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php if (is_front_page() && bv_optb('fx_preloader')) : ?><div class="preloader" aria-hidden="true" data-logo="<?php echo esc_url($logo_pre_url); ?>"></div><?php endif; ?>
<a class="skip" href="#main"><?php bv_te('skip'); ?></a>
<?php if (bv_optb('fx_cursor')) : ?><div class="cursor" aria-hidden="true"></div><?php endif; ?>
<div class="progress" aria-hidden="true"></div>
<?php if (bv_optb('st_announce')) : ?>
<div class="announce"><a class="pill" href="<?php echo esc_url(bv_contact_url()); ?>"><span class="dot"></span><span class="mono"><?php bv_te('announce_label'); ?></span><span><?php bv_te('announce_text'); ?></span></a></div>
<?php endif; ?>
<header class="site-header">
  <a class="brand" href="<?php echo esc_url(bv_home_url()); ?>"><?php echo bv_logo('header'); // phpcs:ignore ?></a>
  <nav class="nav-desktop" aria-label="<?php echo esc_attr(bv_t('nav_aria')); ?>"><?php bv_menu('primary', 'desktop'); ?></nav>
  <?php if ($sw) : ?><a class="lang-sw mono" href="<?php echo esc_url($sw['url']); ?>" hreflang="<?php echo esc_attr($sw['slug']); ?>" lang="<?php echo esc_attr($sw['slug']); ?>" aria-label="<?php echo esc_attr(bv_t('switch_aria')); ?>"><?php bv_te('switch_label'); ?></a><?php endif; ?>
  <a class="btn btn-yellow btn-sm" href="<?php echo esc_url($cta_url); ?>"<?php echo bv_fxa('magnetic'); // phpcs:ignore ?>><?php bv_te('header_cta'); ?> <?php echo bv_arrow(); // phpcs:ignore ?></a>
  <button class="menu-btn" type="button" aria-expanded="false" aria-controls="menu" data-open="<?php echo esc_attr(bv_t('menu_open')); ?>" data-close="<?php echo esc_attr(bv_t('menu_close')); ?>"><span><?php bv_te('menu_open'); ?></span><i aria-hidden="true"></i></button>
</header>
<div class="menu" id="menu" role="dialog" aria-label="<?php echo esc_attr(bv_t('menu_aria')); ?>">
  <nav class="menu-links" aria-label="<?php echo esc_attr(bv_t('menu_open')); ?>"><?php bv_menu('primary', 'mobile'); ?></nav>
  <div class="menu-foot"><a class="link-u" href="<?php echo esc_url(bv_contact_url()); ?>"><?php bv_te('menu_foot_cta'); ?></a><?php if ($sw) : ?><a class="link-u" href="<?php echo esc_url($sw['url']); ?>" hreflang="<?php echo esc_attr($sw['slug']); ?>" lang="<?php echo esc_attr($sw['slug']); ?>"><?php bv_te('switch_label'); ?></a><?php endif; ?><span class="mono"><?php bv_te('menu_tagline'); ?></span><?php echo bv_social_icons(); // phpcs:ignore ?></div>
</div>
