<?php
/** CONFERP - Cadastro administrativo de Atas e Reuniões. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Editor clássico para oferecer o mesmo formulário em todas as instalações.
add_filter( 'use_block_editor_for_post_type', function( $use, $type ) {
    return 'ata_reuniao' === $type ? false : $use;
}, 10, 2 );

function conferp_atas_register_meta_boxes() {
    add_meta_box( 'conferp_atas_identificacao', 'Identificação da ata', 'conferp_atas_identificacao_box', 'ata_reuniao', 'normal', 'high' );
    add_meta_box( 'conferp_atas_documento', 'Documento oficial (PDF)', 'conferp_atas_documento_box', 'ata_reuniao', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'conferp_atas_register_meta_boxes' );

function conferp_atas_identificacao_box( $post ) {
    wp_nonce_field( 'conferp_atas_save', 'conferp_atas_nonce' );
    $mode = get_post_meta( $post->ID, '_ata_modo', true ) ?: 'padrao';
    $number = get_post_meta( $post->ID, '_ata_numero', true );
    $date = get_post_meta( $post->ID, '_ata_data', true );
    $precision = get_post_meta( $post->ID, '_ata_precisao', true ) ?: 'dia';
    $source = get_post_meta( $post->ID, '_ata_origem_url', true );
    echo '<p><label for="ata_modo"><strong>Modalidade de cadastro</strong></label><br><select id="ata_modo" name="ata_modo"><option value="padrao" ' . selected( $mode, 'padrao', false ) . '>Padronizado (título automático)</option><option value="historico" ' . selected( $mode, 'historico', false ) . '>Histórico / excepcional (título original)</option></select></p>';
    echo '<p class="description">Selecione o tipo de reunião e o mandato nos painéis de classificação do editor. A numeração é independente por tipo e mandato.</p>';
    echo '<p><label for="ata_numero"><strong>Número da ata</strong></label><br><input type="number" min="1" step="1" id="ata_numero" name="ata_numero" value="' . esc_attr( $number ) . '" class="small-text"> <span class="description">Não reinicia a cada ano. Preenchimento manual para evitar numeração indevida.</span></p>';
    echo '<p><label for="ata_data"><strong>Data da reunião</strong></label><br><input type="date" id="ata_data" name="ata_data" value="' . esc_attr( $date ) . '"> <label for="ata_precisao">Precisão:</label> <select name="ata_precisao" id="ata_precisao"><option value="dia" ' . selected( $precision, 'dia', false ) . '>Data completa</option><option value="mes" ' . selected( $precision, 'mes', false ) . '>Somente mês e ano (histórico)</option><option value="desconhecida" ' . selected( $precision, 'desconhecida', false ) . '>Desconhecida (histórico)</option></select></p>';
    echo '<p class="description">Para histórico com apenas mês/ano, informe o primeiro dia do mês como referência técnica; o portal deverá exibir apenas mês/ano. Essa data técnica não deve ser apresentada como dia real.</p>';
    echo '<p id="ata_title_help" class="description">No modo padronizado, o título é gerado no salvamento. No histórico, preencha o campo nativo de título acima, sem duplicação.</p>';
    echo '<div class="conferp-ata-historico"><p><label for="ata_origem_url">URL de origem (opcional)</label><br><input type="url" class="widefat" id="ata_origem_url" name="ata_origem_url" value="' . esc_attr( $source ) . '"></p></div>';
    echo '<p class="description"><strong>Importante:</strong> somente publique PDFs já autorizados institucionalmente. Este formulário não controla aprovação, assinatura ou DOU.</p>';
}

function conferp_atas_documento_box( $post ) {
    $id = absint( get_post_meta( $post->ID, '_ata_pdf_id', true ) );
    $url = $id ? wp_get_attachment_url( $id ) : '';
    echo '<p><input type="hidden" id="ata_pdf_id" name="ata_pdf_id" value="' . esc_attr( $id ) . '"><button type="button" class="button" id="ata_pdf_select">Selecionar PDF na Mídia</button> <button type="button" class="button" id="ata_pdf_remove">Remover</button></p>';
    echo '<p id="ata_pdf_preview">' . ( $url ? '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( basename( get_attached_file( $id ) ?: $url ) ) . '</a>' : 'Nenhum PDF selecionado.' ) . '</p>';
    echo '<p class="description">O PDF principal é obrigatório para publicação e será aberto diretamente na listagem pública.</p>';
    $extras = get_post_meta( $post->ID, '_ata_anexos_pdf_ids', true );
    if ( ! is_array( $extras ) ) { $extras = array(); }
    $extras = array_values( array_unique( array_filter( array_map( 'absint', $extras ) ) ) );
    echo '<hr><p><strong>Anexos complementares (PDF)</strong></p>';
    echo '<p class="description">Adicione quantos anexos forem necessários. Arraste para reorganizar. Cada PDF será um documento complementar da mesma ata.</p>';
    echo '<input type="hidden" id="ata_anexos_pdf_ids" name="ata_anexos_pdf_ids" value="' . esc_attr( implode( ',', $extras ) ) . '">';
    echo '<ul id="ata_anexos_lista" style="max-width:720px">';
    foreach ( $extras as $extra_id ) {
        if ( 'attachment' !== get_post_type( $extra_id ) || 'application/pdf' !== get_post_mime_type( $extra_id ) ) { continue; }
        $file_url = wp_get_attachment_url( $extra_id );
        if ( ! $file_url ) { continue; }
        $file_name = get_the_title( $extra_id ) ?: basename( get_attached_file( $extra_id ) ?: $file_url );
        echo '<li data-id="' . esc_attr( $extra_id ) . '" style="padding:9px 10px;background:#fff;border:1px solid #c3c4c7;margin:5px 0;display:flex;gap:12px;align-items:center">';
        echo '<span class="dashicons dashicons-move" aria-hidden="true" style="cursor:move"></span>';
        echo '<a style="flex:1" href="' . esc_url( $file_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $file_name ) . '</a>';
        echo '<button type="button" class="button-link-delete ata-anexo-remove" aria-label="Remover anexo">Remover</button></li>';
    }
    echo '</ul><p><button type="button" class="button" id="ata_anexos_add">Adicionar PDFs complementares</button></p>';
}

function conferp_atas_valid_date( $value ) {
    if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value ) ) { return false; }
    list( $y, $m, $d ) = array_map( 'intval', explode( '-', $value ) );
    return checkdate( $m, $d, $y );
}

function conferp_atas_selected_term( $post_id, $taxonomy ) {
    $terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'all' ) );
    return ( ! is_wp_error( $terms ) && count( $terms ) === 1 ) ? $terms[0] : null;
}

function conferp_atas_title_for( $number, $date, $term ) {
    $type = trim( $term->name );
    // Termos especiais devem ter seus próprios rótulos institucionais.
    $label = in_array( mb_strtolower( $type ), array( 'ordinária', 'extraordinária', 'solene', 'especial' ), true )
        ? 'Reunião ' . $type : ( stripos( $type, 'reunião' ) === 0 ? $type : 'Reunião ' . $type );
    return sprintf( 'Ata nº %02d/%s - %s do CONFERP', $number, substr( $date, 0, 4 ), $label );
}

/** Validação final no save_post, sem depender de JavaScript. */
function conferp_atas_save( $post_id, $post, $update ) {
    if ( ! isset( $_POST['conferp_atas_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_atas_nonce'] ) ), 'conferp_atas_save' ) ) { return; }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) { return; }
    $mode = isset( $_POST['ata_modo'] ) && 'historico' === sanitize_key( wp_unslash( $_POST['ata_modo'] ) ) ? 'historico' : 'padrao';
    $number = isset( $_POST['ata_numero'] ) ? absint( $_POST['ata_numero'] ) : 0;
    $date = isset( $_POST['ata_data'] ) ? sanitize_text_field( wp_unslash( $_POST['ata_data'] ) ) : '';
    $precision = isset( $_POST['ata_precisao'] ) ? sanitize_key( wp_unslash( $_POST['ata_precisao'] ) ) : 'dia';
    if ( ! in_array( $precision, array( 'dia', 'mes', 'desconhecida' ), true ) ) { $precision = 'dia'; }
    // O único título editável é o campo nativo post_title do WordPress.
    $original = sanitize_text_field( $post->post_title );
    $source = isset( $_POST['ata_origem_url'] ) ? esc_url_raw( wp_unslash( $_POST['ata_origem_url'] ) ) : '';
    $pdf = isset( $_POST['ata_pdf_id'] ) ? absint( $_POST['ata_pdf_id'] ) : 0;
    $pdf_ok = $pdf && 'application/pdf' === get_post_mime_type( $pdf ) && 'attachment' === get_post_type( $pdf );
    if ( ! $pdf_ok ) { $pdf = 0; }
    update_post_meta( $post_id, '_ata_modo', $mode );
    update_post_meta( $post_id, '_ata_numero', $number ?: '' );
    update_post_meta( $post_id, '_ata_data', conferp_atas_valid_date( $date ) ? $date : '' );
    update_post_meta( $post_id, '_ata_precisao', $precision );
    // Preserva o valor legado apenas como referência, sem criar segundo título editável.
    if ( 'historico' === $mode && ! get_post_meta( $post_id, '_ata_titulo_original', true ) && $original ) {
        update_post_meta( $post_id, '_ata_titulo_original', $original );
    }
    update_post_meta( $post_id, '_ata_origem_url', $source );
    update_post_meta( $post_id, '_ata_pdf_id', $pdf );
    // Anexos complementares: IDs da Mídia, somente PDFs válidos, sem duplicação.
    $raw_extras = isset( $_POST['ata_anexos_pdf_ids'] ) && is_string( $_POST['ata_anexos_pdf_ids'] )
        ? sanitize_text_field( wp_unslash( $_POST['ata_anexos_pdf_ids'] ) ) : '';
    $extra_ids = array_values( array_unique( array_filter( array_map( 'absint', explode( ',', $raw_extras ) ) ) ) );
    $valid_extras = array();
    foreach ( array_slice( $extra_ids, 0, 200 ) as $extra_id ) {
        if ( $extra_id !== $pdf && 'attachment' === get_post_type( $extra_id )
            && 'application/pdf' === get_post_mime_type( $extra_id ) && wp_get_attachment_url( $extra_id ) ) {
            $valid_extras[] = $extra_id;
        }
    }
    update_post_meta( $post_id, '_ata_anexos_pdf_ids', $valid_extras );

    $type = conferp_atas_selected_term( $post_id, 'ata_tipo' );
    $term_mandate = conferp_atas_selected_term( $post_id, 'ata_gestao' );
    $valid_date = conferp_atas_valid_date( $date );
    $title = $mode === 'historico' ? $original : ( $number && $valid_date && $type ? conferp_atas_title_for( $number, $date, $type ) : '' );
    $errors = array();
    if ( ! $pdf ) { $errors[] = 'Selecione um PDF válido.'; }
    if ( $mode === 'padrao' ) {
        if ( ! $number ) { $errors[] = 'Informe o número.'; }
        if ( ! $valid_date || 'dia' !== $precision ) { $errors[] = 'Informe a data completa da reunião.'; }
        if ( ! $type ) { $errors[] = 'Selecione exatamente um tipo de reunião.'; }
        if ( ! $term_mandate ) { $errors[] = 'Selecione exatamente um mandato.'; }
        if ( $type && $term_mandate && $number ) {
            $duplicates = get_posts( array( 'post_type' => 'ata_reuniao', 'post_status' => array( 'publish', 'future', 'draft', 'pending', 'private' ), 'posts_per_page' => -1, 'post__not_in' => array( $post_id ), 'fields' => 'ids', 'meta_query' => array( array( 'key' => '_ata_numero', 'value' => $number ), array( 'key' => '_ata_modo', 'value' => 'padrao' ) ), 'tax_query' => array( 'relation' => 'AND', array( 'taxonomy' => 'ata_tipo', 'terms' => $type->term_id ), array( 'taxonomy' => 'ata_gestao', 'terms' => $term_mandate->term_id ) ) ) );
            if ( $duplicates ) { $errors[] = 'Número já utilizado para este tipo e mandato.'; }
        }
    } elseif ( ! $title ) {
        $errors[] = 'Informe o título original da ata histórica.';
    }
    $changes = array( 'ID' => $post_id );
    if ( $title && $title !== $post->post_title ) { $changes['post_title'] = $title; }
    // Não publicar registros incompletos; rascunhos continuam permitidos.
    if ( 'publish' === $post->post_status && $errors ) { $changes['post_status'] = 'draft'; }
    if ( count( $changes ) > 1 ) {
        remove_action( 'save_post_ata_reuniao', 'conferp_atas_save', 20 );
        wp_update_post( wp_slash( $changes ) );
        add_action( 'save_post_ata_reuniao', 'conferp_atas_save', 20, 3 );
    }
    if ( $errors ) { set_transient( 'conferp_ata_errors_' . get_current_user_id(), $errors, MINUTE_IN_SECONDS ); }
}
add_action( 'save_post_ata_reuniao', 'conferp_atas_save', 20, 3 );

