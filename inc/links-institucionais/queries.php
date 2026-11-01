<?php
/** Consulta pública de cards ativos, com links e imagens resolvidos. */
if (!defined('ABSPATH')) { exit; }

function conferp_links_institucionais_get($limit = 12) {
    $items = get_posts([
        'post_type' => 'conferp_link',
        'post_status' => 'publish',
        'posts_per_page' => max(1, min(50, absint($limit))),
        'orderby' => ['menu_order' => 'ASC', 'date' => 'DESC'],
        'meta_query' => [['relation' => 'OR',
            ['key' => '_conferp_link_active', 'value' => '1'],
            ['key' => '_conferp_link_active', 'compare' => 'NOT EXISTS'],
        ]],
    ]);
    $result = [];
    foreach ($items as $item) {
        $type = get_post_meta($item->ID, '_conferp_link_type', true) ?: 'page';
        $target_id = (int) get_post_meta($item->ID, '_conferp_link_target_id', true);
        if ($type === 'external') {
            $url = esc_url(get_post_meta($item->ID, '_conferp_link_url', true), ['http', 'https']);
        } elseif (in_array($type, ['page', 'post'], true) && $target_id && get_post_type($target_id) === $type && get_post_status($target_id) === 'publish') {
            $url = get_permalink($target_id);
        } else {
            continue;
        }
        if (!$url) { continue; }
        $image_id = get_post_thumbnail_id($item->ID);
        if (!$image_id && $type !== 'external') { $image_id = get_post_thumbnail_id($target_id); }
        $result[] = [
            'id' => $item->ID,
            'title' => get_the_title($item->ID),
            'description' => get_post_meta($item->ID, '_conferp_link_description', true),
            'url' => $url,
            'image_id' => (int) $image_id,
            'new_tab' => get_post_meta($item->ID, '_conferp_link_new_tab', true) === '1',
            'type' => $type,
        ];
    }
    return $result;
}
