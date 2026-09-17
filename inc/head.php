<?php
/**
 * Document head configuration.
 *
 * Provides theme-level document head defaults without
 * conflicting with WordPress or SEO plugins.
 *
 * SEO metadata such as:
 *
 * - Document title
 * - Meta description
 * - Robots directives
 * - Canonical URL
 * - Open Graph
 * - Twitter Cards
 * - Schema / JSON-LD
 *
 * must be managed by WordPress and/or the installed
 * SEO plugin.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * Output the default CONFERP favicon.
 *
 * The favicon bundled with the theme acts as a fallback.
 *
 * When a Site Icon is configured in WordPress, WordPress
 * becomes responsible for generating the appropriate
 * favicon and device icon markup.
 *
 * This allows each CONFERP / CONRERP installation to
 * configure its own institutional Site Icon without
 * modifying the theme.
 *
 * @return void
 */
function conferp_output_default_favicon() {

	/*
	 * WordPress Site Icon takes priority.
	 */
	if ( has_site_icon() ) {
		return;
	}


	$favicon_path =
		get_template_directory()
		. '/assets/img/global/favicon.svg';

	$favicon_url =
		get_template_directory_uri()
		. '/assets/img/global/favicon.svg';


	/*
	 * Avoid outputting a broken favicon URL if the
	 * fallback asset is unavailable.
	 */
	if ( ! file_exists( $favicon_path ) ) {
		return;
	}

	?>
	<link
		rel="icon"
		href="<?php echo esc_url( $favicon_url ); ?>"
		type="image/svg+xml"
	>
	<?php
}

add_action(
	'wp_head',
	'conferp_output_default_favicon',
	2
);


/**
 * Output basic browser metadata.
 *
 * Search-engine and social metadata intentionally remain
 * outside the theme so they can be managed by the
 * installed SEO plugin.
 *
 * @return void
 */
function conferp_output_basic_head_meta() {

	?>
	<meta
		name="theme-color"
		content="#1E284B"
	>
	<?php
}

add_action(
	'wp_head',
	'conferp_output_basic_head_meta',
	1
);