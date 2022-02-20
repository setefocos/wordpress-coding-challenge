<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'twenty-twenty-one-style','twenty-twenty-one-style','twenty-twenty-one-print-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION

// -- Featured Meta Box --

add_action( 'load-post.php', 'meta_boxes_setup' );
add_action( 'load-post-new.php', 'meta_boxes_setup' );
add_action( 'save_post', 'save_post_meta', 10, 2 );

function meta_boxes_setup() {
	add_action( 'add_meta_boxes', 'add_featured_meta_box' );
}

function add_featured_meta_box() {
	add_meta_box(
		'featured_post',
		__( 'Featured Post' ),
		'display_meta_box',
		'post',
		'side',
		'high'
	);
}

function display_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'meta_boxes_nonce' );
	?>
	<label for="meta-box-checkbox"><?php _e( 'Mark as featured'); ?></label>
	<input type="checkbox" id="meta-box-checkbox"  name="meta-box-checkbox" value="yes" <?php if ( get_post_meta( $post->ID, 'featured_post', true ) == 'yes' ) echo ' checked="checked"'; ?>>
	<?php
}

// Save meta value.
function save_post_meta( $post_id, $post ) {

	// Verify the nonce before proceeding.
	if ( !isset( $_POST['meta_boxes_nonce'] ) || !wp_verify_nonce( $_POST['meta_boxes_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	// Get the post type object.
	$post_type = get_post_type_object( $post->post_type );

	// Check if the current user has permission to edit the post.
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	$meta_box_checkbox_value = $_POST["meta-box-checkbox"] == 'yes' ? 'yes' : 'no';

	update_post_meta( $post_id, "featured_post", $meta_box_checkbox_value );
}

// Add class.
// There is already a category named 'featured', so I'm naming this class 'featured_post'.
function featured_class( $classes ) {
	global $post;
	if ( get_post_meta( $post->ID, 'featured_post' ) &&  get_post_meta( $post->ID, 'featured_post', true ) == 'yes' ) {
		$classes[] = 'featured-post';
	}
	return $classes;
}
add_filter( 'post_class', 'featured_class' );