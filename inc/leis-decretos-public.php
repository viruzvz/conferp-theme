<?php
/** Public archive helpers for Leis e Decretos. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function conferp_leis_decretos_filters() {
    $read = static function ( $key ) {
        return isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] )
            ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : '';
    };
    $order = $read( 'ld_ordem' );
    return array(
        'q'     => $read( 'ld_busca' ),
        'tipo'  => sanitize_title( $read( 'ld_tipo' ) ),
        'ano'   => preg_match( '/^\d{4}$/', $read( 'ld_ano' ) ) ? $read( 'ld_ano' ) : '',
        'ordem' => in_array( $order, array( 'recentes', 'antigas' ), true ) ? $order : 'recentes',
    );
}

function conferp_leis_decretos_years() {
    global $wpdb;
    $years = $wpdb->get_col( $wpdb->prepare(
        "SELECT DISTINCT LEFT(pm.meta_value, 4) FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE p.post_type = %s AND p.post_status = 'publish'
         AND pm.meta_key = %s AND pm.meta_value REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
         ORDER BY 1 DESC", 'lei_decreto', '_legislacao_data'
    ) );
    return array_map( 'strval', $years );
}

/** Search title, excerpt, Gutenberg content, number and ementa; only on marked queries. */
function conferp_leis_decretos_search_sql( $search, $query ) {
    if ( ! $query->get( 'conferp_ld_archive' ) ) { return $search; }
    $term = $query->get( 'conferp_ld_term' );
    if ( ! is_string( $term ) || '' === trim( $term ) ) { return ''; }
    global $wpdb;
    $like = '%' . $wpdb->esc_like( trim( $term ) ) . '%';
    return $wpdb->prepare(
        " AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s
          OR {$wpdb->posts}.post_content LIKE %s
          OR EXISTS ( SELECT 1 FROM {$wpdb->postmeta} ld_meta
                      WHERE ld_meta.post_id = {$wpdb->posts}.ID
                      AND ld_meta.meta_key IN ('_legislacao_numero', '_legislacao_ementa')
                      AND ld_meta.meta_value LIKE %s ))",
        $like, $like, $like, $like
    );
}
add_filter( 'posts_search', 'conferp_leis_decretos_search_sql', 10, 2 );

/** Sort by legal document date, not WordPress publication date. */
function conferp_leis_decretos_order_sql( $orderby, $query ) {
    if ( ! $query->get( 'conferp_ld_archive' ) ) { return $orderby; }
    global $wpdb;
    $direction = 'ASC' === $query->get( 'conferp_ld_direction' ) ? 'ASC' : 'DESC';
    return "COALESCE((SELECT NULLIF(pm.meta_value, '') FROM {$wpdb->postmeta} pm
            WHERE pm.post_id = {$wpdb->posts}.ID AND pm.meta_key = '_legislacao_data'
            LIMIT 1), DATE({$wpdb->posts}.post_date)) {$direction}, {$wpdb->posts}.ID {$direction}";
}
add_filter( 'posts_orderby', 'conferp_leis_decretos_order_sql', 10, 2 );

function conferp_leis_decretos_query( $filters ) {
    $args = array(
        'post_type'          => 'lei_decreto',
        'post_status'        => 'publish',
        'posts_per_page'     => 6,
        'paged'              => max( 1, absint( get_query_var( 'paged' ) ) ),
        'ignore_sticky_posts'=> true,
        'conferp_ld_archive' => true,
        'conferp_ld_term'    => $filters['q'],
        'conferp_ld_direction' => 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC',
    );
    if ( $filters['tipo'] && term_exists( $filters['tipo'], 'legislacao_tipo' ) ) {
        $args['tax_query'] = array( array(
            'taxonomy' => 'legislacao_tipo', 'field' => 'slug', 'terms' => $filters['tipo'],
        ) );
    } elseif ( $filters['tipo'] ) {
        $args['post__in'] = array( 0 );
    }
    if ( $filters['ano'] ) {
        $args['meta_query'] = array( array(
            'key' => '_legislacao_data', 'value' => $filters['ano'] . '-', 'compare' => 'LIKE',
        ) );
    }
    return new WP_Query( $args );
}

function conferp_leis_decretos_date( $post_id ) {
    $date = get_post_meta( $post_id, '_legislacao_data', true );
    if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) { return ''; }
    $timestamp = strtotime( $date . ' 12:00:00 UTC' );
    return $timestamp ? wp_date( get_option( 'date_format' ), $timestamp, new DateTimeZone( 'UTC' ) ) : '';
}

/**
 * Keep the WordPress main archive query in sync with the custom listing.
 * This allows /leis-e-decretos/page/2/ to resolve for Load More.
 */
function conferp_leis_decretos_main_archive_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'lei_decreto' ) ) {
        return;
    }

    $filters = conferp_leis_decretos_filters();
    $query->set( 'posts_per_page', 6 );
    $query->set( 'post_status', 'publish' );
    $query->set( 'ignore_sticky_posts', true );
    $query->set( 'conferp_ld_archive', true );
    $query->set( 'conferp_ld_term', $filters['q'] );
    $query->set( 'conferp_ld_direction', 'antigas' === $filters['ordem'] ? 'ASC' : 'DESC' );

    if ( $filters['tipo'] ) {
        if ( term_exists( $filters['tipo'], 'legislacao_tipo' ) ) {
            $query->set( 'tax_query', array( array(
                'taxonomy' => 'legislacao_tipo',
                'field'    => 'slug',
                'terms'    => $filters['tipo'],
            ) ) );
        } else {
            $query->set( 'post__in', array( 0 ) );
        }
    }

    if ( $filters['ano'] ) {
        $query->set( 'meta_query', array( array(
            'key'     => '_legislacao_data',
            'value'   => $filters['ano'] . '-',
            'compare' => 'LIKE',
        ) ) );
    }
}
add_action( 'pre_get_posts', 'conferp_leis_decretos_main_archive_query' );
