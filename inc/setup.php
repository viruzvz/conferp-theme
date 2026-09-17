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


	/*
	 * ======================================================
	 * Navigation Menus
	 * ======================================================
	 *
	 * Menu locations are intentionally generic.
	 *
	 * This allows each CONFERP / CONRERP installation
	 * to define its own footer structure without the
	 * theme being tied to names such as:
	 *
	 * - Acesse
	 * - Links rápidos
	 * - Políticas
	 *
	 * The assigned menu name will be used as the
	 * visible title of each footer column.
	 */
	register_nav_menus(
		array(
			'primary'  => __( 'Menu Principal', 'conferp' ),
			'footer_1' => __( 'Rodapé — Coluna 1', 'conferp' ),
			'footer_2' => __( 'Rodapé — Coluna 2', 'conferp' ),
			'footer_3' => __( 'Rodapé — Coluna 3', 'conferp' ),
		)
	);
}

add_action( 'after_setup_theme', 'conferp_theme_setup' );