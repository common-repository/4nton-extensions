<?php

if ( !defined('ABSPATH') ){ exit; }

function ae_string( $string, $echo = false, $separator = "-" ){
	$string = strtolower( $string );
	$string = str_replace( ' ', '-', $string );
	$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
	if( $echo == true ){
		echo preg_replace( '/-+/', $separator, $string );
		return;
	}
   	return preg_replace( '/-+/', $separator, $string );
}

function ae_field_name( $key, $echo = true ) {
 	if( ! $echo ){
 		return AE . "[$key]";
 	}
 	echo AE . "[$key]";
}

function ae_meta( $id, $key, $echo = false ) {
	$output = get_post_meta( $id, $key, true );
	if( ! $output && ! metadata_exists( 'post', $id, $key ) ) {
		$output = ae_usermeta( $key );
	}
	if ( is_array( $output ) ) {
		$output = array_filter( $output );
	}
	if( $echo ){
		echo $output;
	}else{
		return $output;
	}
}

function ae_term_meta_value( $key, $echo = true ) {
	$value = ae_usermeta( $key );
	if( wpget( 'tag_ID' ) != '' ){
		if( get_term_meta( wpget( 'tag_ID' ), $key, true ) ){
			$value = get_term_meta( wpget( 'tag_ID' ), $key, true );
		}	
	}
	if( $echo ){
		echo $value;
	}else{
		return $value;
	}
}

function ae_term_meta( $id, $key, $echo = false ) {
	$output = '';
	if( get_term_meta( $id, $key, true ) ){
		$output = get_term_meta( $id, $key, true );
	}		
	if ( is_array( $output ) ) {
		$output = array_filter( $output );
	}
	if( $echo ){
		echo $output;
	}else{
		return $output;
	}
}

function ae_usermeta( $key, $userid = false ) {
	global $current_user;
 	return get_user_meta( wpstdclass( $current_user, 'ID' ), $key, true );
}

function ae_get_meta( $key, $id = null ) {
	if( $id == null ){
		$id = get_the_ID();
	}
	return get_post_meta( $id, ae_name_to_key( $key ), true ); 	
}

function ae_attr_id( $id, $key ) {
 	return ae_return_slug( ae_meta( $id, $key ) );
}

function ae_get_option( $key, $echo = true ) {
 	$options = get_option( AE );
 	$output = wpkeyvalue( $options, $key );
	if( ! $echo ){
		return apply_filters( "ae_" . $key, $output );
	}
 	echo apply_filters( "ae_" . $key, $output );
}

function ae_x( $classes, $echo = true ){
	if( empty( $classes ) ){ return; }
	$classes = explode( ' ', $classes );
	$output = '';
	for( $a = 0; $a < count( $classes ); $a++ ){
		$output .= ' ae-' . $classes[$a];
	}
	if( $echo ){ echo $output; return; }
	return $output;
}

function ae_get_fields( $fields ){
	return $fields;
}

function ae_get_image_url( $id ){
	global $wpdb;
	return get_the_post_thumbnail_url( $id, 'full' );
}

function ae_term_id( $id ){
	global $wpdb;
	$prefix = $wpdb->prefix;
	$value = $wpdb->get_var( "SELECT {$prefix}term_taxonomy.term_id FROM {$prefix}term_taxonomy LEFT JOIN {$prefix}term_relationships ON {$prefix}term_relationships.term_taxonomy_id = {$prefix}term_taxonomy.term_id WHERE {$prefix}term_taxonomy.taxonomy IN ('ae_boarding_house','ae_apartment','ae_dormitory','ae_condominium') AND {$prefix}term_relationships.object_id = {$id} GROUP BY {$prefix}term_taxonomy.term_id" );
	return $value;	
}

function ae_wpdb( $method, $select, $type = OBJECT ){
	global $wpdb;
	if( ! $type ){
		return $wpdb->$method( $select );
	}
	return $wpdb->$method( $select, $type );
}

function ae_is_home(){
	if( is_home() || is_front_page() ){
		return true;
	}
	return false;
}

