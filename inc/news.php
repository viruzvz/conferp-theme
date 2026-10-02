<?php
/**
 * News module.
 *
 * Functions and helpers used by the CONFERP
 * institutional news module.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register image sizes used by the news module.
 *
 * @return void
 */
function conferp_news_image_sizes() {

	add_image_size(
		'conferp-news-card',
		640,
		420,
		true
	);
}
add_action( 'after_setup_theme', 'conferp_news_image_sizes' );

/**
 * Get the primary category of a news post.
 *
 * For now, the first assigned WordPress category
 * is used as the primary category.
 *
 * @param int|null $post_id Post ID.
 *
 * @return WP_Term|null
 */
function conferp_get_news_primary_category( $post_id = null ) {

	$post_id = $post_id ?: get_the_ID();

	$categories = get_the_category( $post_id );

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return null;
	}

	return $categories[0];
}

/**
 * Get the news excerpt.
 *
 * Uses the manual WordPress excerpt when available.
 * Otherwise generates a short excerpt from the post content.
 *
 * Images, shortcodes and HTML are removed so that content
 * inserted inside the post body is never rendered in the
 * news listing.
 *
 * @param int      $length  Number of words.
 * @param int|null $post_id Post ID.
 *
 * @return string
 */
function conferp_get_news_excerpt( $length = 24, $post_id = null ) {

	$post_id = $post_id ?: get_the_ID();

	$manual_excerpt = get_post_field(
		'post_excerpt',
		$post_id
	);

	if ( ! empty( $manual_excerpt ) ) {

		return wp_trim_words(
			wp_strip_all_tags( $manual_excerpt ),
			$length,
			'…'
		);
	}

	$content = get_post_field(
		'post_content',
		$post_id
	);

	if ( empty( $content ) ) {
		return '';
	}

	$content = strip_shortcodes( $content );
	$content = wp_strip_all_tags( $content, true );
	$content = preg_replace( '/\s+/', ' ', $content );
	$content = trim( $content );

	if ( empty( $content ) ) {
		return '';
	}

	return wp_trim_words(
		$content,
		$length,
		'…'
	);
}
