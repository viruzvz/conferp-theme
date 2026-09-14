<?php
/**
 * Theme helper functions.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Output an inline SVG icon from the theme icon library.
 *
 * SVG files are stored in:
 * /assets/img/icons/
 *
 * All icons used here are controlled by the theme and must
 * be normalized to use currentColor whenever their color
 * should be controlled through CSS.
 *
 * Example:
 *
 * conferp_icon( 'chat' );
 *
 * conferp_icon(
 *     'transparency',
 *     array(
 *         'class' => 'my-custom-class',
 *     )
 * );
 *
 * @param string $icon Icon identifier without .svg.
 * @param array  $args Optional SVG attributes.
 *
 * @return void
 */
function conferp_icon( $icon, $args = array() ) {

	/**
	 * Whitelist of SVG icons available in the theme.
	 *
	 * This prevents arbitrary file access.
	 */
	$icons = array(
		'accessibility'    => 'accessibility.svg',
		'back-to-top'      => 'back-to-top.svg',
		'chat'             => 'chat.svg',
		'facebook'         => 'facebook.svg',
		'instagram'        => 'instagram.svg',
		'linkedin'         => 'linkedin.svg',
		'menu'             => 'menu.svg',
		'transparency'     => 'transparency.svg',
		'universal-access' => 'universal-access.svg',
		'utility-symbol'   => 'utility-symbol.svg',
		'vlibras'          => 'vlibras.svg',
		'youtube'          => 'youtube.svg',
	);


	if ( ! isset( $icons[ $icon ] ) ) {
		return;
	}


	$defaults = array(
		'class'       => '',
		'aria-hidden' => 'true',
		'focusable'   => 'false',
	);

	$args = wp_parse_args(
		$args,
		$defaults
	);


	$svg_path = get_template_directory()
		. '/assets/img/icons/'
		. $icons[ $icon ];


	if ( ! file_exists( $svg_path ) ) {
		return;
	}


	$svg = file_get_contents( $svg_path );

	if ( false === $svg ) {
		return;
	}


	/**
	 * Remove XML declaration when present.
	 */
	$svg = preg_replace(
		'/<\?xml.*?\?>/i',
		'',
		$svg
	);


	/**
	 * Remove DOCTYPE declarations.
	 */
	$svg = preg_replace(
		'/<!DOCTYPE.*?>/i',
		'',
		$svg
	);


	/**
	 * Build attributes added to the opening SVG tag.
	 */
	$attributes = '';


	if ( ! empty( $args['class'] ) ) {

		$attributes .= sprintf(
			' class="%s"',
			esc_attr( $args['class'] )
		);
	}


	$attributes .= sprintf(
		' aria-hidden="%s"',
		esc_attr( $args['aria-hidden'] )
	);


	$attributes .= sprintf(
		' focusable="%s"',
		esc_attr( $args['focusable'] )
	);


	/**
	 * Inject our attributes into the opening <svg>.
	 */
	$svg = preg_replace(
		'/<svg\b/',
		'<svg' . $attributes,
		$svg,
		1
	);


	/**
	 * Allowed SVG markup.
	 *
	 * The SVG library is part of the theme, but we still
	 * sanitize the output before printing.
	 */
	$allowed_svg = array(

		'svg' => array(
			'class'       => true,
			'xmlns'       => true,
			'viewbox'     => true,
			'width'       => true,
			'height'      => true,
			'fill'        => true,
			'stroke'      => true,
			'role'        => true,
			'aria-hidden' => true,
			'focusable'   => true,
		),

		'g' => array(
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
			'transform'    => true,
			'clip-path'    => true,
		),

		'path' => array(
			'd'               => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
			'fill-rule'       => true,
			'clip-rule'       => true,
			'transform'       => true,
		),

		'circle' => array(
			'cx'           => true,
			'cy'           => true,
			'r'            => true,
			'fill'         => true,
			'stroke'       => true,
			'stroke-width' => true,
		),

		'ellipse' => array(
			'cx' => true,
			'cy' => true,
			'rx' => true,
			'ry' => true,
		),

		'rect' => array(
			'x'      => true,
			'y'      => true,
			'width'  => true,
			'height' => true,
			'rx'     => true,
			'ry'     => true,
			'fill'   => true,
		),

		'line' => array(
			'x1' => true,
			'y1' => true,
			'x2' => true,
			'y2' => true,
		),

		'polyline' => array(
			'points' => true,
		),

		'polygon' => array(
			'points' => true,
		),

		'defs' => array(),

		'clippath' => array(
			'id' => true,
		),

		'mask' => array(
			'id' => true,
		),

		'title' => array(),
	);


	echo wp_kses(
		$svg,
		$allowed_svg
	);
}