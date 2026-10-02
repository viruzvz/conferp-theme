<?php
/**
 * News sidebar.
 *
 * Sidebar used by the institutional news module.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="news-sidebar">

	<?php
	get_template_part(
		'template-parts/news/news-search-form'
	);
	?>

	<?php
	get_template_part(
		'template-parts/news/news-categories'
	);
	?>

</div>