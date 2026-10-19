<?php
/** Ações de impressão do documento institucional. @package conferp */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="institutional-document-actions" aria-label="<?php esc_attr_e( 'Ações do documento', 'conferp' ); ?>">
	<button class="institutional-document-actions__button" type="button" onclick="window.print()">
		<?php esc_html_e( 'Imprimir documento', 'conferp' ); ?>
	</button>
	<button class="institutional-document-actions__button institutional-document-actions__button--secondary" type="button" onclick="window.print()" aria-describedby="institutional-document-pdf-help">
		<?php esc_html_e( 'Salvar como PDF', 'conferp' ); ?>
	</button>
	<p id="institutional-document-pdf-help" class="institutional-document-actions__help"><?php esc_html_e( 'Na janela de impressão, selecione “Salvar como PDF” como destino.', 'conferp' ); ?></p>
</div>
