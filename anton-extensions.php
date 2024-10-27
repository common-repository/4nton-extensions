<?php

/**
 * Plugin Name: Anton Extensions
 * Plugin URI: https://www.anthonycarbon.com/
 * Description: Anton Extensions has PHP coding SOP fuction that prevent errors, list of addons that may suitable for your site requirements, and many more free features that you might love. This plugin is can integrate with Gravity Forms, Override WordPress default emails, login landing page, and more.
 * Version: 1.2.2
 * Author: <a href="https://www.anthonycarbon.com/">Anthony Carbon</a>
 * Author URI: https://www.anthonycarbon.com/
 * Donate link: https://www.paypal.me/anthonypagaycarbon
 * Tags: wp-login.php, gravityform, media-upload, field, ajax, anthonycarbon.com
 * Requires at least: 4.4
 * Tested up to: 5.0
 * Stable tag: 1.2.2
 * Text Domain: ae
 * License: GPL v3
 **/
 
if ( ! defined('ABSPATH') ){ exit; }

if ( ! class_exists( 'Anton_Extensions' ) ) :

class Anton_Extensions {
	public $add_ons = array();
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->register();
		$this->init_hooks();
	}
	public function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	public function register() {
		
	}
	public function define_constants() {
		$this->define( 'AE', 'ae' );
		$this->define( 'AE_NAME', 'Anton Extensions' );
		$this->define( 'AE_BN', plugin_basename( __FILE__ ) );
		$this->define( 'AE_URL', plugin_dir_url(__FILE__) );
		$this->define( 'AE_IMG_URL', AE_URL . 'assets/images' );
		$this->define( 'AE_JS_URL', AE_URL . 'assets/js' );
		$this->define( 'AE_CSS_URL', AE_URL . 'assets/css' );
		// PATH
		$this->define( 'AE_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'AE_LIB_PATH', AE_PATH . 'lib' );
		$this->define( 'AE_ADMIN_PATH', AE_LIB_PATH . '/admin' );
		$this->define( 'AE_VIEW_PATH', AE_LIB_PATH . '/view' );
		$this->define( 'AE_CLASS_PATH', AE_LIB_PATH . '/class' );
		$this->define( 'AE_CORE_PATH', AE_LIB_PATH . '/core' );
		$this->define( 'AE_AJAX_PATH', AE_CORE_PATH . '/ajax' );
		$this->define( 'AE_TEMPLATE_PATH', AE_LIB_PATH . '/templates' );
		// DIR
		$this->define( 'AE_PARENT_THEME_DIR', get_template_directory() );
		$this->define( 'AE_CHILD_THEME_DIR', get_stylesheet_directory() );
	}
	public function init_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_url' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'add_action_links' ), 10, 2 );
		add_filter( 'wp_mail_from', array( $this, 'wp_mail_from' ) );
		add_filter( 'wp_new_user_notification_email_admin', array( $this, 'new_user_registration' ), 10, 3 );
		add_filter( 'wp_mail_from_name', array( $this, 'wp_mail_from_name' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
		add_action( 'admin_print_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles_scripts' ) );
		add_action( 'wp_ajax_ae_ajax', array( $this, 'ae_ajax' ) );
		add_action( 'wp_ajax_nopriv_ae_ajax', array( $this, 'ae_ajax' ) );
		add_action( 'wp_ajax_croppie', array( $this, 'croppie' ) );
		add_action( 'wp_ajax_nopriv_croppie', array( $this, 'croppie' ) );

		add_action( 'wp_ajax_single_image_crop', array( $this, 'single_image_crop' ) );
		add_action( 'wp_ajax_nopriv_single_image_crop', array( $this, 'single_image_crop' ) );
		// USER AVATAR UPLOAD
		add_action( 'wp_ajax_user_avatar_upload', array( $this, 'ajax_user_avatar_upload' ) );
		add_action( 'wp_ajax_nopriv_user_avatar_upload', array( $this, 'ajax_user_avatar_upload' ) );		
		add_shortcode( 'user-avatar-upload', array( $this, 'user_avatar_upload' ) );

		do_action( 'ae_init' );
	}
	public function add_action_links( $plugin_meta, $plugin_file ) {
		if( $plugin_file == plugin_basename(__FILE__) ){
			//$plugin_meta[] = sprintf( '<a href="https://www.anthonycarbon.com/" target="_blank">%s</a>', __( 'Documentaion', AE ) );
			$plugin_meta[] = '<a class="dashicons-before dashicons-awards" href="https://www.paypal.me/anthonypagaycarbon" target="_blank">' . __( 'Donate, buy me a coffee', AE ) . '</a>';
		}
		return $plugin_meta;
	}
	public function register_setting(){
		register_setting( AE, AE );
		if( ! get_option( AE ) && get_option( 'anton-extensions' ) ){
			update_option( AE, get_option( 'anton-extensions' ) );
		}
	}
	public function admin_menu(){  
		add_menu_page(
			AE_NAME, // Page Name
			AE_NAME, // Menu Name
			'manage_options',
			AE,
			array( $this, 'admin_settings' )
		);
	}
	public function admin_settings() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( AE . '-croppie' );
		wp_enqueue_style( AE . '-admin' );
		wp_enqueue_media();
		wp_enqueue_script( AE . '-admin' );
		wp_enqueue_script( AE . '-croppie' );
		wp_localize_script(
			AE . '-admin',
			'ae',
			array(
				'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
				'homeurl' 	=> get_bloginfo( 'url' ),
				'img_url'	=> AE_IMG_URL,
				'spinner'	=> get_bloginfo( 'url' ) . '/wp-includes/images/spinner.gif',
				'spinner2x'	=> get_bloginfo( 'url' ) . '/wp-includes/images/spinner-2x.gif',
			)
		);
		include( AE_ADMIN_PATH . '/settings.php' );
	}
	public function includes() {
		include_once( AE_CORE_PATH . '/development-functions.php' );
		include_once( AE_CORE_PATH . '/functions-template.php' );
		include_once( AE_CORE_PATH . '/functions.php' );
		include_once( AE_CORE_PATH . '/features.php' );
		include_once( AE_CORE_PATH . '/upload.php' );
	}
	public function admin_styles(){
		wp_register_style( AE . '-croppie', AE_CSS_URL . '/croppie.min.css' );
		wp_register_style( AE . '-admin', AE_CSS_URL . '/admin.min.css', array( AE . '-croppie' ) );
			
	}	
	public function admin_scripts() {
		wp_register_script( AE . '-admin', AE_JS_URL . '/admin.min.js', array( 'jquery' ) );
		wp_register_script( AE . '-croppie', AE_JS_URL . '/croppie.min.js', array( 'jquery' ) );
	}
	public function styles_scripts(){
		global $wp_query;
		// wp_enqueue_media();
		wp_register_script( AE . '-croppie', AE_JS_URL . '/croppie.min.js', array( 'jquery' ) );
		wp_register_script( AE, AE_JS_URL . '/script.min.js', array( 'jquery' ) );
		wp_register_style( AE . '-croppie', AE_CSS_URL . '/croppie.min.css' );
		wp_register_style( AE, AE_CSS_URL . '/style.min.css', array( AE . '-croppie' ) );	
		// wp_enqueue_script( AE );
		// wp_enqueue_style( AE );
		$localize_script = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'homeurl' => get_bloginfo( 'url' ),
			'img_url' => AE_IMG_URL,
	 		'spinner'	=> get_bloginfo( 'url' ) . '/wp-includes/images/spinner.gif',
	 		'spinner2x'	=> get_bloginfo( 'url' ) . '/wp-includes/images/spinner-2x.gif',
		);
		wp_localize_script(
			AE,
			'ae',
			apply_filters( 'ae_localize_script', $localize_script )
		);
	}
	public function ae_ajax(){
		$a = array(
			'ids' => maybe_serialize( wppost( 'ids' ) ),
			'preview' => ''
		);
		if( in_array( wppost( 'multiple' ), array( 'true', 'add' ) ) && wppost( 'ids' ) ):
			foreach( wppost( 'ids' ) as $id ) :
				$a['preview'] .= wp_get_attachment_image( $id, 'thumbnail' );
			endforeach;
		endif;
		switch( wppost( 'multiple' ) ) :
			case 'true' :
				echo json_encode( $a );
				break;
			case 'add' :
				echo json_encode( $a );
				break;
			default:
				echo sanitize_text_field( wppost( 'ids' ) );
				break;
		endswitch;
		die();
	}
	public function settings_url( $links, $file ){
		if ( $file != AE_BN ) { return $links; }
		array_unshift(
			$links,
			sprintf(
				'<a href="%s?page=%s">%s</a>',
				esc_url( admin_url( 'admin.php' ) ),
				AE,
				esc_html__( 'Settings', AE )			
			)
		);
		return $links;
	}
	public function ajax_user_avatar_upload(){
		include( apply_filters( 'ajax_user_avatar_php', AE_AJAX_PATH . '/user-avatar.php' ) );
		die();
	}
	public function user_avatar_upload( $atts, $args = false ){
		$defaults = array(
			'savetext' => __( 'SAVE', AE ),
			'newimage' => __( 'UPLOAD NEW IMAGE', AE ),
			'cancel' => __( 'CANCEL', AE ),
			'width' => 300,
			'height' => 300
		);
		$r = shortcode_atts( $defaults, $atts );
		if( $args ){
			$r = wp_parse_args( $atts, $defaults );
		}
		ob_start();
		include( apply_filters( 'shortcode_user_avatar_php', AE_TEMPLATE_PATH . '/user-avatar.php' ) );
		return ob_get_clean();
	}
	public function single_image_crop(){
		include( AE_AJAX_PATH . '/single-image-crop.php' );
		die();
	}
	public function croppie(){
		global $current_user;
		if( ! wppost( 'attach_id' ) ) {
			return;
		}
		$query = array();
		$upload_dir       = wp_upload_dir();
		$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, wpkeyvalue( $upload_dir, 'path' ) ) . DIRECTORY_SEPARATOR;
		$img = wppost( 'base64' );
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$decoded          = base64_decode($img) ;
		$filename         = explode( "fakepath", wppost( 'file' ) );
		$filename         = wpkeyvalue( $filename, 1 );
		$hashed_filename  = $filename;
		// @new
		$image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );
		//HANDLE UPLOADED FILE
		if( !function_exists( 'wp_handle_sideload' ) ) {
		  require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		// Without that I'm getting a debug error!?
		if( !function_exists( 'wp_get_current_user' ) ) {
		  require_once( ABSPATH . 'wp-includes/pluggable.php' );
		}
		// @new
		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = 'image/png';
		$file['size']     = filesize( $upload_path . $hashed_filename );
		// upload file to server
		// @new use $file instead of $image_upload
		$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );
		$filename = wpkeyvalue( $file_return, 'file' );
		$post_title = preg_replace('/\.[^.]+$/', '', basename($filename));
		$attachment = array(
		 	'post_mime_type' => wpkeyvalue( $file_return, 'type' ),
		 	'post_title' => $post_title,
		 	'post_content' => '',
		 	'post_status' => 'inherit',
		 	'guid' => wpkeyvalue( $wp_upload_dir, 'url' ) . '/' . basename($filename)
		 );
		global $wpdb, $current_user;
		if( wppost( 'attach_id' ) ) {
			if( wppost( 'key' ) ) {
				$args = array(
					'post_type' => 'attachment',
					'posts_per_page' => -1,
					'fields' => 'ids',
					'author' => wpstdclass( $current_user, 'ID' ) ? wpstdclass( $current_user, 'ID' ) : 0,
					'meta_key' => 'wp_login_logo',
					'meta_value' => 1,
					'post_status' => array( 'publish', 'private', 'inherit' ),
				);
				$query = new WP_Query( $args );
				$query->posts[] = wppost( 'attach_id' );
				if( $posts = array_filter( $query->posts ) ){
					foreach ( $posts as $key => $post ) {
						wp_delete_attachment( $post, true );
					}
				}
			}else{
			 	wp_delete_attachment( wppost( 'attach_id' ), true );
			}
		}
		$attach_id = wp_insert_attachment( $attachment, $filename, 289 );
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
		update_post_meta( $attach_id, wppost( 'key' ), true );
		$a = array(
			'user_id' => wpstdclass( $current_user, 'ID' ),
			'post_title' => $post_title,
			'post_attach_id' => wppost( 'attach_id' ),
			'attach_id' => $attach_id,
			'attach_url' => wpkeyvalue( $image_src, 0 ),
			'key' => wppost( 'key' )	
		);
		echo json_encode( $a );
		die();
	}
	public function wp_mail_from( $from_email ){
		$wordpress = explode( 'wordpress@', $from_email );
		if( count( $wordpress ) > 1 ){
			return ae_get_option( 'wp_mail_address', false ) ? ae_get_option( 'wp_mail_address', false ) : get_bloginfo( 'admin_email' );
		}
		return $from_email;
	}
	public function wp_mail_from_name( $from_name ){
		if( $from_name == 'WordPress' ){
			return ae_get_option( 'wp_mail_name', false ) ? ae_get_option( 'wp_mail_name', false ) : get_bloginfo( 'name' );
		}
		return $from_name;
	}
	public function new_user_registration( $notification, $user, $blogname ) {
		$default_notification = wpkeyvalue( $notification, 'subject' );
		$subject = ae_get_option( 'new_user_registration_subject_name', false );
		if( $subject ){
			$notification['subject'] = $subject;
			$in_array = explode( "{if}", $subject );
			if( wpkeyvalue( $in_array, 1 ) ){
				$in_array = explode( "{/if}", wpkeyvalue( $in_array, 1 ) );
				$in_array = explode( "|", wpkeyvalue( $in_array, 0 ) );
				if( wpkeyvalue( $in_array, 0 ) && wpkeyvalue( $in_array, 1 ) ){
					if( in_array( wpkeyvalue( $in_array, 0 ), (array) wpstdclass( $user, 'roles' ) ) ){
						$notification['subject'] = wpkeyvalue( $in_array, 1 );
					}else{
						$notification['subject'] = $default_notification;
					}
				}
			}
		}
		return $notification;
	}
}

add_action( 'plugins_loaded', 'ae_loaded', 10 );
function ae_loaded(){
	new Anton_Extensions;
	do_action( 'ae_loaded' );
}

endif;