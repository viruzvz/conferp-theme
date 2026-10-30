<?php
/**
 * Cadastro dos Conselhos Regionais.
 *
 * @package Conferp_Theme
 */
defined( 'ABSPATH' ) || exit;

add_action( 'init', 'conferp_register_regional_post_type', 10 );

function conferp_register_regional_post_type() {
    register_post_type( 'conferp_regional', array(
        'labels' => array(
            'name'               => 'Conselhos Regionais',
            'singular_name'      => 'Conselho Regional',
            'menu_name'          => 'Conselhos Regionais',
            'add_new_item'       => 'Adicionar Conselho Regional',
            'edit_item'          => 'Editar Conselho Regional',
            'new_item'           => 'Novo Conselho Regional',
            'view_item'          => 'Visualizar Conselho Regional',
            'search_items'       => 'Pesquisar Conselhos Regionais',
            'not_found'          => 'Nenhum Conselho Regional encontrado',
            'not_found_in_trash' => 'Nenhum Conselho Regional na lixeira',
        ),
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-location-alt',
        'menu_position'      => 26,
        'supports'           => array( 'title' ),
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
        'has_archive'       => false,
        'rewrite'           => false,
        'exclude_from_search'=> true,
    ) );
}
