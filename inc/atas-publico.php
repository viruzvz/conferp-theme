<?php
/** Public archive for Atas e Reuniões. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_atas_public_filters() {
    $get = wp_unslash( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    return array(
        'q'       => isset( $get['ata_busca'] ) && is_string( $get['ata_busca'] ) ? sanitize_text_field( $get['ata_busca'] ) : '',
        'tipo'    => isset( $get['ata_tipo'] ) && is_string( $get['ata_tipo'] ) ? sanitize_title( $get['ata_tipo'] ) : '',
        'assunto' => isset( $get['ata_assunto'] ) && is_string( $get['ata_assunto'] ) ? sanitize_title( $get['ata_assunto'] ) : '',
        'ano'     => isset( $get['ata_ano'] ) && is_string( $get['ata_ano'] ) && preg_match( '/^\d{4}$/', $get['ata_ano'] ) ? $get['ata_ano'] : '',
        'ordem'   => isset( $get['ata_ordem'] ) && 'antigas' === $get['ata_ordem'] ? 'antigas' : 'recentes',
    );
}

/** Restrict to published entries with a valid PDF; filter metadata in PHP because WordPress default search ignores post meta. */
function conferp_atas_public_query( $filters ) {
    $tax_query = array( 'relation' => 'AND' );
    foreach ( array( 'tipo' => 'ata_tipo', 'assunto' => 'ata_assunto' ) as $key => $taxonomy ) {
        if ( $filters[ $key ] ) {
            $tax_query[] = array( 'taxonomy' => $taxonomy, 'field' => 'slug', 'terms' => $filters[ $key ] );
        }
    }
    $args = array(
        'post_type' => 'ata_reuniao', 'post_status' => 'publish',
        'posts_per_page' => -1, 'no_found_rows' => true,
        'fields' => 'ids', 'orderby' => 'ID', 'order' => 'DESC',
    );
    if ( count( $tax_query ) > 1 ) { $args['tax_query'] = $tax_query; }
    $ids = get_posts( $args );
    $result = array();
    foreach ( $ids as $id ) {
        $pdf_id = absint( get_post_meta( $id, '_ata_pdf_id', true ) );
        if ( ! $pdf_id || 'application/pdf' !== get_post_mime_type( $pdf_id ) || ! wp_get_attachment_url( $pdf_id ) ) { continue; }
        $date = get_post_meta( $id, '_ata_data', true );
        $precision = get_post_meta( $id, '_ata_precisao', true );
        if ( $filters['ano'] && ( ! $date || substr( $date, 0, 4 ) !== $filters['ano'] ) ) { continue; }
        if ( $filters['q'] ) {
            $types = wp_get_post_terms( $id, 'ata_tipo', array( 'fields' => 'names' ) );
            $subjects = wp_get_post_terms( $id, 'ata_assunto', array( 'fields' => 'names' ) );
            $haystack = implode( ' ', array_merge( array( get_the_title( $id ), get_post_meta( $id, '_ata_numero', true ), $date ), is_wp_error( $types ) ? array() : $types, is_wp_error( $subjects ) ? array() : $subjects ) );
            if ( false === mb_stripos( remove_accents( $haystack ), remove_accents( $filters['q'] ) ) ) { continue; }
        }
        $result[] = array( 'id' => $id, 'date' => $date && 'desconhecida' !== $precision ? $date : '' );
    }
    usort( $result, function( $a, $b ) use ( $filters ) {
        $compare = strcmp( $a['date'], $b['date'] );
        if ( ! $compare ) { $compare = $a['id'] <=> $b['id']; }
        return 'antigas' === $filters['ordem'] ? $compare : -$compare;
    } );
    return array_column( $result, 'id' );
}

function conferp_atas_public_date( $id ) {
    $date = get_post_meta( $id, '_ata_data', true );
    $precision = get_post_meta( $id, '_ata_precisao', true );
    if ( ! $date || 'desconhecida' === $precision ) { return 'Data não informada'; }
    $timestamp = strtotime( $date . ' 12:00:00' );
    if ( ! $timestamp ) { return 'Data não informada'; }
    return 'mes' === $precision ? wp_date( 'F \d\e Y', $timestamp ) : wp_date( 'd/m/Y', $timestamp );
}

/** Prevent individual CPT pages, including old indexed URLs. */
function conferp_atas_block_single() {
    if ( is_singular( 'ata_reuniao' ) ) {
        wp_safe_redirect( get_post_type_archive_link( 'ata_reuniao' ), 301 );
        exit;
    }
}
add_action( 'template_redirect', 'conferp_atas_block_single' );

/** Enable archive without editing the previously approved CPT registration. */
function conferp_atas_enable_archive( $args, $post_type ) {
    if ( 'ata_reuniao' === $post_type ) {
        $args['publicly_queryable'] = true;
        $args['has_archive'] = 'atas-e-reunioes';
        $args['rewrite'] = array( 'slug' => 'atas-e-reunioes', 'with_front' => false );
        $args['query_var'] = true;
        $args['exclude_from_search'] = true;
    }
    return $args;
}
add_filter( 'register_post_type_args', 'conferp_atas_enable_archive', 10, 2 );

