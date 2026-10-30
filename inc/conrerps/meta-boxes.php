<?php
/**
 * Campos administrativos dos Conselhos Regionais.
 * @package Conferp_Theme
 */
defined( 'ABSPATH' ) || exit;

add_action( 'add_meta_boxes_conferp_regional', 'conferp_regional_add_meta_boxes' );
add_action( 'save_post_conferp_regional', 'conferp_regional_save_meta_boxes' );
add_action( 'admin_enqueue_scripts', 'conferp_regional_admin_assets' );

function conferp_regional_add_meta_boxes() {
    add_meta_box( 'conferp_regional_details', 'Informações do Conselho Regional', 'conferp_regional_render_meta_boxes', 'conferp_regional', 'normal', 'high' );
}

/**
 * Campos simples. Chaves prefixadas evitam conflitos com outros módulos.
 */
function conferp_regional_field_schema() {
    return array(
        'numero'      => array( 'label' => 'Número / identificação da região', 'type' => 'text', 'placeholder' => 'Ex.: 1ª Região' ),
        'sede'        => array( 'label' => 'Cidade-sede', 'type' => 'text', 'placeholder' => 'Ex.: Rio de Janeiro' ),
        'ordem'       => array( 'label' => 'Ordem de exibição', 'type' => 'number', 'placeholder' => 'Ex.: 10' ),
        'telefones'   => array( 'label' => 'Telefones (um por linha)', 'type' => 'textarea', 'placeholder' => '(21) 0000-0000' ),
        'emails'      => array( 'label' => 'E-mails (um por linha)', 'type' => 'textarea', 'placeholder' => 'atendimento@exemplo.org.br' ),
        'site'        => array( 'label' => 'Site oficial', 'type' => 'url', 'placeholder' => 'https://exemplo.org.br' ),
        'whatsapp'    => array( 'label' => 'WhatsApp (número com DDI e DDD)', 'type' => 'text', 'placeholder' => '5561999999999' ),
        'endereco'    => array( 'label' => 'Endereço completo', 'type' => 'textarea', 'placeholder' => 'Logradouro, número, complemento...' ),
        'cidade'      => array( 'label' => 'Cidade do endereço', 'type' => 'text', 'placeholder' => 'Brasília' ),
        'uf_endereco' => array( 'label' => 'UF do endereço', 'type' => 'text', 'placeholder' => 'DF' ),
        'cep'         => array( 'label' => 'CEP', 'type' => 'text', 'placeholder' => '00000-000' ),
        'localizacao' => array( 'label' => 'Link de localização', 'type' => 'url', 'placeholder' => 'https://maps.google.com/...' ),
        'horario'     => array( 'label' => 'Horário de atendimento', 'type' => 'textarea', 'placeholder' => 'Segunda a sexta, das 9h às 17h' ),
        'descricao'   => array( 'label' => 'Descrição institucional complementar', 'type' => 'textarea', 'placeholder' => 'Informações adicionais...' ),
    );
}

function conferp_regional_render_meta_boxes( $post ) {
    wp_nonce_field( 'conferp_regional_save', 'conferp_regional_nonce' );
    $ativo = get_post_meta( $post->ID, '_conferp_regional_ativo', true );
    // Registros anteriores à criação deste metabox são considerados ativos.
    $ativo = '' === $ativo ? '1' : $ativo;
    echo '<p><label><input type="checkbox" name="conferp_regional_ativo" value="1" ' . checked( $ativo, '1', false ) . '> <strong>Regional ativo</strong></label></p>';
    echo '<p class="description">Apenas regionais publicados e ativos aparecerão nas consultas públicas.</p>';

    foreach ( conferp_regional_field_schema() as $key => $config ) {
        $id    = 'conferp_regional_' . $key;
        $value = get_post_meta( $post->ID, '_conferp_regional_' . $key, true );
        echo '<p><label for="' . esc_attr( $id ) . '"><strong>' . esc_html( $config['label'] ) . '</strong></label><br>';
        if ( 'textarea' === $config['type'] ) {
            echo '<textarea class="widefat" rows="3" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" placeholder="' . esc_attr( $config['placeholder'] ) . '">' . esc_textarea( $value ) . '</textarea>';
        } else {
            $step = 'number' === $config['type'] ? ' step="1" min="0"' : '';
            echo '<input class="widefat" type="' . esc_attr( $config['type'] ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $config['placeholder'] ) . '"' . $step . '>';
        }
        echo '</p>';
    }

    $logo_id = absint( get_post_meta( $post->ID, '_conferp_regional_logo_id', true ) );
    echo '<div><strong>Logotipo do regional</strong><p id="conferp-regional-logo-preview">';
    if ( $logo_id ) {
        echo wp_get_attachment_image( $logo_id, 'thumbnail', false, array( 'style' => 'max-width:160px;height:auto' ) );
    }
    echo '</p><input type="hidden" id="conferp_regional_logo_id" name="conferp_regional_logo_id" value="' . esc_attr( $logo_id ) . '">';
    echo '<button type="button" class="button" id="conferp-regional-logo-select">Selecionar imagem</button> ';
    echo '<button type="button" class="button" id="conferp-regional-logo-remove">Remover</button>';
    echo '<p class="description">Use imagens já permitidas e verificadas pela Biblioteca de Mídia. O suporte seguro a SVG será configurado separadamente.</p></div>';
}

