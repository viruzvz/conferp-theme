<?php
/**
 * News categories.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_categories(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$current_category = get_queried_object_id();
?>

<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>

	<section
		class="news-sidebar__section news-categories"
		aria-labelledby="news-categories-title"
	>

		<header class="news-sidebar__header">

			<h2
				id="news-categories-title"
				class="news-sidebar__title"
			>
				<?php esc_html_e( 'Categorias', 'conferp' ); ?>
			</h2>

		</header>


		<div class="news-sidebar__body">

			<!-- Desktop category list -->
			<ul class="news-categories__list">

				<?php foreach ( $categories as $category ) : ?>

					<li class="news-categories__item">

						<a
							class="news-categories__link"
							href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
						>
							<span class="news-categories__name">
								<?php echo esc_html( $category->name ); ?>
							</span>

							<span class="news-categories__count">
								(<?php echo esc_html( $category->count ); ?>)
							</span>
						</a>

					</li>

				<?php endforeach; ?>

			</ul>


			<!-- Tablet / Mobile category selector -->
			<div class="news-categories__select-wrapper">

				<label
					class="screen-reader-text"
					for="news-category-select"
				>
					<?php esc_html_e( 'Selecione uma categoria de notícias', 'conferp' ); ?>
				</label>

				<select
					id="news-category-select"
					class="news-categories__select"
					onchange="if (this.value) { window.location.href = this.value; }"
				>

					<option value="">
						<?php esc_html_e( 'Selecione uma categoria', 'conferp' ); ?>
					</option>

					<?php foreach ( $categories as $category ) : ?>

						<option
							value="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
							<?php selected( $current_category, $category->term_id ); ?>
						>
							<?php
							echo esc_html(
								sprintf(
									'%1$s (%2$d)',
									$category->name,
									$category->count
								)
							);
							?>
						</option>

					<?php endforeach; ?>

				</select>

			</div>

		</div>

	</section>

<?php endif; ?>