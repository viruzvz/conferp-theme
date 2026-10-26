<?php
/** Cadastro administrativo de Contratos — CONFERP. */
defined( 'ABSPATH' ) || exit;

function conferp_contratos_campos() {
    return array(
        '_contrato_numero' => array( 'Número do contrato (ex.: 002/2026)', 'text' ),
        '_contrato_processo' => array( 'Número do processo administrativo', 'text' ),
        '_contrato_objeto' => array( 'Objeto do contrato', 'textarea' ),
        '_contrato_contratada' => array( 'Razão social / nome da contratada', 'text' ),
        '_contrato_documento' => array( 'CNPJ/CPF da contratada', 'text' ),
        '_contrato_valor_inicial' => array( 'Valor inicial (R$)', 'money' ),
        '_contrato_valor_atualizado' => array( 'Valor atualizado (R$), se aplicável', 'money' ),
        '_contrato_assinatura' => array( 'Data de assinatura', 'date' ),
        '_contrato_fundamentacao' => array( 'Fundamentação legal (opcional)', 'textarea' ),
    );
}

function conferp_contratos_registrar() {
    register_post_type( 'contrato', array(
        'labels' => array( 'name' => 'Contratos', 'singular_name' => 'Contrato', 'menu_name' => 'Contratos', 'add_new_item' => 'Adicionar Contrato', 'edit_item' => 'Editar Contrato' ),
        'public' => true, 'show_in_rest' => true, 'show_in_menu' => 'conferp-transparencia',
        'has_archive' => 'contratos', 'rewrite' => array( 'slug' => 'contratos', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'revisions', 'author' ),
        'taxonomies' => array( 'contrato_situacao', 'contrato_tipo' ),
    ) );
    foreach ( array( 'contrato_situacao' => 'Situação', 'contrato_tipo' => 'Tipo de contrato' ) as $taxonomy => $label ) {
        register_taxonomy( $taxonomy, array( 'contrato' ), array(
            'labels' => array( 'name' => $label, 'singular_name' => $label ),
            'public' => true, 'hierarchical' => true, 'show_in_rest' => true,
            'show_admin_column' => true, 'rewrite' => array( 'slug' => $taxonomy ),
        ) );
    }
    foreach ( conferp_contratos_campos() as $key => $field ) {
        register_post_meta( 'contrato', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
        ) );
    }
    foreach ( array( '_contrato_vigencia_tipo', '_contrato_vigencia_inicio', '_contrato_vigencia_fim' ) as $key ) {
        register_post_meta( 'contrato', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
        ) );
    }
    register_post_meta( 'contrato', '_contrato_pdf_id', array( 'type' => 'integer', 'single' => true, 'show_in_rest' => true,
        'auth_callback' => static function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
    ) );
}
add_action( 'init', 'conferp_contratos_registrar' );

function conferp_contratos_termos() {
    $grupos = array(
        'contrato_situacao' => array( 'vigente' => 'Vigente', 'encerrado' => 'Encerrado', 'rescindido' => 'Rescindido', 'suspenso' => 'Suspenso', 'em-execucao' => 'Em execução' ),
        'contrato_tipo' => array( 'prestacao-de-servicos' => 'Prestação de Serviços', 'aquisicao-de-bens' => 'Aquisição de Bens', 'tecnologia-da-informacao' => 'Tecnologia da Informação', 'locacao' => 'Locação', 'obras-e-engenharia' => 'Obras e Serviços de Engenharia', 'outros' => 'Outros' ),
    );
    foreach ( $grupos as $taxonomy => $termos ) {
        foreach ( $termos as $slug => $nome ) {
            if ( ! term_exists( $slug, $taxonomy ) ) { wp_insert_term( $nome, $taxonomy, array( 'slug' => $slug ) ); }
        }
    }
}
add_action( 'init', 'conferp_contratos_termos', 20 );

function conferp_contratos_metaboxes() {
    add_meta_box( 'conferp-contrato-dados', 'Dados administrativos e PDF original', 'conferp_contratos_dados_html', 'contrato', 'normal', 'high' );
    add_meta_box( 'conferp-contrato-documentos', 'Termos aditivos, apostilamentos e anexos', 'conferp_contratos_documentos_html', 'contrato', 'normal', 'default' );
}
add_action( 'add_meta_boxes_contrato', 'conferp_contratos_metaboxes' );

