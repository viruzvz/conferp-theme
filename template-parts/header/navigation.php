<?php
/**
 * Primary Navigation
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<nav
    class="primary-navigation"
    aria-label="<?php esc_attr_e( 'Menu principal', 'conferp-theme' ); ?>"
>

    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'primary-navigation__menu',
            'fallback_cb'    => false,
        )
    );
    ?>

</nav>