<style>
.gf-activation #content h2, .gf-activation #content span.h3, .gf-activation #content #signup-welcome p, .gf-activation #content div, .gf-activation #content p {
	display: block;
}
.gf-activation #content #signup-welcome p:first-child, .gf-activation #content p.lead-in {
	display: block;
	margin-bottom: 0;
}
.gf-activation #content {
	position: fixed;
	top: 0;
	margin: auto;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 999999;
	text-align: center;
	background-color: rgba(0, 0, 0, 0.5);
	display: table;
	width: 100%;
	height: 100%;
}
.gf-activation #content #signup-welcome {
	display: table-cell;
	width: 100%;
	box-sizing: border-box;
	text-align: center;
	vertical-align: middle;
}
.gf-activation #signup-welcome .group-cell {
	width: 430px;
	max-width: 100%;
	margin: auto;
	background-color: rgba(255, 255, 255, 0.8);
	padding: 20px;
	border-radius: 5px;
}
.gf-activation #content .button {
	background: #3089C3;
	color: #fff;
	font-size: 15px;
	padding: 10px 5px;
	width: 100px;
	margin: 15px auto 0;
	line-height: normal !important;
	cursor: pointer;
}
</style>
<script>
jQuery( document ).ready( function( $ ){
	var getPostValue = (function getPostValue(key) {
        var pageURL = decodeURIComponent(window.location.search.substring(1)),
            URLvar = pageURL.split('&'),
            keyName,
            i;      
        for (i = 0; i < URLvar.length; i++) {
            keyName = URLvar[i].split('=');
      
            if (keyName[0] === key) {
                return keyName[1] === undefined ? true : keyName[1];
            }
        }
    }),
	<?php
		$login = '';
		$user_login = '';
		$user_email = '';
		$lostpassword = '';
		$first_name = '';
		$last_name = '';
		$display_name = '';
		$gf_title = ae_get_option( 'gf_title', false );
		$gf_description = ae_get_option( 'gf_description', false );
		if( ae_get_option( 'gf_btn_link', false ) ){
			$login = sprintf( '<a class="button" href="%s">%s</a>', ae_get_option( 'gf_btn_link', false ), __( 'Login' ) );
		}
		require_once( gf_user_registration()->get_base_path() . '/includes/signups.php' );		
        if( wpget( 'page' ) == 'gf_activation' && wpget( 'key' ) ){			
			$result = GFUserSignups::activate_signup( wpget( 'key' ) );
			$user = new stdClass();
			if( wpkeyvalue( $result, 'user_id' ) ){
				$user = get_user_by( 'id', wpkeyvalue( $result, 'user_id' ) );
			}elseif( is_wp_error( $result ) ){
				$signup = $result->get_error_data();
				$user = get_user_by( 'email', wpstdclass( $signup, 'user_email' ) );
			}
			if( wpstdclass( $user, 'ID' ) ){
				$user_login = wpstdclass( $user, 'data', 'user_login' );
				$user_email = wpstdclass( $user, 'data', 'user_email' );
				$lostpassword = network_site_url( 'wp-login.php?action=lostpassword', 'login' );
				$first_name = get_user_meta( wpstdclass( $user, 'ID' ), 'first_name', true );
				$last_name = get_user_meta( wpstdclass( $user, 'ID' ), 'last_name', true );
				$display_name = wpstdclass( $user, 'data', 'display_name' );
				
				$gf_title = str_replace( '{user_login}', $user_login, $gf_title );
				$gf_title = str_replace( '{user_email}', $user_email, $gf_title );
				$gf_title = str_replace( '{lostpassword}', $lostpassword, $gf_title );
				$gf_title = str_replace( '{first_name}', $first_name, $gf_title );
				$gf_title = str_replace( '{last_name}', $last_name, $gf_title );
				$gf_title = str_replace( '{display_name}', $display_name, $gf_title );
				
				$gf_description = str_replace( '{user_login}', $user_login, $gf_description );
				$gf_description = str_replace( '{user_email}', $user_email, $gf_description );
				$gf_description = str_replace( '{lostpassword}', $lostpassword, $gf_description );
				$gf_description = str_replace( '{first_name}', $first_name, $gf_description );
				$gf_description = str_replace( '{last_name}', $last_name, $gf_description );
				$gf_description = str_replace( '{display_name}', $display_name, $gf_description );
			}		
		}
		
		$jason = json_encode( '<div id="signup-welcome"><div class="group-cell"><h2 class="h2">' . $gf_title . '</h2><div class="desc">' . $gf_description . '</div>' . $login . '</div></div>' );
	?>
	html = <?php echo $jason; ?>;
    if( getPostValue( 'page' ) == 'gf_activation' ){
        $( 'body' ).addClass( 'gf-activation' );
		$( '.gf-activation .widecolumn' ).html( html );
        $( document ).click( function( e ){
            var target = e.target;
            var targetid = target.id;
        });
    }
});
</script>
