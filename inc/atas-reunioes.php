<?php
/**
 * Atas e Reuniões: content registration only.
 * @package conferp
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_register_atas_reunioes() {
    register_post_type( 'ata_reuniao', array(
        'labels' => array(
            'name' => __( 'Atas e Reuniões', 'conferp' ),
            'singular_name' => __( 'Ata', 'conferp' ),
            'menu_name' => __( 'Atas e Reuniões', 'conferp' ),
            'all_items' => __( 'Todas as Atas', 'conferp' ),
            'add_new_item' => __( 'Adicionar nova ata', 'conferp' ),
            'edit_item' => __( 'Editar ata', 'conferp' ),
            'search_items' => __( 'Pesquisar atas', 'conferp' ),
            'not_found' => __( 'Nenhuma ata encontrada.', 'conferp' ),
        ),
        'public' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => 'conferp-transparencia',
        'show_in_rest' => true,
        'show_in_nav_menus' => false,
        'has_archive' => false,
        'rewrite' => false,
        'query_var' => false,
        'supports' => array( 'title', 'revisions' ),
        'capability_type' => 'post',
        'menu_icon' => 'dashicons-media-document',
    ) );

    register_taxonomy( 'ata_tipo', array( 'ata_reuniao' ), array(
        'labels' => array( 'name' => __( 'Tipos de Reunião', 'conferp' ), 'singular_name' => __( 'Tipo de Reunião', 'conferp' ), 'menu_name' => __( 'Tipos de Reunião', 'conferp' ) ),
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => false,
    ) );

    register_taxonomy( 'ata_assunto', array( 'ata_reuniao' ), array(
        'labels' => array( 'name' => __( 'Assuntos', 'conferp' ), 'singular_name' => __( 'Assunto', 'conferp' ), 'menu_name' => __( 'Assuntos', 'conferp' ) ),
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => false,
    ) );

    register_taxonomy( 'ata_gestao', array( 'ata_reuniao' ), array(
        'labels' => array( 'name' => __( 'Mandatos', 'conferp' ), 'singular_name' => __( 'Mandato', 'conferp' ), 'menu_name' => __( 'Mandatos', 'conferp' ) ),
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => false,
    ) );
}
add_action( 'init', 'conferp_register_atas_reunioes' );
