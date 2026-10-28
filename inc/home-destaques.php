<?php
/**
 * Destaques editoriais: reutiliza posts fixados (sticky) do WordPress.
 * Shortcode: [conferp_destaques quantidade="2" titulo="Destaques"]
 */
defined( 'ABSPATH' ) || exit;

function conferp_render_destaques( $atts = array() ) {
    $atts = shortcode_atts( array( 'quantidade' => 2, 'titulo' => 'Destaques' ), $atts, 'conferp_destaques' );
    $limit = min( 12, max( 1, absint( $atts['quantidade'] ) ) );
    $ids = array_values( array_filter( array_map( 'absint', (array) get_option( 'sticky_posts', array() ) ) ) );
    if ( ! $ids ) {
        return ''; // Sem destaques: não mostra contêiner vazio.
    }
    $posts = new WP_Query( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'post__in' => $ids,
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => 1,
        'no_found_rows' => true,
    ) );
    if ( ! $posts->have_posts() ) {
        return '';
    }
    ob_start();
    ?>
    <section class="conferp-highlights" aria-label="<?php echo esc_attr( $atts['titulo'] ?: 'Destaques' ); ?>">
        <?php if ( trim( $atts['titulo'] ) !== '' ) : ?>
            <h2 class="conferp-highlights__heading"><?php echo esc_html( $atts['titulo'] ); ?></h2>
        <?php endif; ?>
        <div class="conferp-highlights__grid">
            <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
                <article <?php post_class( 'conferp-highlight' ); ?>>
                    <a class="conferp-highlight__image-link" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'conferp-highlight__image', 'loading' => 'lazy' ) ); ?>
                        <?php else : ?>
                            <span class="conferp-highlight__image-placeholder" aria-hidden="true"></span>
                        <?php endif; ?>
                    </a>
                    <div class="conferp-highlight__body">
                        <h3 class="conferp-highlight__title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></h3>
                        <div class="conferp-highlight__bottom">
                            <time class="conferp-highlight__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j F Y' ) ); ?></time>
                            <a class="conferp-highlight__button" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Saiba mais sobre %s', get_the_title() ) ); ?>">Saiba mais <span aria-hidden="true">›</span></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <div class="conferp-highlights__rule" aria-hidden="true"></div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'conferp_destaques', 'conferp_render_destaques' );

/** Na página de notícias, os posts fixados seguem a ordem cronológica normal. */
function conferp_news_ignore_sticky_promotion( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
        $query->set( 'ignore_sticky_posts', 1 );
    }
}
add_action( 'pre_get_posts', 'conferp_news_ignore_sticky_promotion', 20 );
