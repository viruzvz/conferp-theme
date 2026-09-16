<?php
/**
 * Header
 *
 * Estrutura principal do cabeçalho do tema.
 *
 * A Utility Bar é um componente global do Sistema
 * CONFERP/CONRERPs e permanece fora do cabeçalho
 * institucional dinâmico.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>

<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<meta
		name="viewport"
		content="width=device-width, initial-scale=1"
	>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>


<?php
/**
 * ==========================================================
 * Global Utility Bar
 * ==========================================================
 *
 * Componente federal global.
 *
 * Compartilhado pelo CONFERP e por todos os CONRERPs.
 * Sua identidade e estrutura não dependem da instalação
 * regional do WordPress.
 */
get_template_part(
	'template-parts/global/utility-bar'
);
?>


<a
	class="skip-link screen-reader-text"
	href="#primary"
>
	<?php esc_html_e( 'Ir para o conteúdo', 'conferp' ); ?>
</a>


<?php
/**
 * ==========================================================
 * Institutional Site Header
 * ==========================================================
 *
 * Cabeçalho da instituição atual.
 *
 * Este componente poderá representar tanto o CONFERP
 * quanto qualquer CONRERP que utilize o tema.
 *
 * Internamente será responsável por:
 *
 * - Branding institucional;
 * - Redes sociais;
 * - Navegação principal;
 * - Navegação mobile.
 */
get_template_part(
	'template-parts/header/site-header'
);
?>