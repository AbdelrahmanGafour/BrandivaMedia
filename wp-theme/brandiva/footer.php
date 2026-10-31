<?php defined('ABSPATH') || exit;
$wa = bv_wa_url(); $mail = bv_opt('c_email'); $phone = bv_opt('c_phone');
?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <a class="brand easter" href="<?php echo esc_url(bv_home_url()); ?>"><?php echo bv_logo('footer'); // phpcs:ignore ?></a>
        <p style="margin-block:1rem 1.25rem;max-width:34ch"><?php echo bv_br(bv_t('footer_blurb')); ?></p>
        <?php echo bv_social_icons(); // phpcs:ignore ?>
      </div>
      <nav aria-label="<?php echo esc_attr(bv_t('footer_pages_aria')); ?>"><h2><?php bv_te('footer_pages'); ?></h2><ul><?php bv_menu('footer_pages', 'footer'); ?></ul></nav>
      <nav aria-label="<?php echo esc_attr(bv_t('footer_services')); ?>"><h2><?php bv_te('footer_services'); ?></h2><ul><?php bv_menu('footer_services', 'footer'); ?></ul></nav>
      <div><h2><?php bv_te('footer_contact'); ?></h2><ul>
        <?php if ($wa) : ?><li><a href="<?php echo esc_url($wa); ?>" rel="noopener"><?php echo bv_icon('wa', 'fa fa-inline'); // phpcs:ignore ?> <?php bv_te('ct_whatsapp'); ?></a></li><?php endif; ?>
        <?php if ($mail) : ?><li><a href="mailto:<?php echo esc_attr($mail); ?>"><?php echo esc_html($mail); ?></a></li><?php endif; ?>
        <?php if ($phone) : ?><li><a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $phone)); ?>" dir="ltr"><?php echo esc_html($phone); ?></a></li><?php endif; ?>
        <li><a href="<?php echo esc_url(bv_contact_url()); ?>"><?php bv_te('footer_form'); ?></a></li>
        <?php if (bv_optb('c_show_clock')) : ?><li><span class="mono" style="color:var(--text-500)"><?php bv_te('footer_city'); ?></span> <span class="clock" aria-label="<?php echo esc_attr(bv_t('clock_aria')); ?>">--:--:--</span></li><?php endif; ?></ul></div>
    </div>
    <div class="footer-bar"><span>© <span id="yr"><?php echo esc_html(date_i18n('Y')); ?></span> <?php echo esc_html(bv_opt('site_name', 'Brandiva Media')); ?>. <?php bv_te('copyright'); ?></span><a class="to-top" href="#top" aria-label="<?php echo esc_attr(bv_t('to_top_aria')); ?>">↑ <?php bv_te('to_top'); ?></a></div>
  </div>
  <div class="giant" aria-hidden="true"><?php echo esc_html(strtoupper(explode(' ', trim((string) bv_opt('site_name', 'Brandiva Media')))[0])); ?></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