function ae_the_content( $more_link_text = null, $strip_teaser = false ) {
	global $ae_content_atts;
	if( $ae_content_atts['content'] ) :
		echo do_shortcode( $ae_content_atts['content'] );
	else :
		$content = get_the_content( $more_link_text, $strip_teaser );
		$content = apply_filters( 'ae_the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		echo $content;
	endif;
}

function ae_get( $value ) {
	if( isset( $_REQUEST[$value] ) ) {
		return $_REQUEST[$value];
	}
	return;
}

function ae_save_options(){
	$default = array();
	$args = apply_filters( 'ae_save_options', array() );
	$type = isset( $arg['type'] ) ? $arg['type'] : '';
	if( $args ) :
		foreach( $args as $arg ) :
			if( isset( $arg['name'] ) && $type != 'html' ) :
				$default[] = $arg['name'];
			endif;
		endforeach;
	endif;
	return $default;
}

function ae_checked( $value, $array ){
	if( in_array( $value, $array ) ) :
		echo ' checked="checked"';
	endif;
}


function ae_debug_pre( $pre, $print = true ){
	if( isset( $_GET['ae_debug_pre'] )){
		echo " \n <pre>";
		if( $print ) :
			print_r($pre);
		else:
			echo $pre;
		endif;
		echo '</pre>';
	}
}

function ae_repeater( $h3, $key, $type = 'text', $desc = false, $results = array() ){
	$id = ae_get_post();
	if( ! $results && ae_meta( $id, $key ) ){
		$results = ae_meta( $id, $key );
	}
	?>
    <h3><?php echo $h3; ?></h3>
    <p><em><?php 
		if( $desc ) {
			echo $desc; 
		}else{
			_e( "Add your custom $h3 as much as you can. Click `+` to add or `X` to remove button.", RD );
		}
	?></em></p>
    <table class="ae-repeater">
        <?php
        	if( $results ){
				foreach($results as $result ){
					?>
					<tr>
						<td><input type="<?php echo $type; ?>" name="<?php echo $key; ?>[]" id="<?php echo $key; ?>" class="field <?php echo $key; ?>" value="<?php echo $result; ?>" /></td>
						<td class="ar-wrap"><span class="add">+</span><span class="remove">X</span></td>
					</tr>
					<?php
				}
			}else{
				?>
				<tr>
					<td><input type="<?php echo $type; ?>" name="<?php echo $key; ?>[]" id="<?php echo $key; ?>" class="field <?php echo $key; ?>" value="" /></td>
					<td class="ar-wrap"><span class="ar add">+</span><span class="ar remove">X</span></td>
				</tr>
				<?php
			}
		?>
    </table>
	<?php
}

function ae_term_repeater( $h3, $key, $type = 'text', $desc = false ){
	$results = get_term_meta( ae_get( 'tag_ID' ), $key, true ) ? : array();
	?>
    <h3><?php echo $h3; ?></h3>
    <p><em><?php 
		if( $desc ) {
			echo $desc; 
		}else{
			_e( "Add your custom $h3 as much as you can. Click `+` to add or `X` to remove button.", RD );
		}
	?></em></p>
    <table class="ae-repeater">
        <?php
        	if( $results ){
				foreach($results as $result ){
					?>
					<tr>
						<td><input type="<?php echo $type; ?>" name="<?php echo $key; ?>[]" id="<?php echo $key; ?>" class="field <?php echo $key; ?>" value="<?php echo $result; ?>" /></td>
						<td class="ar-wrap"><span class="add">+</span><span class="remove">X</span></td>
					</tr>
					<?php
				}
			}else{
				?>
				<tr>
					<td><input type="<?php echo $type; ?>" name="<?php echo $key; ?>[]" id="<?php echo $key; ?>" class="field <?php echo $key; ?>" value="" /></td>
					<td class="ar-wrap"><span class="ar add">+</span><span class="ar remove">X</span></td>
				</tr>
				<?php
			}
		?>
    </table>
	<?php
}

function ae_title( $id ){
	global $current_user;
	$display_name = array_filter( array( ae_usermeta( 'first_name' ), ae_usermeta( 'last_name' ) ) );
	//print_r($display_name);
	$display_name = join( " ", $display_name );
	//echo "1 `$display_name`";
	if( ! $display_name ){
		$display_name = $current_user->display_name;
		//echo "2 `$display_name`";
	}
	if( ! $display_name ){
		$display_name = ae_meta( $id, 'complete_address' );
		//echo "3 `$display_name`";
	}
	//echo "3 `$display_name`";
	return $display_name;
}

function ae_term_list( $args = array() ) {
	$defaults = array(
		'after'    => '',
		'before'   => __( '', RD ),
		'sep'      => ', ',
		'taxonomy' => 'category',
	);
	$atts = wp_parse_args( $args, $defaults );
	$terms = get_the_term_list( get_the_ID(), $atts['taxonomy'], $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] );
	if ( is_wp_error( $terms ) )
			return '';
	if ( empty( $terms ) )
			return '';
	$output = '<span class="terms">' . $terms . '</span>';
	return apply_filters( 'ae_term_list', $output, $terms, $atts );
}

function ae_pricing( $id, $key = false ) {
	$default_args = array( 
		'rate' => '', 
		'per' => '', 
		'type' => '', 
		'negotiable' => '' 
	);
	$pricing = wp_parse_args( ae_meta( $id, 'pricing' ), $default_args );
	if( empty( $pricing['rate'] ) ){
		return array();
	}
	if( $key ){
		return apply_filters( 'ae_pricing', $pricing[$key] );
	}
	return apply_filters( 'ae_pricing', $pricing );
}

