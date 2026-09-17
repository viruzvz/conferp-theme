<?php
/**
 * CONFERP Global Back to Top.
 *
 * Federal global component shared across CONFERP
 * and all CONRERP installations.
 *
 * @package Conferp_Theme
 */

defined( 'ABSPATH' ) || exit;
?>

<button
	type="button"
	class="conferp-back-to-top"
	data-conferp-back-to-top
	aria-label="<?php esc_attr_e( 'Voltar ao topo da página', 'conferp' ); ?>"
	title="<?php esc_attr_e( 'Voltar ao topo', 'conferp' ); ?>"
>

	<?php
	conferp_icon(
		'back-to-top',
		array(
			'class' => 'conferp-back-to-top__icon',
		)
	);
	?>

</button>