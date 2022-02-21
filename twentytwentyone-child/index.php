<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header(); ?>

<?php if ( is_home() && ! is_front_page() && ! empty( single_post_title( '', false ) ) ) : ?>
	<header class="page-header alignwide">
		<h1 class="page-title"><?php single_post_title(); ?></h1>
	</header><!-- .page-header -->
<?php endif; ?>

<div class="container">
<?php

if ( have_posts() ) {

  // Getting the page.
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	// Page 1?
	if ( $paged == 1 ) {

    // Sticky post.
    $sticky = new WP_Query( 'p=' . get_option( 'sticky_posts' )[0] );

    // Is there a sticky post?
    if ( $sticky->have_posts() ){
      $sticky->the_post();
      get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ), [ 'class' => 'full-width top-sticky' ] );
    }

    // Featured posts.
    $featured = new WP_Query( [
      'post_type' => 'post',
      'post__not_in' => get_option("sticky_posts"),
      'ignore_sticky_posts' => 1,
      'meta_key' => 'featured_post',
      'meta_query' => [
        'key' => 'featured_post',
        'value' => 'yes'
      ],
      'orderby'=>'publish_date',
      'order'=>'DESC',
      'posts_per_page' => 20
    ] );

    // Are there featured posts?
    if ( $featured->have_posts() ) {
      ?>
      <div class="full-width">
        <h4><strong><?php _e( 'FEATURED ARTICLES', 'twentytwentyone' ); ?></strong></h4>
      </div>
      <?php

      while ( $featured->have_posts() ) {
        $featured->the_post();
        get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ), [ 'class' => 'hide-post-content home-featured-post' ] );
      }
    }
  }

  // Recent articles.
	$recent = new WP_Query( [
		'post_type' => 'post',
	  'post__not_in' => get_option("sticky_posts"),
		'ignore_sticky_posts' => 1,
		'meta_query' => [
      'relation' => 'OR',
      [
        'key' => 'featured_post',
        'compare' => 'NOT EXISTS',
        'value' => ''
      ],
      [
        'key' => 'featured_post',
        'value' => 'no',
      ],
	  ],
	  'orderby'=>'publish_date',
	  'order'=>'DESC',
		'offset' => ( ( $paged - 1 ) * 10 ) + 4,
		'posts_per_page' => $paged == 1 ? 4 : 10
	] );

	// Are There recent posts?
	if ( $recent->have_posts() ) {
		?>
      <div class="full-width">
        <h4><strong><?php _e( 'RECENT', 'twentytwentyone' ); ?></strong></h4>
      </div>
		<?php

    while ( $recent->have_posts() ) {
      $recent->the_post();
      get_template_part( 'template-parts/content/content', get_theme_mod( 'display_excerpt_or_full_post', 'excerpt' ), [ 'class' => 'hide-post-content home-recent-post' ] );
    }
  }

	?>

  <div class="full-width">
    <?php
      // "See more recent articles" on page 1.
      if ( $paged == 1 ) { ?><div class="col-12 textright"><?php next_posts_link( __( "See more recent articles >>", 'twentytwentyone' ) ); ?></div><?php }
      // Theme pagination on other pages.
      else { ?><div class="col-12"><?php twenty_twenty_one_the_posts_navigation(); ?></div><?php };
    ?>
  </div>
  <?php
} else {

	// If no content, include the "No posts found" template.
	get_template_part( 'template-parts/content/content-none' );

}
?></div><?php

get_footer();
