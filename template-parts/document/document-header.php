<?php
/** Cabeçalho compartilhado dos documentos institucionais. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$document_id = get_the_ID();
$is_resolution = 'resolucao_normativa' === get_post_type( $document_id );
$is_portaria = 'portaria' === get_post_type( $document_id );
$prefix = $is_resolution ? '_resolucao_' : ( $is_portaria ? '_portaria_' : '_legislacao_' );
$number = get_post_meta( $document_id, $prefix . 'numero', true );
$date_raw = get_post_meta( $document_id, $prefix . 'data', true );
$summary = get_post_meta( $document_id, $prefix . 'ementa', true );

// Informações oficiais do DOU, disponíveis atualmente para Resoluções Normativas.
$dou_date = $is_resolution ? get_post_meta( $document_id, '_resolucao_dou_data', true ) : '';
$dou_edition = $is_resolution ? get_post_meta( $document_id, '_resolucao_dou_edicao', true ) : '';
$dou_section = $is_resolution ? get_post_meta( $document_id, '_resolucao_dou_secao', true ) : '';
$dou_page = $is_resolution ? get_post_meta( $document_id, '_resolucao_dou_pagina', true ) : '';
$has_dou = $dou_date || $dou_edition || $dou_section || $dou_page;
$dou_date_formatted = '';
if ( $dou_date ) {
    $dou_timestamp = strtotime( $dou_date . ' 12:00:00' );
    if ( $dou_timestamp ) {
        $dou_date_formatted = wp_date( 'd/m/Y', $dou_timestamp );
    }
}
$types = ( $is_resolution || $is_portaria ) ? array() : get_the_terms( $document_id, 'legislacao_tipo' );
$has_types = ! is_wp_error( $types ) && ! empty( $types );
$coat_path = get_theme_file_path( 'assets/img/global/brasao-republica.svg' );
$coat_url = get_theme_file_uri( 'assets/img/global/brasao-republica.svg' );
?>
<header class="institutional-document__header">
    <?php if ( file_exists( $coat_path ) ) : ?>
        <img class="institutional-document__coat" src="<?php echo esc_url( $coat_url ); ?>" alt="<?php esc_attr_e( 'Brasão da República Federativa do Brasil', 'conferp' ); ?>" width="112" height="112" decoding="async">
    <?php endif; ?>
    <p class="institutional-document__republic"><?php esc_html_e( 'República Federativa do Brasil', 'conferp' ); ?></p>
    <?php if ( $is_resolution ) : ?>
        <p class="institutional-document__types"><span class="institutional-document__type"><?php esc_html_e( 'Resolução Normativa', 'conferp' ); ?></span></p>
    <?php elseif ( $is_portaria ) : ?>
        <p class="institutional-document__types"><span class="institutional-document__type"><?php esc_html_e( 'Portaria', 'conferp' ); ?></span></p>
    <?php elseif ( $has_types ) : ?>
        <p class="institutional-document__types">
            <?php foreach ( $types as $type ) : ?>
                <span class="institutional-document__type"><?php echo esc_html( $type->name ); ?></span>
            <?php endforeach; ?>
        </p>
    <?php endif; ?>
    <h2 class="institutional-document__title"><?php echo esc_html( get_the_title() ); ?></h2>
    <?php if ( $number || $date_raw ) : ?>
        <p class="institutional-document__identification">
            <?php if ( $number ) : ?><span><?php echo esc_html( sprintf( __( 'Nº %s', 'conferp' ), $number ) ); ?></span><?php endif; ?>
            <?php if ( $date_raw ) : ?>
                <?php $timestamp = strtotime( $date_raw . ' 12:00:00' ); ?>
                <?php if ( $timestamp ) : ?><time datetime="<?php echo esc_attr( $date_raw ); ?>"><?php echo esc_html( wp_date( get_option( 'date_format' ), $timestamp ) ); ?></time><?php endif; ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>
    <?php if ( $summary ) : ?><p class="institutional-document__summary"><?php echo esc_html( $summary ); ?></p><?php endif; ?>
    <?php if ( $has_dou ) : ?>
        <div class="institutional-document__publication" aria-label="<?php esc_attr_e( 'Dados da publicação oficial', 'conferp' ); ?>">
            <p class="institutional-document__publication-title"><strong><?php esc_html_e( 'Publicação no Diário Oficial da União (DOU)', 'conferp' ); ?></strong></p>
            <p class="institutional-document__publication-details">
                <?php
                $dou_parts = array();
                if ( $dou_date_formatted ) {
                    $dou_parts[] = $dou_date_formatted;
                }
                if ( $dou_edition ) {
                    $dou_parts[] = sprintf( __( 'Edição %s', 'conferp' ), $dou_edition );
                }
                if ( $dou_section ) {
                    $dou_parts[] = sprintf( __( 'Seção %s', 'conferp' ), $dou_section );
                }
                if ( $dou_page ) {
                    $dou_parts[] = sprintf( __( 'Página %s', 'conferp' ), $dou_page );
                }
                echo esc_html( implode( ' · ', $dou_parts ) );
                ?>
            </p>
        </div>
    <?php endif; ?>
</header>
