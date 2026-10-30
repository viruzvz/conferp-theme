<?php
/** Shortcode da Home: Encontre seu Conrerp. @package Conferp_Theme */
defined( 'ABSPATH' ) || exit;

function conferp_conrerps_contact_icon( $name ) {
    $allowed = array( 'telephone-fill', 'email', 'site', 'whatsapp', 'map', 'timer' );
    if ( ! in_array( $name, $allowed, true ) ) return '';
    return '<img class="conferp-conrerps__contact-icon" src="' . esc_url( get_template_directory_uri() . '/assets/img/icons/' . $name . '.svg' ) . '" alt="" aria-hidden="true" width="14" height="14">';
}

function conferp_conrerps_home_render_contacts( $regional ) {
    ob_start(); ?>
    <article class="conferp-conrerps__contact" aria-label="<?php echo esc_attr( $regional['nome'] ); ?>">
        <div class="conferp-conrerps__contact-brand">
            <?php if ( ! empty( $regional['logo_url'] ) ) : ?>
                <img class="conferp-conrerps__logo" src="<?php echo esc_url( $regional['logo_url'] ); ?>" alt="<?php echo esc_attr( 'Logotipo do ' . $regional['nome'] ); ?>" loading="lazy">
            <?php else : ?>
                <h3 class="conferp-conrerps__regional-name"><?php echo esc_html( $regional['nome'] ); ?></h3>
            <?php endif; ?>
        </div>
        <div class="conferp-conrerps__contact-group">
            <?php if ( ! empty( $regional['telefones'] ) ) : ?>
                <p><?php echo conferp_conrerps_contact_icon( 'telephone-fill' ); ?><strong>Telefone:</strong> <?php foreach ( $regional['telefones'] as $i => $tel ) : ?><?php if ( $i ) echo ', '; ?><?php if ( ! empty( $tel['href'] ) ) : ?><a href="<?php echo esc_url( $tel['href'], array( 'tel' ) ); ?>"><?php echo esc_html( $tel['texto'] ); ?></a><?php else : ?><?php echo esc_html( $tel['texto'] ); ?><?php endif; ?><?php endforeach; ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $regional['emails'] ) ) : ?>
                <p><?php echo conferp_conrerps_contact_icon( 'email' ); ?><strong>E-mail:</strong> <?php foreach ( $regional['emails'] as $i => $email ) : ?><?php if ( $i ) echo ', '; ?><a href="<?php echo esc_url( 'mailto:' . $email, array( 'mailto' ) ); ?>"><?php echo esc_html( $email ); ?></a><?php endforeach; ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $regional['site'] ) ) : ?><p><?php echo conferp_conrerps_contact_icon( 'site' ); ?><strong>Site:</strong> <a href="<?php echo esc_url( $regional['site'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( preg_replace( '~^https?://~', '', $regional['site'] ) ); ?></a></p><?php endif; ?>
            <?php if ( ! empty( $regional['whatsapp'] ) ) : ?><p><?php echo conferp_conrerps_contact_icon( 'whatsapp' ); ?><strong>WhatsApp:</strong> <a href="<?php echo esc_url( $regional['whatsapp'] ); ?>" target="_blank" rel="noopener noreferrer">Conversar pelo WhatsApp</a></p><?php endif; ?>
        </div>
        <div class="conferp-conrerps__contact-group">
            <?php $address = array_filter( array( $regional['endereco'], $regional['cidade'], $regional['uf_endereco'], $regional['cep'] ), 'strlen' ); ?>
            <?php if ( $address ) : ?><p><?php echo conferp_conrerps_contact_icon( 'map' ); ?><strong>Endereço:</strong> <?php echo esc_html( implode( ' — ', $address ) ); ?><?php if ( ! empty( $regional['localizacao'] ) ) : ?> — <a href="<?php echo esc_url( $regional['localizacao'] ); ?>" target="_blank" rel="noopener noreferrer">Ver localização</a><?php endif; ?></p><?php endif; ?>
            <?php if ( ! empty( $regional['horario'] ) ) : ?><p><?php echo conferp_conrerps_contact_icon( 'timer' ); ?><strong>Horário de atendimento:</strong> <?php echo nl2br( esc_html( $regional['horario'] ) ); ?></p><?php endif; ?>
        </div>
    </article>
    <?php return ob_get_clean();
}

