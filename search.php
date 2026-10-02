<?php
/**
 * Search results template.
 *
 * Currently handles the institutional News search.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$search_query = get_search_query();

global $wp_query;

$results_count = isset( $wp_query->found_posts )
	? (int) $wp_query->found_posts
	: 0;
?>

<main
	id="primary"
	class="site-main news-page news-search-results"
>

	<div class="container">

		<header class="news-page__header">

			<div class="news-page__header-inner">

				<span class="news-page__eyebrow">
					<?php esc_html_e( 'Notícias', 'conferp' ); ?>
				</span>

				<h1 class="news-page__title">
					<?php esc_html_e( 'Resultados da busca', 'conferp' ); ?>
				</h1>

			</div>

		</header>


		<div class="news-layout">

			<div class="news-layout__main">

				<div class="news-results-summary">

					<?php if ( $search_query ) : ?>

						<p class="news-results-summary__label">
							<?php esc_html_e( 'Resultados para:', 'conferp' ); ?>
						</p>

						<p class="news-results-summary__term">
							&ldquo;<?php echo esc_html( $search_query ); ?>&rdquo;
						</p>

					<?php endif; ?>


					<p class="news-results-summary__count">

						<?php
						printf(
							/* translators: %s: number of news results. */
							esc_html(
								_n(
									'%s notícia encontrada',
									'%s notícias encontradas',
									$results_count,
									'conferp'
								)
							),
							esc_html(
								number_format_i18n( $results_count )
							)
						);
						?>

					</p>


					<div class="news-results-summary__actions">

						<button
							class="news-results-summary__back"
							type="button"
							onclick="history.back();"
						>
							<span aria-hidden="true">
								&larr;
							</span>

							<?php esc_html_e( 'Voltar', 'conferp' ); ?>
						</button>

					</div>

				</div>


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