<?php
/** CONFERP — Banner de evento: imagem integral com descrição acessível. */
defined('ABSPATH') || exit;

if (!function_exists('conferp_evento_timestamp')) {
    function conferp_evento_timestamp($value) {
        if (!$value || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value)) return false;
        $date = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $value, wp_timezone());
        return $date && $date->format('Y-m-d\TH:i') === $value ? $date->getTimestamp() : false;
    }
}

add_shortcode('conferp_evento_banner', function($atts) {
    $atts = shortcode_atts(['id' => ''], $atts, 'conferp_evento_banner');
    $now = current_datetime()->getTimestamp();
    $args = [
        'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => 100,
        'category_name' => 'eventos', 'orderby' => 'date', 'order' => 'DESC',
        'ignore_sticky_posts' => true, 'no_found_rows' => true,
        'meta_query' => [['key' => '_conferp_evento_enabled', 'value' => '1']],
    ];
    if (absint($atts['id'])) $args['post__in'] = [absint($atts['id'])];
    $query = new WP_Query($args);
    $chosen = null;
    foreach ($query->posts as $post) {
        $start = conferp_evento_timestamp(get_post_meta($post->ID, '_conferp_evento_start', true));
        $end = conferp_evento_timestamp(get_post_meta($post->ID, '_conferp_evento_end', true));
        $desktop = absint(get_post_meta($post->ID, '_conferp_evento_desktop', true));
        if ($start !== false && $end !== false && $start <= $now && $now < $end && $end > $start && $desktop) {
            $chosen = $post;
            break;
        }
    }
    if (!$chosen) return '';
    $id = $chosen->ID;
    $meta = static function($key) use ($id) { return get_post_meta($id, '_conferp_evento_' . $key, true); };
    $title = $meta('headline') ?: get_the_title($id);
    $date = trim((string)$meta('date'));
    $place = trim((string)$meta('place'));
    $description = trim((string)$meta('description'));
    $details = array_filter([
        $date ? 'Data e horário: ' . $date : '',
        $place ? 'Local: ' . $place : '',
        $description,
    ]);
    $desktop = absint($meta('desktop'));
    $mobile = absint($meta('mobile'));
    $desktop_image = wp_get_attachment_image($desktop, 'full', false, [
        'class' => 'conferp-event-banner__image',
        'alt' => '', // Informações essenciais no texto HTML associado ao link.
        'loading' => 'eager', 'decoding' => 'async',
        'sizes' => '100vw',
    ]);
    if (!$desktop_image) return '';
    $mobile_src = $mobile ? wp_get_attachment_image_url($mobile, 'full') : false;
    $uid = 'conferp-event-' . $id;
    ob_start();
    ?>
    <section class="conferp-event-banner" aria-label="Evento em destaque">
      <a class="conferp-event-banner__link" href="<?php echo esc_url(get_permalink($id)); ?>"
         aria-labelledby="<?php echo esc_attr($uid); ?>-title"
         aria-describedby="<?php echo esc_attr($uid); ?>-details">
        <picture class="conferp-event-banner__picture">
          <?php if ($mobile_src): ?>
            <source media="(max-width: 767.98px)" srcset="<?php echo esc_url($mobile_src); ?>">
          <?php endif; ?>
          <?php echo $desktop_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </picture>
        <span class="conferp-event-banner__sr-only" id="<?php echo esc_attr($uid); ?>-title"><?php echo esc_html($title); ?> — acessar notícia do evento</span>
        <span class="conferp-event-banner__sr-only" id="<?php echo esc_attr($uid); ?>-details"><?php echo esc_html(implode('. ', $details)); ?></span>
      </a>
    </section>
    <?php
    return ob_get_clean();
});
