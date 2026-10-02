<?php
/**
 * Empty state for the News module.
 *
 * Used when a news search, category archive or
 * news listing does not contain any posts.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_search   = is_search();
$is_category = is_category();

$search_query = get_search_query();


if ( $is_search ) {

	$title = __(
		'Nenhuma notícia encontrada',
		'conferp'
	);


	if ( $search_query ) {

		$message = sprintf(
			/* translators: %s: search term. */
			__(
				'Não encontramos notícias relacionadas a “%s”. Tente utilizar outro termo ou consulte uma das categorias disponíveis.',
				'conferp'
			),
			$search_query
		);

	} else {

		$message = __(
			'Não encontramos notícias para esta pesquisa. Tente utilizar outro termo ou consulte uma das categorias disponíveis.',
			'conferp'
		);
	}

} elseif ( $is_category ) {

	$title = __(
		'Nenhuma notícia encontrada',
		'conferp'
	);

	$message = __(
		'Esta categoria ainda não possui notícias publicadas.',
		'conferp'
	);

} else {

	$title = __(
		'Nenhuma notícia encontrada',
		'conferp'
	);

	$message = __(
		'No momento não existem notícias disponíveis.',
		'conferp'
	);
}
?>

<div class="news-empty">

	<div
		class="news-empty__icon"
		aria-hidden="true"
	>
		<?php
		if ( function_exists( 'conferp_icon' ) ) {
			echo conferp_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>


	<h2 class="news-empty__title">
		<?php echo esc_html( $title ); ?>
	</h2>


	<p class="news-empty__message">
		<?php echo esc_html( $message ); ?>
	</p>


	<?php if ( $is_search ) : ?>

		<button
			class="news-empty__back"
			type="button"
			onclick="history.back();"
		>
			<?php esc_html_e( 'Voltar', 'conferp' ); ?>
		</button>

	<?php else : ?>

		<a
			class="news-empty__back"
			href="<?php echo esc_url( home_url( '/noticias/' ) ); ?>"
		>
			<?php esc_html_e( 'Ver todas as notícias', 'conferp' ); ?>
		</a>

	<?php endif; ?>

</div>