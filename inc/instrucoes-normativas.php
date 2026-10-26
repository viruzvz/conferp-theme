<?php
/** Cadastro de Instruções Normativas — CONFERP. @package conferp */
defined( 'ABSPATH' ) || exit;

function conferp_instrucoes_campos() {
    return array(
        '_instrucao_numero' => array( 'Número', 'text' ),
        '_instrucao_data' => array( 'Data de assinatura', 'date' ),
        '_instrucao_ementa' => array( 'Ementa oficial', 'textarea' ),
        '_instrucao_publicacao' => array( 'Data de publicação (opcional)', 'date' ),
        '_instrucao_fonte' => array( 'URL da publicação oficial (opcional)', 'url' ),
        '_instrucao_signatario' => array( 'Autoridade signatária', 'text' ),
    );
}
function conferp_instrucoes_registrar() {
    register_post_type( 'instrucao_normativa', array(
        'labels' => array( 'name' => __( 'Instruções Normativas', 'conferp' ), 'singular_name' => __( 'Instrução Normativa', 'conferp' ), 'menu_name' => __( 'Instruções Normativas', 'conferp' ), 'add_new_item' => __( 'Adicionar Instrução Normativa', 'conferp' ) ),
        'public' => true, 'show_in_rest' => true, 'show_in_menu' => 'conferp-legislacao',
        'has_archive' => 'instrucoes-normativas', 'rewrite' => array( 'slug' => 'instrucoes-normativas', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'revisions', 'author' ),
        'taxonomies' => array( 'instrucao_situacao', 'instrucao_assunto' ),
    ) );
    foreach ( array( 'instrucao_situacao' => 'Situação', 'instrucao_assunto' => 'Assuntos' ) as $slug => $label ) {
        register_taxonomy( $slug, array( 'instrucao_normativa' ), array(
            'labels' => array( 'name' => __( $label, 'conferp' ) ),
            'public' => true, 'hierarchical' => true, 'show_in_rest' => true,
            'show_admin_column' => true, 'rewrite' => array( 'slug' => $slug ),
        ) );
    }
    foreach ( conferp_instrucoes_campos() as $key => $field ) {
        register_post_meta( 'instrucao_normativa', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
        ) );
    }
    foreach ( array( '_instrucao_pdf_id', '_instrucao_revoga', '_instrucao_revogada_por', '_instrucao_altera' ) as $key ) {
        register_post_meta( 'instrucao_normativa', $key, array( 'type' => 'integer', 'single' => true, 'show_in_rest' => true,
            'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
        ) );
    }
}
add_action( 'init', 'conferp_instrucoes_registrar' );

function conferp_instrucoes_termos() {
    $groups = array(
        'instrucao_situacao' => array( 'vigente' => 'Vigente', 'revogada' => 'Revogada', 'parcialmente-revogada' => 'Parcialmente revogada', 'sem-vigencia' => 'Sem vigência' ),
        'instrucao_assunto' => array( 'registro-profissional' => 'Registro Profissional', 'carteira-identidade-profissional' => 'Carteira de Identidade Profissional', 'taxas-emolumentos' => 'Taxas e Emolumentos', 'fiscalizacao-etica-profissional' => 'Fiscalização e Ética Profissional', 'gestao-financeira-prestacao-contas' => 'Gestão Financeira e Prestação de Contas', 'procedimentos-administrativos' => 'Procedimentos Administrativos', 'atas-reunioes' => 'Atas e Reuniões', 'formularios-requerimentos' => 'Formulários e Requerimentos', 'gestao-sistema-conferp-conrerps' => 'Gestão do Sistema CONFERP/CONRERPs' ),
    );
    foreach ( $groups as $taxonomy => $terms ) {
        foreach ( $terms as $slug => $name ) {
            if ( ! term_exists( $slug, $taxonomy ) ) { wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) ); }
        }
    }
}
add_action( 'init', 'conferp_instrucoes_termos', 20 );

