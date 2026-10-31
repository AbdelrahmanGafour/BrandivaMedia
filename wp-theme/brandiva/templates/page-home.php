<?php
/* Template Name: Home */
get_header();
$pid  = get_the_ID();
$F    = function ($n, $d = '') use ($pid) { return bv_f($n, $pid, $d); };
$secs = function_exists('get_field') ? get_field('sections', $pid) : null;
$secs = is_array($secs) ? $secs : ['services', 'why', 'marquee', 'process', 'industries', 'tools', 'testimonials', 'faq', 'cta'];
$on   = function ($k) use ($secs) { return in_array($k, $secs, true); };
$eyebrow = function ($t) { return $t !== '' ? '<p class="eyebrow mono" data-reveal>' . esc_html($t) . '</p>' : ''; };
$anchor_ok = bv_anchor_choices();
?>
<main id="main">
<?php /* ------------------------------------------------ HERO */ ?>
<section class="hero" aria-labelledby="hero-title">
<?php if (bv_optb('fx_particles')) : ?><canvas class="hero-particles" aria-hidden="true"></canvas><?php endif; ?>
<div class="hero-slash" aria-hidden="true"></div><div class="hero-glow" aria-hidden="true"></div>
<?php if (bv_optb('fx_hero_play')) : $cv = bv_img_url($F('hero_canvas_img', 0), 'large') ?: bv_asset('img/svc-web-design-800.webp'); ?>
<div class="hero-play" aria-hidden="true"><canvas class="p-canvas" width="480" height="460" data-src="<?php echo esc_url($cv); ?>"></canvas>
<svg viewBox="0 0 400 460" aria-hidden="true" focusable="false"><defs><linearGradient id="tg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" style="stop-color:var(--purple-600)"/><stop offset="1" style="stop-color:var(--yellow-500)"/></linearGradient></defs>
<g class="p-fill"><path d="M60 40 L360 230 L60 420Z" fill="url(#tg)" opacity=".4" class="p-tint"/></g>
<path class="p-line two" d="M60 40 L360 230 L60 420Z" transform="translate(-26 -18)"/><path class="p-line" d="M60 40 L360 230 L60 420Z"/></svg></div>
<?php endif; ?>
<div class="container hero-inner">
<p class="eyebrow mono"><?php echo esc_html($F('hero_eyebrow')); ?></p>
<h1 class="display" id="hero-title" data-split="hero"><span class="line"><?php echo esc_html($F('hero_line1')); ?></span><span class="line accent"><?php echo esc_html($F('hero_line2_pre')); ?> <em><?php echo esc_html($F('hero_line2_em')); ?></em></span></h1>
<p class="lead" data-reveal><?php echo bv_br($F('hero_lead')); ?></p>
<div class="hero-cta" data-reveal>
<a class="btn btn-yellow btn-lg" href="<?php echo esc_url(bv_contact_url($F('hero_cta1_service'))); ?>"<?php echo bv_fxa('magnetic'); // phpcs:ignore ?>><?php echo esc_html($F('hero_cta1_label')); ?> <?php echo bv_arrow(); // phpcs:ignore ?></a>
<?php $a2 = $F('hero_cta2_anchor', 'services'); ?>
<a class="btn btn-ghost btn-lg" href="#<?php echo esc_attr(isset($anchor_ok[$a2]) ? $a2 : 'services'); ?>" data-play><?php echo esc_html($F('hero_cta2_label')); ?></a></div>
<?php $trust = $F('hero_trust', []); if ($trust) : ?><ul class="trust mono" data-reveal aria-label="<?php echo esc_attr(bv_t('trust_aria')); ?>"><?php foreach ($trust as $r) echo '<li>' . esc_html($r['text']) . '</li>'; ?></ul><?php endif; ?>
</div>
<a class="scroll-cue mono" href="#services" aria-label="<?php echo esc_attr(bv_t('scroll_aria')); ?>"><i></i><span><?php bv_te('scroll_cue'); ?></span></a>
</section>

