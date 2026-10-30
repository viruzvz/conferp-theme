<?php
/**
 * Elementor Pro integration for the existing CONFERP page heading.
 *
 * @package conferp
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'conferp_page_heading_shortcode' ) ) {
    function conferp_page_heading_shortcode( $atts = array() ) {
        $atts = shortcode_atts(
            array(
                'title'       => '',
                'highlight'   => '',
                'description' => '',
            ),
            $atts,
            'conferp_page_heading'
        );

        $title       = sanitize_text_field( $atts['title'] );
        $highlight   = sanitize_text_field( $atts['highlight'] );
        $description = sanitize_text_field( $atts['description'] );

        if ( '' === $title && '' === $highlight ) {
            $title = get_the_title( get_queried_object_id() );
        }

        if ( '' === $title && '' === $highlight ) {
            return '';
        }

        ob_start();
        get_template_part(
            'template-parts/global/page-heading',
            null,
            array(
                'title'       => $title,
                'highlight'   => $highlight,
                'description' => $description,
            )
        );
        return ob_get_clean();
    }
}
add_shortcode( 'conferp_page_heading', 'conferp_page_heading_shortcode' );