function conferp_atas_show_errors() {
    $screen = get_current_screen();
    if ( ! $screen || 'ata_reuniao' !== $screen->post_type ) { return; }
    $key = 'conferp_ata_errors_' . get_current_user_id();
    $errors = get_transient( $key );
    if ( ! $errors ) { return; }
    delete_transient( $key );
    echo '<div class="notice notice-warning is-dismissible"><p><strong>Ata mantida em rascunho ou salva com pendências:</strong></p><ul>';
    foreach ( $errors as $error ) { echo '<li>' . esc_html( $error ) . '</li>'; }
    echo '</ul></div>';
}
add_action( 'admin_notices', 'conferp_atas_show_errors' );

/** Colunas administrativas específicas para o acervo, sem alterar outros CPTs. */
function conferp_atas_admin_columns( $columns ) {
    $result = array();
    foreach ( $columns as $key => $label ) {
        if ( 'date' === $key ) { continue; }
        $result[ $key ] = $label;
        if ( 'title' === $key ) {
            $result['ata_numero'] = 'Nº da ata';
            $result['ata_data_reuniao'] = 'Data da reunião';
            $result['ata_documentos'] = 'PDFs';
        }
    }
    $result['date'] = 'Publicação no WordPress';
    return $result;
}
add_filter( 'manage_ata_reuniao_posts_columns', 'conferp_atas_admin_columns' );

