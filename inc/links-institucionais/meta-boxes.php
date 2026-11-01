<?php
/** Campos do card: destino, texto, ordenação e visibilidade. */
if (!defined('ABSPATH')) { exit; }

add_action('add_meta_boxes_conferp_link', function () {
    add_meta_box('conferp_link_details', 'Configurações do card', 'conferp_link_render_meta_box', 'conferp_link', 'normal', 'high');
});

function conferp_link_render_meta_box($post) {
    wp_nonce_field('conferp_link_save', 'conferp_link_nonce');
    $type = get_post_meta($post->ID, '_conferp_link_type', true) ?: 'page';
    $target_id = (int) get_post_meta($post->ID, '_conferp_link_target_id', true);
    $url = get_post_meta($post->ID, '_conferp_link_url', true);
    $description = get_post_meta($post->ID, '_conferp_link_description', true);
    $active = get_post_meta($post->ID, '_conferp_link_active', true);
    $new_tab = get_post_meta($post->ID, '_conferp_link_new_tab', true);
    $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC']);
    $posts = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC']);
    ?>
    <p><label for="conferp_link_type"><strong>Tipo de destino</strong></label><br>
    <select id="conferp_link_type" name="conferp_link_type">
        <option value="page" <?php selected($type, 'page'); ?>>Página interna</option>
        <option value="post" <?php selected($type, 'post'); ?>>Notícia / publicação</option>
        <option value="external" <?php selected($type, 'external'); ?>>URL externa</option>
    </select></p>
    <p><label for="conferp_link_target_id"><strong>Página ou notícia de destino</strong></label><br>
    <select id="conferp_link_target_id" name="conferp_link_target_id" style="max-width:100%;width:550px">
      <option value="0">Selecione o conteúdo</option>
      <optgroup label="Páginas">
      <?php foreach ($pages as $item): ?><option value="<?php echo esc_attr($item->ID); ?>" <?php selected($target_id, $item->ID); ?>><?php echo esc_html($item->post_title); ?></option><?php endforeach; ?>
      </optgroup><optgroup label="Notícias">
      <?php foreach ($posts as $item): ?><option value="<?php echo esc_attr($item->ID); ?>" <?php selected($target_id, $item->ID); ?>><?php echo esc_html($item->post_title); ?></option><?php endforeach; ?>
      </optgroup>
    </select><br><small>Utilizado somente para páginas e notícias. O tipo escolhido precisa corresponder ao conteúdo.</small></p>
    <p><label for="conferp_link_url"><strong>URL externa</strong></label><br>
    <input type="url" id="conferp_link_url" name="conferp_link_url" value="<?php echo esc_attr($url); ?>" class="large-text" placeholder="https://exemplo.com.br"></p>
    <p><label for="conferp_link_description"><strong>Descrição do card</strong></label><br>
    <textarea id="conferp_link_description" name="conferp_link_description" rows="4" class="large-text"><?php echo esc_textarea($description); ?></textarea></p>
    <p><label><input type="checkbox" name="conferp_link_active" value="1" <?php checked($active !== '0'); ?>> Card ativo</label></p>
    <p><label><input type="checkbox" name="conferp_link_new_tab" value="1" <?php checked($new_tab === '1'); ?>> Abrir em nova aba</label></p>
    <p><em>Imagem:</em> utilize a caixa <strong>Imagem destacada</strong>. A ordem é definida em <strong>Atributos da página → Ordem</strong> quando disponível, ou no campo de ordem do editor.</p>
    <?php
}

add_action('save_post_conferp_link', function ($post_id) {
    if (!isset($_POST['conferp_link_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['conferp_link_nonce'])), 'conferp_link_save')) { return; }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }
    if (wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) { return; }
    $type = isset($_POST['conferp_link_type']) ? sanitize_key(wp_unslash($_POST['conferp_link_type'])) : 'page';
    if (!in_array($type, ['page', 'post', 'external'], true)) { $type = 'page'; }
    $target_id = isset($_POST['conferp_link_target_id']) ? absint($_POST['conferp_link_target_id']) : 0;
    if ($type !== 'external' && ($target_id === 0 || get_post_type($target_id) !== $type || get_post_status($target_id) !== 'publish')) { $target_id = 0; }
    $url = isset($_POST['conferp_link_url']) ? esc_url_raw(wp_unslash($_POST['conferp_link_url']), ['http', 'https']) : '';
    $description = isset($_POST['conferp_link_description']) ? sanitize_textarea_field(wp_unslash($_POST['conferp_link_description'])) : '';
    update_post_meta($post_id, '_conferp_link_type', $type);
    update_post_meta($post_id, '_conferp_link_target_id', $target_id);
    update_post_meta($post_id, '_conferp_link_url', $url);
    update_post_meta($post_id, '_conferp_link_description', $description);
    update_post_meta($post_id, '_conferp_link_active', isset($_POST['conferp_link_active']) ? '1' : '0');
    update_post_meta($post_id, '_conferp_link_new_tab', isset($_POST['conferp_link_new_tab']) ? '1' : '0');
});
