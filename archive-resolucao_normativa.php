<?php
/** Archive — Resoluções Normativas. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$filters = conferp_rn_filters();
$documents = conferp_rn_query( $filters );
$years = conferp_rn_years();
$topics = get_terms( array( 'taxonomy' => 'resolucao_assunto', 'hide_empty' => true, 'orderby' => 'name' ) );
if ( is_wp_error( $topics ) ) { $topics = array(); }
$archive_url = get_post_type_archive_link( 'resolucao_normativa' );
?>
<main id="primary" class="site-main resolucoes-page">
    <?php get_template_part( 'template-parts/global/page-heading', null, array(
        'title' => __( 'Resoluções', 'conferp' ),
        'highlight' => __( 'Normativas', 'conferp' ),
        'description' => __( 'Consulte as Resoluções Normativas do Sistema Conferp.', 'conferp' ),
    ) ); ?>
    <section class="resolucoes-search" aria-label="<?php esc_attr_e( 'Pesquisar resoluções normativas', 'conferp' ); ?>">
        <div class="container">
            <form class="resolucoes-search__form" action="<?php echo esc_url( $archive_url ); ?>" method="get" role="search">
                <div class="resolucoes-search__field">
                    <label for="rn-busca"><?php esc_html_e( 'Pesquisar resoluções', 'conferp' ); ?></label>
                    <input id="rn-busca" type="search" name="rn_busca" value="<?php echo esc_attr( $filters['q'] ); ?>" placeholder="<?php esc_attr_e( 'Buscar por título, número, ementa ou texto do documento...', 'conferp' ); ?>">
                </div>
                <div class="resolucoes-search__controls">
                    <div class="resolucoes-search__field">
                        <label for="rn-ano"><?php esc_html_e( 'Ano do documento', 'conferp' ); ?></label>
                        <select id="rn-ano" name="rn_ano">
                            <option value=""><?php esc_html_e( 'Todos os anos', 'conferp' ); ?></option>
                            <?php foreach ( $years as $year ) : ?>
                                <option value="<?php echo esc_attr( $year ); ?>" <?php selected( $filters['ano'], $year ); ?>><?php echo esc_html( $year ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="resolucoes-search__field">
                        <label for="rn-ordem"><?php esc_html_e( 'Ordenação', 'conferp' ); ?></label>
                        <select id="rn-ordem" name="rn_ordem">
                            <option value="recentes" <?php selected( $filters['ordem'], 'recentes' ); ?>><?php esc_html_e( 'Mais recentes', 'conferp' ); ?></option>
                            <option value="antigas" <?php selected( $filters['ordem'], 'antigas' ); ?>><?php esc_html_e( 'Mais antigas', 'conferp' ); ?></option>
                        </select>
                    </div>
                </div>
                <?php if ( $filters['assunto'] ) : ?><input type="hidden" name="rn_assunto" value="<?php echo esc_attr( $filters['assunto'] ); ?>"><?php endif; ?>
                <div class="resolucoes-search__actions">
                    <button type="submit"><?php esc_html_e( 'Filtrar documentos', 'conferp' ); ?></button>
                    <a class="resolucoes-search__clear" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Limpar filtros', 'conferp' ); ?></a>
                </div>
            </form>
        </div>
    </section>
    <div class="resolucoes-results container">
        <p class="resolucoes-page__count" aria-live="polite"><?php
            printf( esc_html( _n( '%d resolução encontrada', '%d resoluções encontradas', $documents->found_posts, 'conferp' ) ), absint( $documents->found_posts ) );
        ?></p>
        <div class="resolucoes-layout">
            <div class="resolucoes-layout__main">
                <div class="resolucoes-lista" id="resolucoes-lista">
                    <?php if ( $documents->have_posts() ) : ?>
                        <?php while ( $documents->have_posts() ) : $documents->the_post(); ?>
                            <?php
                            $post_id = get_the_ID();
                            $number = get_post_meta( $post_id, '_resolucao_numero', true );
                            $date = conferp_rn_date( $post_id );
                            $summary = get_post_meta( $post_id, '_resolucao_ementa', true );
                            $assigned_topics = get_the_terms( $post_id, 'resolucao_assunto' );
                            ?>
                            <article <?php post_class( 'resolucoes-card' ); ?>>
                                <div class="resolucoes-card__icon" aria-hidden="true">
                                    <svg viewBox="0 0 48 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M7 2H30L43 15V52C43 53.1 42.1 54 41 54H7C5.9 54 5 53.1 5 52V4C5 2.9 5.9 2 7 2Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M30 2V15H43M13 27H35M13 35H35M13 43H29" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="resolucoes-card__body">
                                    <div class="resolucoes-card__meta">
                                        <span class="resolucoes-card__type"><?php esc_html_e( 'Resolução Normativa', 'conferp' ); ?></span>
                                        <?php if ( $number ) : ?><span><?php echo esc_html( sprintf( __( 'Nº %s', 'conferp' ), $number ) ); ?></span><?php endif; ?>
                                        <?php if ( $date ) : ?><time><?php echo esc_html( $date ); ?></time><?php endif; ?>
                                    </div>
                                    <h2 class="resolucoes-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <?php if ( $summary ) : ?><p class="resolucoes-card__summary"><strong><?php esc_html_e( 'Ementa:', 'conferp' ); ?></strong> <em><?php echo esc_html( $summary ); ?></em></p><?php endif; ?>
                                    <?php if ( $assigned_topics && ! is_wp_error( $assigned_topics ) ) : ?>
                                        <p class="resolucoes-card__topics"><strong><?php esc_html_e( 'Assuntos:', 'conferp' ); ?></strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $assigned_topics, 'name' ) ) ); ?></p>
                                    <?php endif; ?>
                                </div>
                                <a class="resolucoes-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Ler documento', 'conferp' ); ?> <span aria-hidden="true">↗</span></a>
                            </article>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p class="resolucoes-page__empty"><?php esc_html_e( 'Nenhuma resolução encontrada para os filtros selecionados.', 'conferp' ); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ( $documents->max_num_pages > 1 ) : ?>
                    <?php
                    get_template_part(
                        'template-parts/global/load-more',
                        null,
                        array(
                            'list'       => '#resolucoes-lista',
                            'card'       => '.resolucoes-card',
                            'pagination' => '.resolucoes-pagination',
                            'total'      => $documents->found_posts,
                        )
                    );
                    ?>
                    <nav class="resolucoes-pagination" aria-label="<?php esc_attr_e( 'Paginação de resoluções', 'conferp' ); ?>">
                        <?php
                        echo wp_kses_post( paginate_links( array(
                            'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
                            'total' => $documents->max_num_pages,
                            'type' => 'list',
                            'add_args' => array_filter( array(
                                'rn_busca' => $filters['q'],
                                'rn_ano' => $filters['ano'],
                                'rn_assunto' => $filters['assunto'],
                                'rn_ordem' => $filters['ordem'],
                            ) ),
                        ) ) );
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
            <aside class="resolucoes-layout__sidebar" aria-label="<?php esc_attr_e( 'Filtros por assunto e ano', 'conferp' ); ?>">
                <?php $topic_query_args = array_filter( array( 'rn_busca' => $filters['q'], 'rn_ano' => $filters['ano'], 'rn_ordem' => $filters['ordem'] ) ); ?>
                <section class="resolucoes-widget">
                    <h2 class="resolucoes-widget__title"><?php esc_html_e( 'ACESSO RÁPIDO', 'conferp' ); ?></h2>
                    <div class="resolucoes-widget__body">
                        <?php foreach ( $topics as $topic ) : ?>
                            <a <?php if ( $filters['assunto'] === $topic->slug ) : ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url( add_query_arg( array_merge( $topic_query_args, array( 'rn_assunto' => $topic->slug ) ), $archive_url ) ); ?>">
                                <span><?php echo esc_html( $topic->name ); ?></span><span class="resolucoes-widget__count"><?php echo esc_html( $topic->count ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <section class="resolucoes-widget">
                    <h2 class="resolucoes-widget__title"><?php esc_html_e( 'ANO', 'conferp' ); ?></h2>
                    <div class="resolucoes-widget__body">
                        <?php $year_query_args = array_filter( array( 'rn_busca' => $filters['q'], 'rn_assunto' => $filters['assunto'], 'rn_ordem' => $filters['ordem'] ) ); ?>
                        <?php foreach ( $years as $year ) : ?>
                            <a <?php if ( $filters['ano'] === $year ) : ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url( add_query_arg( array_merge( $year_query_args, array( 'rn_ano' => $year ) ), $archive_url ) ); ?>"><?php echo esc_html( $year ); ?></a>
                        <?php endforeach; ?>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</main>
<?php wp_reset_postdata(); get_footer(); ?>
