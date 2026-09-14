<?php
/**
 * Theme setup.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sets up theme defaults and registers support
 * for various WordPress features.
 *
 * @return void
 */
function conferp_theme_setup() {

    /*
     * Make theme available for translation.
     */
    load_theme_textdomain(
        'conferp',
        get_template_directory() . '/languages'
    );

    /*
     * Let WordPress manage the document title.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable featured images.
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Enable custom logo.
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 200,
            'width'       => 500,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );

    /*
     * Enable HTML5 markup.
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    /*
     * Enable automatic feed links.
     */
    add_theme_support( 'automatic-feed-links' );

    /*
     * Gutenberg responsive embeds.
     */
    add_theme_support( 'responsive-embeds' );

    /*
     * Gutenberg wide/full alignment.
     */
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'conferp_theme_setup' );