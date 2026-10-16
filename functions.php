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

/**
 * News posts.
 */
require_once get_template_directory() . '/inc/news.php';

/**
 * Library posts.
 */
require_once get_template_directory() . '/inc/biblioteca.php';

/**
 * Breadcrumbs template.
 */
require_once get_template_directory() . '/inc/breadcrumb.php';

/**
 * Atas e Reuniões posts.
 */
require_once get_template_directory() . '/inc/atas-reunioes.php';

/**
 * Gestão automática Atas e Reuniões.
 */
require_once get_template_directory() . '/inc/atas-cadastro.php';

/**
 * Gestão automática Público Atas e Reuniões.
 */
require_once get_template_directory() . '/inc/atas-publico.php';

/**
 * CPT Transparância.
 */
require_once get_template_directory() . '/inc/admin/transparencia-menu.php';
/**
 * Document head.
 *
 * Handles theme-level head defaults such as:
 *
 * - Default CONFERP favicon fallback
 * - Browser theme color
 *
 * SEO metadata remains the responsibility of WordPress
 * and the installed SEO plugin.
 */
require_once get_template_directory() . '/inc/head.php';