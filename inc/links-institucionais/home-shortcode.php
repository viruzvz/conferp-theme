<?php
/** Carrossel de links institucionais da Home. */
defined('ABSPATH') || exit;

add_shortcode('conferp_links_institucionais', function ($atts) {
    $atts = shortcode_atts(['limit' => 12, 'title' => 'Informações para você'], $atts, 'conferp_links_institucionais');
    if (!function_exists('conferp_links_institucionais_get')) { return ''; }
    $items = conferp_links_institucionais_get(absint($atts['limit']));
    if (!$items) { return ''; }
    $id = wp_unique_id('conferp-links-');
    ob_start();
    ?>
    <section class="conferp-links" aria-label="<?php echo esc_attr($atts['title']); ?>" data-conferp-links>
      <div class="conferp-links__heading">
        <h2 class="conferp-links__title"><?php echo esc_html($atts['title']); ?></h2>
        <?php if (count($items) > 1) : ?>
          <div class="conferp-links__controls" aria-label="Controles do carrossel">
            <button type="button" class="conferp-links__arrow" data-conferp-links-prev aria-label="Ver cards anteriores" aria-controls="<?php echo esc_attr($id); ?>"><span aria-hidden="true">&#10094;</span></button>
            <button type="button" class="conferp-links__arrow" data-conferp-links-next aria-label="Ver próximos cards" aria-controls="<?php echo esc_attr($id); ?>"><span aria-hidden="true">&#10095;</span></button>
          </div>
        <?php endif; ?>
      </div>
      <div class="conferp-links__track" id="<?php echo esc_attr($id); ?>" tabindex="0" aria-label="Links institucionais; navegue horizontalmente">
        <?php foreach ($items as $item) : ?>
          <article class="conferp-links__card">
            <a class="conferp-links__link" href="<?php echo esc_url($item['url']); ?>" <?php if ($item['new_tab']) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?> aria-label="<?php echo esc_attr($item['title'] . ($item['new_tab'] ? ' (abre em nova aba)' : '')); ?>">
              <div class="conferp-links__media">
                <?php if (!empty($item['image_id'])) : ?>
                  <?php echo wp_get_attachment_image($item['image_id'], 'large', false, ['class' => 'conferp-links__image', 'loading' => 'lazy', 'alt' => '']); ?>
                <?php else : ?>
                  <span class="conferp-links__placeholder" aria-hidden="true"><?php echo esc_html($item['title']); ?></span>
                <?php endif; ?>
              </div>
              <h3 class="conferp-links__card-title"><?php echo esc_html($item['title']); ?></h3>
            </a>
            <?php if (!empty($item['description'])) : ?><p class="conferp-links__description"><?php echo esc_html($item['description']); ?></p><?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php
    return ob_get_clean();
});
