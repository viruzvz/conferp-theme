
<?php
/**
 * Sidebar — Atas e Reuniões.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$types       = $args['types'] ?? array();
$years       = $args['years'] ?? array();
$filters     = $args['filters'] ?? array();
$archive_url = $args['archive_url'] ?? get_post_type_archive_link( 'ata_reuniao' );

$current_type = $filters['tipo'] ?? '';
$current_year = $filters['ano'] ?? '';

/**
 * Monta URLs preservando os filtros ativos.
 */
$build_url = static function ( $changes = array() ) use ( $filters, $archive_url ) {

    $params = array(
        'ata_busca'   => $filters['q'] ?? '',
        'ata_tipo'    => $filters['tipo'] ?? '',
        'ata_assunto' => $filters['assunto'] ?? '',
        'ata_ano'     => $filters['ano'] ?? '',
        'ata_ordem'   => $filters['ordem'] ?? '',
    );

    $params = array_merge( $params, $changes );

    $params = array_filter(
        $params,
        static function ( $value ) {
            return '' !== $value && null !== $value;
        }
    );

    return add_query_arg( $params, $archive_url );
};

/**
 * Contagem por ano da reunião.
 */
$year_counts = array();

$posts = get_posts(
    array(
        'post_type'      => 'ata_reuniao',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    )
);

foreach ( $posts as $post_id ) {

    $date = get_post_meta( $post_id, '_ata_data', true );

    if (
        ! is_string( $date ) ||
        ! preg_match( '/^(\d{4})-\d{2}-\d{2}$/', $date, $matches )
    ) {
        continue;
    }

    $year = $matches[1];

    if ( ! isset( $year_counts[ $year ] ) ) {
        $year_counts[ $year ] = 0;
    }

    $year_counts[ $year ]++;
}

krsort( $year_counts );
?>

<aside
    class="atas-sidebar"
    aria-label="<?php esc_attr_e( 'Navegação de atas e reuniões', 'conferp' ); ?>"
>

    <!-- Acesso rápido -->
    <section class="atas-sidebar__panel">

        <h2 class="atas-sidebar__title">
            <?php esc_html_e( 'Acesso rápido', 'conferp' ); ?>
        </h2>

        <div class="atas-sidebar__content">

            <ul class="atas-sidebar__list">

                <?php if ( ! is_wp_error( $types ) && ! empty( $types ) ) : ?>

                    <?php foreach ( $types as $term ) : ?>

                        <li>
                            <a
                                href="<?php echo esc_url(
                                    $build_url(
                                        array(
                                            'ata_tipo' => $term->slug,
                                        )
                                    )
                                ); ?>"
                                <?php if ( $current_type === $term->slug ) : ?>
                                    aria-current="page"
                                <?php endif; ?>
                            >
                                <span>
                                    <?php echo esc_html( $term->name ); ?>
                                </span>

                                <span class="atas-sidebar__count">
                                    <?php echo esc_html( $term->count ); ?>
                                </span>
                            </a>
                        </li>

                    <?php endforeach; ?>

                <?php endif; ?>

            </ul>

        </div>

    </section>

    <!-- Navegação por ano -->
    <section class="atas-sidebar__panel">

        <h2 class="atas-sidebar__title">
            <?php esc_html_e( 'Ano', 'conferp' ); ?>
        </h2>

        <div class="atas-sidebar__content">

            <ul class="atas-sidebar__list">

                <?php foreach ( array_keys( $years ) as $year ) : ?>

                    <li>
                        <a
                            href="<?php echo esc_url(
                                $build_url(
                                    array(
                                        'ata_ano' => $year,
                                    )
                                )
                            ); ?>"
                            <?php if ( (string) $current_year === (string) $year ) : ?>
                                aria-current="page"
                            <?php endif; ?>
                        >
                            <span>
                                <?php echo esc_html( $year ); ?>
                            </span>

                            <span class="atas-sidebar__count">
                                <?php echo esc_html( $year_counts[ $year ] ?? 0 ); ?>
                            </span>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>

            <?php if ( $current_year ) : ?>

                <a
                    class="atas-sidebar__all"
                    href="<?php echo esc_url(
                        $build_url(
                            array(
                                'ata_ano' => '',
                            )
                        )
                    ); ?>"
                >
                    <?php esc_html_e( 'Ver todos os anos', 'conferp' ); ?>
                </a>

            <?php endif; ?>

        </div>

    </section>

</aside>
