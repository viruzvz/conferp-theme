<?php
/** Download seguro do PDF público do contrato — incluir via functions.php. */
defined( 'ABSPATH' ) || exit;
add_action( 'template_redirect', function () {
    if ( ! isset( $_GET['conferp_contrato_download'] ) ) { return; }
    $post_id = absint( wp_unslash( $_GET['conferp_contrato_download'] ) );
    if ( ! $post_id || 'contrato' !== get_post_type( $post_id ) || 'publish' !== get_post_status( $post_id ) ) { status_header( 404 ); exit; }
    $attachment_id = absint( get_post_meta( $post_id, '_contrato_pdf_id', true ) );
    if ( ! $attachment_id || 'application/pdf' !== get_post_mime_type( $attachment_id ) ) { status_header( 404 ); exit; }
    $path = get_attached_file( $attachment_id );
    if ( ! $path || ! is_readable( $path ) ) { status_header( 404 ); exit; }
    $filename = sanitize_file_name( basename( $path ) );
    while ( ob_get_level() ) { ob_end_clean(); }
    header( 'Content-Type: application/pdf' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Content-Length: ' . filesize( $path ) );
    header( 'X-Content-Type-Options: nosniff' );
    readfile( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_readfile
    exit;
} );
