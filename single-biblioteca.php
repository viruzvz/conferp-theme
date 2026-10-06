<?php
/**
 * Single Biblioteca template.
 *
 * Used for individual Biblioteca items.
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
	class="site-main biblioteca-single"
>

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

		<?php

		/*
		 * Biblioteca collections.
		 */
		$collections = get_the_terms(
			get_the_ID(),
			'biblioteca_colecao'
		);


		/*
		 * Biblioteca intellectual authors.
		 */
		$authors = get_the_terms(
			get_the_ID(),
			'biblioteca_autor'
		);


		/*
		 * Main Biblioteca document.
		 */
		$document_id = conferp_get_biblioteca_document_id(
			get_the_ID()
		);

		$document_url      = '';
		$document_name     = '';
		$document_type     = '';
		$document_size     = '';
		$document_filename = '';


		if ( $document_id ) {

			$document_url = wp_get_attachment_url(
				$document_id
			);

			$document_file = get_attached_file(
				$document_id
			);


			/*
			 * Filename.
			 */
			if ( $document_file ) {

				$document_filename = wp_basename(
					$document_file
				);

			} else {

				$document_filename = get_the_title(
					$document_id
				);
			}


			/*
			 * Prefer the attachment title for the
			 * human-readable document name.
			 */
			$document_name = get_the_title(
				$document_id
			);

			if ( ! $document_name ) {
				$document_name = $document_filename;
			}


			/*
			 * Document extension / type.
			 */
			if ( $document_filename ) {

				$document_extension = pathinfo(
					$document_filename,
					PATHINFO_EXTENSION
				);

				if ( $document_extension ) {
					$document_type = strtoupper(
						$document_extension
					);
				}
			}


			/*
			 * Document size.
			 */
			if (
				$document_file
				&& file_exists( $document_file )
			) {

				$file_size = filesize(
					$document_file
				);

				if ( false !== $file_size ) {

					$document_size = size_format(
						$file_size,
						1
					);
				}
			}
		}

		?>

		<article
			id="post-<?php the_ID(); ?>"
			<?php post_class( 'biblioteca-single__article' ); ?>
		>

			<header class="biblioteca-single__header">

				<div class="biblioteca-single__header-inner">
					<?php conferp_breadcrumb(); ?>
					<h1 class="biblioteca-single__title">
						<?php the_title(); ?>
					</h1>

				</div>

			</header>


			<div class="biblioteca-single__container">


				<?php
				/*
				 * ==================================================
				 * METADATA
				 * Collection + intellectual authors.
				 * ==================================================
				 */
				?>

				<?php
				if (
					( ! empty( $collections ) && ! is_wp_error( $collections ) )
					||
					( ! empty( $authors ) && ! is_wp_error( $authors ) )
				) :
				?>

					<div class="biblioteca-single__meta">


						<?php
						/*
						 * Collection.
						 */
						if (
							! empty( $collections )
							&& ! is_wp_error( $collections )
						) :
							?>

							<div class="biblioteca-single__meta-item">

								<span
									class="biblioteca-single__meta-icon"
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
										<path d="M20.59 13.41 11 3.83V3H4v7h.83l9.58 9.59a2 2 0 0 0 2.82 0l3.36-3.36a2 2 0 0 0 0-2.82Z"/>
										<circle
											cx="7.5"
											cy="6.5"
											r=".8"
											fill="currentColor"
											stroke="none"
										/>
									</svg>
								</span>


								<div class="biblioteca-single__collections">

									<span class="biblioteca-single__meta-label">
										<?php
										echo count( $collections ) > 1
											? esc_html__( 'Coleções:', 'conferp' )
											: esc_html__( 'Coleção:', 'conferp' );
										?>
									</span>


									<?php
									foreach (
										$collections as $index => $collection
									) :
										?>

										<?php if ( $index > 0 ) : ?>
											<span aria-hidden="true">, </span>
										<?php endif; ?>


										<a
											href="<?php
											echo esc_url(
												add_query_arg(
													'colecao',
													$collection->slug,
													get_post_type_archive_link(
														'biblioteca'
													)
												)
											);
											?>"
										>
											<?php
											echo esc_html(
												$collection->name
											);
											?>
										</a>

									<?php endforeach; ?>

								</div>

							</div>

						<?php endif; ?>


						<?php
						/*
						 * Intellectual authors.
						 */
						if (
							! empty( $authors )
							&& ! is_wp_error( $authors )
						) :
							?>

							<div class="biblioteca-single__meta-item">

								<span
									class="biblioteca-single__meta-icon"
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
										<path
											d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"
										/>
									</svg>
								</span>


								<div class="biblioteca-single__authors">

									<span class="biblioteca-single__meta-label">
										<?php
										echo count( $authors ) > 1
											? esc_html__( 'Autores:', 'conferp' )
											: esc_html__( 'Autor:', 'conferp' );
										?>
									</span>


									<?php
									foreach (
										$authors as $index => $author
									) :
										?>

										<?php if ( $index > 0 ) : ?>
											<span aria-hidden="true">, </span>
										<?php endif; ?>


										<a
											href="<?php
											echo esc_url(
												get_term_link(
													$author
												)
											);
											?>"
										>
											<?php
											echo esc_html(
												$author->name
											);
											?>
										</a>

									<?php endforeach; ?>

								</div>

							</div>

						<?php endif; ?>

					</div>

				<?php endif; ?>


				<?php
				/*
				 * ==================================================
				 * CONTENT
				 * ==================================================
				 */
				?>

				<div class="biblioteca-single__content">

					<?php the_content(); ?>


					<?php
					wp_link_pages(
						array(
							'before' =>
								'<nav class="biblioteca-single__page-links" aria-label="' .
								esc_attr__(
									'Páginas da publicação',
									'conferp'
								) .
								'">',

							'after' => '</nav>',
						)
					);
					?>

				</div>


				<?php
				/*
				 * ==================================================
				 * DOCUMENT
				 * ==================================================
				 */
				?>

				<?php if ( $document_id && $document_url ) : ?>

					<section
						class="biblioteca-single__document"
						aria-labelledby="biblioteca-document-title"
					>

						<h2
							id="biblioteca-document-title"
							class="biblioteca-single__document-heading"
						>
							<?php
							esc_html_e(
								'Documento',
								'conferp'
							);
							?>
						</h2>


						<div class="biblioteca-document-card">


							<div
								class="biblioteca-document-card__icon"
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
									<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
									<path d="M14 2v6h6"/>
									<path d="M8 13h8"/>
									<path d="M8 17h8"/>
								</svg>

							</div>


							<div class="biblioteca-document-card__content">

								<h3 class="biblioteca-document-card__title">
									<?php
									echo esc_html(
										$document_name
									);
									?>
								</h3>


								<?php
								if (
									$document_type
									|| $document_size
								) :
									?>

									<p class="biblioteca-document-card__meta">

										<?php if ( $document_type ) : ?>

											<span>
												<?php
												echo esc_html(
													$document_type
												);
												?>
											</span>

										<?php endif; ?>


										<?php
										if (
											$document_type
											&& $document_size
										) :
											?>

											<span aria-hidden="true">
												&middot;
											</span>

										<?php endif; ?>


										<?php if ( $document_size ) : ?>

											<span>
												<?php
												echo esc_html(
													$document_size
												);
												?>
											</span>

										<?php endif; ?>

									</p>

								<?php endif; ?>

							</div>


							<div class="biblioteca-document-card__action">

								<a
									class="biblioteca-document-card__button"
									href="<?php echo esc_url( $document_url ); ?>"
									target="_blank"
									rel="noopener noreferrer"
								>
									<?php
									esc_html_e(
										'Acessar documento',
										'conferp'
									);
									?>

									<span aria-hidden="true">
										&rarr;
									</span>
								</a>

							</div>

						</div>

					</section>

				<?php endif; ?>


				<?php
				/*
				 * ==================================================
				 * PREVIOUS / NEXT
				 * ==================================================
				 */
				?>

				<nav
					class="biblioteca-single__navigation"
					aria-label="<?php
					esc_attr_e(
						'Navegação entre itens da Biblioteca',
						'conferp'
					);
					?>"
				>

					<div class="biblioteca-single__navigation-previous">

						<?php
						previous_post_link(
							'%link',
							'<span class="biblioteca-single__navigation-direction">' .
								esc_html__(
									'← Item anterior',
									'conferp'
								) .
							'</span>' .
							'<span class="biblioteca-single__navigation-title">%title</span>',
							false,
							'',
							'biblioteca_colecao'
						);
						?>

					</div>


					<div class="biblioteca-single__navigation-next">

						<?php
						next_post_link(
							'%link',
							'<span class="biblioteca-single__navigation-direction">' .
								esc_html__(
									'Próximo item →',
									'conferp'
								) .
							'</span>' .
							'<span class="biblioteca-single__navigation-title">%title</span>',
							false,
							'',
							'biblioteca_colecao'
						);
						?>

					</div>

				</nav>

			</div>

		</article>

	<?php endwhile; ?>

</main>

<?php
get_footer();