function ae_sanitize_term_meta ( $value ) {
    return sanitize_text_field ($value);
}

function ae_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function ae_get_latlng( $address, $type = 'lat' ){
	$address = str_replace( " ", "+", $address );
	$geocode = ae_get_contents( "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false" );
	$results = json_decode( $geocode );
 	if( isset( $results->results[0] ) ){
 		$LatLong = $results->results[0]->geometry->location->lat;
	 	if( $type == 'lng' ){
			$LatLong = $results->results[0]->geometry->location->lng;
 		}
	}
	return $LatLong;
}

function ae_get_distance( $lat1, $lat2, $lng1, $lng2 ){
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$lng1."&destinations=".$lat2.",".$lng2."&mode=driving&units=imperial&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
	if( $response_a['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS' ) :
		return false;
	endif;
    $_dist = $response_a['rows'][0]['elements'][0]['distance']['value'];
    return $_dist * 0.000621371;
}

function ae_galleries( $output ){
	global $rdgalleries;
	$rdgalleries = $output;
	include( RD_TEMPLATE_PATH . '/gallery.php' );
}

function ae_get_default( $key ){
	return;
}

function ae_get_font_family( $option_name ){
	$font_family = json_decode( ae_get_option( $option_name ), true );
	return $font_family['family'];
}

function ae_get_google_font_url( $font_array ) {
	if ( count( $font_array ) > 0 ) {	
		$font_array = ae_get_google_font( $font_array );	
		$base_url = '';
		$font_familys = array();
		$subsets = array();	
		foreach( $font_array as $font ) {
			if ( isset( $font['family'] ) ){
				$font_familys[] = str_replace( ' ', '+', $font['family'] ) . ':' . implode( ',', array_unique( $font['variants'] ) );
				$subsets = array_merge( $subsets, array_unique( $font['subsets'] ) );
			}
		}
		if ( count( $font_familys ) > 0 ) {
			$base_url .= implode('|', $font_familys );
		}
		if ( count( $subsets ) > 0) {
			$base_url .= '&subset=' . implode( ',', $subsets );
		}
		if ( $base_url != '' ) {
	  		return '//fonts.googleapis.com/css?family=' . $base_url;
		}
	}
	return null;
}

function ae_get_google_font( $font_array ){
	$fonts = array();
	foreach ( $font_array as $font ){
		if ( ! isset( $fonts[$font['family']] ) ){
			$fonts[$font['family']] = $font;
		} else {
			$fonts[$font['family']]['variants'] = array_merge( $fonts[$font['family']]['variants'], $font['variants'] );
			$fonts[$font['family']]['subsets'] = array_merge( $fonts[$font['family']]['subsets'], $font['subsets'] );
		}
	}
	return $fonts;
}

function ae_pixels_css(){
	return array( 'font-size', 'border-width', 'border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width', 'width', 'height', 'padding', 'padding-top', 'padding-bottom', 'padding-left', 'padding-right', 'margin', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right' );
}

function ae_get_css( $properties, $key, $pre = false ){
	$value = ae_get_option( $key, false );
	if(
		//( $properties == 'background-image' ) ||
		( $properties == 'font-family' )
	){
		$value = $key;
	}
	if(
		empty( $value ) && 
		in_array( $properties, array( 'border-top-color', 'border-bottom-color', 'border-left-color', 'border-right-color', 'border-color', 'background-color', 'color' ) )
	){
		$value = 'transparent';
	}
	if( $pre ){
		return "{$properties}:{$pre};";
	}
	if( 
		( empty( $value ) || ( $value == ae_get_default( $key ) ) ) &&
		( $properties != 'font-family' )
	){
		return;	
	}
	if(
		( $properties == 'background-image' ) &&
		$value
	){
		return "{$properties}:url('{$value}');";
	}
	$pixels = ae_pixels_css();
	$default = array_merge( $pixels, array( 'font-family' ) );
	if( 
		in_array( $properties, $pixels ) && 
		( ae_get_default( $key ) != $value ) &&
		$value
	){
		return "{$properties}:{$value}px;";
	}
	if(
		( ! in_array( $properties, $default ) ) &&
		$value
	){
		return "{$properties}:{$value};";
	}
	if(
		( $properties == 'font-family' ) &&
		$value
	){
		return "{$properties}:'{$value}';";
	}
}

function ae_get_styles( $selector, $styles ){
	$styles = array_filter( $styles );
	if( $styles ){echo "$selector{";}
	foreach( $styles as $style ){
		if( ! empty( $style ) ){
			echo $style;
		}
	}
	if( $styles ){echo "}";}
}

function ae_user_avatar_upload( $args ){
	$ae = new Anton_Extensions;	
	if( wpkeyvalue( $args, 'echo' ) ){
		echo $ae->user_avatar_upload( $args, true );
	}else{
		return user_avatar_upload( $args, true );
	}
}