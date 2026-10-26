<?php
/** Consulta pública exclusiva de Contratos — CONFERP. */
defined( 'ABSPATH' ) || exit;
function conferp_contratos_filters() {
    $get = static function($key) { return isset($_GET[$key]) && is_string($_GET[$key]) ? sanitize_text_field(wp_unslash($_GET[$key])) : ''; };
    $year=$get('ct_ano'); $order=$get('ct_ordem');
    return array('q'=>$get('ct_busca'),'numero'=>$get('ct_numero'),'situacao'=>sanitize_title($get('ct_situacao')),'tipo'=>sanitize_title($get('ct_tipo')),'ano'=>preg_match('/^\d{4}$/',$year)?$year:'','ordem'=>in_array($order,array('recentes','antigas'),true)?$order:'recentes');
}
function conferp_contratos_years() {
    global $wpdb;
    $years=$wpdb->get_col($wpdb->prepare("SELECT DISTINCT LEFT(pm.meta_value,4) FROM {$wpdb->postmeta} pm INNER JOIN {$wpdb->posts} p ON p.ID=pm.post_id WHERE p.post_type=%s AND p.post_status='publish' AND pm.meta_key=%s AND pm.meta_value REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$' ORDER BY 1 DESC",'contrato','_contrato_assinatura'));
    return array_map('strval',(array)$years);
}
function conferp_contratos_search_sql($search,$query) {
    if (!$query->get('conferp_contratos_archive')) return $search;
    $term=$query->get('conferp_contratos_term');
    if (!is_string($term) || ''===trim($term)) return '';
    global $wpdb; $like='%'.$wpdb->esc_like(trim($term)).'%';
    return $wpdb->prepare(" AND ({$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s OR {$wpdb->posts}.post_content LIKE %s OR EXISTS (SELECT 1 FROM {$wpdb->postmeta} cm WHERE cm.post_id={$wpdb->posts}.ID AND cm.meta_key IN ('_contrato_numero','_contrato_processo','_contrato_contratada','_contrato_objeto','_contrato_documento') AND cm.meta_value LIKE %s))",$like,$like,$like,$like);
}
add_filter('posts_search','conferp_contratos_search_sql',10,2);
function conferp_contratos_order_sql($orderby,$query) {
    if (!$query->get('conferp_contratos_archive')) return $orderby;
    global $wpdb; $dir='ASC'===$query->get('conferp_contratos_direction')?'ASC':'DESC';
    return "COALESCE((SELECT NULLIF(pm.meta_value,'') FROM {$wpdb->postmeta} pm WHERE pm.post_id={$wpdb->posts}.ID AND pm.meta_key='_contrato_assinatura' LIMIT 1), DATE({$wpdb->posts}.post_date)) {$dir}, {$wpdb->posts}.ID {$dir}";
}
add_filter('posts_orderby','conferp_contratos_order_sql',10,2);
function conferp_contratos_query_args($filters) {
    $args=array('post_type'=>'contrato','post_status'=>'publish','posts_per_page'=>6,'ignore_sticky_posts'=>true,'conferp_contratos_archive'=>true,'conferp_contratos_term'=>$filters['q'],'conferp_contratos_direction'=>'antigas'===$filters['ordem']?'ASC':'DESC');
    $tax=array();
    foreach (array('situacao'=>'contrato_situacao','tipo'=>'contrato_tipo') as $key=>$taxonomy) {
        if ($filters[$key]) { if (term_exists($filters[$key],$taxonomy)) $tax[]=array('taxonomy'=>$taxonomy,'field'=>'slug','terms'=>$filters[$key]); else $args['post__in']=array(0); }
    }
    if ($tax) $args['tax_query']=$tax;
    $meta=array();
    if ($filters['ano']) $meta[]=array('key'=>'_contrato_assinatura','value'=>$filters['ano'].'-','compare'=>'LIKE');
    if ($filters['numero']) $meta[]=array('key'=>'_contrato_numero','value'=>$filters['numero'],'compare'=>'LIKE');
    if ($meta) $args['meta_query']=$meta;
    return $args;
}
function conferp_contratos_query($filters) {
    $args=conferp_contratos_query_args($filters);
    $args['paged']=max(1,absint(get_query_var('paged')));
    return new WP_Query($args);
}
function conferp_contratos_main_query($query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_post_type_archive('contrato')) return;
    $args=conferp_contratos_query_args(conferp_contratos_filters());
    foreach ($args as $key=>$value) $query->set($key,$value);
}
add_action('pre_get_posts','conferp_contratos_main_query');
function conferp_contratos_date($id) {
    $date=get_post_meta($id,'_contrato_assinatura',true);
    if (!is_string($date)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)) return '';
    $time=strtotime($date.' 12:00:00 UTC');
    return $time?wp_date(get_option('date_format'),$time,new DateTimeZone('UTC')):'';
}
