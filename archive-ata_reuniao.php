
<?php
/**
 * Archive — Atas e Reuniões.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

/**
 * Consulta e paginação.
 */
$filters = conferp_atas_public_filters();
$ids     = conferp_atas_public_query( $filters );

$per_page = 6;
$page     = max( 1, absint( get_query_var( 'paged' ) ) );
$total    = count( $ids );
$pages    = max( 1, (int) ceil( $total / $per_page ) );
$page     = min( $page, $pages );

$visible = array_slice(
    $ids,
    ( $page - 1 ) * $per_page,
    $per_page
);

/**
 * Taxonomias disponíveis para os filtros.
 *
 * Os tipos de reunião são exibidos somente quando
 * possuem atas publicadas, conforme decisão atual.
 */
$types = get_terms(
    array(
        'taxonomy'   => 'ata_tipo',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);

$subjects = get_terms(
    array(
        'taxonomy'   => 'ata_assunto',
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    )
);

/**
 * Anos disponíveis.
 *
 * Utiliza a data da reunião, não a data
 * de publicação do WordPress.
 */
$years = array();

$year_posts = get_posts(
    array(
        'post_type'      => 'ata_reuniao',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    )
);

foreach ( $year_posts as $year_id ) {
    $date = get_post_meta( $year_id, '_ata_data', true );

    if ( is_string( $date ) && preg_match( '/^\d{4}-/', $date ) ) {
        $years[ substr( $date, 0, 4 ) ] = true;
    }
}

krsort( $years );

$archive_url = get_post_type_archive_link( 'ata_reuniao' );
?>

<main id="primary" class="site-main atas-page">

    <?php
    /**
     * Cabeçalho institucional global.
     *
     * Inclui o breadcrumb automaticamente.
     */
    get_template_part(
        'template-parts/global/page-heading',
        null,
        array(
            'title'       => __( 'Atas', 'conferp' ),
            'highlight'   => __( 'e Reuniões', 'conferp' ),
            'description' => __(
                'Consulte e acesse as atas das reuniões do CONFERP.',
                'conferp'
            ),
        )
    );
    ?>

    <!-- Pesquisa e filtros -->
    <section
        class="atas-search"
        aria-label="<?php esc_attr_e( 'Pesquisar atas e reuniões', 'conferp' ); ?>"
    >
        <div class="container">

            <form
                class="atas-filtros"
                method="get"
                action="<?php echo esc_url( $archive_url ); ?>"
                role="search"
            >

                <div class="atas-filtros__search">

                    <label for="ata_busca">
                        <?php esc_html_e( 'Pesquisar atas', 'conferp' ); ?>
                    </label>

                    <input
                        id="ata_busca"
                        name="ata_busca"
                        type="search"
                        value="<?php echo esc_attr( $filters['q'] ); ?>"
                        placeholder="<?php esc_attr_e(
                            'Buscar por número da ata, ano, tipo, assunto ou palavra-chave...',
                            'conferp'
                        ); ?>"
                    >

                </div>

                <div class="atas-filtros__grid">

                    <!-- Tipo de reunião -->
                    <div class="atas-filtros__field">

                        <label for="ata_tipo">
                            <?php esc_html_e( 'Tipo de reunião', 'conferp' ); ?>
                        </label>

                        <select id="ata_tipo" name="ata_tipo">

                            <option value="">
                                <?php esc_html_e( 'Todos os tipos', 'conferp' ); ?>
                            </option>

                            <?php if ( ! is_wp_error( $types ) ) : ?>
                                <?php foreach ( $types as $term ) : ?>

                                    <option
                                        value="<?php echo esc_attr( $term->slug ); ?>"
                                        <?php selected( $filters['tipo'], $term->slug ); ?>
                                    >
                                        <?php echo esc_html( $term->name ); ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>

                    </div>

                    <!-- Ano da ata -->
                    <div class="atas-filtros__field">

                        <label for="ata_ano">
                            <?php esc_html_e( 'Ano da ata', 'conferp' ); ?>
                        </label>

                        <select id="ata_ano" name="ata_ano">

                            <option value="">
                                <?php esc_html_e( 'Todos os anos', 'conferp' ); ?>
                            </option>

                            <?php foreach ( array_keys( $years ) as $year ) : ?>

                                <option
                                    value="<?php echo esc_attr( $year ); ?>"
                                    <?php selected( $filters['ano'], $year ); ?>
                                >
                                    <?php echo esc_html( $year ); ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <!-- Assuntos -->
                    <div class="atas-filtros__field">

                        <label for="ata_assunto">
                            <?php esc_html_e( 'Assunto', 'conferp' ); ?>
                        </label>

                        <select id="ata_assunto" name="ata_assunto">

                            <option value="">
                                <?php esc_html_e( 'Todos os assuntos', 'conferp' ); ?>
                            </option>

                            <?php if ( ! is_wp_error( $subjects ) ) : ?>
                                <?php foreach ( $subjects as $term ) : ?>

                                    <option
                                        value="<?php echo esc_attr( $term->slug ); ?>"
                                        <?php selected( $filters['assunto'], $term->slug ); ?>
                                    >
                                        <?php echo esc_html( $term->name ); ?>
                                    </option>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>

                    </div>

                    <!-- Ordenação -->
                    <div class="atas-filtros__field">

                        <label for="ata_ordem">
                            <?php esc_html_e( 'Ordenação', 'conferp' ); ?>
                        </label>

                        <select id="ata_ordem" name="ata_ordem">

                            <option
                                value="recentes"
                                <?php selected( $filters['ordem'], 'recentes' ); ?>
                            >
                                <?php esc_html_e( 'Mais recentes', 'conferp' ); ?>
                            </option>

                            <option
                                value="antigas"
                                <?php selected( $filters['ordem'], 'antigas' ); ?>
                            >
                                <?php esc_html_e( 'Mais antigas', 'conferp' ); ?>
                            </option>

                        </select>

                    </div>

                </div>

                <div class="atas-filtros__actions">

                    <button type="submit">
                        <?php esc_html_e( 'Filtrar atas', 'conferp' ); ?>
                    </button>

                    <a href="<?php echo esc_url( $archive_url ); ?>">
                        <?php esc_html_e( 'Limpar filtros', 'conferp' ); ?>
                    </a>

                </div>

            </form>

        </div>
    </section>

    <!-- Resultados -->
    <div class="atas-results container">

        <p class="atas-page__count" aria-live="polite">
            <?php
            echo esc_html(
                sprintf(
                    _n(
                        '%d ata encontrada',
                        '%d atas encontradas',
                        $total,
                        'conferp'
                    ),
                    $total
                )
            );
            ?>
        </p>

        <div class="atas-layout">

            <div class="atas-layout__main">

                <div class="atas-lista">

                    <?php foreach ( $visible as $id ) : ?>

                        <?php
                        $pdf_id  = absint( get_post_meta( $id, '_ata_pdf_id', true ) );
                        $pdf_url = $pdf_id ? wp_get_attachment_url( $pdf_id ) : false;

                        $type_names = wp_get_post_terms(
                            $id,
                            'ata_tipo',
                            array( 'fields' => 'names' )
                        );

                        $subject_names = wp_get_post_terms(
                            $id,
                            'ata_assunto',
                            array( 'fields' => 'names' )
                        );

                        $extras = get_post_meta(
                            $id,
                            '_ata_anexos_pdf_ids',
                            true
                        );

                        $extras = is_array( $extras ) ? $extras : array();
                        ?>

                        <article id="ata-<?php echo esc_attr( $id ); ?>" class="ata-card">

                          <div class="ata-card__icon" aria-hidden="true">
                              <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="48"
                                  height="56"
                                  viewBox="0 0 48 56"
                                  fill="none"
                                  focusable="false"
                              >
                                  <path
                                      d="M7 2H30L42 14V54H7C4.24 54 2 51.76 2 49V7C2 4.24 4.24 2 7 2Z"
                                      stroke="currentColor"
                                      stroke-width="3"
                                      stroke-linejoin="round"
                                  />
                                  <path
                                      d="M30 2V14H42"
                                      stroke="currentColor"
                                      stroke-width="3"
                                      stroke-linejoin="round"
                                  />
                                  <rect
                                      x="8"
                                      y="32"
                                      width="33"
                                      height="17"
                                      rx="3"
                                      fill="currentColor"
                                  />
                                  <text
                                      x="24.5"
                                      y="44.5"
                                      text-anchor="middle"
                                      font-size="11"
                                      font-family="Arial, sans-serif"
                                      font-weight="700"
                                      fill="white"
                                  >PDF</text>
                              </svg>
                          </div>

                          <div class="ata-card__body">

                                <div class="ata-card__tag">
                                    <?php
                                    echo esc_html(
                                        ! is_wp_error( $type_names ) && $type_names
                                            ? implode( ', ', $type_names )
                                            : __( 'Ata', 'conferp' )
                                    );
                                    ?>
                                </div>

                                <h2>
                                    <?php echo esc_html( get_the_title( $id ) ); ?>
                                </h2>

                                <?php
                                $meeting_date = conferp_atas_public_date( $id );
                                ?>

                                <?php if ( $meeting_date ) : ?>
                                    <p class="ata-card__date">
                                        <span aria-hidden="true">◷</span>
                                        <?php echo esc_html( $meeting_date ); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if (
                                    ! is_wp_error( $subject_names ) &&
                                    ! empty( $subject_names )
                                ) : ?>

                                    <p class="ata-card__subjects">
                                        <strong>
                                            <?php esc_html_e( 'Assuntos:', 'conferp' ); ?>
                                        </strong>

                                        <?php echo esc_html( implode( ', ', $subject_names ) ); ?>
                                    </p>

                                <?php endif; ?>

                            </div>

                            <div class="ata-card__documents">

                                <?php if ( $pdf_url ) : ?>

                                    <a
                                        class="ata-card__pdf"
                                        href="<?php echo esc_url( $pdf_url ); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        aria-label="<?php echo esc_attr(
                                            sprintf(
                                                __( 'Visualizar PDF principal: %s', 'conferp' ),
                                                get_the_title( $id )
                                            )
                                        ); ?>"
                                    >
                                        <?php esc_html_e( 'Visualizar PDF', 'conferp' ); ?>
                                        <span aria-hidden="true">↗</span>
                                    </a>

                                <?php endif; ?>

                                <?php if ( $extras ) : ?>

                                    <?php
                                    $valid_extras = array();

                                    foreach ( $extras as $extra_id ) {
                                        $extra_id = absint( $extra_id );

                                        if (
                                            'application/pdf' !== get_post_mime_type( $extra_id )
                                        ) {
                                            continue;
                                        }

                                        $extra_url = wp_get_attachment_url( $extra_id );

                                        if ( $extra_url ) {
                                            $valid_extras[] = $extra_url;
                                        }
                                    }
                                    ?>

                                    <?php if ( $valid_extras ) : ?>

                                        <div class="ata-card__extras">

                                            <strong>
                                                <?php esc_html_e( 'Anexos:', 'conferp' ); ?>
                                            </strong>

                                            <?php foreach ( $valid_extras as $index => $extra_url ) : ?>

                                                <a
                                                    href="<?php echo esc_url( $extra_url ); ?>"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <?php
                                                    echo esc_html(
                                                        sprintf(
                                                            __( 'Anexo %d', 'conferp' ),
                                                            $index + 1
                                                        )
                                                    );
                                                    ?>
                                                    <span aria-hidden="true">↗</span>
                                                </a>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php endif; ?>

                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endforeach; ?>

                    <?php if ( ! $visible ) : ?>

                        <p class="atas-page__empty">
                            <?php esc_html_e(
                                'Nenhuma ata encontrada com os filtros selecionados.',
                                'conferp'
                            ); ?>
                        </p>

                    <?php endif; ?>

                </div>

                <?php
                // Componente global de carregamento progressivo.
                if ( $pages > 1 ) {
                    get_template_part(
                        'template-parts/global/load-more',
                        null,
                        array(
                            'list'       => '.atas-lista',
                            'card'       => '.ata-card',
                            'pagination' => '.atas-pagination',
                            'total'      => $total,
                        )
                    );
                }
                ?>

                <!-- Paginação -->
                <?php if ( $pages > 1 ) : ?>

                    <nav
                        class="atas-pagination"
                        aria-label="<?php esc_attr_e( 'Paginação das atas', 'conferp' ); ?>"
                    >

                        <?php
                        $pagination_args = array_filter(
                            array(
                                'ata_busca'   => $filters['q'],
                                'ata_tipo'    => $filters['tipo'],
                                'ata_assunto' => $filters['assunto'],
                                'ata_ano'     => $filters['ano'],
                                'ata_gestao'  => $filters['gestao'],
                                'ata_ordem'   => $filters['ordem'],
                            )
                        );

                        for ( $n = 1; $n <= $pages; $n++ ) {

                            $pagination_url = add_query_arg(
                                $pagination_args,
                                get_pagenum_link( $n )
                            );

                            printf(
                                '<a href="%s"%s>%d</a>',
                                esc_url( $pagination_url ),
                                $n === $page
                                    ? ' aria-current="page"'
                                    : '',
                                $n
                            );
                        }
                        ?>

                        <?php if ( $page < $pages ) : ?>
                            <a class="next page-numbers" href="<?php echo esc_url( add_query_arg( $pagination_args, get_pagenum_link( $page + 1 ) ) ); ?>" rel="next">
                                <?php esc_html_e( 'Próximo »', 'conferp' ); ?>
                            </a>
                        <?php endif; ?>

                    </nav>

                <?php endif; ?>

            </div>

            <div class="atas-layout__sidebar">
                <?php
                get_template_part(
                    'template-parts/atas/atas-sidebar',
                    null,
                    array(
                        'types'       => $types,
                        'years'       => $years,
                        'filters'     => $filters,
                        'archive_url' => $archive_url,
                    )
                );
                ?>
            </div>

        </div><!-- .atas-layout -->

    </div><!-- .atas-results -->

</main>

<?php get_footer(); ?>
