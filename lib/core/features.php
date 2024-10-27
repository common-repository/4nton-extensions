<?php

add_action( 'login_header', 'ae_wp_login_action' );
function ae_wp_login_action(){
	if ( ae_get_option( 'wp_login', false ) ) {
		include( AE_TEMPLATE_PATH . '/wp-login.php' );
	}
}

add_action( 'init', 'ae_init_action' );
function ae_init_action(){
    if ( ! is_admin() && ae_get_option( 'inputautofill', false ) ){
   		wp_deregister_script( 'jquery-ui-autocomplete' );
    }
}

add_action( 'wp_head', 'ae_wp_head_action' );
function ae_wp_head_action(){
	// FOR GRAVITY FORM ACTIVATION
	if ( ae_get_option( 'gravity_form_activation', false ) && ( wpget( 'page' ) == 'gf_activation' ) ) {
		do_action( 'gf_activation_before' );
		include( AE_TEMPLATE_PATH . '/gf-activation.php' );
		do_action( 'gf_activation_after' );
	}
	// ENQUEUE GRAVITY FORM RECAPCHA LINK
	if ( ae_get_option( 'gf_grecaptcharender_error', false ) ) {
		printf( '<script type="text/javascript" src="%s"></script>', 'https://www.google.com/recaptcha/api.js?hl=en&render=explicit' );
	}
}

add_action( 'wp_footer', 'ae_wp_footer_action' );
function ae_wp_footer_action(){
	// DEQUEUE GRAVITY FORM RECAPCHA LINK
	//$enqueued = array();
	$wp_scripts = wp_scripts();
	//$registered = wpstdclass( $wp_scripts, 'registered' );
	/*if( is_array( $registered ) ){
		foreach( $registered as $item ){
			
		}
	}*/
	$queue = wpstdclass( $wp_scripts, 'queue' );
	if ( ae_get_option( 'gf_grecaptcharender_error', false ) && is_array( $queue ) ) {
		if ( in_array( 'gform_recaptcha', $queue ) ) {
			$wp_scripts->dequeue( 'gform_recaptcha' );
		}
	}
}
