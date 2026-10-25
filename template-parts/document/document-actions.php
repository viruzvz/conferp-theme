<?php
/** Ações de impressão do documento institucional. @package conferp */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_portaria = 'portaria' === get_post_type( get_the_ID() );
$pdf_id = $is_portaria ? absint( get_post_meta( get_the_ID(), '_portaria_pdf_id', true ) ) : 0;
$pdf_url = ( $pdf_id && 'application/pdf' === get_post_mime_type( $pdf_id ) ) ? wp_get_attachment_url( $pdf_id ) : '';
?>
<div class="institutional-document-actions" aria-label="<?php esc_attr_e( 'Ações do documento', 'conferp' ); ?>">
	<button class="institutional-document-actions__button" type="button" onclick="window.print()">
		<?php esc_html_e( 'Imprimir documento', 'conferp' ); ?>
	</button>
	<?php if ( $is_portaria ) : ?>
        <?php if ( $pdf_url ) : ?>
            <a class="institutional-document-actions__button institutional-document-actions__button--secondary" href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visualizar PDF assinado', 'conferp' ); ?></a>
            <a class="institutional-document-actions__button institutional-document-actions__button--secondary" href="<?php echo esc_url( add_query_arg( 'conferp_portaria_download', get_the_ID(), home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Baixar portaria assinada', 'conferp' ); ?></a>
        <?php else : ?>
            <p class="institutional-document-actions__help"><?php esc_html_e( 'O PDF assinado ainda não está disponível.', 'conferp' ); ?></p>
        <?php endif; ?>
    <?php else : ?>
	<button class="institutional-document-actions__button institutional-document-actions__button--secondary" type="button" onclick="window.print()" aria-describedby="institutional-document-pdf-help">
		<?php esc_html_e( 'Salvar como PDF', 'conferp' ); ?>
	</button>
	<?php endif; ?>
	<p id="institutional-document-pdf-help" class="institutional-document-actions__help"><?php esc_html_e( 'Orientações para impressão e PDF: na janela de impressão do navegador, abra “Mais definições” e desmarque “Cabeçalhos e rodapés” para remover a data, o título, a URL e a numeração automática. Para gerar um PDF, selecione “Salvar como PDF” no campo “Destino”.', 'conferp' ); ?></p>
</div>
