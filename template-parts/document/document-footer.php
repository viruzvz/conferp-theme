<?php
/** Rodapé compartilhado dos documentos institucionais. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$document_id = get_the_ID();
$is_resolution = 'resolucao_normativa' === get_post_type( $document_id );
$is_portaria = 'portaria' === get_post_type( $document_id );
$is_instruction = 'instrucao_normativa' === get_post_type( $document_id );
$instruction_pdf_id = $is_instruction ? absint( get_post_meta( $document_id, '_instrucao_pdf_id', true ) ) : 0;
$source = get_post_meta( $document_id, $is_resolution ? '_resolucao_fonte' : ( $is_portaria ? '_portaria_fonte' : ( $is_instruction ? '_instrucao_fonte' : '_legislacao_fonte' ) ), true );
?>
<footer class="institutional-document__footer">
    <p><strong><?php esc_html_e( 'Conselho Federal de Profissionais de Relações Públicas — CONFERP', 'conferp' ); ?></strong></p>
    <p><?php esc_html_e( 'Documento disponibilizado para consulta no portal institucional do CONFERP.', 'conferp' ); ?></p>
    <?php if ( $is_portaria ) : ?>
        <p><?php esc_html_e( 'Transcrição disponibilizada para consulta. Para conferir o ato oficial, utilize o PDF assinado.', 'conferp' ); ?></p>
    <?php endif; ?>
    <?php if ( $is_instruction ) : ?>
        <p><?php echo esc_html( $instruction_pdf_id ? __( 'Transcrição para consulta. Confira o documento original no PDF oficial.', 'conferp' ) : __( 'Transcrição disponibilizada para consulta. Não há PDF oficial cadastrado neste acervo.', 'conferp' ) ); ?></p>
    <?php endif; ?>
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
