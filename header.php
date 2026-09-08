<?php
/**
 * Cabeçalho do tema.
 *
 * @package CONFERP
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#conteudo-principal">
    <?php esc_html_e( 'Ir para o conteúdo', 'conferp' ); ?>
</a>

<header id="site-header" class="site-header">
    <!-- Utility Bar global do CONFERP será implementada aqui. -->
    <!-- Header institucional e navegação dinâmica serão implementados aqui. -->
</header>

<main id="conteudo-principal" class="site-main">
