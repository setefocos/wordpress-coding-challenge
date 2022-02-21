<?php
/**
 * Template part for displaying post archives and search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

// Getting post class argument.
$post_class = get_post_class($args['class']);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($args['class']); ?>>

	<?php
  get_template_part( 'template-parts/header/excerpt-header', get_post_format() );

  // If the class 'hide-post-content' is present, the post content will not be shown.
  if ( !in_array( 'hide-post-content', $post_class ) ): ?>
    <div class="entry-content">
      <?php get_template_part( 'template-parts/excerpt/excerpt', get_post_format() ); ?>
    </div><!-- .entry-content -->
	<?php endif ?>

	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-${ID} -->
