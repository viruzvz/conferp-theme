<?php
/**
 * Footer
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;


/**
 * Institutional Footer.
 */
get_template_part(
	'template-parts/footer/site-footer'
);


/**
 * Global CONFERP Subfooter.
 */
get_template_part(
	'template-parts/global/subfooter'
);
?>

<?php wp_footer(); ?>

</body>
</html>