function conferp_regional_save_meta_boxes( $post_id ) {
    if ( ! isset( $_POST['conferp_regional_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['conferp_regional_nonce'] ) ), 'conferp_regional_save' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    update_post_meta( $post_id, '_conferp_regional_ativo', isset( $_POST['conferp_regional_ativo'] ) ? '1' : '0' );

    foreach ( conferp_regional_field_schema() as $key => $config ) {
        $name = 'conferp_regional_' . $key;
        if ( ! isset( $_POST[ $name ] ) || ! is_string( $_POST[ $name ] ) ) {
            continue;
        }
        $raw = wp_unslash( $_POST[ $name ] );
        if ( 'emails' === $key ) {
            $lines = preg_split( '/\r\n|\r|\n/', $raw );
            $lines = array_filter( array_map( 'sanitize_email', array_map( 'trim', $lines ) ), 'is_email' );
            $value = implode( "\n", array_unique( $lines ) );
        } elseif ( 'telefones' === $key ) {
            $lines = preg_split( '/\r\n|\r|\n/', $raw );
            $lines = array_filter( array_map( 'sanitize_text_field', array_map( 'trim', $lines ) ) );
            $value = implode( "\n", array_unique( $lines ) );
        } elseif ( 'url' === $config['type'] ) {
            $value = esc_url_raw( $raw, array( 'http', 'https' ) );
        } elseif ( 'number' === $config['type'] ) {
            $value = (string) max( 0, absint( $raw ) );
        } elseif ( 'textarea' === $config['type'] ) {
            $value = sanitize_textarea_field( $raw );
        } else {
            $value = sanitize_text_field( $raw );
        }
        if ( 'uf_endereco' === $key ) {
            $value = strtoupper( substr( $value, 0, 2 ) );
        }
        if ( 'whatsapp' === $key ) {
            $value = preg_replace( '/\D+/', '', $value );
        }
        update_post_meta( $post_id, '_conferp_regional_' . $key, $value );
    }

    if ( isset( $_POST['conferp_regional_logo_id'] ) ) {
        $id = absint( $_POST['conferp_regional_logo_id'] );
        if ( 0 === $id || ( 'attachment' === get_post_type( $id ) && wp_attachment_is_image( $id ) ) ) {
            update_post_meta( $post_id, '_conferp_regional_logo_id', $id );
        }
    }
}

function conferp_regional_admin_assets( $hook ) {
    $screen = get_current_screen();
    if ( ! $screen || 'conferp_regional' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'jquery' );
    $js = <<<'JS'
jQuery(function($) {
    var frame;
    $('#conferp-regional-logo-select').on('click', function(e) {
        e.preventDefault();
        if (frame) { frame.open(); return; }
        frame = wp.media({title: 'Selecionar logotipo do regional', button: {text: 'Usar imagem'}, library: {type: 'image'}, multiple: false});
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#conferp_regional_logo_id').val(attachment.id);
            var src = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            $('#conferp-regional-logo-preview').empty().append($('<img>', {src: src, alt: '', css: {maxWidth: '160px', height: 'auto'}}));
        });
        frame.open();
    });
    $('#conferp-regional-logo-remove').on('click', function(e) {
        e.preventDefault();
        $('#conferp_regional_logo_id').val('0');
        $('#conferp-regional-logo-preview').empty();
    });
});
JS;
    wp_add_inline_script( 'jquery', $js );
}
