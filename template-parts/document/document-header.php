<?php
/** Cabeçalho reutilizável do documento institucional. @package conferp */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$document_id = get_the_ID();
$number      = get_post_meta( $document_id, '_legislacao_numero', true );
$date_raw    = get_post_meta( $document_id, '_legislacao_data', true );
$summary     = get_post_meta( $document_id, '_legislacao_ementa', true );
$types       = get_the_terms( $document_id, 'legislacao_tipo' );
$has_types   = ! is_wp_error( $types ) && ! empty( $types );
$coat_path   = get_theme_file_path( 'assets/img/global/brasao-republica.svg' );
$coat_url    = get_theme_file_uri( 'assets/img/global/brasao-republica.svg' );
?>
<header class="institutional-document__header">
	<?php if ( file_exists( $coat_path ) ) : ?>
		<img class="institutional-document__coat" src="<?php echo esc_url( $coat_url ); ?>" alt="<?php esc_attr_e( 'Brasão da República Federativa do Brasil', 'conferp' ); ?>" width="112" height="112" decoding="async">
	<?php endif; ?>
	<p class="institutional-document__republic"><?php esc_html_e( 'República Federativa do Brasil', 'conferp' ); ?></p>
	<?php if ( $has_types ) : ?>
		<p class="institutional-document__types">
			<?php foreach ( $types as $type ) : ?>
				<span class="institutional-document__type"><?php echo esc_html( $type->name ); ?></span>
			<?php endforeach; ?>
		</p>
	<?php endif; ?>
	<h2 class="institutional-document__title"><?php echo esc_html( get_the_title() ); ?></h2>
	<?php if ( $number || $date_raw ) : ?>
		<p class="institutional-document__identification">
			<?php if ( $number ) : ?>
				<span><?php echo esc_html( sprintf( __( 'Nº %s', 'conferp' ), $number ) ); ?></span>
			<?php endif; ?>
			<?php if ( $date_raw ) : ?>
				<?php
				$timestamp = strtotime( $date_raw . ' 12:00:00' );
				if ( $timestamp ) {
					echo '<time datetime="' . esc_attr( $date_raw ) . '">' . esc_html( wp_date( get_option( 'date_format' ), $timestamp ) ) . '</time>';
				}
				?>
			<?php endif; ?>
		</p>
	<?php endif; ?>
	<?php if ( $summary ) : ?>
		<p class="institutional-document__summary"><?php echo esc_html( $summary ); ?></p>
	<?php endif; ?>
</header>