function conferp_instrucoes_metabox() {
    add_meta_box( 'conferp-instrucao-dados', 'Identificação e PDF oficial', 'conferp_instrucoes_metabox_html', 'instrucao_normativa', 'normal', 'high' );
}
add_action( 'add_meta_boxes_instrucao_normativa', 'conferp_instrucoes_metabox' );
function conferp_instrucoes_metabox_html( $post ) {
    wp_nonce_field( 'conferp_instrucoes_salvar', 'conferp_instrucoes_nonce' );
    foreach ( conferp_instrucoes_campos() as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label></p>';
        if ( 'textarea' === $field[1] ) {
            echo '<p><textarea class="widefat" rows="3" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea></p>';
        } else {
            echo '<p><input class="widefat" type="' . esc_attr( $field[1] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '"></p>';
        }
    }
    $pdf = absint( get_post_meta( $post->ID, '_instrucao_pdf_id', true ) );
    $url = $pdf ? wp_get_attachment_url( $pdf ) : '';
    echo '<hr><p><strong>PDF oficial (opcional)</strong></p>';
    echo '<input type="hidden" id="conferp-instrucao-pdf-id" name="_instrucao_pdf_id" value="' . esc_attr( $pdf ) . '">';
    echo '<p id="conferp-instrucao-pdf-preview">';
    if ( $url ) { echo '<a target="_blank" rel="noopener noreferrer" href="' . esc_url( $url ) . '">' . esc_html( get_the_title( $pdf ) ) . '</a>'; }
    else { echo 'Sem PDF oficial cadastrado.'; }
    echo '</p><p><button type="button" class="button" id="conferp-instrucao-pdf-select">Selecionar PDF oficial</button> <button type="button" class="button" id="conferp-instrucao-pdf-remove">Remover PDF</button></p>';
    echo '<p class="description">O PDF oficial é a fonte oficial. O texto do Gutenberg é uma transcrição para consulta.</p>';
    echo '<hr><p><strong>Relações com outras instruções normativas</strong></p>';
    $others = get_posts( array( 'post_type' => 'instrucao_normativa', 'post_status' => array( 'publish', 'draft', 'pending', 'private' ), 'posts_per_page' => -1, 'exclude' => array( $post->ID ), 'orderby' => 'title', 'order' => 'ASC' ) );
    foreach ( array( '_instrucao_revoga' => 'Revoga', '_instrucao_revogada_por' => 'Revogada por', '_instrucao_altera' => 'Altera' ) as $key => $label ) {
        $selected = absint( get_post_meta( $post->ID, $key, true ) );
        echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label></p>';
        echo '<p><select class="widefat" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '"><option value="0">Nenhuma</option>';
        foreach ( $others as $other ) {
            echo '<option value="' . esc_attr( $other->ID ) . '" ' . selected( $selected, $other->ID, false ) . '>' . esc_html( $other->post_title ) . '</option>';
        }
        echo '</select></p>';
    }
    echo '<p class="description">Confirme manualmente a situação jurídica na taxonomia Situação. Os relacionamentos não alteram a vigência automaticamente.</p>';
}
function conferp_instrucoes_salvar( $post_id ) {
    if ( ! isset( $_POST['conferp_instrucoes_nonce'] ) || ! is_string( $_POST['conferp_instrucoes_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_instrucoes_nonce'] ) ), 'conferp_instrucoes_salvar' ) ||
         ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
    foreach ( conferp_instrucoes_campos() as $key => $field ) {
        if ( ! isset( $_POST[ $key ] ) || ! is_string( $_POST[ $key ] ) ) { continue; }
        $raw = wp_unslash( $_POST[ $key ] );
        if ( 'date' === $field[1] ) {
            $value = '';
            if ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m ) && checkdate( (int) $m[2], (int) $m[3], (int) $m[1] ) ) { $value = $raw; }
        } elseif ( 'url' === $field[1] ) { $value = esc_url_raw( $raw ); }
        elseif ( 'textarea' === $field[1] ) { $value = sanitize_textarea_field( $raw ); }
        else { $value = sanitize_text_field( $raw ); }
        if ( '' === $value ) { delete_post_meta( $post_id, $key ); } else { update_post_meta( $post_id, $key, $value ); }
    }
    if ( isset( $_POST['_instrucao_pdf_id'] ) ) {
        $id = absint( wp_unslash( $_POST['_instrucao_pdf_id'] ) );
        if ( $id && 'attachment' === get_post_type( $id ) && 'application/pdf' === get_post_mime_type( $id ) ) { update_post_meta( $post_id, '_instrucao_pdf_id', $id ); }
        else { delete_post_meta( $post_id, '_instrucao_pdf_id' ); }
    }
    foreach ( array( '_instrucao_revoga', '_instrucao_revogada_por', '_instrucao_altera' ) as $key ) {
        if ( ! isset( $_POST[ $key ] ) ) { continue; }
        $id = absint( wp_unslash( $_POST[ $key ] ) );
        if ( $id && $id !== (int) $post_id && 'instrucao_normativa' === get_post_type( $id ) ) { update_post_meta( $post_id, $key, $id ); }
        else { delete_post_meta( $post_id, $key ); }
    }
}
add_action( 'save_post_instrucao_normativa', 'conferp_instrucoes_salvar' );
