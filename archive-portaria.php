<?php
/** Archive — Portarias. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$filters = conferp_pt_filters();
$documents = conferp_pt_query( $filters );
$years = conferp_pt_years();
$situations = get_terms( array( 'taxonomy' => 'portaria_situacao', 'hide_empty' => true, 'orderby' => 'name' ) );
if ( is_wp_error( $situations ) ) { $situations = array(); }
$topics = get_terms( array( 'taxonomy' => 'portaria_assunto', 'hide_empty' => true, 'orderby' => 'name' ) );
if ( is_wp_error( $topics ) ) { $topics = array(); }
$archive_url = get_post_type_archive_link( 'portaria' );
?>
<main id="primary" class="site-main portarias-page">
    <?php get_template_part( 'template-parts/global/page-heading', null, array(
        'title' => __( 'Portarias', 'conferp' ),
        'description' => __( 'Consulte as Portarias emitidas pelo CONFERP.', 'conferp' ),
    ) ); ?>
    <section class="portarias-search" aria-label="<?php esc_attr_e( 'Pesquisar portarias', 'conferp' ); ?>">
        <div class="container">
            <form class="portarias-search__form" action="<?php echo esc_url( $archive_url ); ?>" method="get" role="search">
                <div class="portarias-search__field">
                    <label for="pt-busca"><?php esc_html_e( 'Pesquisar portarias', 'conferp' ); ?></label>
                    <input id="pt-busca" type="search" name="pt_busca" value="<?php echo esc_attr( $filters['q'] ); ?>" placeholder="<?php esc_attr_e( 'Buscar por título, número, ementa ou texto do documento...', 'conferp' ); ?>">
                </div>
                <div class="portarias-search__controls">
                    <div class="portarias-search__field"><label for="pt-numero">Número da portaria</label><input id="pt-numero" name="pt_numero" type="text" inputmode="numeric" pattern="[0-9]*" value="<?php echo esc_attr( $filters['numero'] ); ?>" placeholder="Ex.: 230"></div>
                    <div class="portarias-search__field"><label for="pt-situacao">Situação</label><select id="pt-situacao" name="pt_situacao"><option value="">Todas as situações</option><?php foreach ( $situations as $situation ) : ?><option value="<?php echo esc_attr( $situation->slug ); ?>" <?php selected( $filters['situacao'], $situation->slug ); ?>><?php echo esc_html( $situation->name ); ?></option><?php endforeach; ?></select></div>
                    <div class="portarias-search__field">
                        <label for="pt-ano"><?php esc_html_e( 'Ano do documento', 'conferp' ); ?></label>
                        <select id="pt-ano" name="pt_ano">
                            <option value=""><?php esc_html_e( 'Todos os anos', 'conferp' ); ?></option>
                            <?php foreach ( $years as $year ) : ?>
                                <option value="<?php echo esc_attr( $year ); ?>" <?php selected( $filters['ano'], $year ); ?>><?php echo esc_html( $year ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="portarias-search__field">
                        <label for="pt-ordem"><?php esc_html_e( 'Ordenação', 'conferp' ); ?></label>
                        <select id="pt-ordem" name="pt_ordem">
                            <option value="recentes" <?php selected( $filters['ordem'], 'recentes' ); ?>><?php esc_html_e( 'Mais recentes', 'conferp' ); ?></option>
                            <option value="antigas" <?php selected( $filters['ordem'], 'antigas' ); ?>><?php esc_html_e( 'Mais antigas', 'conferp' ); ?></option>
                        </select>
                    </div>
                </div>
                <?php if ( $filters['assunto'] ) : ?><input type="hidden" name="pt_assunto" value="<?php echo esc_attr( $filters['assunto'] ); ?>"><?php endif; ?>
                <div class="portarias-search__actions">
                    <button type="submit"><?php esc_html_e( 'Filtrar documentos', 'conferp' ); ?></button>
                    <a class="portarias-search__clear" href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Limpar filtros', 'conferp' ); ?></a>
                </div>
            </form>
        </div>
    </section>
    <div class="portarias-results container">
        <p class="portarias-page__count" aria-live="polite"><?php
            printf( esc_html( _n( '%d portaria encontrada', '%d portarias encontradas', $documents->found_posts, 'conferp' ) ), absint( $documents->found_posts ) );
        ?></p>
        <div class="portarias-layout">
            <div class="portarias-layout__main">
                <div class="portarias-lista" id="portarias-lista">
                    <?php if ( $documents->have_posts() ) : ?>
                        <?php while ( $documents->have_posts() ) : $documents->the_post(); ?>
                            <?php
                            $post_id = get_the_ID();
                            $number = get_post_meta( $post_id, '_portaria_numero', true );
                            $date = conferp_pt_date( $post_id );
                            $summary = get_post_meta( $post_id, '_portaria_ementa', true );
                            $assigned_topics = get_the_terms( $post_id, 'portaria_assunto' );
                             $assigned_situations = get_the_terms( $post_id, 'portaria_situacao' );
                            ?>
                            <article <?php post_class( 'portarias-card' ); ?>>
                                <div class="portarias-card__icon" aria-hidden="true">
                                    <svg viewBox="0 0 48 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M7 2H30L43 15V52C43 53.1 42.1 54 41 54H7C5.9 54 5 53.1 5 52V4C5 2.9 5.9 2 7 2Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M30 2V15H43M13 27H35M13 35H35M13 43H29" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <div class="portarias-card__body">
                                    <div class="portarias-card__meta">
                                        <span class="portarias-card__type"><?php esc_html_e( 'Portaria', 'conferp' ); ?></span>
                                        <?php if ( $assigned_situations && ! is_wp_error( $assigned_situations ) ) : ?><span class="portarias-card__status"><?php echo esc_html( implode( ', ', wp_list_pluck( $assigned_situations, 'name' ) ) ); ?></span><?php endif; ?>
                                         <?php if ( $number ) : ?><span><?php echo esc_html( sprintf( __( 'Nº %s', 'conferp' ), $number ) ); ?></span><?php endif; ?>
                                        <?php if ( $date ) : ?><time><?php echo esc_html( $date ); ?></time><?php endif; ?>
                                    </div>
                                    <h2 class="portarias-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <?php if ( $summary ) : ?><p class="portarias-card__summary"><strong><?php esc_html_e( 'Ementa:', 'conferp' ); ?></strong> <em><?php echo esc_html( $summary ); ?></em></p><?php endif; ?>
                                    <?php if ( $assigned_topics && ! is_wp_error( $assigned_topics ) ) : ?>
                                        <p class="portarias-card__topics"><strong><?php esc_html_e( 'Assuntos:', 'conferp' ); ?></strong> <?php echo esc_html( implode( ', ', wp_list_pluck( $assigned_topics, 'name' ) ) ); ?></p>
                                    <?php endif; ?>
                                </div>
                                <a class="portarias-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Acessar portaria', 'conferp' ); ?> <span aria-hidden="true">↗</span></a>
                            </article>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <p class="portarias-page__empty"><?php esc_html_e( 'Nenhuma portaria encontrada para os filtros selecionados.', 'conferp' ); ?></p>
                    <?php endif; ?>
                </div>
                <?php if ( $documents->max_num_pages > 1 ) : ?>
                    <?php
                    get_template_part(
                        'template-parts/global/load-more',
                        null,
                        array(
                            'list'       => '#portarias-lista',
                            'card'       => '.portarias-card',
                            'pagination' => '.portarias-pagination',
                            'total'      => $documents->found_posts,
                        )
                    );
                    ?>
                    <nav class="portarias-pagination" aria-label="<?php esc_attr_e( 'Paginação de portarias', 'conferp' ); ?>">
                        <?php
                        echo wp_kses_post( paginate_links( array(
                            'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max( 1, absint( get_query_var( 'paged' ) ) ),
                            'total' => $documents->max_num_pages,
                            'type' => 'list',
                            'add_args' => array_filter( array(
                                'pt_busca' => $filters['q'],
                                'pt_ano' => $filters['ano'],
                                'pt_assunto' => $filters['assunto'],
                                 'pt_situacao' => $filters['situacao'],
                                 'pt_numero' => $filters['numero'],
                                'pt_ordem' => $filters['ordem'],
                            ) ),
                        ) ) );
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
            <aside class="portarias-layout__sidebar" aria-label="<?php esc_attr_e( 'Filtros por assunto e ano', 'conferp' ); ?>">
                <?php $topic_query_args = array_filter( array( 'pt_busca' => $filters['q'], 'pt_ano' => $filters['ano'], 'pt_situacao' => $filters['situacao'], 'pt_numero' => $filters['numero'], 'pt_ordem' => $filters['ordem'] ) ); ?>
                <section class="portarias-widget">
                    <h2 class="portarias-widget__title"><?php esc_html_e( 'SITUAÇÃO', 'conferp' ); ?></h2>
                    <div class="portarias-widget__body">
                        <?php foreach ( $situations as $situation ) : ?>
                            <a <?php if ( $filters['situacao'] === $situation->slug ) : ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url( add_query_arg( array_filter( array( 'pt_busca' => $filters['q'], 'pt_ano' => $filters['ano'], 'pt_numero' => $filters['numero'], 'pt_assunto' => $filters['assunto'], 'pt_ordem' => $filters['ordem'], 'pt_situacao' => $situation->slug ) ), $archive_url ) ); ?>"><span><?php echo esc_html( $situation->name ); ?></span><span class="portarias-widget__count"><?php echo esc_html( $situation->count ); ?></span></a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <section class="portarias-widget">
                    <h2 class="portarias-widget__title"><?php esc_html_e( 'ACESSO RÁPIDO', 'conferp' ); ?></h2>
                    <div class="portarias-widget__body">
                        <?php foreach ( $topics as $topic ) : ?>
                            <a <?php if ( $filters['assunto'] === $topic->slug ) : ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url( add_query_arg( array_merge( $topic_query_args, array( 'pt_assunto' => $topic->slug ) ), $archive_url ) ); ?>">
                                <span><?php echo esc_html( $topic->name ); ?></span><span class="portarias-widget__count"><?php echo esc_html( $topic->count ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <section class="portarias-widget">
                    <h2 class="portarias-widget__title"><?php esc_html_e( 'ANO', 'conferp' ); ?></h2>
                    <div class="portarias-widget__body">
                        <?php $year_query_args = array_filter( array( 'pt_busca' => $filters['q'], 'pt_assunto' => $filters['assunto'],
                                 'pt_situacao' => $filters['situacao'],
                                 'pt_numero' => $filters['numero'], 'pt_ordem' => $filters['ordem'] ) ); ?>
                        <?php foreach ( $years as $year ) : ?>
                            <a <?php if ( $filters['ano'] === $year ) : ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url( add_query_arg( array_merge( $year_query_args, array( 'pt_ano' => $year ) ), $archive_url ) ); ?>"><?php echo esc_html( $year ); ?></a>
                        <?php endforeach; ?>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</main>
<?php wp_reset_postdata(); get_footer(); ?>
