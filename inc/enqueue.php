<?php
/**
 * Enqueue scripts and styles.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme assets.
 *
 * @return void
 */
function conferp_enqueue_assets() {

	$theme_version = wp_get_theme()->get( 'Version' );

	$theme_css_path = get_template_directory() . '/assets/css/theme.css';
	$theme_js_path  = get_template_directory() . '/assets/js/theme.js';


	/**
	 * Main stylesheet.
	 *
	 * Bootstrap + CONFERP styles are compiled
	 * through Sass into theme.css.
	 */
	wp_enqueue_style(
		'conferp-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		array(),
		file_exists( $theme_css_path )
			? filemtime( $theme_css_path )
			: $theme_version
	);


	/**
	 * Bootstrap 5 Bundle.
	 *
	 * Includes Popper.
	 */
	wp_enqueue_script(
		'bootstrap',
		get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js',
		array(),
		'5.3.8',
		true
	);


	/**
	 * CONFERP theme JavaScript.
	 */
	wp_enqueue_script(
		'conferp-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		array( 'bootstrap' ),
		file_exists( $theme_js_path )
			? filemtime( $theme_js_path )
			: $theme_version,
		true
	);
}

add_action( 'wp_enqueue_scripts', 'conferp_enqueue_assets' );