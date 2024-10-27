<?php

global $current_user;

$query = array();
$upload_dir       = wp_upload_dir();
$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, wpkeyvalue( $upload_dir, 'path' ) ) . DIRECTORY_SEPARATOR;
$img = wppost( 'base64' );
$img = str_replace( 'data:image/png;base64,', '', $img );
$img = str_replace( ' ', '+', $img );
$decoded          = base64_decode( $img );
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
if( ! function_exists( 'wp_get_current_user' ) ) {
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
$post_title = preg_replace( '/\.[^.]+$/', '', basename( $filename ) );
$attachment = array(
 	'post_mime_type' => wpkeyvalue( $file_return, 'type' ),
 	'post_title' => $post_title,
 	'post_content' => '',
 	'post_status' => 'inherit',
 	'guid' => wpkeyvalue( $wp_upload_dir, 'url' ) . '/' . basename($filename)
 );
global $wpdb, $current_user;
$args = array(
	'post_type' => 'attachment',
	'posts_per_page' => -1,
	'fields' => 'ids',
	'author' => wpstdclass( $current_user, 'ID' ),
	'meta_key' => wppost( 'key' ),
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
$attach_id = wp_insert_attachment( $attachment, $filename, 289 );
require_once(ABSPATH . 'wp-admin/includes/image.php');
$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
wp_update_attachment_metadata( $attach_id, $attach_data );
$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
update_post_meta( $attach_id, wppost( 'key' ), true );
update_user_meta( wpstdclass( $current_user, 'ID' ), 'ae_' . wppost( 'key' ), $attach_id );
$a = array(
	'user_id' => wpstdclass( $current_user, 'ID' ),
	'post_title' => $post_title,
	'post_attach_id' => wppost( 'attach_id' ),
	'attach_id' => $attach_id,
	'attach_url' => wpkeyvalue( $image_src, 0 ),
	'key' => wppost( 'key' )
);
echo json_encode( $a );