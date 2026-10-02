<?php
/**
 * News search form.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_query = get_search_query();
?>

<section
	class="news-sidebar__section news-search"
	aria-labelledby="news-search-title"
>

	<header class="news-sidebar__header">
		<h2
			id="news-search-title"
			class="news-sidebar__title"
		>
			<?php esc_html_e( 'Buscar em Notícias', 'conferp' ); ?>
		</h2>
	</header>

	<div class="news-sidebar__body">

		<form
			class="news-search__form"
			role="search"
			method="get"
			action="<?php echo esc_url( home_url( '/' ) ); ?>"
		>

			<div class="news-search__field">

				<label
					class="screen-reader-text"
					for="news-search-input"
				>
					<?php esc_html_e( 'Buscar em Notícias', 'conferp' ); ?>
				</label>

				<input
					id="news-search-input"
					class="news-search__input"
					type="search"
					name="s"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Digite o termo', 'conferp' ); ?>"
					autocomplete="off"
				>

				<span
					class="news-search__icon"
					aria-hidden="true"
				>
					<?php
					if ( function_exists( 'conferp_icon' ) ) {
						echo conferp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</span>

			</div>

			<input
				type="hidden"
				name="post_type"
				value="post"
			>

			<button
				class="news-search__submit"
				type="submit"
			>
				<?php esc_html_e( 'Buscar', 'conferp' ); ?>
			</button>

		</form>

	</div>

</section>