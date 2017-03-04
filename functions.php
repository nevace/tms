<?php
///////////////////////////////////////////
//--------- OT THEME OPTIONS ---------------//
///////////////////////////////////////////

// Optional OT params
add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_theme_mode', '__return_true' );
include_once( 'option-tree/ot-loader.php' );

// BOC Theme Options
include_once( 'includes/theme-options.php' );

///////////////////////////////////////////
//--------- OT THEME OPTIONS :: END --------//
///////////////////////////////////////////


// Aqua Customizer Theme Options
include_once( 'includes/customizer_theme-options.php' );
include_once( 'includes/meta_boxes.php' );

// Default RSS feed links
add_theme_support('automatic-feed-links');

// Sets up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 940;

// Post Formats
add_theme_support( 'post-formats',  array( 'gallery','video' ));
add_post_type_support( 'post', 'post-formats' );
add_post_type_support( 'portfolio', 'post-formats' );


// Enable Background Support
$args = array(
    'default-color' => 'f6f6f6',
    'default-image' => get_template_directory_uri() . '/images/main_bgr.png',
);
add_theme_support( 'custom-background', $args );

// Aqua Customizer Theme Options
add_action( 'customize_register', 'aqua_customize_register' );

// Register Navigation
add_theme_support('menus');
register_nav_menu('main_navigation', 'Main Navigation');

// Custom functions + Widgets
require_once( 'includes/boc_custom.php' );
require_once( 'includes/boc_widgets.php' );

add_action('widgets_init', 'boc_load_widgets');


// Make theme available for translation
load_theme_textdomain( 'Aqua', get_template_directory() . '/languages' );



// Images

add_theme_support('post-thumbnails');

set_post_thumbnail_size(640, 300, true); //size of thumbs
add_image_size('small-thumb', 60, 60, true);  
add_image_size('portfolio-main', 700, 340, true);
add_image_size('portfolio-medium', 460, 290, true);
add_image_size('portfolio-thumb', 300, 180, true);
add_image_size('portfolio-full', 940, 600, true);


add_action('init', 'boc_register_widgets');
function boc_register_widgets(){
		
	// Register widgetized locations
	if(function_exists('register_sidebar')) {
		
		// Register Dynamic Widgets (OT)
		if (ot_get_option('boc_sidebars')){
			$dynamic_sidebars = ot_get_option('boc_sidebars');
			foreach ($dynamic_sidebars as $dynamic_sidebar) {
				register_sidebar(array(
					'name' => $dynamic_sidebar["title"],
					'id' => $dynamic_sidebar["id"],
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget' => '</div>',
					'before_title' => '<h4 class="title"><span>',
					'after_title' => '</span></h4>',
					));
			}
		}	
		
		register_sidebar(array(
			'name' => 'Aqua Sidebar',
			'id'   => 'aqua_sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="title"><span>',
			'after_title' => '</span></h4>',
		));

		register_sidebar(array(
			'name' => 'Footer Widget 1',
			'id'   => 'aqua_footer_widget1',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));

		register_sidebar(array(
			'name' => 'Footer Widget 2',
			'id'   => 'aqua_footer_widget2',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));

		register_sidebar(array(
			'name' => 'Footer Widget 3',
			'id'   => 'aqua_footer_widget3',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));

		register_sidebar(array(
			'name' => 'Footer Widget 4',
			'id'   => 'aqua_footer_widget4',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		));
		
		register_sidebar(array(
			'name' => 'Aqua Contact Sidebar',
			'id'   => 'aqua_contact_widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="title"><span>',
			'after_title' => '</span></h4>',
		));		
		
	}
}


//	Change default OT Upload text
add_filter( 'ot_upload_text', 'boc_ot_upload_text', 10, 2 );
function boc_ot_upload_text($txt) {
	return 'Upload';
}



// Register custom post types
add_action('init', 'boc_custom_types');
function boc_custom_types() {
	register_post_type(
		'portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio',
				'singular_name' => 'Portfolio'
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'portfolio_item'),
			'supports' => array('title', 'editor', 'thumbnail'),
			'can_export' => true,
			'show_in_nav_menus' => true,
		)
	);

	// register_post_type(
	// 	'articles',
	// 	array(
	// 		'labels' => array(
	// 			'name' => 'Articles',
	// 			'singular_name' => 'Articles'
	// 		),
	// 		'public' => true,
	// 		'has_archive' => true,
	// 		'rewrite' => array('slug' => 'services_item'),
	// 		'supports' => array('title', 'editor', 'thumbnail','page-attributes'),
	// 		'can_export' => true,
	// 		'show_in_nav_menus' => true,
	// 	)
	// );

	register_taxonomy('portfolio_category', 'portfolio', array('hierarchical' => true, 'label' => 'Portfolio Categories', 'query_var' => true, 'rewrite' => true));
}

/*
function my_rewrite_flush() {
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'my_rewrite_flush' );
*/

/**
 * add a default-gravatar to options
 */
if ( !function_exists('fb_addgravatar') ) {
	function fb_addgravatar( $avatar_defaults ) {
		$myavatar = get_template_directory_uri() . '/images/comment_avatar.png';
		$avatar_defaults[$myavatar] = 'people';
		return $avatar_defaults;
	}
	add_filter( 'avatar_defaults', 'fb_addgravatar' );
}


// BOC Shortcodes
include_once( 'includes/shortcodes.php' );
add_action('init', 'boc_add_buttons');

// Use shortcodes in Widgets
add_filter('widget_text', 'do_shortcode');


// Customize Tag Cloud
function my_tag_cloud_args($in){
    return 'smallest=13&largest=13&number=25&orderby=name&unit=px';
}
add_filter( 'widget_tag_cloud_args', 'my_tag_cloud_args');


// Customize Items per page for Portfolio Taxonomy
$option_posts_per_page = get_option( 'posts_per_page' );
add_action( 'init', 'my_modify_posts_per_page', 0);
function my_modify_posts_per_page() {
    add_filter( 'option_posts_per_page', 'my_option_posts_per_page' );
}
function my_option_posts_per_page( $value ) {
    global $option_posts_per_page;
    if ( is_tax( 'portfolio_category') ) {
        return (ot_get_option('portfolio_items_per_page',9) ? ot_get_option('portfolio_items_per_page',9) : 9);
    } else {
        return $option_posts_per_page;
    }
}

function unregister_post_type() {
    global $wp_post_types;
    if ( isset( $wp_post_types[ 'services' ] ) ) {
    	print('ffffff');
        unset( $wp_post_types[ 'services' ] );
        return true;
    }
    return false;
}

add_action('init', 'unregister_post_type', 99999);
