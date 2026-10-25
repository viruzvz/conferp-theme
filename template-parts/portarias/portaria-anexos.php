<?php
/** Lista de anexos complementares de uma Portaria. @package conferp */
defined( 'ABSPATH' ) || exit;
$post_id = get_the_ID();
$items = function_exists( 'conferp_portarias_anexos_validar' )
    ? conferp_portarias_anexos_validar( get_post_meta( $post_id, '_portaria_anexos', true ) )
    : array();
if ( ! $items ) { return; }
?>
<section class="portaria-anexos" aria-labelledby="portaria-anexos-title">
    <h2 class="portaria-anexos__title" id="portaria-anexos-title"><?php esc_html_e( 'Anexos complementares', 'conferp' ); ?></h2>
    <p class="portaria-anexos__description"><?php esc_html_e( 'Arquivos vinculados a esta portaria. Não substituem o documento oficial assinado.', 'conferp' ); ?></p>
    <ul class="portaria-anexos__list">
        <?php foreach ( $items as $item ) :
            $url = wp_get_attachment_url( $item['id'] );
            if ( ! $url ) { continue; }
            $mime = (string) get_post_mime_type( $item['id'] );
            $kind = 'application/pdf' === $mime ? 'PDF' : __( 'Imagem', 'conferp' );
            ?>
            <li class="portaria-anexos__item">
                <div class="portaria-anexos__details">
                    <strong><?php echo esc_html( $item['title'] ); ?></strong>
                    <span><?php echo esc_html( $kind ); ?></span>
                </div>
                <div class="portaria-anexos__links">
                    <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visualizar', 'conferp' ); ?></a>
                    <a href="<?php echo esc_url( $url ); ?>" download><?php esc_html_e( 'Baixar', 'conferp' ); ?></a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
