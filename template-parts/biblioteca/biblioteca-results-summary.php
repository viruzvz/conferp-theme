<?php
/**
 * Biblioteca results summary.
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

$search_term          = conferp_get_biblioteca_search_term();
$selected_collection = conferp_get_biblioteca_selected_collection();

if ( ! $search_term && ! $selected_collection ) {
	return;
}

$total_results = (int) $biblioteca_query->found_posts;
$archive_url   = get_post_type_archive_link( 'biblioteca' );

$collection = null;

if ( $selected_collection ) {
	$collection = get_term_by(
		'slug',
		$selected_collection,
		'biblioteca_colecao'
	);

	if ( ! $collection || is_wp_error( $collection ) ) {
		$collection = null;
	}
}
?>

<div class="biblioteca-results-summary">

	<div class="biblioteca-results-summary__content">

		<p class="biblioteca-results-summary__label">
			<?php esc_html_e( 'Resultados para:', 'conferp' ); ?>
		</p>


		<h2 class="biblioteca-results-summary__title">

			<?php if ( $search_term && $collection ) : ?>

				<?php
				printf(
					/* translators: 1: search term, 2: collection name. */
					esc_html__( '“%1$s” em “%2$s”', 'conferp' ),
					esc_html( $search_term ),
					esc_html( $collection->name )
				);
				?>

			<?php elseif ( $search_term ) : ?>

				<?php
				printf(
					/* translators: %s: search term. */
					esc_html__( '“%s”', 'conferp' ),
					esc_html( $search_term )
				);
				?>

			<?php elseif ( $collection ) : ?>

				<?php
				printf(
					/* translators: %s: collection name. */
					esc_html__( 'Coleção “%s”', 'conferp' ),
					esc_html( $collection->name )
				);
				?>

			<?php endif; ?>

		</h2>


		<p class="biblioteca-results-summary__count">
			<?php
			printf(
				esc_html(
					_n(
						'%d item encontrado',
						'%d itens encontrados',
						$total_results,
						'conferp'
					)
				),
				$total_results
			);
			?>
		</p>


		<a
			class="biblioteca-results-summary__back"
			href="<?php echo esc_url( $archive_url ); ?>"
		>
			<span aria-hidden="true">&larr;</span>

			<?php esc_html_e( 'Voltar', 'conferp' ); ?>
		</a>

	</div>

</div>