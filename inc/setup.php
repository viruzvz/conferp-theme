<?php
/**
 * Configuração base do tema.
 *
 * @package CONFERP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function conferp_theme_setup() {
    load_theme_textdomain( 'conferp', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 120,
        'width'       => 420,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    register_nav_menus( array(
        'primary' => __( 'Menu principal', 'conferp' ),
        'footer'  => __( 'Menu do rodapé', 'conferp' ),
    ) );
}
add_action( 'after_setup_theme', 'conferp_theme_setup' );
