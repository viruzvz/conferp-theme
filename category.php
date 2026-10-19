<?php
/**
 * News category archive.
 *
 * Categories are used exclusively by the
 * institutional News module.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$category = get_queried_object();
?>

<main
	id="primary"
	class="site-main news-page news-category"
>

	<?php
	get_template_part(
		'template-parts/global/page-heading',
		null,
		array(
			'title' => single_cat_title( '', false ),
		)
	);
	?>

	<div class="container">

		<div class="news-layout">

			<div class="news-layout__main">

				<?php if ( ! empty( $category->description ) ) : ?>

					<div class="news-category__description">
						<?php echo wp_kses_post( wpautop( $category->description ) ); ?>
					</div>

				<?php endif; ?>


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