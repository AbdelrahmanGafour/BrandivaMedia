<?php
/* Template Name: Contact */
get_header();
$pid  = get_the_ID();
$F    = function ($n, $d = '') use ($pid) { return bv_f($n, $pid, $d); };
$wa   = bv_wa_url(); $mail = bv_opt('c_email'); $phone = bv_opt('c_phone');
$next = $F('cp_next', []);
?>
<main id="main">
<section class="phero" aria-labelledby="h1"><div class="container">
<ol class="crumbs mono" aria-label="<?php echo esc_attr(bv_t('crumbs_aria')); ?>"><li><a href="<?php echo esc_url(bv_home_url()); ?>"><?php bv_te('crumb_home'); ?></a></li><li>/</li><li><span aria-current="page"><?php the_title(); ?></span></li></ol>
<p class="eyebrow mono" style="margin-block-start:1rem"><?php echo esc_html($F('cp_eyebrow')); ?></p>
<h1 class="h1 rise" id="h1"><?php echo esc_html($F('cp_h1', get_the_title())); ?></h1>
<p class="lead" data-reveal><?php echo bv_br($F('cp_lead')); ?></p>
</div></section>
<section class="container contact-grid" style="padding-block-end:var(--sec)" aria-label="<?php echo esc_attr(bv_t('ct_aria')); ?>">
<div class="card rise"><?php echo bv_form(bv_form_id('contact')); // phpcs:ignore ?></div>
<div class="ci rise">
<?php if ($wa && bv_fb('cp_show_wa', $pid, true)) : ?><a class="row" href="<?php echo esc_url($wa); ?>" rel="noopener"><span class="ic" aria-hidden="true"><?php echo bv_icon('wa'); // phpcs:ignore ?></span><span><strong><?php bv_te('ct_whatsapp'); ?></strong><br><span class="cap" dir="ltr"><?php echo esc_html($phone ?: '+' . preg_replace('/\D/', '', (string) bv_opt('c_whatsapp'))); ?></span></span></a><?php endif; ?>
<?php if ($mail && bv_fb('cp_show_email', $pid, true)) : ?><a class="row" href="mailto:<?php echo esc_attr($mail); ?>"><span class="ic" aria-hidden="true">@</span><span><strong><?php bv_te('ct_email'); ?></strong><br><span class="cap" dir="ltr"><?php echo esc_html($mail); ?></span></span></a><?php endif; ?>
<?php if ($next) : ?><div class="card"><h2 style="font-size:var(--fs-h3);margin-block-end:.6rem"><?php echo esc_html($F('cp_next_title')); ?></h2><ul class="ticks p" data-reveal><?php foreach ($next as $r) echo '<li>' . esc_html($r['text']) . '</li>'; ?></ul></div><?php endif; ?>
<?php if (bv_fb('cp_show_social', $pid, true) && ($soc = bv_social_icons())) : ?><div class="card"><span class="mono y"><?php bv_te('ct_follow'); ?></span><div style="margin-block-start:.8rem"><?php echo $soc; // phpcs:ignore ?></div></div><?php endif; ?>
<?php if (bv_fb('cp_show_clock', $pid, true) && bv_optb('c_show_clock')) : ?><div class="card"><span class="mono y"><?php bv_te('ct_clock'); ?></span><p class="clock" style="font-size:2rem;margin-block-start:.4rem">--:--:--</p></div><?php endif; ?>
</div></section>
</main>
<?php get_footer(); ?>
