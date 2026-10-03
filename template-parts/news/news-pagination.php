<?php
/**
 * News pagination.
 *
 * Shared pagination component used by:
 * - News index
 * - News search results
 * - News category archives
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$current_page = max(
	1,
	get_query_var( 'paged' )
);

$total_pages = isset( $wp_query->max_num_pages )
	? (int) $wp_query->max_num_pages
	: 1;


/*
 * Pagination is unnecessary when all results
 * fit on a single page.
 */
if ( $total_pages <= 1 ) {
	return;
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
			'<span aria-hidden="true">&larr;</span><span class="news-pagination__label">%s</span>',
			esc_html__( 'Anterior', 'conferp' )
		),
		'next_text' => sprintf(
			'<span class="news-pagination__label">%s</span><span aria-hidden="true">&rarr;</span>',
			esc_html__( 'Próxima', 'conferp' )
		),
		'type'      => 'array',
	)
);


if ( empty( $pagination ) ) {
	return;
}

?>

<nav
	class="news-pagination"
	aria-label="<?php esc_attr_e( 'Navegação entre páginas de notícias', 'conferp' ); ?>"
>

	<ul class="news-pagination__list">

		<?php foreach ( $pagination as $page_link ) : ?>

			<li class="news-pagination__item">
				<?php
				echo wp_kses_post( $page_link );
				?>
			</li>

		<?php endforeach; ?>

	</ul>

</nav>