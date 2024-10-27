<p><strong><?php _e( "Useful user information you can use. Please read each of the parameter's text output.", AE ); ?></strong><br />
<pre><code style="display:block;padding:10px;">{user_login} - Display the user login information.
{user_email} - Display the user email information.
{lostpassword} - Display lost password link.
{first_name} - Display the user first name information.
{last_name} - Display the user last name information.
{display_name} - Display the user display name information.
</code></pre></p>
<?php ae_field_html( 'text', 'gf_btn_link', array( 'label' => __( 'Button Link', AE ), 'wrap' => 'p', 'wrap_class' => 'separator' ) ); ?>
<?php ae_field_html( 'text', 'gf_title', array( 'label' => __( 'Title', AE ), 'wrap' => 'p', 'wrap_class' => 'separator' ) ); ?> 
<?php ae_field_html( 'textarea', 'gf_description', array( 'label' => __( 'Description', AE ), 'wrap' => 'p', 'wrap_class' => 'separator' ) ); ?> 