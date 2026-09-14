<?php
/**
 * Theme Customizer.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 *
 * @return void
 */
function conferp_customize_register( $wp_customize ) {

    /*
     * Theme customizer settings
     * will be registered here.
     */

}
add_action( 'customize_register', 'conferp_customize_register' );