function conferp_atas_admin_column_value( $column, $post_id ) {
    if ( 'ata_numero' === $column ) {
        $number = get_post_meta( $post_id, '_ata_numero', true );
        echo $number ? esc_html( str_pad( (string) absint( $number ), 2, '0', STR_PAD_LEFT ) ) : '—';
    } elseif ( 'ata_data_reuniao' === $column ) {
        $date = get_post_meta( $post_id, '_ata_data', true );
        $precision = get_post_meta( $post_id, '_ata_precisao', true );
        if ( ! $date || ! conferp_atas_valid_date( $date ) || 'desconhecida' === $precision ) {
            echo 'Não informada';
        } elseif ( 'mes' === $precision ) {
            echo esc_html( wp_date( 'm/Y', strtotime( $date . ' 12:00:00' ) ) );
        } else {
            echo esc_html( wp_date( 'd/m/Y', strtotime( $date . ' 12:00:00' ) ) );
        }
    } elseif ( 'ata_documentos' === $column ) {
        $id = absint( get_post_meta( $post_id, '_ata_pdf_id', true ) );
        $valid = $id && 'application/pdf' === get_post_mime_type( $id );
        $extras = get_post_meta( $post_id, '_ata_anexos_pdf_ids', true );
        $count = is_array( $extras ) ? count( array_filter( $extras ) ) : 0;
        echo $valid ? 'Principal: sim' : 'Sem PDF principal';
        echo '<br><small>' . esc_html( sprintf( 'Anexos: %d', $count ) ) . '</small>';
    }
}
add_action( 'manage_ata_reuniao_posts_custom_column', 'conferp_atas_admin_column_value', 10, 2 );
