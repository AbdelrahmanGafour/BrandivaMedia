<?php get_header(); ?>
<main id="main">
<section class="err404"><div class="container"><div class="giant" aria-hidden="true">404</div>
<h1 class="h1" style="margin-block:1rem"><?php bv_te('e404_title'); ?></h1><p class="lead" style="margin-inline:auto;margin-block-end:2rem"><?php echo esc_html(bv_t('e404_text')); ?></p>
<div class="hero-cta" style="justify-content:center"><a class="btn btn-yellow btn-lg" href="<?php echo esc_url(bv_home_url()); ?>"><?php bv_te('e404_home'); ?></a><a class="btn btn-ghost btn-lg" href="<?php echo esc_url(bv_contact_url()); ?>"><?php bv_te('e404_contact'); ?></a></div></div></section>
</main>
<?php get_footer(); ?>
