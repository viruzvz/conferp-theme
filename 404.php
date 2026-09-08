<?php
/**
 * Template de página não encontrada.
 *
 * @package CONFERP
 */

get_header();
?>
<section class="conferp-container">
    <h1><?php esc_html_e( 'Página não encontrada', 'conferp' ); ?></h1>
    <p><?php esc_html_e( 'O conteúdo solicitado não foi localizado.', 'conferp' ); ?></p>
</section>
<?php
get_footer();
