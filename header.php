<?php
/**
 * Header
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>

<html <?php language_attributes(); ?>>

<head>

    <meta charset="<?php bloginfo( 'charset' ); ?>">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php
    get_template_part(
        'template-parts/global/utility-bar'
    );
?>

<a
    class="skip-link screen-reader-text"
    href="#primary"
>
    <?php esc_html_e( 'Ir para o conteúdo', 'conferp' ); ?>
</a>

<header
    id="masthead"
    class="site-header"
>


    <div class="site-header__main">

        <div class="container">

            <div class="site-header__inner">

                <?php
                get_template_part(
                    'template-parts/header/branding'
                );
                ?>

                <?php
                get_template_part(
                    'template-parts/header/navigation'
                );
                ?>

                <?php
                get_template_part(
                    'template-parts/header/mobile-navigation'
                );
                ?>

            </div>

        </div>

    </div>

</header>