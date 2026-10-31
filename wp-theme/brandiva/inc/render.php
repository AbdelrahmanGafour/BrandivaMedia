<?php
/** Shared renderers (FAQ, notes, flexible sections) */
defined('ABSPATH') || exit;

function bv_faq_html($items, $open_first = false) {
	if (empty($items)) return '';
	$h = '<div class="faq" data-reveal>';
	foreach (array_values($items) as $i => $r) {
		if (empty($r['q'])) continue;
		$h .= '<details' . ($open_first && $i === 0 ? ' open' : '') . '><summary>' . esc_html($r['q']) . '</summary><div class="ans"><p>' . bv_br($r['a'] ?? '') . '</p></div></details>';
	}
	return $h . '</div>';
}

function bv_note_html($n) {
	if (empty($n) || (empty($n['title']) && empty($n['text']))) return '';
	$h = '<div class="note" data-reveal>' . (!empty($n['title']) ? '<h3>' . esc_html($n['title']) . '</h3>' : '') . (!empty($n['text']) ? '<p>' . bv_br($n['text']) . '</p>' : '');
	if (!empty($n['btn'])) $h .= '<a class="btn btn-yellow" href="' . esc_url(bv_contact_url($n['btn_service'] ?? '')) . '" style="margin-block-start:1rem">' . esc_html($n['btn']) . ' ' . bv_arrow() . '</a>';
	return $h . '</div>';
}

function bv_num2($i) { return sprintf('%02d', $i); }

