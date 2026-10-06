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

	<div class="container">

		<header class="news-page__header">

			<div class="news-page__header-inner">
				<?php conferp_breadcrumb(); ?>
				<h1 class="news-page__title">
					<?php esc_html_e( 'Notícias', 'conferp' ); ?>
				</h1>

			</div>

		</header>


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