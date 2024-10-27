<?php 
	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	ae_field_html( 
		'text', 
		'wp_mail_name', 
		array( 
			'label' => __( 'Default Subject Name', AE ), 
			'description' => __( 'This will override the default WordPress subject name.', AE ), 
			'default' => get_bloginfo( 'name' ),
			'wrap' => 'p',
			'wrap_class' => 'separator'
		) 
	);
	ae_field_html( 
		'text', 
		'wp_mail_address', 
		array( 
			'label' => __( 'Default Email Address', AE ), 
			'description' => __( 'This will override the default WordPress email address.', AE ),
			'default' => get_bloginfo( 'admin_email' ),
			'wrap' => 'p',
			'wrap_class' => 'separator'
		) 
	);
?>
<p>
<strong><?php _e( "Useful tips.", AE ); ?></strong>
<pre>
<code style="display:block;padding:10px;"><strong>{if}role_slug_name|subject_name_text{/if}</strong><br />
&nbsp;&nbsp;&nbsp;<strong>Note</strong>, {if} must have always {/if} closing to avoid error setup.
&nbsp;&nbsp;&nbsp;<strong>role_slug_name</strong> - example slug role is `subscriber`, `contributor`, `author`, `editor`administrator`.
&nbsp;&nbsp;&nbsp;<strong>subject_name_text</strong> - defaul subject name is `[%s] New User Registration`.
</code>
</pre>
</p>
<?php
	ae_field_html( 
		'text', 
		'new_user_registration_subject_name', 
		array( 
			'label' => __( 'New user registration subject name', AE ), 
			'description' => sprintf( __( 'This will override the default `[%s] New User Registration` WordPress subject name. This settings will work since WordPress 4.9.0', AE ), $blogname ),
			'default' => sprintf( __( '[%s] New User Registration', AE ), $blogname ),
			'wrap' => 'p',
			'wrap_class' => 'separator'
		) 
	);