<?php /* ------------------------------------------------ SERVICES */
if ($on('services')) : $items = $F('svc_items', []); ?>
<section class="section" id="services" aria-labelledby="services-h"><div class="container">
<div class="sec-head split"><div><?php echo $eyebrow($F('svc_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="services-h" data-split><?php echo bv_br($F('svc_title')); ?></h2></div><p class="lead" data-reveal><?php echo bv_br($F('svc_lead')); ?></p></div>
<ol class="work-list">
<?php foreach ($items as $i => $r) : $url = bv_url($r['page'] ?? ''); ?>
<li><a class="work-row" href="<?php echo esc_url($url ?: '#'); ?>" data-cursor="view"><span class="num mono"><?php echo bv_num2($i + 1); ?></span><h3 data-split><?php echo esc_html($r['title']); ?></h3>
<ul class="tags"><?php foreach ((array) ($r['tags'] ?? []) as $t) echo '<li class="tag mono">' . esc_html($t['tag']) . '</li>'; ?></ul>
<span class="go" aria-hidden="true"><svg><use href="<?php echo esc_url(bv_asset('img/sprite.svg#arrow')); ?>"/></svg></span>
<?php echo bv_img($r['image'] ?? 0, $r['alt'] ?? '', 'row-thumb', '100vw', true, 'bv-card'); // phpcs:ignore ?></a></li>
<?php endforeach; ?>
</ol>
<div class="work-preview" aria-hidden="true"><?php foreach ($items as $r) echo bv_img($r['image'] ?? 0, '', '', '40vw', true, 'bv-card'); // phpcs:ignore ?></div>
</div></section>
<?php endif; ?>

<?php /* ------------------------------------------------ WHY (bento) */
if ($on('why')) :
	$ap = $F('why_approach', []); $me = $F('why_metrics', []); $sp = $F('why_speed', []); $sr = $F('why_serp', []); $rp = $F('why_reports', []); $su = $F('why_support', []); $ti = $F('why_time', []);
	$card = function ($c, $cls, $extra = '') { if (!$c) return ''; return '<article class="card ' . $cls . '"><span class="mono y">' . esc_html($c['label'] ?? '') . '</span><h3>' . esc_html($c['title'] ?? '') . '</h3><p>' . bv_br($c['text'] ?? '') . '</p>' . $extra . '</article>'; };
?>
<section class="section slant" id="why" aria-labelledby="why-h"><div class="container">
<div class="sec-head"><?php echo $eyebrow($F('why_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="why-h" data-split><?php echo esc_html($F('why_title')); ?></h2></div>
<div class="bento">
<?php if ($ap) : ?><article class="card b-approach"<?php echo bv_fxa('tilt'); // phpcs:ignore ?>><span class="mono y"><?php echo esc_html($ap['label'] ?? ''); ?></span><h3><?php echo esc_html($ap['title'] ?? ''); ?></h3><p><?php echo bv_br($ap['text'] ?? ''); ?></p>
<ol class="approach"><?php foreach ((array) ($ap['steps'] ?? []) as $s) echo '<li><strong>' . esc_html($s['title']) . '</strong><span>' . esc_html($s['text']) . '</span></li>'; ?></ol></article><?php endif; ?>
<?php if ($me) : ?><article class="card b-metrics"><span class="mono y"><?php echo esc_html($me['label'] ?? ''); ?></span><h3><?php echo esc_html($me['title'] ?? ''); ?></h3>
<div class="metrics"><?php foreach ((array) ($me['items'] ?? []) as $m) echo '<div><b data-count="' . (int) $m['number'] . '">' . (int) $m['number'] . '</b><span>' . esc_html($m['label']) . '</span></div>'; ?></div></article><?php endif; ?>
<?php echo $card($sp, 'b-speed', '<div class="speed-bar" aria-hidden="true"><i></i></div><span class="cap">' . esc_html($sp['note'] ?? bv_t('illustrative')) . '</span>'); // phpcs:ignore ?>
<?php if ($sr) echo $card($sr, 'b-serp', '<div class="serp" aria-hidden="true" dir="' . (is_rtl() ? 'rtl' : 'ltr') . '"><small>' . esc_html($sr['url'] ?? '') . '</small><b>' . esc_html($sr['snip_title'] ?? '') . '</b><p>' . esc_html($sr['snip_text'] ?? '') . '</p></div><span class="cap">' . esc_html($sr['note'] ?? bv_t('illustrative_serp')) . '</span>'); // phpcs:ignore ?>
<?php if (bv_fb('why_logo', $pid, true)) : $lg = bv_opt('logo'); ?><article class="card b-logo" aria-hidden="true"><?php echo $lg ? bv_img($lg, '', '', '300px', true, 'medium') : '<img src="' . esc_url(bv_asset('img/logo.webp')) . '" alt="" width="300" height="199" loading="lazy" decoding="async">'; // phpcs:ignore ?></article><?php endif; ?>
<?php echo $card($rp, 'b-rep', '<svg class="spark" viewBox="0 0 200 70" aria-hidden="true"><path d="M2 60 C30 58 40 40 70 44 S110 20 135 26 S175 8 198 6"/></svg>'); // phpcs:ignore ?>
<?php echo $card($su, 'b-support'); echo $card($ti, 'b-time'); // phpcs:ignore ?>
</div></div></section>
<?php endif; ?>

<?php /* ------------------------------------------------ MARQUEE */
if ($on('marquee')) : $mw = array_map(function ($r) { return $r['word']; }, (array) $F('mq_words', [])); if ($mw) : ?>
<section class="marquees" aria-label="<?php echo esc_attr(bv_t('marquee_aria')); ?>">
<?php echo bv_marquee($mw, 0); echo bv_marquee(array_map('mb_strtoupper', $mw), 1, true); // phpcs:ignore ?>
</section>
<?php endif; endif; ?>

<?php /* ------------------------------------------------ PROCESS */
if ($on('process')) : $steps = $F('pr_steps', []); ?>
<section class="section process" id="process" aria-labelledby="process-h">
<div class="container"><div class="sec-head"><?php echo $eyebrow($F('pr_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="process-h" data-split><?php echo esc_html($F('pr_title')); ?></h2></div></div>
<div class="process-stage"><div class="container"><div class="pbar" aria-hidden="true"><i></i></div>
<div class="psteps"><?php foreach ($steps as $i => $s) echo '<article class="pstep"><div class="n" aria-hidden="true">' . bv_num2($i + 1) . '</div><div><h3>' . esc_html($s['title']) . '</h3><p>' . bv_br($s['text']) . '</p></div></article>'; ?></div>
<div class="pdots" aria-hidden="true"><?php echo str_repeat('<span></span>', count($steps)); ?></div></div></div>
</section>
<?php endif; ?>

<?php /* ------------------------------------------------ INDUSTRIES */
if ($on('industries')) : $ind = array_values((array) $F('in_items', [])); $c1 = array_slice($ind, 0, 7); $c2 = array_slice($ind, 7);
	$chips = function ($arr, $off) { $h = ''; foreach ($arr as $i => $r) $h .= '<span class="chip' . ($i % 2 ? ' alt' : '') . '"><small>' . bv_num2($i + 1 + $off) . '</small>' . esc_html($r['text']) . '</span>'; return $h; }; ?>
<section class="section slant slant-rev" id="industries" aria-labelledby="ind-h">
<div class="container sec-head split"><div><?php echo $eyebrow($F('in_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="ind-h" data-split><?php echo esc_html($F('in_title')); ?></h2></div><p class="lead" data-reveal><?php echo bv_br($F('in_lead')); ?></p></div>
<div class="ind" aria-label="<?php echo esc_attr(bv_t('industries_aria')); ?>"><div class="ind-row"><?php echo $chips($c1, 0); // phpcs:ignore ?></div><div class="ind-row"><?php echo $chips($c2, 7); // phpcs:ignore ?></div></div>
</section>
<?php endif; ?>

<?php /* ------------------------------------------------ TOOLS */
if ($on('tools')) : ?>
<section class="section" id="tools" aria-labelledby="tools-h"><div class="container">
<div class="sec-head split"><div><?php echo $eyebrow($F('tl_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="tools-h" data-split><?php echo esc_html($F('tl_title')); ?></h2></div><p class="lead" data-reveal><?php echo bv_br($F('tl_lead')); ?></p></div>
<div class="tools"><?php foreach ((array) $F('tl_items', []) as $i => $r) echo '<article class="card tool"' . bv_fxa('tilt') . ' data-reveal><span class="mono y">' . bv_num2($i + 1) . '</span><div class="logo-t">' . esc_html($r['name']) . '</div><p>' . bv_br($r['text']) . '</p></article>'; // phpcs:ignore ?></div>
</div></section>
<?php endif; ?>

<?php /* ------------------------------------------------ TESTIMONIALS */
if ($on('testimonials')) : $ts = $F('ts_items', []); if ($ts) : ?>
<section class="section slant" id="testimonials" aria-labelledby="t-h"><div class="container">
<div class="sec-head"><?php echo $eyebrow($F('ts_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="t-h" data-split><?php echo esc_html($F('ts_title')); ?></h2></div>
<div class="slider" data-cursor="drag" tabindex="0" role="region" aria-label="<?php echo esc_attr(bv_t('slider_aria')); ?>">
<?php foreach ($ts as $r) : ?>
<figure class="card slide"><?php if (!empty($r['placeholder'])) echo '<span class="todo-tag mono">' . esc_html(bv_t('placeholder_tag')) . '</span>'; ?>
<blockquote><q><?php echo esc_html($r['quote']); ?></q></blockquote>
<figcaption class="who"><span class="avatar" aria-hidden="true"><?php echo !empty($r['avatar']) ? bv_img($r['avatar'], '', '', '48px', true, 'thumbnail') : ''; // phpcs:ignore ?></span><span><strong><?php echo esc_html($r['name']); ?></strong><br><span class="cap"><?php echo esc_html($r['role']); ?></span></span></figcaption></figure>
<?php endforeach; ?>
</div></div></section>
<?php endif; endif; ?>

<?php /* ------------------------------------------------ FAQ */
if ($on('faq')) : ?>
<section class="section" id="faq" aria-labelledby="faq-h"><div class="container">
<div class="sec-head split"><div><?php echo $eyebrow($F('fq_eyebrow')); // phpcs:ignore ?><h2 class="h1" id="faq-h" data-split><?php echo esc_html($F('fq_title')); ?></h2></div><p class="lead" data-reveal><?php echo bv_br($F('fq_lead')); ?></p></div>
<?php echo bv_faq_html($F('fq_items', []), true); // phpcs:ignore ?>
</div></section>
<?php endif; ?>

<?php /* ------------------------------------------------ CTA + quick form */
if ($on('cta')) : $wa = bv_wa_url(); ?>
<section class="cta-big" id="contact" aria-labelledby="cta-h">
<div class="bg-marq" aria-hidden="true" inert><?php echo bv_marquee(['BRANDIVA', 'MEDIA'], 1, true); // phpcs:ignore ?></div>
<div class="container cta-grid">
<div><?php echo $eyebrow($F('cta_eyebrow')); // phpcs:ignore ?><h2 class="display" id="cta-h" data-split><?php echo esc_html($F('cta_title')); ?></h2>
<p class="lead" style="margin-block:1.25rem" data-reveal><?php echo bv_br($F('cta_text')); ?></p>
<?php if ($wa && bv_fb('cta_wa_show', $pid, true)) : ?><a class="wa link-u" href="<?php echo esc_url($wa); ?>" rel="noopener" data-reveal><?php echo bv_icon('wa', 'fa fa-inline'); // phpcs:ignore ?> <?php echo esc_html($F('cta_wa_label')); ?></a><?php endif; ?></div>
<div class="card cta-card" data-reveal><?php echo bv_form(bv_form_id('quote')); // phpcs:ignore ?></div>
</div></section>
<?php endif; ?>
</main>
<?php get_footer(); ?>
