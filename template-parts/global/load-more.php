
<?php
/**
 * Componente global: Load More.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$list       = $args['list'] ?? '';
$card       = $args['card'] ?? '';
$pagination = $args['pagination'] ?? '';
$total      = absint( $args['total'] ?? 0 );

if ( ! $list || ! $card || ! $pagination ) {
    return;
}
?>

<div
    class="conferp-load-more"
    data-conferp-load-more
    data-list="<?php echo esc_attr( $list ); ?>"
    data-card="<?php echo esc_attr( $card ); ?>"
    data-pagination="<?php echo esc_attr( $pagination ); ?>"
    data-total="<?php echo esc_attr( $total ); ?>"
    hidden
>
    <p
        class="conferp-load-more__status"
        data-load-more-status
        role="status"
        aria-live="polite"
    ></p>

    <button
        class="conferp-load-more__button"
        data-load-more-button
        type="button"
    >
        <?php esc_html_e( 'Ver mais documentos', 'conferp' ); ?>
    </button>
</div>
