<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen' );

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
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'quick-link'  => __( 'Quick Links Menu', 'twentysixteen' ),
		'support'  => __( 'Support Menu', 'twentysixteen' ),
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
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'About Us', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Social Links', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );

function new_submenu_class($menu) {    
    $menu = preg_replace('/ class="sub-menu"/','/ class="" /',$menu);        
    return $menu;      
}

add_filter('wp_nav_menu','new_submenu_class');

/*login form code*/

function ajax_login_init(){

    wp_register_script('ajax-login-script', get_template_directory_uri() . '/ajax-login-script.js', array('jquery') ); 
    wp_enqueue_script('ajax-login-script');

    wp_localize_script( 'ajax-login-script', 'ajax_login_object', array( 
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'redirecturl' => home_url().'/pricing',
        'loadingmessage' => __('Sending user info, please wait...')
    ));

    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}
function ajax_login(){

    // First check the nonce, if it fails the function will break
    check_ajax_referer( 'ajax-login-nonce', 'security' );

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
    } else {
        echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...')));
    }

    die();
}

/*Redirect Code For Login User*/

function redirect_user() {
  if ( is_user_logged_in() && is_page( 'login' ) ) {
    $return_url = esc_url( home_url( '/pricing/' ) );
    wp_redirect( $return_url );
    exit;
  }
}
add_action( 'template_redirect', 'redirect_user' );

/*function redirect_user_login() {
  if (!is_user_logged_in() && is_page( 'pricing' ) ) {
    $return_url = esc_url( home_url( '/login/' ) );
    wp_redirect( $return_url );
    exit;
  }
}
add_action( 'template_redirect', 'redirect_user_login' );*/

