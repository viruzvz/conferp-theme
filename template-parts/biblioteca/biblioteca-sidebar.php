<?php
/**
 * Biblioteca sidebar.
 *
 * Displays Biblioteca collections on desktop.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$collections          = conferp_get_biblioteca_collections();
$selected_collection = conferp_get_biblioteca_selected_collection();
$search_term          = conferp_get_biblioteca_search_term();

$archive_url = get_post_type_archive_link( 'biblioteca' );
?>

<div class="biblioteca-sidebar">

	<section
		class="biblioteca-sidebar__panel"
		aria-labelledby="biblioteca-collections-title"
	>

		<header class="biblioteca-sidebar__header">

			<h2
				id="biblioteca-collections-title"
				class="biblioteca-sidebar__title"
			>
				<?php esc_html_e( 'Coleções', 'conferp' ); ?>
			</h2>

		</header>


		<div class="biblioteca-sidebar__body">

			<ul class="biblioteca-sidebar__list">

				<li class="biblioteca-sidebar__item">

					<a
						class="biblioteca-sidebar__link<?php echo empty( $selected_collection ) ? ' is-active' : ''; ?>"
						href="<?php
						echo esc_url(
							$search_term
								? add_query_arg(
									'biblioteca_s',
									$search_term,
									$archive_url
								)
								: $archive_url
						);
						?>"
					>
						<span class="biblioteca-sidebar__link-label">
							<?php esc_html_e( 'Todas as Coleções', 'conferp' ); ?>
						</span>

					</a>

				</li>


				<?php if ( ! empty( $collections ) && ! is_wp_error( $collections ) ) : ?>

					<?php foreach ( $collections as $collection ) : ?>

						<?php
						$collection_url = add_query_arg(
							'colecao',
							$collection->slug,
							$archive_url
						);

						if ( $search_term ) {
							$collection_url = add_query_arg(
								'biblioteca_s',
								$search_term,
								$collection_url
							);
						}

						$is_active = (
							$selected_collection === $collection->slug
						);
						?>

						<li class="biblioteca-sidebar__item">

							<a
								class="biblioteca-sidebar__link<?php echo $is_active ? ' is-active' : ''; ?>"
								href="<?php echo esc_url( $collection_url ); ?>"
								<?php echo $is_active ? 'aria-current="page"' : ''; ?>
							>

								<span class="biblioteca-sidebar__link-label">
									<?php echo esc_html( $collection->name ); ?>
								</span>

								<span
									class="biblioteca-sidebar__count"
									aria-label="<?php
									echo esc_attr(
										sprintf(
											/* translators: %d: number of Biblioteca items. */
											_n(
												'%d item',
												'%d itens',
												(int) $collection->count,
												'conferp'
											),
											(int) $collection->count
										)
									);
									?>"
								>
									<?php echo esc_html( $collection->count ); ?>
								</span>

							</a>

						</li>

					<?php endforeach; ?>

				<?php endif; ?>

			</ul>

		</div>

	</section>

</div>