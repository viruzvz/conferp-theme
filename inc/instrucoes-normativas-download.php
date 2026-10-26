<?php
/** Download controlado do PDF oficial de Instrução Normativa. @package conferp */
defined( 'ABSPATH' ) || exit;
add_action( 'template_redirect', function () {
    if ( ! isset( $_GET['conferp_instrucao_download'] ) ) { return; }
    $post_id = absint( wp_unslash( $_GET['conferp_instrucao_download'] ) );
    if ( ! $post_id || 'instrucao_normativa' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
        status_header( 404 ); exit;
    }
    $attachment_id = absint( get_post_meta( $post_id, '_instrucao_pdf_id', true ) );
    if ( ! $attachment_id || 'application/pdf' !== get_post_mime_type( $attachment_id ) ) {
        status_header( 404 ); exit;
    }
    $file = get_attached_file( $attachment_id );
    $uploads = wp_get_upload_dir();
    $base = realpath( $uploads['basedir'] );
    $real = $file ? realpath( $file ) : false;
    if ( ! $base || ! $real || 0 !== strpos( $real, $base . DIRECTORY_SEPARATOR ) || ! is_readable( $real ) ) {
        status_header( 404 ); exit;
    }
    $number = sanitize_file_name( (string) get_post_meta( $post_id, '_instrucao_numero', true ) );
    $year = substr( (string) get_post_meta( $post_id, '_instrucao_data', true ), 0, 4 );
    $filename = 'instrucao-normativa-conferp-' . ( $number ?: $post_id ) . ( preg_match( '/^[0-9]{4}$/', $year ) ? '-' . $year : '' ) . '.pdf';
    while ( ob_get_level() ) { ob_end_clean(); }
    nocache_headers();
    header( 'Content-Type: application/pdf' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . filesize( $real ) );
    header( 'X-Content-Type-Options: nosniff' );
    readfile( $real ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
    exit;
}, 1 );
