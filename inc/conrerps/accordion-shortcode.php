<?php
/**
 * Acordeão público dos Conselhos Regionais para páginas Elementor.
 * Depende de queries.php e home-shortcode.php, carregados previamente.
 * @package Conferp_Theme
 */
defined( 'ABSPATH' ) || exit;

function conferp_conrerps_accordion_shortcode( $atts = array() ) {
    if ( ! function_exists( 'conferp_conrerps_get_public_data' ) || ! function_exists( 'conferp_conrerps_home_render_contacts' ) ) {
        return '';
    }

    $atts = shortcode_atts( array( 'titulo' => '' ), $atts, 'conferp_conrerps_accordion' );
    $regionais = conferp_conrerps_get_public_data();
    if ( empty( $regionais ) ) {
        return '<p class="conferp-conrerps-accordion__empty">Nenhum Conselho Regional disponível no momento.</p>';
    }

    $uid = wp_unique_id( 'conferp-regionais-' );
    ob_start();
    ?>
    <section class="conferp-conrerps-accordion" aria-label="Conselhos Regionais de Relações Públicas">
        <?php if ( '' !== trim( (string) $atts['titulo'] ) ) : ?>
            <h2 class="conferp-conrerps-accordion__title"><?php echo esc_html( $atts['titulo'] ); ?></h2>
        <?php endif; ?>
        <div class="accordion" id="<?php echo esc_attr( $uid ); ?>">
            <?php foreach ( $regionais as $index => $regional ) :
                $heading_id = $uid . '-heading-' . (int) $regional['id'];
                $panel_id = $uid . '-panel-' . (int) $regional['id'];
                $ufs = array_map( static function ( $uf ) { return $uf['sigla']; }, $regional['ufs'] );
                $numero = trim( (string) $regional['numero'] );
                $nome = trim( (string) $regional['nome'] );
                $rotulo = $nome !== '' ? $nome : ( $numero !== '' ? 'CONRERP ' . $numero : 'Conselho Regional' );
                ?>
                <div class="accordion-item conferp-conrerps-accordion__item">
                    <h3 class="accordion-header conferp-conrerps-accordion__header" id="<?php echo esc_attr( $heading_id ); ?>">
                        <button class="accordion-button collapsed conferp-conrerps-accordion__trigger" type="button"
                            data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $panel_id ); ?>"
                            aria-expanded="false" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                            <span class="conferp-conrerps-accordion__heading-content">
                                <span class="conferp-conrerps-accordion__name"><?php echo esc_html( $rotulo ); ?></span>
                                <?php if ( $ufs ) : ?>
                                    <span class="conferp-conrerps-accordion__ufs">Estados atendidos: <?php echo esc_html( implode( ' · ', $ufs ) ); ?></span>
                                <?php endif; ?>
                            </span>
                        </button>
                    </h3>
                    <div id="<?php echo esc_attr( $panel_id ); ?>" class="accordion-collapse collapse"
                        aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
                        data-bs-parent="#<?php echo esc_attr( $uid ); ?>">
                        <div class="accordion-body conferp-conrerps-accordion__body">
                            <?php if ( ! empty( $regional['sede'] ) ) : ?>
                                <p class="conferp-conrerps-accordion__sede"><strong>Sede:</strong> <?php echo esc_html( $regional['sede'] ); ?></p>
                            <?php endif; ?>
                            <?php echo conferp_conrerps_home_render_contacts( $regional ); // Saída HTML escapada na função existente. ?>
                            <?php if ( ! empty( $regional['descricao'] ) ) : ?>
                                <div class="conferp-conrerps-accordion__description"><?php echo wpautop( esc_html( $regional['descricao'] ) ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode( 'conferp_conrerps_accordion', 'conferp_conrerps_accordion_shortcode' );
