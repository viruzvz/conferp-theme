
<?php
/**
 * Componente global — Cabeçalho institucional de página.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$title       = isset( $args['title'] ) ? $args['title'] : '';
$highlight   = isset( $args['highlight'] ) ? $args['highlight'] : '';
$description = isset( $args['description'] ) ? $args['description'] : '';

if ( '' === $title && '' === $highlight ) {
    return;
}
?>

<header class="conferp-page-heading">
    <div class="container">

        <?php conferp_breadcrumb(); ?>

        <h1 class="conferp-page-heading__title">

            <span class="conferp-page-heading__primary">
                <?php echo esc_html( $title ); ?>
            </span>

            <?php if ( ! empty( $highlight ) ) : ?>
                <span class="conferp-page-heading__highlight">
                    <?php echo esc_html( $highlight ); ?>
                </span>
            <?php endif; ?>

        </h1>

        <?php if ( '' !== $description ) : ?>
            <p class="conferp-page-heading__description">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>

    </div>
</header>
