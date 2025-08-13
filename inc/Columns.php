<?php
if (!defined('ABSPATH')) exit;

/**
 * ساختار option: admino_columns
 * {
 *   "posts": {
 *     "post": [
 *       {"key":"price","label":"قیمت","type":"meta","meta_key":"price","sortable":true,"numeric":true,"filterable":true},
 *       {"key":"brand","label":"برند","type":"taxonomy","taxonomy":"brand","filterable":true}
 *     ]
 *   },
 *   "tax": {
 *     "product_cat": [
 *       {"key":"badge","label":"نشان","type":"meta","meta_key":"badge"}
 *     ]
 *   }
 * }
 */

add_action('admin_init', function() {
    $opt = admino_get_option('admino_columns', []);
    $posts = admino_array_get($opt, 'posts', []);
    $taxes = admino_array_get($opt, 'tax', []);

    foreach ($posts as $pt => $cols) {
        add_filter("manage_{$pt}_posts_columns", function($columns) use ($cols) {
            foreach ($cols as $c) {
                $columns[$c['key']] = esc_html($c['label']);
            }
            return $columns;
        });

        add_action("manage_{$pt}_posts_custom_column", function($column, $post_id) use ($cols) {
            foreach ($cols as $c) {
                if ($column !== $c['key']) continue;
                $type = $c['type'];
                if ($type === 'meta' || $type === 'acf') {
                    $key = $type === 'acf' ? admino_array_get($c, 'acf_key', '') : admino_array_get($c, 'meta_key', '');
                    $val = $type === 'acf' && function_exists('get_field') ? get_field($key, $post_id) : get_post_meta($post_id, $key, true);
                    if (is_array($val)) $val = implode(', ', array_map('strval', $val));
                    $out = wp_strip_all_tags((string)$val);
                    echo esc_html(mb_strimwidth($out, 0, 80, '…'));
                } elseif ($type === 'taxonomy') {
                    $tax = admino_array_get($c, 'taxonomy', '');
                    $terms = get_the_terms($post_id, $tax);
                    if (is_wp_error($terms) || empty($terms)) { echo '—'; return; }
                    echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
                } elseif ($type === 'author') {
                    $u = get_userdata(get_post_field('post_author', $post_id));
                    echo $u ? esc_html($u->display_name) : '—';
                } elseif ($type === 'date') {
                    echo esc_html(get_the_date('', $post_id));
                } elseif ($type === 'title') {
                    echo esc_html(get_the_title($post_id));
                } else {
                    echo '—';
                }
            }
        }, 10, 2);

        add_filter("manage_edit-{$pt}_sortable_columns", function($sortable) use ($cols) {
            foreach ($cols as $c) {
                if (!empty($c['sortable'])) $sortable[$c['key']] = $c['key'];
            }
            return $sortable;
        });

        add_action('restrict_manage_posts', function() use ($cols, $pt) {
            global $typenow;
            if ($typenow !== $pt) return;
            foreach ($cols as $c) {
                if (empty($c['filterable'])) continue;
                $key = $c['key'];
                $val = isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : '';
                if ($c['type'] === 'taxonomy') {
                    $tax = $c['taxonomy'];
                    wp_dropdown_categories([
                        'show_option_all' => __('All', 'admino'),
                        'taxonomy' => $tax,
                        'name' => $key,
                        'orderby' => 'name',
                        'selected' => $val,
                        'hierarchical' => true,
                        'show_count' => false,
                        'hide_empty' => false
                    ]);
                } else {
                    echo '<input type="text" name="'.esc_attr($key).'" value="'.esc_attr($val).'" placeholder="'.esc_attr($c['label']).'" />';
                }
            }
        });

        add_action('pre_get_posts', function($q) use ($cols, $pt) {
            if (!is_admin() || !$q->is_main_query()) return;
            if ($q->get('post_type') !== $pt) return;

            $orderby = $q->get('orderby');
            foreach ($cols as $c) {
                $key = $c['key'];
                if ($orderby === $key) {
                    if ($c['type'] === 'meta' || $c['type'] === 'acf') {
                        $mkey = $c['type'] === 'acf' ? admino_array_get($c, 'acf_key', '') : admino_array_get($c, 'meta_key', '');
                        $q->set('meta_key', $mkey);
                        $q->set('orderby', !empty($c['numeric']) ? 'meta_value_num' : 'meta_value');
                    } elseif ($c['type'] === 'taxonomy') {
                        // Sort by first term name (fallback)
                        $q->set('orderby', 'title'); // یا بگذار همون پیش‌فرض بمونه
                    }
                }

                if (!empty($c['filterable'])) {
                    if ($c['type'] === 'taxonomy') {
                        $val = isset($_GET[$key]) ? intval($_GET[$key]) : 0;
                        if ($val) {
                            $tax = admino_array_get($c, 'taxonomy', '');
                            $tax_q = (array)$q->get('tax_query');
                            $tax_q[] = [
                                'taxonomy' => $tax,
                                'field'    => 'term_id',
                                'terms'    => [$val],
                            ];
                            $q->set('tax_query', $tax_q);
                        }
                    } else {
                        $val = isset($_GET[$key]) ? sanitize_text_field($_GET[$key]) : '';
                        if ($val !== '') {
                            $meta_q = (array)$q->get('meta_query');
                            $meta_q[] = [
                                'key'     => admino_array_get($c, $c['type'] === 'acf' ? 'acf_key' : 'meta_key', ''),
                                'value'   => $val,
                                'compare' => 'LIKE',
                            ];
                            $q->set('meta_query', $meta_q);
                        }
                    }
                }
            }
        });
    }

    foreach ($taxes as $tax => $cols) {
        add_filter("manage_edit-{$tax}_columns", function($columns) use ($cols) {
            foreach ($cols as $c) $columns[$c['key']] = esc_html($c['label']);
            return $columns;
        });

        add_filter("manage_{$tax}_custom_column", function($out, $column, $term_id) use ($cols) {
            foreach ($cols as $c) {
                if ($column !== $c['key']) continue;
                $val = get_term_meta($term_id, admino_array_get($c, 'meta_key', ''), true);
                if (is_array($val)) $val = implode(', ', array_map('strval', $val));
                $out = esc_html(mb_strimwidth(wp_strip_all_tags((string)$val), 0, 80, '…'));
            }
            return $out;
        }, 10, 3);
    }
});
