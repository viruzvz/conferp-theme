<?php
/**
 * Biblioteca pagination.
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

$current_page = max(
	1,
	get_query_var( 'paged' ),
	get_query_var( 'page' )
);

$total_pages = (int) $biblioteca_query->max_num_pages;

if ( $total_pages <= 1 ) {
	return;
}

$search_term          = conferp_get_biblioteca_search_term();
$selected_collection = conferp_get_biblioteca_selected_collection();

$add_args = array();

if ( $search_term ) {
	$add_args['biblioteca_s'] = $search_term;
}

if ( $selected_collection ) {
	$add_args['colecao'] = $selected_collection;
}

$pagination = paginate_links(
	array(
		'base'      => str_replace(
			999999999,
			'%#%',
			esc_url(
				get_pagenum_link( 999999999 )
			)
		),
		'format'    => '?paged=%#%',
		'current'   => $current_page,
		'total'     => $total_pages,
		'mid_size'  => 2,
		'end_size'  => 1,

		'prev_text' => sprintf(
			'<span aria-hidden="true">&larr;</span><span class="biblioteca-pagination__label">%s</span>',
			esc_html__( 'Anterior', 'conferp' )
		),

		'next_text' => sprintf(
			'<span class="biblioteca-pagination__label">%s</span><span aria-hidden="true">&rarr;</span>',
			esc_html__( 'Próxima', 'conferp' )
		),

		'add_args'  => $add_args,
		'type'      => 'array',
	)
);

if ( empty( $pagination ) ) {
	return;
}
?>

<nav
	class="biblioteca-pagination"
	aria-label="<?php esc_attr_e( 'Navegação entre páginas da Biblioteca', 'conferp' ); ?>"
>

	<ul class="biblioteca-pagination__list">

		<?php foreach ( $pagination as $page_link ) : ?>

			<li class="biblioteca-pagination__item">
				<?php echo wp_kses_post( $page_link ); ?>
			</li>

		<?php endforeach; ?>

	</ul>

</nav>