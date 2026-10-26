<?php
/** Anexos complementares (não integram a impressão). @package conferp */
defined( 'ABSPATH' ) || exit;
$items = function_exists( 'conferp_instrucoes_anexos_validar' )
    ? conferp_instrucoes_anexos_validar( get_post_meta( get_the_ID(), '_instrucao_anexos', true ) )
    : array();
if ( ! $items ) { return; }
?>
<section class="instrucao-anexos" aria-labelledby="instrucao-anexos-title">
    <h2 id="instrucao-anexos-title"><?php esc_html_e( 'Anexos complementares', 'conferp' ); ?></h2>
    <p><?php esc_html_e( 'Arquivos de apoio vinculados a esta instrução normativa.', 'conferp' ); ?></p>
    <ul class="instrucao-anexos__list">
    <?php foreach ( $items as $item ) :
        $url = wp_get_attachment_url( $item['id'] );
        if ( ! $url ) { continue; }
    ?>
        <li class="instrucao-anexos__item">
            <div class="instrucao-anexos__details">
                <strong><?php echo esc_html( $item['title'] ); ?></strong>
                <small><?php echo esc_html( 'application/pdf' === get_post_mime_type( $item['id'] ) ? 'PDF' : 'Imagem' ); ?></small>
            </div>
            <div class="instrucao-anexos__links">
                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visualizar', 'conferp' ); ?></a>
                <a href="<?php echo esc_url( $url ); ?>" download><?php esc_html_e( 'Baixar', 'conferp' ); ?></a>
            </div>
        </li>
    <?php endforeach; ?>
    </ul>
</section>
