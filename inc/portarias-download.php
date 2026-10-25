<?php
/** Download do PDF assinado de uma Portaria pública. @package conferp */
defined( 'ABSPATH' ) || exit;
add_action( 'template_redirect', function () {
    if ( ! isset( $_GET['conferp_portaria_download'] ) ) { return; }
    $post_id = absint( wp_unslash( $_GET['conferp_portaria_download'] ) );
    if ( ! $post_id || 'portaria' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) {
        status_header( 404 ); exit;
    }
    $attachment_id = absint( get_post_meta( $post_id, '_portaria_pdf_id', true ) );
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
    $filename = 'portaria-conferp-' . sanitize_file_name( (string) get_post_meta( $post_id, '_portaria_numero', true ) ) . '-' . (int) substr( (string) get_post_meta( $post_id, '_portaria_data', true ), 0, 4 ) . '-assinada.pdf';
    while ( ob_get_level() ) { ob_end_clean(); }
    nocache_headers();
    header( 'Content-Type: application/pdf' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . filesize( $real ) );
    header( 'X-Content-Type-Options: nosniff' );
    readfile( $real ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
    exit;
}, 1 );
