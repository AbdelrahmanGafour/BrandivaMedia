<?php
/** Field groups for pages (Secure Custom Fields) */
defined('ABSPATH') || exit;

function bv_service_param_choices() { return ['' => 'Contact page (no preset)', 'web-design' => 'Web design', 'seo' => 'SEO services', 'hosting' => 'Web hosting', 'other' => 'Other']; }
function bv_anchor_choices() { return ['services' => 'Services', 'why' => 'Why us', 'process' => 'Process', 'industries' => 'Industries', 'tools' => 'Tools', 'testimonials' => 'Testimonials', 'faq' => 'FAQ', 'contact' => 'Contact / CTA']; }

function bv_section_layouts() {
	$common = function () {
		return [
			bv_text('anchor', 'Anchor ID', ['instructions' => 'Latin letters / dashes. Used for the table of contents link (#id).', 'required' => 1]),
			bv_text('title', 'Section heading', ['required' => 1]),
			bv_text('toc', 'Label in “On this page” (optional)', ['instructions' => 'Leave empty to use the heading.']),
			bv_wys('intro', 'Intro text (optional)', ['toolbar' => 'basic']),
		];
	};
	$tail = function () {
		return [
			bv_wys('after', 'Closing text (optional)', ['toolbar' => 'basic']),
			bv_grp('note', 'Highlight box (optional)', [
				bv_text('title', 'Title'), bv_area('text', 'Text'),
				bv_text('btn', 'Button label'), bv_sel('btn_service', 'Button preset service', bv_service_param_choices(), ''),
			], ['layout' => 'row']),
		];
	};
	$L = function ($name, $label, array $body) use ($common, $tail) {
		return ['name' => $name, 'label' => $label, 'display' => 'block', 'sub_fields' => array_merge($common(), $body, $tail()), 'min' => '', 'max' => ''];
	};
	return [
		$L('rich', 'Rich text', [bv_wys('body', 'Content'), bv_sel('list_style', 'Bullet list style', ['tick' => 'Check marks', 'plain' => 'Plain'], 'tick')]),
		$L('cards_stack', 'Numbered cards (stack)', [bv_rep('items', 'Cards', [bv_text('title', 'Title'), bv_wys('body', 'Content', ['toolbar' => 'basic'])], ['layout' => 'block'])]),
		$L('tabs', 'Vertical tabs', [bv_rep('items', 'Tabs', [bv_text('title', 'Title'), bv_wys('body', 'Content')], ['layout' => 'block'])]),
		$L('steps', 'Steps', [bv_sel('style', 'Layout', ['' => 'Vertical', 'h' => 'Horizontal'], ''), bv_rep('items', 'Steps', [bv_text('title', 'Title'), bv_wys('body', 'Description', ['toolbar' => 'basic'])], ['layout' => 'block'])]),
		$L('tools', 'Tools cards', [bv_rep('items', 'Tools', [bv_text('name', 'Name'), bv_area('text', 'Description')], ['layout' => 'table'])]),
		$L('chips', 'Chips / tags', [bv_rep('items', 'Items', [bv_text('text', 'Text')], ['layout' => 'table'])]),
		$L('flips', 'Flip cards', [bv_rep('items', 'Cards', [bv_text('title', 'Front title'), bv_area('text', 'Back text')], ['layout' => 'block'])]),
		$L('types', 'Image cards', [bv_rep('items', 'Cards', [bv_img_f('image', 'Image'), bv_text('alt', 'Image alt text'), bv_text('title', 'Title'), bv_area('text1', 'Paragraph 1'), bv_area('text2', 'Paragraph 2')], ['layout' => 'block'])]),
		$L('features', 'Feature cards', [bv_rep('items', 'Cards', [bv_text('title', 'Title'), bv_wys('body', 'Content', ['toolbar' => 'basic']), bv_bool('wide', 'Wide card', 0)], ['layout' => 'block'])]),
		$L('plans', 'Pricing / plan cards', [
			bv_rep('items', 'Plans', [
				bv_text('id', 'Anchor ID'), bv_text('name', 'Plan name'), bv_text('tag', 'Badge text'), bv_area('text', 'Description'),
				bv_rep('features', 'Features', [bv_text('text', 'Feature')], ['layout' => 'table']),
				bv_bool('featured', 'Featured', 0), bv_text('btn', 'Button label'), bv_sel('btn_service', 'Button preset service', bv_service_param_choices(), 'hosting'),
			], ['layout' => 'block']),
			bv_area('footnote', 'Footnote under the plans'),
		]),
		$L('addons', 'Add-on cards', [bv_rep('items', 'Cards', [bv_text('title', 'Title'), bv_area('text', 'Text')], ['layout' => 'block'])]),
		$L('faq', 'FAQ accordion', [bv_bool('open_first', 'Open the first question', 0), bv_rep('items', 'Questions', [bv_text('q', 'Question'), bv_area('a', 'Answer')], ['layout' => 'block'])]),
	];
}

