<?php
/**
 * Header
 *
 * Main document header structure.
 *
 * The Utility Bar belongs to the Federal Global layer
 * shared across CONFERP and all CONRERP installations.
 *
 * The institutional header remains independent and can
 * represent either CONFERP or a CONRERP installation.
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
 * Federal global component shared across CONFERP
 * and all CONRERP installations.
 *
 * Its identity and structure are independent from
 * institutional theme customization.
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
 * Header of the current institution.
 *
 * This component may represent either CONFERP or any
 * CONRERP installation using the theme.
 *
 * Internally responsible for:
 *
 * - Institutional branding;
 * - Social networks;
 * - Primary navigation;
 * - Mobile navigation.
 */
get_template_part(
	'template-parts/header/site-header'
);
?>