/*function redirect_user_signup() {
  if (is_user_logged_in() && is_page( 'sign-up' ) ) {
    $return_url = esc_url( home_url( '/products/' ) );
    wp_redirect( $return_url );
    exit;
  }
}
add_action( 'template_redirect', 'redirect_user_signup' );*/
/*code for sign up */
function st_handle_registration(){
 
	if($_POST['action'] == 'register_action' ) {
		
		$error = '';
		$uname = trim( $_POST['email'] );
		$email = trim( $_POST['email'] );
		$pswrd = $_POST['pass'];
		$number_of_users = $_POST['number_of_users'];
		$price = $_POST['price'];
		$company = $_POST['company'];
		$admin_name = $_POST['admin_name'];
		$address = $_POST['address'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$type_business = $_POST['type_business'];
		$telephone = $_POST['telephone'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$card = $_POST['card'];
		$date = $_POST['date'];
		$cvv = $_POST['cvv'];
		$address_buss = $_POST['address_buss'];
		$city_buss = $_POST['city_buss'];
		$state_buss = $_POST['state_buss'];
		$zip_buss = $_POST['zip_buss'];
		$plan_name=$_POST['plan_name'];
		$siteurl=get_site_url();
		$order_id=rand(10,100);
		$status = wp_create_user($uname,$pswrd,$email);
	 	/*login register user*/
		$curr_date= date('d');			
		$creds = array();
		$creds['user_login'] = $email;
		$creds['user_password'] = $pswrd;
		$creds['remember'] = true;
		$user = wp_signon( $creds, false );
	 	if (!is_user_logged_in()){

	 		if(is_wp_error($status) ){
	 			$msg = '';
	 			foreach( $status->errors as $key=>$val ){
	 				foreach( $val as $k=>$v ){
	 					$msg = '<p class="error">'.$v.'</p>';
	 				}
	 			}
	 			echo $msg;
	 			exit();
	 		}else{

	 			/*Add New Plan*/ 
	 			$QueryString="username=officesweet&password=sweet5200&recurring=add_plan&plan_payments=0&plan_amount=".$price."&plan_name=".$plan_name."&plan_id=".$number_of_users."_".$email ."&month_frequency=1&day_of_month=".$curr_date."";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://secure.tnbcigateway.com/api/transact.php?".$QueryString."");
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
				curl_setopt($ch, CURLOPT_POST, 1);

				if (!($data = curl_exec($ch))) {
				    return ERROR;
				}
				curl_close($ch);
				unset($ch);
				//print "\n$data\n";
				$data = explode("&",$data);
				for($i=0;$i<count($data);$i++) {
				    $rdata = explode("=",$data[$i]);
				    $responses[$rdata[0]] = $rdata[1];
				}
				$plan_res=$responses['response'];

				/*Check Plan Already Exit*/
				if($plan_res==1){

					/*Add subscription Email*/
					$QueryString="username=officesweet&password=sweet5200&recurring=add_subscription&plan_id=".$number_of_users."_".$email ."&ccnumber=".$card."&ccexp=".$date."&payment=creditcard&checkname=123123123&checkaccount=123123123&checkaba=123123123&account_type=checking&account_holder_type=personal&sec_code=personal&first_name=".urlencode($fname)."&last_name=".urlencode($lname)."&address1=".urlencode($address)."&city=".urlencode($city)."&state=".urlencode($state)."&zip=".$zip."&phone=".$telephone."&email=".$email."&company=".urlencode($company)."&address2=".urlencode($address_buss)."&orderid=".$order_id."&order_description=personal&merchant_defined_field_#=personal&ponumber=".$telephone."&processor_id=personal&customer_receipt=personal";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://secure.tnbcigateway.com/api/transact.php?".$QueryString."");
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
					curl_setopt($ch, CURLOPT_POST, 1);

					if (!($data = curl_exec($ch))) {
					    return ERROR;
					}
					curl_close($ch);
					unset($ch);
					//print "\n$data\n";
					$data = explode("&",$data);
					for($i=0;$i<count($data);$i++) {
					    $rdata = explode("=",$data[$i]);
					    $responses[$rdata[0]] = $rdata[1];
					}
					$final_res=$responses['response'];
					$subscription_id=$responses["subscription_id"];
					$transection_id=$responses["transactionid"];

					if($final_res==1){
                    $from_res = 'Office'; // a valid address on your domain
//$to_res = "ankit_kumar@nettechnocrats.com";
$to_res = "subscriptionrequest@officesweeet.com";
$customer_res= $email;
$subject_res = "Request for subscription";
$subject1_res = "subscription details";
$message1_res='<table width="600px" border="0" cellpadding="0" cellspacing="0" style=" margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px; 
    color:#000; border: solid 3px #696969; box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.21);">
      <tr>
        <td>
          <table width="600px" border="0" cellspacing="0" cellpadding="0" style="background:#fff; margin:0px auto 0;">
  
            <tr>
              <td style="color:#2d2d2a; font-size:22px; padding:5px 0 0px 15px; background:#c5c5c5; text-align:center;">
               <img src="'.$siteurl.'/wp-content/themes/office-sweet/images/logo.png" width="80" alt="" />
              </td>
            </tr>
            <tr>
            	<td>
                	<table style="width:100%; border-top: solid 3px #74b143; padding:10px;">
                        <tr>
                        	<td style="padding:8px 8px 8px 10px; font-size:18px; color:#222222; font-weight:bold;"><b>Hi '.$fname.'&nbsp;'.$lname.',</b></td>
                        </tr>
                        <tr>
                        	<td style="padding:0px 8px 8px 10px; font-size:18px; font-weight:normal;">Thank you for subscribing to your 30 day FREE trial of OfficeSweeet! You will get a separate email with how to get started and details on how to reach us for help.
                            </td>
                        </tr>
                        <tr>
                        	<td style="background:#f3f3f3; padding:8px; color:#000; font-weight:bold; font-size:16px;">The subscription details are as follows:</td>
                        </tr>
                        
                        <tr>
                          <td align="left" style="padding:0px;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; padding-bottom:10px;">
                                
                                <tbody>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$fname.'&nbsp;'.$lname.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Email
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$email.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Password
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$pswrd.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Number Of User
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$number_of_users.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Plan Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$plan_name.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Total Price
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$price.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	subscription Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$subscription_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Transection Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$transection_id.'</td>
                                     </tr>
                                    
                                     
                                </tbody>
                            </table>
                          </td>
                        </tr>
                         <tr>
                        	<td style="padding:10px; line-height:1.5; font-size:13px;">
                            	<p>We hope you are thrilled with OfficeSweeet but for whatever reason you decide to discontinue your subscription, you can click on the button below to cancel at any time. Your first billing will occur 30 days from today unless you cancel.</p>
                            	<a style="background: #ef5952;border: 1px solid #d44943;font-size: 14px;color: #fff;border-radius: 3px;text-transform: uppercase;padding: 8px 11px;display: inline-block;" href="'.$siteurl.'/delete-subscription/?adminname='.$fname.'&nbsp;'.$lname.'&subscription_id='.$subscription_id.'&email='.$email.'">Cancel subscription</a>
                            </td>
                        </tr>
                        <tr>
                        	<td style="padding:10px; line-height:1.5; font-size:13px;">
                            	If you have any question regarding this subscription, please contact us <a href="mailto:admin@officesweeet.com">at admin@officesweeet.com</a>.
                            </td>
                        </tr>
                        
                        <tr>
                        	<td style="padding:15px 0 0; font-size:17px; line-height:1.5; font-weight:bold; color:#ea0a0a; text-align:center;">
                            	Thank You!
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
      
    </table>';
$message_res ='<table width="600px" border="0" cellpadding="0" cellspacing="0" style=" margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px; 
    color:#000; border: solid 3px #696969; box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.21);">
      <tr>
        <td>
          <table width="600px" border="0" cellspacing="0" cellpadding="0" style="background:#fff; margin:0px auto 0;">
  
            <tr>
              <td style="color:#2d2d2a; font-size:22px; padding:5px 0 0px 15px; background:#c5c5c5; text-align:center;">
               <img src="'.$siteurl.'/wp-content/themes/office-sweet/images/logo.png" width="80" alt="" />
              </td>
            </tr>
            <tr>
            	<td>
                	<table style="width:100%; border-top: solid 3px #74b143; padding:10px;">
                        <tr>
                        	<td style="padding:8px 8px 8px 10px; font-size:18px; color:#222222; font-weight:bold;"><b>Hello Admin,</b></td>
                        </tr>
                        
                        <tr>
                        	<td style="background:#f3f3f3; padding:8px; color:#000; font-size:16px;">The customer details are as follows:</td>
                        </tr>
                        
                        <tr>
                          <td align="left" style="padding:0px;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; padding-bottom:10px;">
                                
                                <tbody>
                                <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Number Of Users
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$number_of_users.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Plan Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$plan_name.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Total Price
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$price.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	subscription Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$subscription_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Transection Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$transection_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Company Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$company.'</td>
                                     </tr>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	First Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$lname.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Last Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$lname.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Email
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$email.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Password
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$pswrd.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Mobile / Tel No.
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$telephone.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Address
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$address.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	State, City, Zip
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$state.','.$city.','.$zip.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Type of Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$type_business.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Address Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$address_buss.'</td>
                                     </tr>
                                      <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	State, City, Zip Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$state_buss.','.$city_buss.','.$zip_buss.'</td>
                                     </tr>
                                </tbody>
                            </table>
                          </td>
                        </tr>
                        <tr>
                        	<td style="padding:15px 0 0; font-size:17px; line-height:1.5; font-weight:bold; color:#ea0a0a; text-align:center;">
                            	Thank You!
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>';
$headers_res = 'From: $from\r\nReply-to: $email';    
$headers_res = "MIME-Version: 1.0" . "\r\n";
$headers_res .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers_res .= '' . "\r\n";
$headers1_res = 'From: $from_res\r\nReply-to: $email';    
$headers1_res = "MIME-Version: 1.0" . "\r\n";
$headers1_res .= "Content-type:text/html;charset=UTF-8" . "\r\n";
mail($to_res,$subject_res,$message_res,$headers_res);
mail($customer_res,$subject1_res,$message1_res,$headers1_res);

						$msg = '<p class="success">Thank you for registering with us. Your 30 day trial period starts now. Please check your mail for further information.</p>';
						echo $msg;
						exit();
					}else{
						$msg = '<p class="error">subscription Not Added Please Try Again !</p>';
							echo $msg;
						exit();
					}

				}else{
					$msg = '<p class="error">The above plan has already been added to your account. Please add a new plan to complete the registration process !.</p>';
			          echo $msg; 
					exit();
				}
	 		}
	 	}else{

	 		/*Already Login*/

	 		/*Add New Plan*/ 
 			$QueryString="username=officesweet&password=sweet5200&recurring=add_plan&plan_payments=0&plan_amount=".$price."&plan_name=".$plan_name."&plan_id=".$number_of_users."_".$email ."&month_frequency=1&day_of_month=".$curr_date."";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://secure.tnbcigateway.com/api/transact.php?".$QueryString."");
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
			curl_setopt($ch, CURLOPT_POST, 1);

			if (!($data = curl_exec($ch))) {
			    return ERROR;
			}
			curl_close($ch);
			unset($ch);
			//print "\n$data\n";
			$data = explode("&",$data);
			for($i=0;$i<count($data);$i++) {
			    $rdata = explode("=",$data[$i]);
			    $responses[$rdata[0]] = $rdata[1];
			}
			$plan_res=$responses['response'];

			/*Check Plan Already Exit*/
			if($plan_res==1){

				/*Add subscription Email*/
				$QueryString="username=officesweet&password=sweet5200&recurring=add_subscription&plan_id=".$number_of_users."_".$email ."&ccnumber=".$card."&ccexp=".$date."&payment=creditcard&checkname=123123123&checkaccount=123123123&checkaba=123123123&account_type=checking&account_holder_type=personal&sec_code=personal&first_name=".urlencode($fname)."&last_name=".urlencode($lname)."&address1=".urlencode($address)."&city=".urlencode($city)."&state=".urlencode($state)."&zip=".$zip."&phone=".$telephone."&email=".$email."&company=".urlencode($company)."&address2=".urlencode($address_buss)."&orderid=".$order_id."&order_description=personal&merchant_defined_field_#=personal&ponumber=".$telephone."&processor_id=personal&customer_receipt=personal";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://secure.tnbcigateway.com/api/transact.php?".$QueryString."");
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
				curl_setopt($ch, CURLOPT_POST, 1);

				if (!($data = curl_exec($ch))) {
				    return ERROR;
				}
				curl_close($ch);
				unset($ch);
				//print "\n$data\n";
				$data = explode("&",$data);
				for($i=0;$i<count($data);$i++) {
				    $rdata = explode("=",$data[$i]);
				    $responses[$rdata[0]] = $rdata[1];
				}
				$final_res=$responses['response'];
				$subscription_id=$responses["subscription_id"];
				$transection_id=$responses["transactionid"];

				if($final_res==1){
                $from = 'Office'; // a valid address on your domain
//$to= "ankit_kumar@nettechnocrats.com";
$to = "subscriptionrequest@officesweeet.com";
$customer= $email;
$subject = "Request for subscription";
$subject1 = "subscription details";
$message1='<table width="600px" border="0" cellpadding="0" cellspacing="0" style=" margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px; 
    color:#000; border: solid 3px #696969; box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.21);">
      <tr>
        <td>
          <table width="600px" border="0" cellspacing="0" cellpadding="0" style="background:#fff; margin:0px auto 0;">
  
            <tr>
              <td style="color:#2d2d2a; font-size:22px; padding:5px 0 0px 15px; background:#c5c5c5; text-align:center;">
               <img src="'.$siteurl.'/SweetOffice/wp-content/themes/office-sweet/images/logo.png" width="80" alt="" />
              </td>
            </tr>
            <tr>
            	<td>
                	<table style="width:100%; border-top: solid 3px #696969; padding:10px;">
                        <tr>
                        	<td style="padding:8px 8px 8px 10px; font-size:18px; color:#222222; font-weight:bold;"><b>Hi '.$fname.'&nbsp;'.$lname.',</b></td>
                        </tr>
                        <tr>
                        	<td style="padding:0px 8px 8px 10px; font-size:18px; font-weight:normal;">Thank you for subscribing to your 30 day FREE trial of OfficeSweeet! You will get a separate email with how to get started and details on how to reach us for help.
                            </td>
                        </tr>
                        <tr>
                        	<td style="background:#f3f3f3; padding:8px; color:#000; font-weight:bold; font-size:16px;">The subscription details are as follows:</td>
                        </tr>
                        
                        <tr>
                          <td align="left" style="padding:0px;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; padding-bottom:10px;">
                                
                                <tbody>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$fname.'&nbsp;'.$lname.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Email
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$email.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Number Of User
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$number_of_users.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Plan Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$plan_name.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Total Price
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$price.'</td>
                                     </tr>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	subscription Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$subscription_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Transection Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$transection_id.'</td>
                                     </tr>
                                     
                                </tbody>
                            </table>
                          </td>
                        </tr>
                        <tr>
                        	<td style="padding:10px; line-height:1.5; font-size:13px;">
                            	<p>We hope you are thrilled with OfficeSweeet but for whatever reason you decide to discontinue your subscription, you can click on the button below to cancel at any time. Your first billing will occur 30 days from today unless you cancel.</p>
                            	<a style="background: #ef5952;border: 1px solid #d44943;font-size: 14px;color: #fff;border-radius: 3px;text-transform: uppercase;padding: 8px 11px;display: inline-block;" href="'.$siteurl.'/delete-subscription/?adminname='.$fname.'&nbsp;'.$lname.'&subscription_id='.$subscription_id.'&email='.$email.'">Cancel subscription</a>
                            </td>
                        </tr>
                        <tr>
                        	<td style="padding:10px; line-height:1.5; font-size:13px;">
                            	If you have any question regarding this subscription, please contact us <a href="mailto:admin@officesweeet.com">at admin@officesweeet.com</a>.
                            </td>
                        </tr>
                        
                        <tr>
                        	<td style="padding:15px 0 0; font-size:17px; line-height:1.5; font-weight:bold; color:#ea0a0a; text-align:center;">
                            	Thank You!
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
      
    </table>';
$message ='<table width="600px" border="0" cellpadding="0" cellspacing="0" style=" margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px; 
    color:#000; border: solid 3px #696969; box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.21);">
      <tr>
        <td>
          <table width="600px" border="0" cellspacing="0" cellpadding="0" style="background:#fff; margin:0px auto 0;">
  
            <tr>
              <td style="color:#2d2d2a; font-size:22px; padding:5px 0 0px 15px; background:#c5c5c5; text-align:center;">
               <img src="'.$siteurl.'/SweetOffice/wp-content/themes/office-sweet/images/logo.png" width="80" alt="" />
              </td>
            </tr>
            <tr>
            	<td>
                	<table style="width:100%; border-top: solid 3px #696969; padding:10px;">
                        <tr>
                        	<td style="padding:8px 8px 8px 10px; font-size:18px; color:#222222; font-weight:bold;"><b>Hello Admin,</b></td>
                        </tr>
                        
                        <tr>
                        	<td style="background:#f3f3f3; padding:8px; color:#000; font-size:16px;">The customer details are as follows:</td>
                        </tr>
                        
                        <tr>
                          <td align="left" style="padding:0px;">
                            <table cellpadding="0" cellspacing="0" border="0" style="width:100%; padding-bottom:10px;">
                                
                                <tbody>
                                <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Number Of Users
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$number_of_users.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Plan Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$plan_name.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Total Price
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$price.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	subscription Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$subscription_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Transection Id
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$transection_id.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Company Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$company.'</td>
                                     </tr>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	First Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$fname.'</td>
                                     </tr>
                                    <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Last Name
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$lname.'</td>
                                     </tr>                                     
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Email
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$email.'</td>
                                     </tr>
                                     
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Mobile / Tel No.
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$telephone.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Address
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$address.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	State, City, Zip
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$state.','.$city.','.$zip.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Type of Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$type_business.'</td>
                                     </tr>
                                     <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	Address Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$address_buss.'</td>
                                     </tr>
                                      <tr>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:bold; width: 130px;">
                                        	State, City, Zip Business
                                        </td>
                                        <td width="2" style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; ">:</td>
                                        <td style="border-bottom:solid 1px #b6b6b6; padding:8px; color:#000; font-size:14px; 
                                        font-weight:normal;">'.$state_buss.','.$city_buss.','.$zip_buss.'</td>
                                     </tr>
                                </tbody>
                            </table>
                          </td>
                        </tr>
                        <tr>
                        	<td style="padding:15px 0 0; font-size:17px; line-height:1.5; font-weight:bold; color:#ea0a0a; text-align:center;">
                            	Thank You!
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>';
$headers = 'From: $from\r\nReply-to: $email';    
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers_res .= '' . "\r\n";
$headers1 = 'From: $from\r\nReply-to: $email';    
$headers1 = "MIME-Version: 1.0" . "\r\n";
$headers1 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
mail($to,$subject,$message,$headers);
mail($customer,$subject1,$message1,$headers1);

					$msg = '<p class="success">Thank you for registering with us. Your 30 day trial period starts now. Please check your mail for further information.</p>';
						echo $msg;
					exit();
				}else{
					$msg = '<p class="error">subscription Not Added Please Try Again !</p>';
							echo $msg;
					exit();
				}

			}else{
				$msg = '<p class="error">The above plan has already been added to your account. Please add a new plan to complete the registration process !.</p>';
			          echo $msg; 
				exit();
			}
	 		/*Already Login*/ 
	 	}	
	 
	}else{
		echo $error='<p class="error">Please Try Again !</p>';
	}	
}

add_action( 'wp_ajax_register_action', 'st_handle_registration' );
add_action( 'wp_ajax_nopriv_register_action', 'st_handle_registration' );
show_admin_bar(false);