function conferp_conrerps_home_shortcode( $atts ) {
    if ( ! function_exists( 'conferp_conrerps_get_public_data' ) || ! function_exists( 'conferp_conrerps_get_map_settings' ) ) return '';
    $atts = shortcode_atts( array( 'titulo' => 'Encontre seu Conrerp', 'descricao' => 'Selecione seu estado para consultar os contatos do Conselho Regional responsável.', 'pagina_url' => '' ), $atts, 'conferp_conrerps' );
    $settings = conferp_conrerps_get_map_settings();
    $regionals = conferp_conrerps_get_public_data();
    $ufs = conferp_conrerps_get_ufs();
    $id = wp_unique_id( 'conferp-conrerps-' );
    $by_uf = array();
    $regional_ids = array();
    foreach ( $regionals as $regional ) {
        $regional_ids[ $regional['id'] ] = $regional;
        foreach ( $regional['ufs'] as $uf ) {
            $by_uf[ strtolower( $uf['sigla'] ) ][] = $regional['id'];
        }
    }
    ob_start(); ?>
    <section class="conferp-conrerps" aria-labelledby="<?php echo esc_attr( $id ); ?>-heading" data-conferp-conrerps>
        <div class="conferp-conrerps__top">
            <div class="conferp-conrerps__intro">
                <h2 id="<?php echo esc_attr( $id ); ?>-heading"><?php echo esc_html( $atts['titulo'] ); ?></h2>
                <p><?php echo esc_html( $atts['descricao'] ); ?></p>
                <label for="<?php echo esc_attr( $id ); ?>-uf">Selecione seu estado</label>
                <select id="<?php echo esc_attr( $id ); ?>-uf" class="conferp-conrerps__select" data-conferp-uf>
                    <option value="">Selecione seu estado</option>
                    <?php foreach ( $ufs as $uf ) : ?><option value="<?php echo esc_attr( strtolower( $uf->slug ) ); ?>"><?php echo esc_html( $uf->name ); ?></option><?php endforeach; ?>
                </select>
                <?php if ( $atts['pagina_url'] ) : ?><a class="conferp-conrerps__more" href="<?php echo esc_url( $atts['pagina_url'] ); ?>">Saiba mais sobre os Conrerps <span aria-hidden="true">›</span></a><?php endif; ?>
                <?php if ( ! empty( $settings['legends'] ) ) : ?>
                    <div class="conferp-conrerps__legends" aria-label="Legenda das regiões dos Conrerps">
                        <span class="conferp-conrerps__legends-title">Legenda das regiões Conrerps</span>
                        <div class="conferp-conrerps__legend-items">
                        <?php foreach ( $regionals as $regional ) :
                            $legend_id = isset( $settings['legends'][ $regional['id'] ] ) ? absint( $settings['legends'][ $regional['id'] ] ) : 0;
                            if ( ! $legend_id ) continue; ?>
                            <div class="conferp-conrerps__legend" title="<?php echo esc_attr( $regional['nome'] ); ?>">
                                <?php echo wp_get_attachment_image( $legend_id, 'full', false, array( 'alt' => $regional['nome'], 'loading' => 'lazy' ) ); ?>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="conferp-conrerps__map">
                <?php if ( ! empty( $settings['map_id'] ) ) : ?>
                    <?php echo wp_get_attachment_image( $settings['map_id'], 'full', false, array( 'alt' => $settings['map_alt'] ?: 'Mapa ilustrativo do Brasil', 'loading' => 'lazy' ) ); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="conferp-conrerps__details" data-conferp-details hidden>
            <p class="conferp-conrerps__status" data-conferp-status role="status" aria-live="polite"></p>
            <div class="conferp-conrerps__results" data-conferp-results>
                <?php foreach ( $regionals as $regional ) : ?>
                    <div data-conferp-regional="<?php echo esc_attr( $regional['id'] ); ?>" hidden><?php echo conferp_conrerps_home_render_contacts( $regional ); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="application/json" data-conferp-uf-map><?php echo wp_json_encode( $by_uf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>
    </section>
    <?php return ob_get_clean();
}
add_shortcode( 'conferp_conrerps', 'conferp_conrerps_home_shortcode' );
