<?php
/** CONFERP — Configuração de banner em posts da categoria Eventos. */
defined('ABSPATH') || exit;

function conferp_evento_is_evento($post_id) {
    return has_category('eventos', $post_id);
}

add_action('add_meta_boxes_post', function($post) {
    add_meta_box('conferp_evento_banner', 'Banner de Evento — Home', 'conferp_evento_metabox', 'post', 'normal', 'default');
});

function conferp_evento_metabox($post) {
    wp_nonce_field('conferp_evento_save', 'conferp_evento_nonce');
    $get = static function($key) use ($post) { return get_post_meta($post->ID, '_conferp_evento_' . $key, true); };
    $enabled = $get('enabled');
    echo '<p><strong>Disponível somente para posts publicados na categoria Eventos.</strong> O link aponta automaticamente para a notícia. A publicação continua disponível após o banner expirar.</p>';
    echo '<p><label><input type="checkbox" name="conferp_evento[enabled]" value="1" ' . checked($enabled, '1', false) . '> Habilitar banner</label></p>';
    foreach (['start'=>'Início da exibição','end'=>'Fim da exibição'] as $key=>$label) {
        echo '<p><label>' . esc_html($label) . ' (horário do WordPress)<br><input type="datetime-local" name="conferp_evento[' . esc_attr($key) . ']" value="' . esc_attr($get($key)) . '" style="min-width:240px"></label></p>';
    }
    foreach (['desktop'=>'Imagem desktop (horizontal)','mobile'=>'Imagem mobile (flyer vertical recomendado)'] as $key=>$label) {
        $id = absint($get($key));
        echo '<div class="conferp-evento-media"><p><strong>' . esc_html($label) . '</strong></p><input type="hidden" class="conferp-evento-media-id" name="conferp_evento[' . esc_attr($key) . ']" value="' . esc_attr($id) . '"><div class="conferp-evento-preview">';
        if ($id) echo wp_get_attachment_image($id, 'thumbnail');
        echo '</div><p><button type="button" class="button conferp-evento-pick">Selecionar imagem</button> <button type="button" class="button conferp-evento-clear">Remover</button></p></div>';
    }
    foreach (['headline'=>'Título acessível / chamada','date'=>'Data e horário do evento (texto)','place'=>'Local do evento','description'=>'Descrição textual do flyer (inclua informações essenciais presentes na arte)'] as $key=>$label) {
        $value = $get($key);
        echo '<p><label><strong>' . esc_html($label) . '</strong><br>';
        if ($key === 'description') echo '<textarea name="conferp_evento[' . esc_attr($key) . ']" rows="4" style="width:100%">' . esc_textarea($value) . '</textarea>';
        else echo '<input type="text" name="conferp_evento[' . esc_attr($key) . ']" value="' . esc_attr($value) . '" style="width:100%">';
        echo '</label></p>';
    }
    echo '<p class="description">A imagem será exibida integralmente, sem corte. Preencha título, data, local e descrição fiel de todas as informações relevantes do flyer. Esses textos ficam disponíveis a leitores de tela e no HTML, sem aparecer visualmente. Não inclua palavras-chave artificiais.</p>';
}

add_action('save_post_post', function($post_id) {
    if (!isset($_POST['conferp_evento_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['conferp_evento_nonce'])), 'conferp_evento_save')) return;
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) return;
    $input = isset($_POST['conferp_evento']) && is_array($_POST['conferp_evento']) ? wp_unslash($_POST['conferp_evento']) : [];
    $allowed = ['enabled','start','end','desktop','mobile','headline','date','place','description'];
    foreach ($allowed as $key) {
        $raw = isset($input[$key]) && is_scalar($input[$key]) ? (string)$input[$key] : '';
        if ($key === 'enabled') $value = $raw === '1' ? '1' : '0';
        elseif (in_array($key,['desktop','mobile'],true)) $value = absint($raw);
        elseif (in_array($key,['start','end'],true)) $value = preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $raw) ? $raw : '';
        elseif ($key === 'description') $value = sanitize_textarea_field($raw);
        else $value = sanitize_text_field($raw);
        update_post_meta($post_id, '_conferp_evento_' . $key, $value);
    }
}, 10, 1);

add_action('admin_enqueue_scripts', function($hook) {
    if (!in_array($hook, ['post.php','post-new.php'], true) || get_current_screen()->post_type !== 'post') return;
    wp_enqueue_media();
    wp_enqueue_script('conferp-home-eventos-admin', get_template_directory_uri() . '/assets/js/home-eventos-admin.js', ['jquery'], '1.0.0', true);
});
