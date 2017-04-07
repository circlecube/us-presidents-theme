<?php
/**
 * USA Presidents functions and definitions
 *
 * @package USA Presidents
 */

if ( ! function_exists( 'presidents_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function presidents_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on USA Presidents, use a find and replace
	 * to change 'presidents' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'presidents', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'presidents' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'presidents_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // presidents_setup
add_action( 'after_setup_theme', 'presidents_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function presidents_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'presidents_content_width', 640 );
}
add_action( 'after_setup_theme', 'presidents_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function presidents_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'presidents' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'presidents_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function presidents_scripts() {
	wp_enqueue_style( 'presidents-style', get_stylesheet_uri() );

	wp_enqueue_script( 'presidents-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'presidents-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'presidents_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


//not working anymore since update to api v2
function presidentsoftheusa_remove_extra_data( $response, $post, $request ) {
	$_data = $response->data;
	// We only want to modify the 'view' context, for reading posts
	// if ( $context !== 'view' || is_wp_error( $_data ) ) {
	// 	return $data;
	// }
	// if ( $_data['type'] === 'president' ) {
		// Here, we unset any data we don't want to see on the front end:
		// unset( $data['author'] );
		// unset( $data['status'] );
		// unset( $data['links'] );
		// unset( $data['type'] );
		// unset( $data['meta'] );
		// unset( $data['content'] );
		// unset( $data['parent'] );
		// unset( $data['date'] );
		// unset( $data['modified'] );
		// unset( $data['format'] );
		// unset( $data['guid'] );
		// unset( $data['excerpt'] );
		// unset( $data['menu_order'] );
		// unset( $data['comment_status'] );
		// unset( $data['ping_status'] );
		// unset( $data['sticky'] );
		// unset( $data['date_tz'] );
		// unset( $data['date_gmt'] );
		// unset( $data['modified_tz'] );
		// unset( $data['modified_gmt'] );
		// unset( $data['terms'] );
		// unset( $data['featured_image'] );


		// unset( $_data['_links'] );

		$thumbnail_id = get_post_thumbnail_id( $post->ID );
		$thumbnail = wp_get_attachment_image_src( $thumbnail_id );
		$_data['featured_image_url'] = $thumbnail[0];

	// }
	$response->data = $_data;
	return $response;
}

add_filter( 'rest_prepare_post', 'presidentsoftheusa_remove_extra_data', 10, 3 );


// add custom fields query to WP REST API v2
// https://1fix.io/blog/2015/07/20/query-vars-wp-api/
// function my_allow_meta_query( $valid_vars ) {

//     $valid_vars = array_merge( $valid_vars, array( 'meta_key', 'meta_value' ) );
//     return $valid_vars;
// }
// add_filter( 'rest_query_vars', 'my_allow_meta_query' );

	//https://github.com/WP-API/WP-API/issues/2308#issuecomment-262886432
	add_filter('rest_endpoints', function ($routes) {
	    // I'm modifying multiple types here, you won't need the loop if you're just doing posts
	    foreach (['president'] as $type) {
	        if (!($route =& $routes['/wp/v2/' . $type])) {
	            continue;
	        }

	        // Allow ordering by my meta value
	        $route[0]['args']['orderby']['enum'][] = 'meta_value_num';

	        // Allow only the meta keys that I want
	        $route[0]['args']['meta_key'] = array(
	            'description'       => 'The meta key to query.',
	            'type'              => 'string',
	            'enum'              => ['took_office', 'number'],
	            'validate_callback' => 'rest_validate_request_arg',
	        );
	    }

	    return $routes;
	});

	//https://github.com/WP-API/WP-API/issues/2308#issuecomment-265875108
	add_filter('rest_president_query', function ($args, $request) {
	    if ($key = $request->get_param('meta_key')) {
	        $args['meta_key'] = $key;
	    }
	    return $args;
	}, 10, 2);
