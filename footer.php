<?php
/**
 * The template for displaying the footer.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>


<?php
/**
 * Institutional Footer.
 */
get_template_part(
	'template-parts/footer/site-footer'
);


/**
 * Federal Global Subfooter.
 */
get_template_part(
	'template-parts/global/subfooter'
);


/**
 * Federal Global Accessibility Sticky.
 *
 * Floating accessibility controls available
 * across the entire website.
 */
get_template_part(
	'template-parts/global/accessibility-sticky'
);


/**
 * Federal Global Back to Top.
 *
 * Floating button used to return the page
 * to the top.
 */
get_template_part(
	'template-parts/global/back-to-top'
);
?>


<?php wp_footer(); ?>

</body>
</html>