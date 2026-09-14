<?php
/**
 * Header Branding
 *
 * Logo e identificação institucional.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="site-branding">

    <?php if ( has_custom_logo() ) : ?>

        <?php the_custom_logo(); ?>

    <?php else : ?>

        <a
            href="<?php echo esc_url( home_url( '/' ) ); ?>"
            class="site-branding__title"
        >
            <?php bloginfo( 'name' ); ?>
        </a>

    <?php endif; ?>

</div>