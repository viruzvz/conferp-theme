<?php
/** Legal document result card. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$id = get_the_ID();
$types = get_the_terms( $id, 'legislacao_tipo' );
$number = get_post_meta( $id, '_legislacao_numero', true );
$summary = get_post_meta( $id, '_legislacao_ementa', true );
$date = conferp_leis_decretos_date( $id );
?>
<article <?php post_class( 'leis-decretos-card' ); ?>>
    <div class="leis-decretos-card__icon" aria-hidden="true">
        <svg viewBox="0 0 48 56" width="44" height="52" fill="none" focusable="false"><path d="M7 2H30L42 14V54H7C4.24 54 2 51.76 2 49V7C2 4.24 4.24 2 7 2Z" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><path d="M30 2V14H42" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/><path d="M11 27H33M11 35H33M11 43H28" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
    </div>
    <div class="leis-decretos-card__body">
        <div class="leis-decretos-card__tags">
            <?php if ( $types && ! is_wp_error( $types ) ) : foreach ( $types as $term ) : ?><span class="leis-decretos-card__tag"><?php echo esc_html( $term->name ); ?></span><?php endforeach; else : ?><span class="leis-decretos-card__tag"><?php esc_html_e( 'Documento legal', 'conferp' ); ?></span><?php endif; ?>
        </div>
        <h2 class="leis-decretos-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php if ( $summary ) : ?><p class="leis-decretos-card__summary"><strong><?php esc_html_e( 'Ementa:', 'conferp' ); ?></strong> <em><?php echo esc_html( $summary ); ?></em></p><?php endif; ?>
    </div>
    <div class="leis-decretos-card__actions"><a class="leis-decretos-card__open" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Ler documento', 'conferp' ); ?> <span aria-hidden="true">↗</span></a></div>
</article>
