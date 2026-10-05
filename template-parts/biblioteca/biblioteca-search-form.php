<?php
/**
 * Biblioteca search form.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$collections         = conferp_get_biblioteca_collections();
$selected_collection = conferp_get_biblioteca_selected_collection();
$search_term         = conferp_get_biblioteca_search_term();
?>

<form
	class="biblioteca-search__form"
	method="get"
	action="<?php echo esc_url( get_post_type_archive_link( 'biblioteca' ) ); ?>"
>

	<div class="biblioteca-search__collection">

		<label
			class="screen-reader-text"
			for="biblioteca-collection"
		>
			<?php esc_html_e( 'Selecionar coleção', 'conferp' ); ?>
		</label>

		<select
			id="biblioteca-collection"
			class="biblioteca-search__select"
			name="colecao"
		>

			<option value="">
				<?php esc_html_e( 'Todas as Coleções', 'conferp' ); ?>
			</option>

			<?php if ( ! empty( $collections ) && ! is_wp_error( $collections ) ) : ?>

				<?php foreach ( $collections as $collection ) : ?>

					<option
						value="<?php echo esc_attr( $collection->slug ); ?>"
						<?php selected( $selected_collection, $collection->slug ); ?>
					>
						<?php echo esc_html( $collection->name ); ?>
					</option>

				<?php endforeach; ?>

			<?php endif; ?>

		</select>

	</div>


	<div class="biblioteca-search__field">

		<label
			class="screen-reader-text"
			for="biblioteca-search-input"
		>
			<?php esc_html_e( 'Pesquisar na Biblioteca', 'conferp' ); ?>
		</label>

		<input
			id="biblioteca-search-input"
			class="biblioteca-search__input"
			type="search"
			name="biblioteca_s"
			value="<?php echo esc_attr( $search_term ); ?>"
			placeholder="<?php esc_attr_e( 'Pesquisar na biblioteca', 'conferp' ); ?>"
		>

		<span
			class="biblioteca-search__icon"
			aria-hidden="true"
		>
			<?php echo conferp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</span>

	</div>


	<button
		class="biblioteca-search__submit"
		type="submit"
	>
		<?php esc_html_e( 'Buscar', 'conferp' ); ?>
	</button>

</form>