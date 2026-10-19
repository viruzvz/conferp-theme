<?php
/**
 * News index template.
 *
 * Used as the WordPress posts page for the
 * institutional News module.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main
	id="primary"
	class="site-main news-page"
>

	<?php
	get_template_part(
		'template-parts/global/page-heading',
		null,
		array(
			'title' => __( 'Notícias', 'conferp' ),
		)
	);
	?>

	<div class="container">

		<div class="news-layout">

			<div class="news-layout__main">

				<?php
				get_template_part(
					'template-parts/news/news-loop'
				);
				?>

			</div>


			<aside
				class="news-layout__sidebar"
				aria-label="<?php esc_attr_e( 'Filtros de notícias', 'conferp' ); ?>"
			>

				<?php
				get_template_part(
					'template-parts/news/news-sidebar'
				);
				?>

			</aside>

		</div>

	</div>

</main>

<?php
get_footer();