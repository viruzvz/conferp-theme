<?php
/**
 * CONFERP Theme functions and definitions.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 */
require_once get_template_directory() . '/inc/setup.php';

/**
 * Styles and scripts.
 */
require_once get_template_directory() . '/inc/enqueue.php';

/**
 * Navigation menus.
 */
require_once get_template_directory() . '/inc/menus.php';

/**
 * Theme customizer.
 */
require_once get_template_directory() . '/inc/customizer.php';

/**
 * Helper functions.
 */
require_once get_template_directory() . '/inc/helpers.php';