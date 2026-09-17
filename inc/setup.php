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
	 * ======================================================
	 * Translations
	 * ======================================================
	 *
	 * Make the theme available for translation.
	 */

	load_theme_textdomain(
		'conferp',
		get_template_directory() . '/languages'
	);


	/*
	 * ======================================================
	 * Document Title
	 * ======================================================
	 *
	 * Let WordPress manage the document <title>.
	 *
	 * SEO plugins can safely filter and manage the title
	 * without the theme generating a duplicate <title> tag.
	 */

	add_theme_support( 'title-tag' );


	/*
	 * ======================================================
	 * Featured Images
	 * ======================================================
	 */

	add_theme_support( 'post-thumbnails' );


	/*
	 * ======================================================
	 * Institutional Custom Logo
	 * ======================================================
	 *
	 * Each CONFERP / CONRERP installation can configure
	 * its own institutional logo.
	 *
	 * This does not affect the fixed Federal CONFERP
	 * identity used by global theme components.
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
	 * ======================================================
	 * HTML5
	 * ======================================================
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
	 * ======================================================
	 * Feed Links
	 * ======================================================
	 *
	 * WordPress automatically adds RSS feed links
	 * to the document <head>.
	 */

	add_theme_support( 'automatic-feed-links' );


	/*
	 * ======================================================
	 * Responsive Embeds
	 * ======================================================
	 */

	add_theme_support( 'responsive-embeds' );


	/*
	 * ======================================================
	 * Gutenberg Wide / Full Alignment
	 * ======================================================
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

add_action(
	'after_setup_theme',
	'conferp_theme_setup'
);