<?php
/**
 * Categoria das notícias no widget Posts Classic da Home.
 * Não modifica posts, taxonomias nem arquivos internos do Elementor.
 */
defined( 'ABSPATH' ) || exit;

add_filter( 'elementor/widget/render_content', 'conferp_home_news_render_categories', 10, 2 );

function conferp_home_news_render_categories( $content, $widget ) {
    if ( ! is_object( $widget ) || ! method_exists( $widget, 'get_name' ) || 'posts' !== $widget->get_name() ) {
        return $content;
    }

    $settings = $widget->get_settings_for_display();
    $classes  = trim( (string) ( $settings['_css_classes'] ?? $settings['css_classes'] ?? '' ) );

    if ( ! preg_match( '/(?:^|\s)conferp-home-news(?:\s|$)/', $classes ) ) {
        return $content;
    }

    return preg_replace_callback(
        '~<article\b[^>]*\bclass=(["\'])(.*?)\1[^>]*>.*?</article>~is',
        static function ( $match ) {
            $article = $match[0];

            if ( strpos( $article, 'conferp-home-news__category' ) !== false ) {
                return $article;
            }

            if ( ! preg_match( '/(?:^|\s)post-(\d+)(?:\s|$)/', $match[2], $id_match ) ) {
                return $article;
            }

            $post_id = (int) $id_match[1];
            if ( 'post' !== get_post_type( $post_id ) ) {
                return $article;
            }

            $categories = get_the_category( $post_id );
            if ( empty( $categories ) || is_wp_error( $categories ) ) {
                return $article;
            }

            $category = null;
            foreach ( $categories as $candidate ) {
                if ( 'uncategorized' !== $candidate->slug && 'sem-categoria' !== $candidate->slug ) {
                    $category = $candidate;
                    break;
                }
            }
            $category = $category ?: $categories[0];

            $label = '<div class="conferp-home-news__category"><a href="' .
                esc_url( get_category_link( $category->term_id ) ) . '">' .
                esc_html( $category->name ) . '</a></div>';

            // Inserir dentro de .elementor-post__text, antes do título.
            return preg_replace(
                '~(<div\b[^>]*class=["\'][^"\']*\belementor-post__text\b[^"\']*["\'][^>]*>)~i',
                '$1' . $label,
                $article,
                1
            );
        },
        $content
    );
}
