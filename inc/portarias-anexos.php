<?php
/**
 * Anexos complementares de Portarias (independentes do PDF oficial assinado).
 * @package conferp
 */
defined( 'ABSPATH' ) || exit;

function conferp_portarias_anexos_validar( $raw ) {
    if ( ! is_array( $raw ) ) { return array(); }
    $result = array();
    foreach ( array_slice( $raw, 0, 50 ) as $item ) {
        if ( ! is_array( $item ) ) { continue; }
        $id = isset( $item['id'] ) ? absint( $item['id'] ) : 0;
        if ( ! $id || 'attachment' !== get_post_type( $id ) ) { continue; }
        $mime = (string) get_post_mime_type( $id );
        if ( ! in_array( $mime, array(
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ), true ) ) { continue; }
        $title = isset( $item['title'] ) && is_string( $item['title'] )
            ? sanitize_text_field( $item['title'] ) : '';
        $result[] = array(
            'id' => $id,
            'title' => $title ? $title : get_the_title( $id ),
        );
    }
    return $result;
}

function conferp_portarias_anexos_metabox() {
    add_meta_box(
        'conferp-portaria-anexos',
        __( 'Anexos complementares', 'conferp' ),
        'conferp_portarias_anexos_metabox_html',
        'portaria',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes_portaria', 'conferp_portarias_anexos_metabox' );

function conferp_portarias_anexos_metabox_html( $post ) {
    wp_nonce_field( 'conferp_portarias_anexos_salvar', 'conferp_portarias_anexos_nonce' );
    $items = conferp_portarias_anexos_validar( get_post_meta( $post->ID, '_portaria_anexos', true ) );
    echo '<p>' . esc_html__( 'Arquivos de apoio, como o Manual de Identidade Visual. Não substituem o PDF oficial assinado e não criam páginas próprias.', 'conferp' ) . '</p>';
    echo '<div id="conferp-portaria-anexos-list">';
    foreach ( $items as $i => $item ) {
        $url = wp_get_attachment_url( $item['id'] );
        echo '<div class="conferp-portaria-anexo-item" style="padding:12px;margin:8px 0;border:1px solid #dcdcde;background:#fff">';
        echo '<input type="hidden" class="conferp-portaria-anexo-id" name="conferp_portaria_anexos[' . esc_attr( $i ) . '][id]" value="' . esc_attr( $item['id'] ) . '">';
        echo '<p><label><strong>' . esc_html__( 'Título do anexo', 'conferp' ) . '</strong><br>';
        echo '<input class="widefat conferp-portaria-anexo-title" type="text" name="conferp_portaria_anexos[' . esc_attr( $i ) . '][title]" value="' . esc_attr( $item['title'] ) . '"></label></p>';
        echo '<p><a target="_blank" rel="noopener noreferrer" href="' . esc_url( $url ) . '">' . esc_html( wp_basename( get_attached_file( $item['id'] ) ?: $url ) ) . '</a></p>';
        echo '<button type="button" class="button conferp-portaria-anexo-remove">' . esc_html__( 'Remover anexo', 'conferp' ) . '</button>';
        echo '</div>';
    }
    echo '</div>';
    echo '<p><button type="button" class="button button-secondary" id="conferp-portaria-anexo-add">' . esc_html__( 'Adicionar anexos', 'conferp' ) . '</button></p>';
    echo '<p class="description">' . esc_html__( 'Aceita PDF e imagens JPG, PNG ou WebP. A ordem dos arquivos é a ordem de exibição.', 'conferp' ) . '</p>';
}

function conferp_portarias_anexos_salvar( $post_id ) {
    if ( ! isset( $_POST['conferp_portarias_anexos_nonce'] ) ||
         ! is_string( $_POST['conferp_portarias_anexos_nonce'] ) ||
         ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_portarias_anexos_nonce'] ) ), 'conferp_portarias_anexos_salvar' ) ||
         ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
         wp_is_post_revision( $post_id ) ||
         ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    $raw = isset( $_POST['conferp_portaria_anexos'] ) && is_array( $_POST['conferp_portaria_anexos'] )
        ? wp_unslash( $_POST['conferp_portaria_anexos'] ) : array();
    $items = conferp_portarias_anexos_validar( $raw );
    if ( $items ) {
        update_post_meta( $post_id, '_portaria_anexos', $items );
    } else {
        delete_post_meta( $post_id, '_portaria_anexos' );
    }
}
add_action( 'save_post_portaria', 'conferp_portarias_anexos_salvar' );
