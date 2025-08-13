<?php
if (!defined('ABSPATH')) exit;

/**
 * ساختار option: admino_menu_profile_default
 * {
 *   "order": ["index.php","edit.php","upload.php", "..."],
 *   "items": {
 *     "edit.php": {"title":"محتوا","icon":"dashicons-media-text","cap":"edit_posts","hidden":false}
 *   },
 *   "subs": {
 *     "edit.php": {
 *       "post-new.php": {"title":"افزودن جدید","hidden":false}
 *     }
 *   }
 * }
 */

add_filter('custom_menu_order', '__return_true', 999);

add_filter('menu_order', function($menu_order) {
    global $menu;
    $opt = admino_get_option('admino_menu_profile_default', []);
    $order = admino_array_get($opt, 'order', []);

    if (!$order || !is_array($order)) return $menu_order;

    $existing = [];
    foreach ($menu as $i => $item) {
        if (!empty($item[2])) $existing[] = $item[2];
    }

    $new = array_values(array_intersect($order, $existing));
    $rest = array_values(array_diff($existing, $new));
    return array_merge($new, $rest);
}, 999);

add_action('admin_menu', function() {
    global $menu, $submenu;

    $opt    = admino_get_option('admino_menu_profile_default', []);
    $items  = admino_array_get($opt, 'items', []);
    $subs   = admino_array_get($opt, 'subs', []);

    $menu_copy = $menu;

    foreach ($menu_copy as $i => $m) {
        $slug = isset($m[2]) ? $m[2] : '';
        if (!$slug) continue;

        if (isset($items[$slug])) {
            $conf = $items[$slug];

            if (!empty($conf['title'])) $menu[$i][0] = sanitize_text_field($conf['title']);

            if (!empty($conf['icon'])) $menu[$i][6] = $conf['icon']; // dashicon class یا URL

            if (!empty($conf['cap']))  $menu[$i][1] = sanitize_text_field($conf['cap']);

            if (!empty($conf['hidden'])) unset($menu[$i]);
        }
    }

    foreach ($subs as $parent_slug => $children) {
        if (!isset($submenu[$parent_slug])) continue;

        $ordered = [];
        foreach ($children as $child_slug => $cconf) {
            foreach ($submenu[$parent_slug] as $idx => $sub) {
                $s_slug = isset($sub[2]) ? $sub[2] : '';
                if ($s_slug === $child_slug) {
                    if (!empty($cconf['hidden'])) {
                        unset($submenu[$parent_slug][$idx]);
                    } else {
                        if (!empty($cconf['title'])) $submenu[$parent_slug][$idx][0] = sanitize_text_field($cconf['title']);
                    }
                }
            }
        }

        $current = $submenu[$parent_slug];
        $map = [];
        foreach ($current as $row) { $map[$row[2]] = $row; }
        $new_order = array_keys($children);
        $final = [];
        foreach ($new_order as $slug) {
            if (isset($map[$slug])) $final[] = $map[$slug];
        }
        foreach ($map as $slug => $row) {
            if (!in_array($slug, $new_order, true)) $final[] = $row;
        }
        $submenu[$parent_slug] = array_values($final);
    }

}, 999);
