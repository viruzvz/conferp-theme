<?php
/** Rodapé reutilizável do documento institucional. @package conferp */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$source = get_post_meta( get_the_ID(), '_legislacao_fonte', true );
?>
<footer class="institutional-document__footer">
	<p><strong><?php esc_html_e( 'Conselho Federal de Profissionais de Relações Públicas — CONFERP', 'conferp' ); ?></strong></p>
	<p><?php esc_html_e( 'Documento disponibilizado para consulta no portal institucional do CONFERP.', 'conferp' ); ?></p>
	<?php if ( $source ) : ?>
		<p class="institutional-document__source">
			<?php esc_html_e( 'Fonte oficial:', 'conferp' ); ?>
			<a href="<?php echo esc_url( $source ); ?>" rel="noopener noreferrer" target="_blank"><?php echo esc_html( $source ); ?></a>
		</p>
	<?php endif; ?>
	<p class="institutional-document__reference">
		<?php esc_html_e( 'Disponível em:', 'conferp' ); ?>
		<span><?php echo esc_html( get_permalink() ); ?></span>
	</p>
</footer>
