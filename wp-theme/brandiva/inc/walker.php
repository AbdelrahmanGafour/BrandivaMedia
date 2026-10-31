<?php
defined('ABSPATH') || exit;

/** Menu walker producing the theme's link markup: desktop <a>, mobile <a><small>0N</small>, footer <li><a>. */
class BV_Nav_Walker extends Walker_Nav_Menu {
	private $mode; private $n = 0;
	public function __construct($mode = 'desktop') { $this->mode = $mode; }
	public function start_lvl(&$output, $depth = 0, $args = null) {}
	public function end_lvl(&$output, $depth = 0, $args = null) {}
	public function end_el(&$output, $item, $depth = 0, $args = null) { if ($this->mode === 'footer' && $depth === 0) $output .= '</li>'; }
	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		if ($depth > 0) return;
		$cur = !empty($item->current) || in_array('current-menu-item', (array) $item->classes, true);
		$a = ' href="' . esc_url($item->url) . '"' . ($cur && $this->mode !== 'mobile' ? ' aria-current="page"' : '');
		if (!empty($item->target)) $a .= ' target="' . esc_attr($item->target) . '" rel="noopener"';
		$t = esc_html($item->title);
		$this->n++;
		if ($this->mode === 'mobile') $output .= '<a' . $a . '><small>' . sprintf('%02d', $this->n) . '</small>' . $t . '</a>';
		elseif ($this->mode === 'footer') $output .= '<li><a' . $a . '>' . $t . '</a>';
		else $output .= '<a' . $a . '>' . $t . '</a>';
	}
}

function bv_menu($location, $mode) {
	wp_nav_menu(['theme_location' => $location, 'container' => false, 'items_wrap' => '%3$s', 'depth' => 1, 'walker' => new BV_Nav_Walker($mode), 'fallback_cb' => function () use ($location, $mode) { bv_menu_fallback($location, $mode); }]);
}
/** When no menu is assigned: list top-level pages of the current language. */
function bv_menu_fallback($location, $mode) {
	if ($location === 'footer_services') return;
	$pages = get_pages(['sort_column' => 'menu_order,post_title', 'parent' => 0, 'number' => 8]);
	$n = 0;
	foreach ((array) $pages as $p) {
		$n++; $u = esc_url(get_permalink($p)); $t = esc_html(get_the_title($p));
		if ($mode === 'mobile') echo '<a href="' . $u . '"><small>' . sprintf('%02d', $n) . '</small>' . $t . '</a>';
		elseif ($mode === 'footer') echo '<li><a href="' . $u . '">' . $t . '</a></li>';
		else echo '<a href="' . $u . '"' . (is_page($p->ID) ? ' aria-current="page"' : '') . '>' . $t . '</a>';
	}
}
