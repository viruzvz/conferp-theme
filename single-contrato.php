<?php
/** Ficha pública de contrato administrativo — CONFERP. @package conferp */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="primary" class="site-main contrato-single">
<?php while ( have_posts() ) : the_post();
    $id = get_the_ID();
    $meta = static function ( $key ) use ( $id ) { return get_post_meta( $id, '_contrato_' . $key, true ); };
    $format_date = static function ( $date ) { if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) { return ''; } return date_i18n( 'd/m/Y', strtotime( $date . ' 12:00:00' ) ); };
    $format_money = static function ( $value ) { if ( '' === (string) $value ) { return ''; } $value = trim( (string) $value ); $value = str_replace( array( 'R$', ' ', '.' ), '', $value ); $value = str_replace( ',', '.', $value ); return is_numeric( $value ) ? 'R$ ' . number_format( (float) $value, 2, ',', '.' ) : ''; };
    $terms = static function ( $taxonomy ) use ( $id ) { $list = get_the_terms( $id, $taxonomy ); return ( ! is_wp_error( $list ) && $list ) ? implode( ', ', wp_list_pluck( $list, 'name' ) ) : ''; };
    $pdf_id = absint( $meta( 'pdf_id' ) );
    $pdf_url = $pdf_id && 'application/pdf' === get_post_mime_type( $pdf_id ) ? wp_get_attachment_url( $pdf_id ) : '';
    $vigencia = $meta( 'vigencia_tipo' );
    $dados = array(
        'Número do contrato' => $meta( 'numero' ),
        'Processo administrativo' => $meta( 'processo' ),
        'Contratada' => $meta( 'contratada' ),
        'CNPJ/CPF da contratada' => $meta( 'documento' ),
        'Tipo de contrato' => $terms( 'contrato_tipo' ),
        'Situação' => $terms( 'contrato_situacao' ),
        'Valor inicial' => $format_money( $meta( 'valor_inicial' ) ),
        'Valor atualizado' => $format_money( $meta( 'valor_atualizado' ) ),
        'Data de assinatura' => $format_date( $meta( 'assinatura' ) ),
        'Prazo de vigência' => 'indeterminado' === $vigencia ? 'Prazo indeterminado' : ( 'determinado' === $vigencia ? 'Prazo determinado' : '' ),
    );
    if ( 'determinado' === $vigencia ) {
        $dados['Início da vigência'] = $format_date( $meta( 'vigencia_inicio' ) );
        $dados['Fim da vigência'] = $format_date( $meta( 'vigencia_fim' ) );
    }
    get_template_part( 'template-parts/global/page-heading', null, array( 'title' => get_the_title() ) );
?>
<div class="container contrato-single__container">
<article id="post-<?php the_ID(); ?>" <?php post_class( 'contrato-resumo' ); ?>>
    <header class="contrato-resumo__header">
        <p class="contrato-resumo__eyebrow">Transparência · Contratos administrativos</p>
        <h2 class="contrato-resumo__title">Resumo do contrato</h2>
    </header>
    <dl class="contrato-resumo__grid">
        <?php foreach ( $dados as $label => $value ) : if ( '' === (string) $value ) { continue; } ?>
            <div class="contrato-resumo__field"><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
        <?php endforeach; ?>
    </dl>
    <?php foreach ( array( 'Objeto do contrato' => $meta( 'objeto' ), 'Fundamentação legal' => $meta( 'fundamentacao' ) ) as $label => $value ) : if ( ! $value ) { continue; } ?>
        <section class="contrato-resumo__section"><h3><?php echo esc_html( $label ); ?></h3><p><?php echo nl2br( esc_html( $value ) ); ?></p></section>
    <?php endforeach; ?>
    <?php if ( trim( get_the_content() ) ) : ?><section class="contrato-resumo__section entry-content"><h3>Informações complementares</h3><?php the_content(); ?></section><?php endif; ?>
    <section class="contrato-resumo__section contrato-resumo__documents" aria-label="Documentação oficial">
        <h3>Contrato original</h3>
        <?php if ( $pdf_url ) : ?>
            <div class="contrato-resumo__actions">
                <a class="contrato-resumo__button" href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener noreferrer">Visualizar PDF</a>
                <a class="contrato-resumo__button contrato-resumo__button--secondary" href="<?php echo esc_url( add_query_arg( 'conferp_contrato_download', $id, home_url( '/' ) ) ); ?>">Baixar PDF</a>
                <a class="contrato-resumo__button contrato-resumo__button--secondary" href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener noreferrer">Abrir PDF para impressão</a>
            </div>
            <p class="contrato-resumo__hint">Para imprimir, abra o PDF e utilize a opção de impressão do visualizador.</p>
        <?php else : ?><p>O PDF original ainda não está disponível.</p><?php endif; ?>
    </section>
    <?php $docs = get_post_meta( $id, '_contrato_documentos', true ); if ( is_array( $docs ) && $docs ) : ?>
        <section class="contrato-resumo__section"><h3>Termos aditivos, apostilamentos e anexos</h3><div class="contrato-resumo__attachments">
        <?php $tipos = array( 'aditivo' => 'Termo aditivo', 'apostilamento' => 'Apostilamento', 'anexo' => 'Anexo complementar', 'outro' => 'Outro documento' );
        foreach ( $docs as $doc ) :
            if ( ! is_array( $doc ) ) { continue; }
            $attachment_id = absint( $doc['id'] ?? 0 );
            $url = $attachment_id ? wp_get_attachment_url( $attachment_id ) : '';
            if ( ! $url ) { continue; }
            $tipo = $tipos[ $doc['tipo'] ?? '' ] ?? 'Documento'; ?>
            <div class="contrato-resumo__attachment"><div><strong><?php echo esc_html( $doc['titulo'] ?: $tipo ); ?></strong><span><?php echo esc_html( $tipo ); ?><?php if ( ! empty( $doc['data'] ) ) : ?> · <?php echo esc_html( $format_date( $doc['data'] ) ); ?><?php endif; ?></span><?php if ( ! empty( $doc['descricao'] ) ) : ?><p><?php echo esc_html( $doc['descricao'] ); ?></p><?php endif; ?></div><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">Visualizar arquivo</a></div>
        <?php endforeach; ?></div></section>
    <?php endif; ?>
</article></div>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
