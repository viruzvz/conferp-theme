<?php
/** Cadastro editorial dos cards de Links Institucionais. */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    register_post_type('conferp_link', [
        'labels' => [
            'name' => 'Links Institucionais',
            'singular_name' => 'Link Institucional',
            'add_new_item' => 'Adicionar link institucional',
            'edit_item' => 'Editar link institucional',
            'menu_name' => 'Links Institucionais',
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'menu_icon' => 'dashicons-admin-links',
        'supports' => ['title', 'thumbnail', 'page-attributes'],
        'capability_type' => 'post',
        'map_meta_cap' => true,
    ]);
});
