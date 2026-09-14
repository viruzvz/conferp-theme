<?php
/**
 * Navigation menus.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme navigation menus.
 *
 * @return void
 */
function conferp_register_menus() {

    register_nav_menus(
        array(
            'primary' => esc_html__(
                'Menu Principal',
                'conferp'
            ),

            'footer' => esc_html__(
                'Menu do Rodapé',
                'conferp'
            ),
        )
    );
}
add_action( 'after_setup_theme', 'conferp_register_menus' );