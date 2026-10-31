<?php
/** Tiny DSL on top of acf_add_local_field_group so that every field has a deterministic key. */
defined('ABSPATH') || exit;

/** bv_fld('select','service','Service',['choices'=>[...]]) */
function bv_fld($type, $name, $label, $args = []) {
	return array_merge(['type' => $type, 'name' => $name, 'label' => $label], $args);
}
function bv_tab($label) { return ['type' => 'tab', 'name' => 'tab_' . sanitize_key($label), 'label' => $label, 'placement' => 'top', 'endpoint' => 0]; }

/** Recursively assign keys: field_bv_{prefix}_{name}; layouts: layout_bv_{prefix}_{name}. */
function bv_keyed(array $fields, $prefix) {
	$out = [];
	foreach ($fields as $f) {
		$f['key'] = 'field_bv_' . $prefix . '_' . $f['name'];
		if (!empty($f['sub_fields'])) $f['sub_fields'] = bv_keyed($f['sub_fields'], $prefix . '_' . $f['name']);
		if (!empty($f['layouts'])) {
			$ls = [];
			foreach ($f['layouts'] as $l) {
				$l['key'] = 'layout_bv_' . $prefix . '_' . $f['name'] . '_' . $l['name'];
				$l['sub_fields'] = bv_keyed($l['sub_fields'], $prefix . '_' . $f['name'] . '_' . $l['name']);
				$ls[] = $l;
			}
			$f['layouts'] = $ls;
		}
		$out[] = $f;
	}
	return $out;
}
/** Key of a top-level field: bv_key('home','hero_lead'). Used by the demo importer. */
function bv_key($prefix, $name) { return 'field_bv_' . $prefix . '_' . $name; }

function bv_group($id, $title, array $fields, array $location, $extra = []) {
	acf_add_local_field_group(array_merge([
		'key' => 'group_bv_' . $id, 'title' => $title, 'fields' => bv_keyed($fields, $id), 'location' => $location,
		'menu_order' => 0, 'position' => 'normal', 'style' => 'default', 'label_placement' => 'top', 'instruction_placement' => 'label',
		'hide_on_screen' => ['the_content', 'discussion', 'comments', 'author', 'format', 'featured_image'],
	], $extra));
}

/* Short builders ------------------------------------------------------- */
function bv_text($n, $l, $a = [])     { return bv_fld('text', $n, $l, $a); }
function bv_area($n, $l, $a = [])     { return bv_fld('textarea', $n, $l, array_merge(['rows' => 3, 'new_lines' => ''], $a)); }
function bv_wys($n, $l, $a = [])      { return bv_fld('wysiwyg', $n, $l, array_merge(['tabs' => 'all', 'toolbar' => 'full', 'media_upload' => 0, 'delay' => 1], $a)); }
function bv_img_f($n, $l, $a = [])    { return bv_fld('image', $n, $l, array_merge(['return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all'], $a)); }
function bv_bool($n, $l, $def = 1, $a = []) { return bv_fld('true_false', $n, $l, array_merge(['default_value' => $def, 'ui' => 1, 'ui_on_text' => 'On', 'ui_off_text' => 'Off'], $a)); }
function bv_sel($n, $l, $choices, $def = '', $a = []) { return bv_fld('select', $n, $l, array_merge(['choices' => $choices, 'default_value' => $def, 'return_format' => 'value', 'allow_null' => 0, 'ui' => 0], $a)); }
function bv_color($n, $l, $def)       { return bv_fld('color_picker', $n, $l, ['default_value' => $def, 'enable_opacity' => 0, 'return_format' => 'string']); }
function bv_num($n, $l, $def, $a = []) { return bv_fld('number', $n, $l, array_merge(['default_value' => $def], $a)); }
function bv_range($n, $l, $def, $min, $max, $step = 1, $suffix = 'px') { return bv_fld('range', $n, $l, ['default_value' => $def, 'min' => $min, 'max' => $max, 'step' => $step, 'append' => $suffix]); }
function bv_rep($n, $l, array $sub, $a = []) { return bv_fld('repeater', $n, $l, array_merge(['layout' => 'block', 'button_label' => 'Add row', 'sub_fields' => $sub, 'min' => 0], $a)); }
function bv_grp($n, $l, array $sub, $a = []) { return bv_fld('group', $n, $l, array_merge(['layout' => 'block', 'sub_fields' => $sub], $a)); }
function bv_link($n, $l, $a = [])     { return bv_fld('link', $n, $l, array_merge(['return_format' => 'array'], $a)); }
function bv_pagesel($n, $l, $a = [])  { return bv_fld('page_link', $n, $l, array_merge(['post_type' => ['page'], 'allow_null' => 1, 'multiple' => 0, 'allow_archives' => 0], $a)); }
