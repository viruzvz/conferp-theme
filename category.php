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

	<div class="container">

		<header class="news-page__header">

			<div class="news-page__header-inner">

				<span class="news-page__eyebrow">
					<?php esc_html_e( 'Notícias', 'conferp' ); ?>
				</span>

				<h1 class="news-page__title">
					<?php single_cat_title(); ?>
				</h1>

			</div>

		</header>


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