<style>
#login {padding: 100px 0 0 !important;}<?php
ae_get_styles(
	'body.login',
	array(
		ae_get_css( 'background-image', 'wp_login_bi' ),
		ae_get_css( 'background-repeat', '', 'no-repeat' ),
		ae_get_css( 'background-size', '', 'cover' ),
		ae_get_css( 'background-position', '', 'center center' ),
	)
);
ae_get_styles(
	'#login h1',
	array(
		ae_get_css( 'background-color', 'wp_login_header_bc' ),
		ae_get_css( 'padding', '', '30px 0px' ),
	)
);
ae_get_styles(
	'#login h1 a',
	array(
		ae_get_css( 'background-image', 'wp_login_logo' ),
		ae_get_css( 'background-size', '', 'contain' ),
		ae_get_css( 'height', 'wp_login_logo_height' ),
		ae_get_css( 'width', 'wp_login_logo_width' ),
		ae_get_css( 'background-color', 'wp_login_header_bc' ),
		ae_get_css( 'background-position', '', 'center center' ),
		ae_get_css( '-webkit-box-shadow', '', 'none' ),
		ae_get_css( 'box-shadow', '', 'none' ),
	)
);
if( ae_get_option( 'wp_login_title', false ) ){
ae_get_styles(
	'#login h1 a',
	array(
		ae_get_css( 'background-image', '', 'none' ),
		ae_get_css( 'height', '', 'auto' ),
		ae_get_css( 'width', '', 'auto' ),
		ae_get_css( 'text-indent', '', '0px' ),
		ae_get_css( 'width', '', 'auto' ),		
		ae_get_css( 'font-size', 'wp_login_title_size' ),
		ae_get_css( 'color', 'wp_login_title_color' ),
	)
);
}
ae_get_styles(
	'#login input#wp-submit',
	array(
		ae_get_css( 'box-shadow', '', 'none' ),
    	ae_get_css( 'background-color', 'wp_login_content_button_bc' ),
    	ae_get_css( 'border-color', 'wp_login_content_button_bc' ),
    	ae_get_css( 'color', 'wp_login_content_button_color' ),
    	ae_get_css( 'border', '', '0' ),
    	ae_get_css( 'text-shadow', '', 'none' ),
    	ae_get_css( 'border-radius', '', '0 !important' ),
    )
);
ae_get_styles(
	'#login input#wp-submit:hover',
	array(
    	ae_get_css( 'border-color', 'wp_login_content_button_hover_bc' ),
    	ae_get_css( 'background-color', 'wp_login_content_button_hover_bc' ),
    	ae_get_css( 'color', 'wp_login_content_button_hover_color' ),
    )
);
ae_get_styles(
	'#login form label',
	array(
		ae_get_css( 'color', 'wp_login_content_color' ),
	)
);
ae_get_styles(
	'#login .message',
	array(
		ae_get_css( 'border-color', 'wp_login_notice_color' ),
	)
);
ae_get_styles(
	'#login #nav, #login #backtoblog',
	array(
		ae_get_css( 'background-color', 'wp_login_footer_bc' ),
		ae_get_css( 'color', 'wp_login_footer_color' ),
		ae_get_css( 'text-align', '', 'center' ),
	)
);
ae_get_styles(
	'#login h1 a, #login .message, #login form, #login #nav, #login #backtoblog',
	array(
		ae_get_css( 'margin-top', '', '0 !important' ),
		ae_get_css( 'margin-bottom', '', '0 !important' ),
	)
);
ae_get_styles(
	'#login #nav',
	array(
		ae_get_css( 'padding-top', '', '10px' ),
		ae_get_css( 'border-top', '', '1px solid' ),
		ae_get_css( 'border-top-color', 'wp_login_footer_bt_color' ),
	)
);
ae_get_styles(
	'#login #backtoblog',
	array(
		ae_get_css( 'padding-bottom', '', '10px' ),
	)
);
ae_get_styles(
	'#login #nav a,#login #backtoblog a',
	array(
		ae_get_css( 'padding-bottom', '', '10px' ),
		ae_get_css( 'background-color', 'wp_login_button_bc' ),
		ae_get_css( 'display', '', 'inline-block' ),
		ae_get_css( 'margin', '', '10px 5px' ),
		ae_get_css( 'padding', '', '0 20px' ),
		ae_get_css( 'line-height', '', '40px' ),
		ae_get_css( 'color', 'wp_login_button_color' ),
		ae_get_css( 'text-transform', '', 'uppercase' ),
		ae_get_css( 'outline', '', 'none' ),
		ae_get_css( '-webkit-box-shadow', '', 'none' ),
		ae_get_css( 'box-shadow', '', 'none' ),
	)
);
ae_get_styles(
	'#login #nav a:hover, #login #backtoblog a:hover',
	array(
		ae_get_css( 'background-color', 'wp_login_button_hover_bc' ),
		ae_get_css( 'color', 'wp_login_button_hover_color' ),
	)
);
ae_get_styles(
	'#login #loginform',
	array(
		ae_get_css( 'color', 'wp_login_content_color' ),
    	ae_get_css( 'background-color', 'wp_login_content_bc' ),
    	ae_get_css( 'border-top', '', '1px solid' ),
    	ae_get_css( 'border-color', 'wp_login_content_bt_color' ),
	)
);
ae_get_option( 'wp_login_custom_css' );
?></style>
<script type="text/javascript" src="<?php echo bloginfo( 'url' ); ?>/wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript">
	jQuery( document ).ready(function($) {
        $( '#login h1' ).remove();
		$( '#login #loginform' ).before( '<h1><a href="<?php echo bloginfo( 'url' ); ?>" title="<?php echo bloginfo( 'name' ); ?>"><?php echo ae_get_option( 'wp_login_title', false ) ? : bloginfo( 'name' ); ?></a></h1>' );
    });
</script>