function conferp_contratos_dados_html( $post ) {
    wp_nonce_field( 'conferp_contratos_salvar', 'conferp_contratos_nonce' );
    foreach ( conferp_contratos_campos() as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label></p>';
        if ( 'textarea' === $field[1] ) {
            echo '<p><textarea class="widefat" rows="3" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea></p>';
        } else {
            $type = 'money' === $field[1] ? 'text' : $field[1];
            $placeholder = 'money' === $field[1] ? 'Ex.: 12.345,67' : '';
            echo '<p><input class="widefat" type="' . esc_attr( $type ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '"></p>';
        }
    }
    $vigencia = get_post_meta( $post->ID, '_contrato_vigencia_tipo', true );
    echo '<hr><p><label for="conferp-contrato-vigencia"><strong>Prazo de vigência</strong></label></p>';
    echo '<p><select class="widefat" id="conferp-contrato-vigencia" name="_contrato_vigencia_tipo">';
    echo '<option value="">Selecione o tipo de prazo</option>';
    foreach ( array( 'determinado' => 'Prazo determinado', 'indeterminado' => 'Prazo indeterminado' ) as $value => $label ) {
        echo '<option value="' . esc_attr( $value ) . '" ' . selected( $vigencia, $value, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select></p>';
    echo '<div id="conferp-contrato-vigencia-datas"' . ( 'determinado' === $vigencia ? '' : ' hidden' ) . '>';
    foreach ( array( '_contrato_vigencia_inicio' => 'Início da vigência', '_contrato_vigencia_fim' => 'Fim da vigência' ) as $key => $label ) {
        echo '<p><label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label></p>';
        echo '<p><input type="date" class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( get_post_meta( $post->ID, $key, true ) ) . '"></p>';
    }
    echo '</div><p class="description">Prazo indeterminado não significa automaticamente contrato vigente. Informe a situação na taxonomia Situação.</p>';
    $pdf_id = absint( get_post_meta( $post->ID, '_contrato_pdf_id', true ) );
    $url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';
    echo '<hr><p><strong>PDF original do contrato</strong></p>';
    echo '<input type="hidden" id="conferp-contrato-pdf-id" name="_contrato_pdf_id" value="' . esc_attr( $pdf_id ) . '">';
    echo '<p id="conferp-contrato-pdf-preview">';
    echo $url ? '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( get_the_title( $pdf_id ) ) . '</a>' : 'Nenhum PDF selecionado.';
    echo '</p><p><button type="button" class="button" id="conferp-contrato-pdf-select">Selecionar PDF</button> <button type="button" class="button" id="conferp-contrato-pdf-remove">Remover PDF</button></p>';
    echo '<p class="description">O PDF é o documento oficial. O conteúdo do Gutenberg é opcional e não substitui o arquivo original.</p>';
}

function conferp_contratos_documentos_html( $post ) {
    $docs = get_post_meta( $post->ID, '_contrato_documentos', true );
    if ( ! is_array( $docs ) ) { $docs = array(); }
    echo '<p class="description">Cadastre cada termo aditivo ou apostilamento com identificação e data. Anexos comuns também podem ser incluídos. A ordem é a ordem de exibição.</p>';
    echo '<div id="conferp-contrato-documentos-list">';
    foreach ( $docs as $i => $doc ) {
        if ( ! is_array( $doc ) ) { continue; }
        conferp_contratos_documento_item_html( $i, $doc );
    }
    echo '</div><p><button type="button" class="button" id="conferp-contrato-documento-add">Adicionar documento</button></p>';
}

function conferp_contratos_documento_item_html( $index, $doc ) {
    $id = absint( $doc['id'] ?? 0 );
    $url = $id ? wp_get_attachment_url( $id ) : '';
    $tipo = $doc['tipo'] ?? 'anexo';
    $base = 'conferp_contrato_documentos[' . $index . ']';
    echo '<div class="conferp-contrato-documento-item" style="border:1px solid #dcdcde;padding:12px;margin:8px 0;background:#fff">';
    echo '<input type="hidden" class="conferp-contrato-documento-id" name="' . esc_attr( $base . '[id]' ) . '" value="' . esc_attr( $id ) . '">';
    echo '<p><label><strong>Tipo de documento</strong><br><select class="widefat" name="' . esc_attr( $base . '[tipo]' ) . '">';
    foreach ( array( 'aditivo' => 'Termo Aditivo', 'apostilamento' => 'Apostilamento', 'anexo' => 'Anexo complementar', 'outro' => 'Outro documento' ) as $value => $label ) {
        echo '<option value="' . esc_attr( $value ) . '" ' . selected( $tipo, $value, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select></label></p>';
    foreach ( array( 'titulo' => array( 'Identificação / título', 'text' ), 'data' => array( 'Data do documento', 'date' ), 'descricao' => array( 'Descrição / finalidade', 'textarea' ) ) as $key => $config ) {
        echo '<p><label><strong>' . esc_html( $config[0] ) . '</strong><br>';
        if ( 'textarea' === $config[1] ) {
            echo '<textarea class="widefat" rows="2" name="' . esc_attr( $base . '[' . $key . ']' ) . '">' . esc_textarea( $doc[ $key ] ?? '' ) . '</textarea>';
        } else {
            echo '<input class="widefat" type="' . esc_attr( $config[1] ) . '" name="' . esc_attr( $base . '[' . $key . ']' ) . '" value="' . esc_attr( $doc[ $key ] ?? '' ) . '">';
        }
        echo '</label></p>';
    }
    echo '<p class="conferp-contrato-documento-preview">';
    echo $url ? '<a target="_blank" rel="noopener noreferrer" href="' . esc_url( $url ) . '">' . esc_html( get_the_title( $id ) ) . '</a>' : 'Nenhum arquivo selecionado.';
    echo '</p><p><button type="button" class="button conferp-contrato-documento-select">Selecionar arquivo</button> <button type="button" class="button conferp-contrato-documento-remove">Remover documento</button></p>';
    echo '</div>';
}

function conferp_contratos_data_valida( $value ) {
    if ( ! is_string( $value ) || ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $value, $m ) ) { return ''; }
    return checkdate( (int) $m[2], (int) $m[3], (int) $m[1] ) ? $value : '';
}
function conferp_contratos_moeda_valida( $raw ) {
    $raw = trim( (string) $raw );
    if ( '' === $raw ) { return ''; }
    // Aceita 1234.56 ou 1.234,56 e armazena sempre em formato decimal.
    if ( preg_match( '/^\d{1,3}(?:\.\d{3})*(?:,\d{1,2})?$/', $raw ) ) {
        $raw = str_replace( ',', '.', str_replace( '.', '', $raw ) );
    } elseif ( ! preg_match( '/^\d+(?:\.\d{1,2})?$/', $raw ) ) { return ''; }
    return number_format( (float) $raw, 2, '.', '' );
}
function conferp_contratos_salvar( $post_id ) {
    if ( ! isset( $_POST['conferp_contratos_nonce'] ) || ! is_string( $_POST['conferp_contratos_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_contratos_nonce'] ) ), 'conferp_contratos_salvar' ) ||
        ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
    foreach ( conferp_contratos_campos() as $key => $field ) {
        if ( ! isset( $_POST[ $key ] ) || ! is_string( $_POST[ $key ] ) ) { continue; }
        $raw = wp_unslash( $_POST[ $key ] );
        if ( 'date' === $field[1] ) { $value = conferp_contratos_data_valida( $raw ); }
        elseif ( 'money' === $field[1] ) { $value = conferp_contratos_moeda_valida( $raw ); }
        elseif ( 'textarea' === $field[1] ) { $value = sanitize_textarea_field( $raw ); }
        else { $value = sanitize_text_field( $raw ); }
        if ( '' === $value ) { delete_post_meta( $post_id, $key ); } else { update_post_meta( $post_id, $key, $value ); }
    }
    if ( isset( $_POST['_contrato_vigencia_tipo'] ) && is_string( $_POST['_contrato_vigencia_tipo'] ) ) {
        $tipo = sanitize_key( wp_unslash( $_POST['_contrato_vigencia_tipo'] ) );
        if ( in_array( $tipo, array( 'determinado', 'indeterminado' ), true ) ) { update_post_meta( $post_id, '_contrato_vigencia_tipo', $tipo ); }
        else { delete_post_meta( $post_id, '_contrato_vigencia_tipo' ); }
        if ( 'determinado' === $tipo ) {
            foreach ( array( '_contrato_vigencia_inicio', '_contrato_vigencia_fim' ) as $key ) {
                $value = isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) ? conferp_contratos_data_valida( wp_unslash( $_POST[ $key ] ) ) : '';
                if ( $value ) { update_post_meta( $post_id, $key, $value ); } else { delete_post_meta( $post_id, $key ); }
            }
        } else {
            delete_post_meta( $post_id, '_contrato_vigencia_inicio' );
            delete_post_meta( $post_id, '_contrato_vigencia_fim' );
        }
    }
    if ( isset( $_POST['_contrato_pdf_id'] ) ) {
        $id = absint( wp_unslash( $_POST['_contrato_pdf_id'] ) );
        if ( $id && 'attachment' === get_post_type( $id ) && 'application/pdf' === get_post_mime_type( $id ) ) { update_post_meta( $post_id, '_contrato_pdf_id', $id ); }
        else { delete_post_meta( $post_id, '_contrato_pdf_id' ); }
    }
    if ( isset( $_POST['conferp_contrato_documentos'] ) && is_array( $_POST['conferp_contrato_documentos'] ) ) {
        $documents = array();
        foreach ( wp_unslash( $_POST['conferp_contrato_documentos'] ) as $doc ) {
            if ( ! is_array( $doc ) ) { continue; }
            $id = absint( $doc['id'] ?? 0 );
            if ( ! $id || 'attachment' !== get_post_type( $id ) || ! in_array( get_post_mime_type( $id ), array( 'application/pdf', 'image/jpeg', 'image/png', 'image/webp' ), true ) ) { continue; }
            $tipo = sanitize_key( $doc['tipo'] ?? 'anexo' );
            if ( ! in_array( $tipo, array( 'aditivo', 'apostilamento', 'anexo', 'outro' ), true ) ) { $tipo = 'anexo'; }
            $documents[] = array(
                'id' => $id, 'tipo' => $tipo,
                'titulo' => sanitize_text_field( $doc['titulo'] ?? '' ),
                'data' => conferp_contratos_data_valida( $doc['data'] ?? '' ),
                'descricao' => sanitize_textarea_field( $doc['descricao'] ?? '' ),
            );
        }
        if ( $documents ) { update_post_meta( $post_id, '_contrato_documentos', $documents ); }
        else { delete_post_meta( $post_id, '_contrato_documentos' ); }
    } else { delete_post_meta( $post_id, '_contrato_documentos' ); }
}
add_action( 'save_post_contrato', 'conferp_contratos_salvar' );
