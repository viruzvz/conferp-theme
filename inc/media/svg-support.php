<?php
/**
 * CONFERP — política de upload de SVG.
 *
 * Dependência obrigatória: plugin Safe SVG (sanitização real do conteúdo).
 * Este arquivo NÃO substitui um sanitizador SVG.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Verifica se a biblioteca de sanitização fornecida pelo Safe SVG está disponível.
 */
function conferp_svg_sanitizer_available() {
    return class_exists( '\\enshrined\\svgSanitize\\Sanitizer' );
}

/**
 * Restringe o MIME SVG a administradores com permissão de gerenciar opções.
 * A permissão do WordPress e a sanitização do plugin são ambas obrigatórias.
 */
function conferp_svg_upload_mimes( $mimes ) {
    if ( current_user_can( 'manage_options' ) && conferp_svg_sanitizer_available() ) {
        $mimes['svg'] = 'image/svg+xml';
    } else {
        unset( $mimes['svg'], $mimes['svgz'] );
    }
    return $mimes;
}
add_filter( 'upload_mimes', 'conferp_svg_upload_mimes', 999 );

/**
 * Bloqueia SVGs antes do upload se a permissão ou o sanitizador faltarem.
 * O plugin Safe SVG permanece responsável pela limpeza e validação do XML.
 */
function conferp_svg_pre_upload_check( $file ) {
    if ( ! isset( $file['name'] ) || ! is_string( $file['name'] ) ) {
        return $file;
    }

    $extension = strtolower( (string) pathinfo( $file['name'], PATHINFO_EXTENSION ) );
    if ( 'svg' !== $extension && 'svgz' !== $extension ) {
        return $file;
    }

    if ( 'svgz' === $extension ) {
        $file['error'] = __( 'Arquivos SVG compactados (SVGZ) não são permitidos.', 'conferp' );
    } elseif ( ! current_user_can( 'manage_options' ) ) {
        $file['error'] = __( 'Você não tem permissão para enviar arquivos SVG.', 'conferp' );
    } elseif ( ! conferp_svg_sanitizer_available() ) {
        $file['error'] = __( 'Ative o plugin Safe SVG antes de enviar arquivos SVG.', 'conferp' );
    }

    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'conferp_svg_pre_upload_check', 999 );
add_filter( 'wp_handle_sideload_prefilter', 'conferp_svg_pre_upload_check', 999 );

/**
 * Alerta administrativo para instalação/ativação do sanitizador.
 */
function conferp_svg_dependency_notice() {
    if ( ! current_user_can( 'manage_options' ) || conferp_svg_sanitizer_available() ) {
        return;
    }
    echo '<div class="notice notice-warning"><p>';
    echo esc_html__( 'CONFERP: para enviar logotipos, mapas e legendas SVG com segurança, instale e ative o plugin Safe SVG. O envio de SVG permanecerá bloqueado até a biblioteca de sanitização estar disponível.', 'conferp' );
    echo '</p></div>';
}
add_action( 'admin_notices', 'conferp_svg_dependency_notice' );
