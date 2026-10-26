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
 * Gestão automática Leis e Decretos.
 */
require_once get_template_directory() . '/inc/leis-decretos.php';

/**
 * Gestão automática Resoluções Normativas.
 */
require_once get_template_directory() . '/inc/resolucoes-normativas.php';

/** Cadastro de Portarias. */
require_once get_template_directory() . '/inc/portarias.php';
require_once get_template_directory() . '/inc/portarias-anexos.php';

/**
 * Pesquisa exclusiva, filtros, consulta paginada e ordenação pela data do documento jurídico.
 */
require_once get_template_directory() . '/inc/leis-decretos-public.php';

/**
 * Pesquisa exclusiva, filtros, consulta paginada e ordenação pela data do documento jurídico.
 */
require_once get_template_directory() . '/inc/resolucoes-normativas-public.php';

/**
 * CPT Transparância (Aqui cria o menu Transparência no dashboard).
 */
require_once get_template_directory() . '/inc/admin/transparencia-menu.php';

/**
 * CPT Legislação (Aqui cria o menu Legislação no dashboard).
 */
require_once get_template_directory() . '/inc/legislacao.php';
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
/** Consulta pública exclusiva de Portarias. */
require_once get_template_directory() . '/inc/portarias-public.php';

/** Cadastro de Instruções Normativas e anexos. */
require_once get_template_directory() . '/inc/instrucoes-normativas.php';
require_once get_template_directory() . '/inc/instrucoes-normativas-anexos.php';
require_once get_template_directory() . '/inc/instrucoes-normativas-download.php';
require_once get_template_directory() . '/inc/instrucoes-normativas-public.php';

/** Cadastro administrativo de Contratos. */
require_once get_template_directory() . '/inc/contratos.php';
require_once get_template_directory() . '/inc/contratos-download.php';
require_once get_template_directory() . '/inc/contratos-public.php';