/** One flexible-content row → <section>. */
function bv_render_section($row) {
	$l = $row['acf_fc_layout'] ?? ''; $id = sanitize_title($row['anchor'] ?? $l); $title = $row['title'] ?? '';
	$body = !empty($row['intro']) ? bv_rich($row['intro']) : '';
	$items = $row['items'] ?? []; $items = is_array($items) ? $items : [];
	switch ($l) {
		case 'rich':
			$body .= bv_rich($row['body'] ?? '', $row['list_style'] ?? 'tick'); break;
		case 'cards_stack':
			$body .= '<div class="stack3">';
			foreach ($items as $i => $r) $body .= '<article class="card" data-reveal><span class="bignum" aria-hidden="true">' . ($i + 1) . '</span><h3>' . esc_html($r['title']) . '</h3><div>' . bv_rich($r['body'] ?? '') . '</div></article>';
			$body .= '</div>'; break;
		case 'tabs':
			$btn = $pan = ''; $n = count($items);
			foreach ($items as $i => $r) {
				$btn .= '<button class="tab" role="tab" id="tab-' . $id . '-' . $i . '" aria-controls="tp-' . $id . '-' . $i . '" aria-selected="false" type="button"><small>' . bv_num2($i + 1) . '</small><span class="lat" style="font-weight:700;letter-spacing:0;font-family:var(--font-ar)">' . esc_html($r['title']) . '</span></button>';
				$pan .= '<div class="tp card" role="tabpanel" id="tp-' . $id . '-' . $i . '" aria-labelledby="tab-' . $id . '-' . $i . '" tabindex="0"><span class="mono y">' . bv_num2($i + 1) . ' / ' . $n . '</span><h3 data-nosplit>' . esc_html($r['title']) . '</h3>' . bv_rich($r['body'] ?? '') . '</div>';
			}
			$body .= '<div class="tabs" data-tabs><div class="tablist" role="tablist" aria-label="' . esc_attr($title) . '" aria-orientation="vertical">' . $btn . '</div><div class="tabpanels">' . $pan . '</div></div>'; break;
		case 'steps':
			$body .= '<ol class="steps ' . esc_attr($row['style'] ?? '') . '">';
			foreach ($items as $i => $r) $body .= '<li class="step card" data-reveal><span class="sn" aria-hidden="true">' . bv_num2($i + 1) . '</span><h3>' . esc_html($r['title']) . '</h3>' . bv_rich($r['body'] ?? '') . '</li>';
			$body .= '</ol>'; break;
		case 'tools':
			$body .= '<div class="tools" style="margin-block-start:1.5rem">';
			foreach ($items as $i => $r) $body .= '<article class="card tool"' . bv_fxa('tilt') . ' data-reveal><span class="mono y">' . bv_num2($i + 1) . '</span><div class="logo-t">' . esc_html($r['name']) . '</div><p>' . bv_br($r['text'] ?? '') . '</p></article>';
			$body .= '</div>'; break;
		case 'chips':
			$body .= '<ul class="chips" data-reveal>';
			foreach ($items as $i => $r) $body .= '<li class="chip' . ($i % 2 ? ' alt' : '') . '">' . esc_html($r['text']) . '</li>';
			$body .= '</ul>'; break;
		case 'flips':
			$body .= '<div class="flips">';
			foreach ($items as $i => $r) $body .= '<div class="flip" data-reveal tabindex="0" aria-label="' . esc_attr($r['title']) . '"><div class="flip-in"><div class="f"><span class="bignum" aria-hidden="true">' . bv_num2($i + 1) . '</span><h3>' . esc_html($r['title']) . '</h3><span class="cap mono">' . esc_html(bv_t('flip_hint')) . '</span></div><div class="b"><p style="color:#fff">' . bv_br($r['text'] ?? '') . '</p></div></div></div>';
			$body .= '</div>'; break;
		case 'types':
			$body .= '<div class="types">';
			foreach ($items as $i => $r) $body .= '<article class="tc"' . bv_fxa('tilt') . ' data-reveal><span class="n mono">' . bv_num2($i + 1) . '</span>' . bv_img($r['image'] ?? 0, $r['alt'] ?? '', '', '(min-width:1100px) 33vw, 100vw', true, 'bv-card') . '<h3>' . esc_html($r['title']) . '</h3><p>' . bv_br($r['text1'] ?? '') . '</p>' . (!empty($r['text2']) ? '<p>' . bv_br($r['text2']) . '</p>' : '') . '</article>';
			$body .= '</div>'; break;
		case 'features':
			$body .= '<div class="feat">';
			foreach ($items as $i => $r) $body .= '<article class="card' . (!empty($r['wide']) ? ' wide' : '') . '"' . bv_fxa('tilt') . ' data-reveal><span class="mono y">' . bv_num2($i + 1) . '</span><h3>' . esc_html($r['title']) . '</h3>' . bv_rich($r['body'] ?? '') . '</article>';
			$body .= '</div>'; break;
		case 'plans':
			$body .= '<div class="plans">';
			foreach ($items as $r) {
				$f = !empty($r['featured']); $li = '';
				foreach ((array) ($r['features'] ?? []) as $x) $li .= '<li>' . esc_html($x['text']) . '</li>';
				$body .= '<article class="card plan' . ($f ? ' feat-plan' : '') . '"' . (!empty($r['id']) ? ' id="' . esc_attr(sanitize_title($r['id'])) . '"' : '') . bv_fxa('tilt') . ' data-reveal>'
					. (!empty($r['tag']) ? '<span class="tag mono ptag">' . esc_html($r['tag']) . '</span>' : '') . '<h3 class="lat">' . esc_html($r['name']) . '</h3><p>' . bv_br($r['text'] ?? '') . '</p>'
					. ($li ? '<ul class="ticks" data-reveal>' . $li . '</ul>' : '')
					. (!empty($r['btn']) ? '<a class="btn ' . ($f ? 'btn-yellow' : 'btn-ghost') . '" href="' . esc_url(bv_contact_url($r['btn_service'] ?? '')) . '">' . esc_html($r['btn']) . ' ' . bv_arrow() . '</a>' : '') . '</article>';
			}
			$body .= '</div>' . (!empty($row['footnote']) ? '<p class="cap" style="margin-block-start:.75rem">' . bv_br($row['footnote']) . '</p>' : ''); break;
		case 'addons':
			$body .= '<div class="addons">';
			foreach ($items as $r) $body .= '<article class="card" data-reveal><h3>' . esc_html($r['title']) . '</h3><p>' . bv_br($r['text'] ?? '') . '</p></article>';
			$body .= '</div>'; break;
		case 'faq':
			$body .= bv_faq_html($items, !empty($row['open_first'])); break;
	}
	if (!empty($row['after'])) $body .= '<div class="after-text' . ($l === 'plans' ? ' kw' : '') . '" data-reveal>' . bv_rich($row['after']) . '</div>';
	$body .= bv_note_html($row['note'] ?? []);
	return '<section class="psec" id="' . esc_attr($id) . '" aria-labelledby="' . esc_attr($id) . '-h"><h2 class="h2" id="' . esc_attr($id) . '-h" data-reveal>' . esc_html($title) . '</h2>' . $body . '</section>';
}
