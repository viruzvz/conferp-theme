<?php
/**
 * Biblioteca empty state.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_term          = conferp_get_biblioteca_search_term();
$selected_collection = conferp_get_biblioteca_selected_collection();

$archive_url = get_post_type_archive_link( 'biblioteca' );
?>

<div class="biblioteca-empty">

	<div
		class="biblioteca-empty__icon"
		aria-hidden="true"
	>
		<?php echo conferp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>


	<h2 class="biblioteca-empty__title">
		<?php esc_html_e( 'Nenhum resultado encontrado', 'conferp' ); ?>
	</h2>


	<?php if ( $search_term ) : ?>

		<p class="biblioteca-empty__message">

			<?php
			printf(
				/* translators: %s: Biblioteca search term. */
				esc_html__(
					'Não encontramos itens na Biblioteca relacionados a “%s”. Tente outro termo ou pesquise em todas as coleções.',
					'conferp'
				),
				esc_html( $search_term )
			);
			?>

		</p>

	<?php elseif ( $selected_collection ) : ?>

		<p class="biblioteca-empty__message">
			<?php
			esc_html_e(
				'Não encontramos itens nesta coleção.',
				'conferp'
			);
			?>
		</p>

	<?php else : ?>

		<p class="biblioteca-empty__message">
			<?php
			esc_html_e(
				'Nenhum item está disponível na Biblioteca neste momento.',
				'conferp'
			);
			?>
		</p>

	<?php endif; ?>


	<a
		class="biblioteca-empty__back"
		href="<?php echo esc_url( $archive_url ); ?>"
	>
		<?php esc_html_e( 'Ver toda a Biblioteca', 'conferp' ); ?>
	</a>

</div>