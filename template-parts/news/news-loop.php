<?php
/**
 * News loop.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( have_posts() ) : ?>

	<div class="news-grid">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part(
				'template-parts/news/news-card'
			);

		endwhile;
		?>

	</div>

<?php else : ?>

	<?php
	get_template_part(
		'template-parts/news/news-empty'
	);
	?>

<?php endif; ?>