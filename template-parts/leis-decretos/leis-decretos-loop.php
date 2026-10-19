<?php
/** Results loop. @package conferp */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$documents = $args['documents'];
?>
<div class="leis-decretos-lista">
<?php if ( $documents->have_posts() ) : while ( $documents->have_posts() ) : $documents->the_post();
    get_template_part( 'template-parts/leis-decretos/leis-decretos-card' );
endwhile; else : ?>
    <p class="leis-decretos-page__empty"><?php esc_html_e( 'Nenhum documento encontrado com os filtros selecionados.', 'conferp' ); ?></p>
<?php endif; ?>
</div>
