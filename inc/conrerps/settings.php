<?php
/** Configuração visual do mapa da Home. Não administra organogramas. */
defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', function () {
    add_submenu_page( 'edit.php?post_type=conferp_regional', 'Configurações do mapa', 'Configurações do mapa', 'manage_options', 'conferp-conrerps-mapa', 'conferp_conrerps_map_settings_page' );
} );

/** Verifica que o anexo é um SVG da Biblioteca de Mídia. */
function conferp_conrerps_is_svg_attachment( $id ) {
    $id = absint( $id );
    return $id && 'attachment' === get_post_type( $id ) && 'image/svg+xml' === get_post_mime_type( $id );
}

/** API pública para a próxima fase: mapa e legendas indexadas pelo ID do regional. */
function conferp_conrerps_get_map_settings() {
    $data = get_option( 'conferp_conrerps_map_settings', array() );
    return array(
        'map_id'  => isset( $data['map_id'] ) ? absint( $data['map_id'] ) : 0,
        'map_alt' => isset( $data['map_alt'] ) ? sanitize_text_field( $data['map_alt'] ) : '',
        'legends' => isset( $data['legends'] ) && is_array( $data['legends'] ) ? array_map( 'absint', $data['legends'] ) : array(),
    );
}

add_action( 'admin_post_conferp_save_map_settings', function () {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'Sem permissão.', 'conferp' ), '', array( 'response' => 403 ) );
    }
    check_admin_referer( 'conferp_save_map_settings' );
    $input = isset( $_POST['conferp_map'] ) && is_array( $_POST['conferp_map'] ) ? wp_unslash( $_POST['conferp_map'] ) : array();
    $map_id = isset( $input['map_id'] ) ? absint( $input['map_id'] ) : 0;
    $map_id = conferp_conrerps_is_svg_attachment( $map_id ) ? $map_id : 0;
    $alt = isset( $input['map_alt'] ) && is_string( $input['map_alt'] ) ? sanitize_text_field( $input['map_alt'] ) : '';
    $legends = array();
    if ( isset( $input['legends'] ) && is_array( $input['legends'] ) ) {
        foreach ( $input['legends'] as $regional_id => $attachment_id ) {
            $regional_id = absint( $regional_id );
            $attachment_id = absint( $attachment_id );
            if ( 'conferp_regional' === get_post_type( $regional_id ) && conferp_conrerps_is_svg_attachment( $attachment_id ) ) {
                $legends[ $regional_id ] = $attachment_id;
            }
        }
    }
    update_option( 'conferp_conrerps_map_settings', array( 'map_id' => $map_id, 'map_alt' => $alt, 'legends' => $legends ), false );
    wp_safe_redirect( add_query_arg( array( 'post_type' => 'conferp_regional', 'page' => 'conferp-conrerps-mapa', 'updated' => '1' ), admin_url( 'edit.php' ) ) );
    exit;
} );

function conferp_conrerps_map_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $data = conferp_conrerps_get_map_settings();
    $regionals = get_posts( array( 'post_type' => 'conferp_regional', 'post_status' => array( 'publish', 'draft', 'pending', 'private' ), 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
    ?>
    <div class="wrap">
        <h1>Configurações do mapa — Encontre seu Conrerp</h1>
        <p>Gerencie somente o mapa SVG estático da Home e as legendas associadas aos Conselhos Regionais. O organograma permanece no Elementor.</p>
        <?php if ( isset( $_GET['updated'] ) && '1' === $_GET['updated'] ) : ?>
            <div class="notice notice-success is-dismissible"><p>Configurações salvas.</p></div>
        <?php endif; ?>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
            <input type="hidden" name="action" value="conferp_save_map_settings">
            <?php wp_nonce_field( 'conferp_save_map_settings' ); ?>
            <h2>Mapa do Brasil</h2>
            <?php conferp_conrerps_map_media_field( 'conferp_map[map_id]', $data['map_id'], 'Mapa SVG' ); ?>
            <p><label for="conferp-map-alt"><strong>Descrição acessível do mapa</strong></label></p>
            <input class="large-text" type="text" id="conferp-map-alt" name="conferp_map[map_alt]" value="<?php echo esc_attr( $data['map_alt'] ); ?>" placeholder="Mapa ilustrativo das regiões atendidas pelos Conselhos Regionais">
            <p class="description">A descrição complementa a consulta textual por estados. Não inclua informações que não estejam representadas no mapa.</p>
            <hr>
            <h2>Legendas dos Conselhos Regionais</h2>
            <p>Cada legenda é vinculada ao ID do regional, nunca à sua numeração. Cadastre novos regionais no menu Conselhos Regionais para que apareçam aqui.</p>
            <?php if ( ! $regionals ) : ?>
                <p>Nenhum Conselho Regional cadastrado.</p>
            <?php else : ?>
                <table class="widefat striped" style="max-width:960px"><thead><tr><th scope="col">Conselho Regional</th><th scope="col">Legenda SVG</th></tr></thead><tbody>
                <?php foreach ( $regionals as $regional ) : ?>
                    <tr><th scope="row"><?php echo esc_html( $regional->post_title ); ?> <small>(ID <?php echo esc_html( (string) $regional->ID ); ?>)</small></th><td>
                        <?php conferp_conrerps_map_media_field( 'conferp_map[legends][' . $regional->ID . ']', isset( $data['legends'][ $regional->ID ] ) ? $data['legends'][ $regional->ID ] : 0, 'Legenda SVG' ); ?>
                    </td></tr>
                <?php endforeach; ?>
                </tbody></table>
            <?php endif; ?>
            <?php submit_button( 'Salvar configurações do mapa' ); ?>
        </form>
    </div>
    <?php
}

function conferp_conrerps_map_media_field( $name, $id, $label ) {
    $url = conferp_conrerps_is_svg_attachment( $id ) ? wp_get_attachment_url( $id ) : '';
    ?>
    <div class="conferp-svg-media-field" style="margin:8px 0">
        <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $id ); ?>">
        <img class="conferp-svg-preview" src="<?php echo esc_url( $url ); ?>" alt="" style="<?php echo $url ? '' : 'display:none;'; ?>max-width:160px;max-height:100px;object-fit:contain;vertical-align:middle;margin-right:12px">
        <button type="button" class="button conferp-svg-select" data-title="<?php echo esc_attr( $label ); ?>">Selecionar SVG</button>
        <button type="button" class="button conferp-svg-remove">Remover</button>
    </div>
    <?php
}

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( 'conferp_regional_page_conferp-conrerps-mapa' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_script( 'media-views', <<<'JS'
jQuery(function($) {
  $('.conferp-svg-select').on('click', function(e) {
    e.preventDefault();
    var field = $(this).closest('.conferp-svg-media-field');
    var frame = wp.media({title: $(this).data('title'), button: {text: 'Usar SVG'}, multiple: false});
    frame.on('select', function() {
      var item = frame.state().get('selection').first().toJSON();
      if (item.mime !== 'image/svg+xml') {
        window.alert('Selecione um arquivo SVG já enviado à Biblioteca de Mídia.');
        return;
      }
      field.find('input').val(item.id);
      field.find('.conferp-svg-preview').attr('src', item.url).show();
    });
    frame.open();
  });
  $('.conferp-svg-remove').on('click', function(e) {
    e.preventDefault();
    var field = $(this).closest('.conferp-svg-media-field');
    field.find('input').val('0');
    field.find('.conferp-svg-preview').hide().attr('src', '');
  });
});
JS
    );
} );
