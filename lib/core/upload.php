<?php

if ( ! defined('ABSPATH') ){ exit; }

if ( ! class_exists( 'Anton_Extensions_Upload' ) ) {

class Anton_Extensions_Upload {
	public function __construct() {
		$this->init();
	}
	public function init() {
		add_action( 'wp_ajax_croppie', array( $this, 'croppie' ) );
		add_action( 'wp_ajax_nopriv_croppie', array( $this, 'croppie' ) );
		do_action( 'ae_upload_init' );
	}
}

add_action( 'ae_init', array( $this, 'croppie' ) );
function ae_upload(){
	new Anton_Extensions_Upload;
}

}
