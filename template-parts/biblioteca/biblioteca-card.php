<?php
/**
 * Biblioteca card.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$excerpt = conferp_get_biblioteca_excerpt( 28 );
?>

<article
	id="post-<?php the_ID(); ?>"
	<?php post_class( 'biblioteca-card' ); ?>
>

	<h2 class="biblioteca-card__title">

		<a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
		</a>

	</h2>


	<?php if ( $excerpt ) : ?>

		<p class="biblioteca-card__excerpt">
			<?php echo esc_html( $excerpt ); ?>
		</p>

	<?php endif; ?>

</article>