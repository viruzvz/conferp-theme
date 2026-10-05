<?php
/**
 * Biblioteca loop.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$biblioteca_query = isset( $args['query'] )
	&& $args['query'] instanceof WP_Query
	? $args['query']
	: null;

if ( ! $biblioteca_query ) {
	return;
}
?>

<?php if ( $biblioteca_query->have_posts() ) : ?>

	<div class="biblioteca-grid">

		<?php
		while ( $biblioteca_query->have_posts() ) :
			$biblioteca_query->the_post();

			get_template_part(
				'template-parts/biblioteca/biblioteca-card'
			);

		endwhile;
		?>

	</div>


	<?php
	get_template_part(
		'template-parts/biblioteca/biblioteca-pagination',
		null,
		array(
			'query' => $biblioteca_query,
		)
	);
	?>

<?php else : ?>

	<?php
	get_template_part(
		'template-parts/biblioteca/biblioteca-empty'
	);
	?>

<?php endif; ?>