add_action('acf/init', function () {
	$pages = function ($tpl) { return [[['param' => 'page_template', 'operator' => '==', 'value' => $tpl]]]; };

	/* ------------------------------------------------------------ SEO (all pages) */
	bv_group('seo', 'SEO', [
		bv_text('seo_title', 'SEO title', ['instructions' => 'Browser/Google title. Empty = page title.']),
		bv_area('seo_desc', 'Meta description', ['rows' => 3]),
		bv_img_f('seo_image', 'Social share image'),
		bv_bool('seo_noindex', 'Hide from search engines (noindex)', 0),
	], [[['param' => 'post_type', 'operator' => '==', 'value' => 'page']]], ['menu_order' => 20, 'position' => 'normal']);

	/* ------------------------------------------------------------ Home */
	$tag_rep = bv_rep('tags', 'Tags', [bv_text('tag', 'Tag')], ['layout' => 'table']);
	$card = function ($n, $l, $extra = []) { return bv_grp($n, $l, array_merge([bv_text('label', 'Label'), bv_text('title', 'Title'), bv_area('text', 'Text')], $extra), ['layout' => 'row']); };
	$home = [
		bv_tab('Sections'),
		bv_fld('checkbox', 'sections', 'Visible sections', ['choices' => ['services' => 'Services', 'why' => 'Why us (bento)', 'marquee' => 'Keywords marquee', 'process' => 'Process', 'industries' => 'Industries', 'tools' => 'Tools', 'testimonials' => 'Testimonials', 'faq' => 'FAQ', 'cta' => 'Final CTA + form'],
			'default_value' => ['services', 'why', 'marquee', 'process', 'industries', 'tools', 'testimonials', 'faq', 'cta'], 'layout' => 'vertical', 'return_format' => 'value', 'allow_custom' => 0, 'toggle' => 0, 'save_custom' => 0]),

		bv_tab('Hero'),
		bv_text('hero_eyebrow', 'Eyebrow'),
		bv_text('hero_line1', 'Headline — line 1'),
		bv_text('hero_line2_pre', 'Headline — line 2 (plain part)'),
		bv_text('hero_line2_em', 'Headline — line 2 (highlighted word)'),
		bv_area('hero_lead', 'Lead paragraph'),
		bv_text('hero_cta1_label', 'Primary button label'),
		bv_sel('hero_cta1_service', 'Primary button → Contact page preset', bv_service_param_choices(), ''),
		bv_text('hero_cta2_label', 'Secondary button label'),
		bv_sel('hero_cta2_anchor', 'Secondary button scrolls to', bv_anchor_choices(), 'services'),
		bv_rep('hero_trust', 'Trust keywords', [bv_text('text', 'Word')], ['layout' => 'table']),
		bv_img_f('hero_canvas_img', 'Image shown inside the hero shape'),

		bv_tab('Services'),
		bv_text('svc_eyebrow', 'Eyebrow'), bv_area('svc_title', 'Heading (new line = line break)', ['rows' => 2]), bv_area('svc_lead', 'Lead'),
		bv_rep('svc_items', 'Services', [bv_text('title', 'Title'), bv_pagesel('page', 'Links to page'), bv_img_f('image', 'Image'), bv_text('alt', 'Image alt text'), $tag_rep], ['layout' => 'block']),

		bv_tab('Why us'),
		bv_text('why_eyebrow', 'Eyebrow'), bv_text('why_title', 'Heading'),
		bv_grp('why_approach', 'Approach card', [bv_text('label', 'Label'), bv_text('title', 'Title'), bv_area('text', 'Text'),
			bv_rep('steps', 'Steps', [bv_text('title', 'Title'), bv_text('text', 'Text')], ['layout' => 'table'])], ['layout' => 'block']),
		bv_grp('why_metrics', 'Metrics card', [bv_text('label', 'Label'), bv_text('title', 'Title'),
			bv_rep('items', 'Metrics', [bv_num('number', 'Number', 0), bv_text('label', 'Label')], ['layout' => 'table'])], ['layout' => 'block']),
		$card('why_speed', 'Speed card', [bv_text('note', 'Caption')]),
		$card('why_serp', 'Search-ready card', [bv_text('url', 'Snippet URL'), bv_text('snip_title', 'Snippet title'), bv_area('snip_text', 'Snippet text', ['rows' => 2]), bv_text('note', 'Caption')]),
		bv_bool('why_logo', 'Show logo tile', 1),
		$card('why_reports', 'Reports card'), $card('why_support', 'Support card'), $card('why_time', 'Commitment card'),

		bv_tab('Marquee'),
		bv_rep('mq_words', 'Words', [bv_text('word', 'Word')], ['layout' => 'table']),

		bv_tab('Process'),
		bv_text('pr_eyebrow', 'Eyebrow'), bv_text('pr_title', 'Heading'),
		bv_rep('pr_steps', 'Steps', [bv_text('title', 'Title'), bv_area('text', 'Description')], ['layout' => 'block']),

		bv_tab('Industries'),
		bv_text('in_eyebrow', 'Eyebrow'), bv_text('in_title', 'Heading'), bv_area('in_lead', 'Lead'),
		bv_rep('in_items', 'Industries', [bv_text('text', 'Name')], ['layout' => 'table']),

		bv_tab('Tools'),
		bv_text('tl_eyebrow', 'Eyebrow'), bv_text('tl_title', 'Heading'), bv_area('tl_lead', 'Lead'),
		bv_rep('tl_items', 'Tools', [bv_text('name', 'Name'), bv_area('text', 'Description')], ['layout' => 'table']),

		bv_tab('Testimonials'),
		bv_text('ts_eyebrow', 'Eyebrow'), bv_text('ts_title', 'Heading'),
		bv_rep('ts_items', 'Testimonials', [bv_area('quote', 'Quote'), bv_text('name', 'Client name'), bv_text('role', 'Industry — Company'), bv_img_f('avatar', 'Avatar'), bv_bool('placeholder', 'Mark as placeholder', 0)], ['layout' => 'block']),

		bv_tab('FAQ'),
		bv_text('fq_eyebrow', 'Eyebrow'), bv_text('fq_title', 'Heading'), bv_area('fq_lead', 'Lead'),
		bv_rep('fq_items', 'Questions', [bv_text('q', 'Question'), bv_area('a', 'Answer')], ['layout' => 'block']),

		bv_tab('Final CTA'),
		bv_text('cta_eyebrow', 'Eyebrow'), bv_text('cta_title', 'Heading'), bv_area('cta_text', 'Text'),
		bv_bool('cta_wa_show', 'Show WhatsApp link', 1), bv_text('cta_wa_label', 'WhatsApp link label'),
	];
	bv_group('home', 'Home page content', $home, $pages('templates/page-home.php'), ['menu_order' => 0]);

	/* ------------------------------------------------------------ Service pages */
	bv_group('svc', 'Service page content', [
		bv_tab('Hero'),
		bv_text('sp_eyebrow', 'Eyebrow'),
		bv_text('sp_h1', 'Main heading (H1)'),
		bv_wys('sp_intro', 'Intro', ['toolbar' => 'basic']),
		bv_img_f('sp_image', 'Hero image'),
		bv_text('sp_image_alt', 'Hero image alt text'),
		bv_text('sp_name', 'Short service name', ['instructions' => 'Used in breadcrumbs and structured data.']),
		bv_tab('Sections'),
		bv_fld('flexible_content', 'sp_sections', 'Page sections', ['layouts' => bv_section_layouts(), 'button_label' => 'Add section', 'min' => 0]),
		bv_tab('Bottom CTA'),
		bv_grp('sp_cta', 'Call-to-action box', [
			bv_text('title', 'Heading'), bv_wys('text', 'Text', ['toolbar' => 'basic']),
			bv_wys('list', 'Bullet list (optional)', ['toolbar' => 'basic']),
			bv_text('btn', 'Button label'), bv_sel('service', 'Preset service on contact page', bv_service_param_choices(), ''),
		], ['layout' => 'block']),
	], $pages('templates/page-service.php'), ['menu_order' => 0]);

	/* ------------------------------------------------------------ Contact page */
	bv_group('contact', 'Contact page content', [
		bv_text('cp_eyebrow', 'Eyebrow'),
		bv_text('cp_h1', 'Main heading (H1)'),
		bv_area('cp_lead', 'Lead'),
		bv_bool('cp_show_wa', 'Show WhatsApp card', 1),
		bv_bool('cp_show_email', 'Show email card', 1),
		bv_text('cp_next_title', '“What happens next” title'),
		bv_rep('cp_next', '“What happens next” list', [bv_text('text', 'Item')], ['layout' => 'table']),
		bv_bool('cp_show_social', 'Show social card', 1),
		bv_bool('cp_show_clock', 'Show clock card', 1),
	], $pages('templates/page-contact.php'), ['menu_order' => 0]);
});
