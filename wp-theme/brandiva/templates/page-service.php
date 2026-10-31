<?php
/* Template Name: Service page (Web design / SEO / Hosting) */
get_header();
$pid  = get_the_ID();
$F    = function ($n, $d = '') use ($pid) { return bv_f($n, $pid, $d); };
$rows = function_exists('get_field') ? get_field('sp_sections', $pid) : [];
$rows = is_array($rows) ? $rows : [];
$name = $F('sp_name', get_the_title());
$cta  = $F('sp_cta', []);
?>
<main id="main">
<section class="phero" aria-labelledby="h1">
<div class="container phero-grid">
<div>
<ol class="crumbs mono" aria-label="<?php echo esc_attr(bv_t('crumbs_aria')); ?>"><li><a href="<?php echo esc_url(bv_home_url()); ?>"><?php bv_te('crumb_home'); ?></a></li><li>/</li><li><span aria-current="page"><?php echo esc_html($name); ?></span></li></ol>
<p class="eyebrow mono" style="margin-block-start:1rem"><?php echo esc_html($F('sp_eyebrow')); ?></p>
<h1 class="h1 rise" id="h1"><?php echo esc_html($F('sp_h1', get_the_title())); ?></h1>
<div class="intro lead" data-reveal><?php echo bv_rich($F('sp_intro')); // phpcs:ignore ?></div>
<div class="hero-cta" style="margin-block-start:1.75rem"><a class="btn btn-yellow btn-lg" href="#cta"<?php echo bv_fxa('magnetic'); // phpcs:ignore ?>><?php bv_te('svc_cta_primary'); ?> <?php echo bv_arrow(); // phpcs:ignore ?></a><a class="btn btn-ghost btn-lg" href="#toc-start"><?php bv_te('svc_cta_browse'); ?></a></div>
</div>
<?php if ($F('sp_image', 0)) : ?><figure class="phero-fig" data-clip><?php echo bv_img($F('sp_image'), $F('sp_image_alt'), '', '(min-width:1000px) 38vw, 100vw', false, 'large'); // phpcs:ignore ?><figcaption class="sr-only"><?php echo esc_html($F('sp_image_alt')); ?></figcaption></figure><?php endif; ?>
</div></section>

<div class="container page-grid" id="toc-start">
<aside class="toc" aria-label="<?php echo esc_attr(bv_t('toc_title')); ?>"><span class="toc-title mono"><?php bv_te('toc_title'); ?></span><nav aria-label="<?php echo esc_attr(bv_t('toc_aria')); ?>"><ol>
<?php foreach ($rows as $r) { $id = sanitize_title($r['anchor'] ?? ''); if (!$id) continue; echo '<li><a href="#' . esc_attr($id) . '">' . esc_html(!empty($r['toc']) ? $r['toc'] : ($r['title'] ?? '')) . '</a></li>'; } ?>
</ol></nav></aside>
<div class="page-body">
<?php foreach ($rows as $r) echo bv_render_section($r); // phpcs:ignore ?>
<?php if ($cta && !empty($cta['title'])) : ?>
<section class="pcta" id="cta" aria-labelledby="cta-h" data-reveal>
<h2 class="h2" id="cta-h"><?php echo esc_html($cta['title']); ?></h2>
<?php echo bv_rich($cta['text'] ?? ''); // phpcs:ignore ?>
<?php echo bv_rich($cta['list'] ?? ''); // phpcs:ignore ?>
<a class="btn btn-yellow btn-lg" href="<?php echo esc_url(bv_contact_url($cta['service'] ?? '')); ?>"<?php echo bv_fxa('magnetic'); // phpcs:ignore ?>><?php echo esc_html($cta['btn'] ?: bv_t('svc_cta_primary')); ?> <?php echo bv_arrow(); // phpcs:ignore ?></a>
</section>
<?php endif; ?>
</div></div>
</main>
<?php get_footer(); ?>
