<?php
/** Archive de Contratos Administrativos — CONFERP. */
defined('ABSPATH') || exit;
get_header();
$f=conferp_contratos_filters(); $docs=conferp_contratos_query($f); $years=conferp_contratos_years();
$situations=get_terms(array('taxonomy'=>'contrato_situacao','hide_empty'=>true,'orderby'=>'name'));
$types=get_terms(array('taxonomy'=>'contrato_tipo','hide_empty'=>true,'orderby'=>'name'));
if(is_wp_error($situations)) $situations=array(); if(is_wp_error($types)) $types=array();
$url=get_post_type_archive_link('contrato');
$common=array_filter(array('ct_busca'=>$f['q'],'ct_numero'=>$f['numero'],'ct_situacao'=>$f['situacao'],'ct_tipo'=>$f['tipo'],'ct_ano'=>$f['ano'],'ct_ordem'=>$f['ordem']));
?>
<main id="primary" class="site-main contratos-page">
<?php get_template_part('template-parts/global/page-heading',null,array('title'=>__('Contratos','conferp'),'description'=>__('Consulte os contratos administrativos do CONFERP.','conferp'))); ?>
<section class="contratos-search" aria-label="Pesquisar contratos"><div class="container">
<form class="contratos-search__form" action="<?php echo esc_url($url); ?>" method="get" role="search">
<div class="contratos-search__field"><label for="ct-busca">Pesquisar contratos</label><input id="ct-busca" type="search" name="ct_busca" value="<?php echo esc_attr($f['q']); ?>" placeholder="Buscar por número, processo, contratada ou objeto..."></div>
<div class="contratos-search__controls">
<div class="contratos-search__field"><label for="ct-numero">Número do contrato</label><input id="ct-numero" name="ct_numero" type="text" value="<?php echo esc_attr($f['numero']); ?>" placeholder="Ex.: 002/2026"></div>
<div class="contratos-search__field"><label for="ct-situacao">Situação</label><select id="ct-situacao" name="ct_situacao"><option value="">Todas as situações</option><?php foreach($situations as $t): ?><option value="<?php echo esc_attr($t->slug); ?>" <?php selected($f['situacao'],$t->slug); ?>><?php echo esc_html($t->name); ?></option><?php endforeach; ?></select></div>
<div class="contratos-search__field"><label for="ct-ano">Ano da assinatura</label><select id="ct-ano" name="ct_ano"><option value="">Todos os anos</option><?php foreach($years as $y): ?><option value="<?php echo esc_attr($y); ?>" <?php selected($f['ano'],$y); ?>><?php echo esc_html($y); ?></option><?php endforeach; ?></select></div>
<div class="contratos-search__field"><label for="ct-ordem">Ordenação</label><select id="ct-ordem" name="ct_ordem"><option value="recentes" <?php selected($f['ordem'],'recentes'); ?>>Mais recentes</option><option value="antigas" <?php selected($f['ordem'],'antigas'); ?>>Mais antigos</option></select></div>
</div>
<?php if($f['tipo']): ?><input type="hidden" name="ct_tipo" value="<?php echo esc_attr($f['tipo']); ?>"><?php endif; ?>
<div class="contratos-search__actions"><button type="submit">Filtrar documentos</button><a class="contratos-search__clear" href="<?php echo esc_url($url); ?>">Limpar filtros</a></div>
</form></div></section>
<div class="contratos-results container"><p class="contratos-page__count" aria-live="polite"><?php printf(esc_html(_n('%d contrato encontrado','%d contratos encontrados',$docs->found_posts,'conferp')),absint($docs->found_posts)); ?></p>
<div class="contratos-layout"><div class="contratos-layout__main"><div class="contratos-lista" id="contratos-lista">
<?php if($docs->have_posts()): while($docs->have_posts()): $docs->the_post();
$id=get_the_ID(); $number=get_post_meta($id,'_contrato_numero',true); $company=get_post_meta($id,'_contrato_contratada',true); $object=get_post_meta($id,'_contrato_objeto',true); $value=get_post_meta($id,'_contrato_valor_atualizado',true); if(''===$value) $value=get_post_meta($id,'_contrato_valor_inicial',true);
$start=get_post_meta($id,'_contrato_vigencia_inicio',true); $end=get_post_meta($id,'_contrato_vigencia_fim',true); $term=get_post_meta($id,'_contrato_vigencia_tipo',true);
$status=get_the_terms($id,'contrato_situacao'); $type=get_the_terms($id,'contrato_tipo'); $pdf_id=absint(get_post_meta($id,'_contrato_pdf_id',true)); $pdf_url=$pdf_id && 'application/pdf'===get_post_mime_type($pdf_id)?wp_get_attachment_url($pdf_id):'';
?>
<article <?php post_class('contratos-card'); ?>>
<div class="contratos-card__icon" aria-hidden="true"><svg viewBox="0 0 48 56" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M7 2H30L43 15V52C43 53.1 42.1 54 41 54H7C5.9 54 5 53.1 5 52V4C5 2.9 5.9 2 7 2Z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M30 2V15H43M13 27H35M13 35H35M13 43H29" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
<div class="contratos-card__body"><div class="contratos-card__meta"><span class="contratos-card__type">Contrato administrativo</span><?php if($status && !is_wp_error($status)): ?><span class="contratos-card__status"><?php echo esc_html(implode(', ',wp_list_pluck($status,'name'))); ?></span><?php endif; ?><?php if($number): ?><span>Nº <?php echo esc_html($number); ?></span><?php endif; ?><?php if(conferp_contratos_date($id)): ?><span>Assinado em <?php echo esc_html(conferp_contratos_date($id)); ?></span><?php endif; ?></div>
<h2 class="contratos-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<?php if($company): ?><p class="contratos-card__detail"><strong>Contratada:</strong> <?php echo esc_html($company); ?></p><?php endif; ?>
<?php if($object): ?><p class="contratos-card__summary"><strong>Objeto:</strong> <?php echo esc_html(wp_trim_words($object,30,'…')); ?></p><?php endif; ?>
<?php if($value): ?><p class="contratos-card__detail"><strong>Valor:</strong> R$ <?php echo esc_html($value); ?></p><?php endif; ?>
<?php if($term==='indeterminado'): ?><p class="contratos-card__detail"><strong>Vigência:</strong> Prazo indeterminado</p><?php elseif($term==='determinado' && ($start || $end)): ?><p class="contratos-card__detail"><strong>Vigência:</strong> <?php echo esc_html($start?date_i18n('d/m/Y',strtotime($start)):'—'); ?> a <?php echo esc_html($end?date_i18n('d/m/Y',strtotime($end)):'—'); ?></p><?php endif; ?>
<?php if($type && !is_wp_error($type)): ?><p class="contratos-card__detail"><strong>Tipo:</strong> <?php echo esc_html(implode(', ',wp_list_pluck($type,'name'))); ?></p><?php endif; ?>
</div><div class="contratos-card__actions"><a class="contratos-card__link" href="<?php the_permalink(); ?>">Ver resumo <span aria-hidden="true">↗</span></a><?php if($pdf_url): ?><a class="contratos-card__link contratos-card__link--outline" href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener noreferrer">Visualizar PDF <span aria-hidden="true">↗</span></a><?php endif; ?></div>
</article>
<?php endwhile; else: ?><p class="contratos-page__empty">Nenhum contrato encontrado para os filtros selecionados.</p><?php endif; ?>
</div>
<?php if($docs->max_num_pages>1): get_template_part('template-parts/global/load-more',null,array('list'=>'#contratos-lista','card'=>'.contratos-card','pagination'=>'.contratos-pagination','total'=>$docs->found_posts)); ?>
<nav class="contratos-pagination" aria-label="Paginação de contratos"><?php echo wp_kses_post(paginate_links(array('base'=>str_replace(999999999,'%#%',esc_url(get_pagenum_link(999999999))),'format'=>'?paged=%#%','current'=>max(1,absint(get_query_var('paged'))),'total'=>$docs->max_num_pages,'type'=>'list','add_args'=>$common))); ?></nav><?php endif; ?>
</div>
<aside class="contratos-layout__sidebar" aria-label="Filtros por situação, tipo e ano">
<?php foreach(array(array('SITUAÇÃO','situacao','ct_situacao',$situations),array('TIPO DE CONTRATO','tipo','ct_tipo',$types)) as $group): ?>
<section class="contratos-widget"><h2 class="contratos-widget__title"><?php echo esc_html($group[0]); ?></h2><div class="contratos-widget__body"><?php foreach($group[3] as $term): $params=$common; $params[$group[2]]=$term->slug; ?><a <?php if($f[$group[1]]===$term->slug): ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url(add_query_arg($params,$url)); ?>"><span><?php echo esc_html($term->name); ?></span><span class="contratos-widget__count"><?php echo esc_html($term->count); ?></span></a><?php endforeach; ?></div></section>
<?php endforeach; ?>
<section class="contratos-widget"><h2 class="contratos-widget__title">ANO</h2><div class="contratos-widget__body"><?php foreach($years as $year): $params=$common; $params['ct_ano']=$year; ?><a <?php if($f['ano']===$year): ?>aria-current="page"<?php endif; ?> href="<?php echo esc_url(add_query_arg($params,$url)); ?>"><?php echo esc_html($year); ?></a><?php endforeach; ?></div></section>
</aside></div></div></main>
<?php wp_reset_postdata(); get_footer(); ?>
