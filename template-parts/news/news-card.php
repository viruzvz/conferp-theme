<?php
/**
 * News card.
 *
 * @package conferp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$category = conferp_get_news_primary_category();
$excerpt  = conferp_get_news_excerpt( 24 );
?>

<article
	id="post-<?php the_ID(); ?>"
	<?php post_class( 'news-card' ); ?>
>

	<?php if ( has_post_thumbnail() ) : ?>

		<a
			class="news-card__image"
			href="<?php the_permalink(); ?>"
			aria-label="<?php echo esc_attr( get_the_title() ); ?>"
		>

			<?php
			the_post_thumbnail(
				'conferp-news-card',
				array(
					'loading' => 'lazy',
					'alt'     => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>

		</a>

	<?php endif; ?>


	<div class="news-card__content">

		<?php if ( $category ) : ?>

			<a
				class="news-card__category"
				href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
			>
				<?php echo esc_html( $category->name ); ?>
			</a>

		<?php endif; ?>


		<h2 class="news-card__title">

			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>

		</h2>


		<?php if ( $excerpt ) : ?>

			<p class="news-card__excerpt">
				<?php echo esc_html( $excerpt ); ?>
			</p>

		<?php endif; ?>

	</div>

</article>