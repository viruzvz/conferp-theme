<?php
/**
 * Single post template.
 *
 * Used for individual institutional News posts.
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
	class="site-main news-single"
>

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<article
			id="post-<?php the_ID(); ?>"
			<?php post_class( 'news-single__article' ); ?>
		>

			<header class="news-single__header">

				<div class="news-single__header-inner">
					<?php conferp_breadcrumb(); ?>
					<h1 class="news-single__title">
						<?php the_title(); ?>
					</h1>

				</div>

			</header>


			<div class="news-single__container">

				<?php if ( has_post_thumbnail() ) : ?>

					<figure class="news-single__featured-image">

						<?php
						the_post_thumbnail(
							'full',
							array(
								'loading' => 'eager',
								'alt'     => the_title_attribute(
									array(
										'echo' => false,
									)
								),
							)
						);
						?>

					</figure>

				<?php endif; ?>


				<div class="news-single__meta">

					<div class="news-single__meta-item">

						<span
							class="news-single__meta-icon"
							aria-hidden="true"
						>
							<svg
								viewBox="0 0 24 24"
								fill="none"
								stroke="currentColor"
								stroke-width="1.8"
								stroke-linecap="round"
								stroke-linejoin="round"
							>
								<rect
									x="3"
									y="5"
									width="18"
									height="16"
									rx="2"
								/>
								<path d="M16 3v4M8 3v4M3 11h18"/>
							</svg>
						</span>

						<time
							datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"
						>
							<?php
							printf(
								/* translators: %s: post publication date. */
								esc_html__( 'Publicado em %s', 'conferp' ),
								esc_html( get_the_date() )
							);
							?>
						</time>

					</div>


					<div class="news-single__meta-item">

						<span
							class="news-single__meta-icon"
							aria-hidden="true"
						>
							<svg
								viewBox="0 0 24 24"
								fill="none"
								stroke="currentColor"
								stroke-width="1.8"
								stroke-linecap="round"
								stroke-linejoin="round"
							>
								<circle
									cx="12"
									cy="8"
									r="4"
								/>
								<path d="M4 21a8 8 0 0 1 16 0"/>
							</svg>
						</span>

						<span>
							<?php esc_html_e( 'Publicado por:', 'conferp' ); ?>

							<a
								href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
							>
								<?php echo esc_html( get_the_author() ); ?>
							</a>
						</span>

					</div>


					<?php if ( has_category() ) : ?>

						<div class="news-single__meta-item">

							<span
								class="news-single__meta-icon"
								aria-hidden="true"
							>
								<svg
									viewBox="0 0 24 24"
									fill="none"
									stroke="currentColor"
									stroke-width="1.8"
									stroke-linecap="round"
									stroke-linejoin="round"
								>
									<path
										d="M20.59 13.41 11 3.83V3H4v7h.83l9.58 9.59a2 2 0 0 0 2.82 0l3.36-3.36a2 2 0 0 0 0-2.82Z"
									/>
									<circle
										cx="7.5"
										cy="6.5"
										r=".8"
										fill="currentColor"
										stroke="none"
									/>
								</svg>
							</span>

							<div class="news-single__meta-categories">

								<span class="news-single__meta-label">
									<?php esc_html_e( 'Categorias:', 'conferp' ); ?>
								</span>

								<?php the_category( ', ' ); ?>

							</div>

						</div>

					<?php endif; ?>

				</div>


				<div class="news-single__content">

					<?php the_content(); ?>


					<?php
					wp_link_pages(
						array(
							'before' => '<nav class="news-single__page-links" aria-label="' .
								esc_attr__( 'Páginas da notícia', 'conferp' ) .
								'">',
							'after'  => '</nav>',
						)
					);
					?>

				</div>


				<nav
					class="news-single__navigation"
					aria-label="<?php esc_attr_e( 'Navegação entre notícias', 'conferp' ); ?>"
				>

					<div class="news-single__navigation-previous">

						<?php
						previous_post_link(
							'%link',
							'<span class="news-single__navigation-direction">' .
							esc_html__( '← Notícia anterior', 'conferp' ) .
							'</span>' .
							'<span class="news-single__navigation-title">%title</span>'
						);
						?>

					</div>


					<div class="news-single__navigation-next">

						<?php
						next_post_link(
							'%link',
							'<span class="news-single__navigation-direction">' .
							esc_html__( 'Próxima notícia →', 'conferp' ) .
							'</span>' .
							'<span class="news-single__navigation-title">%title</span>'
						);
						?>

					</div>

				</nav>


				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>

			</div>

		</article>

	<?php endwhile; ?>

</main>

<?php
get_footer();