<div id="ae-settings">
	<div class="group">
	    <form method="post" action="options.php">
	        <input type="hidden" name="reset" id="reset-option" value="">
	        <input type="hidden" name="<?php ae_field_name( 'recent_active', true ); ?>" id="recent_active" value="<?php ae_get_option( 'recent_active' ); ?>" />

	        <?php settings_fields( AE ); ?>
		 	<div class="ae-table">
		 		<div class="submit-wrap ae-table-cell align-left"><h1><?php _e( AE_NAME . ' Settings' ); ?></h1></div>
	        	<div class="submit-wrap ae-table-cell align-right"><input name="submit" id="submit" class="transition button" value="<?php _e( 'Save Changes', AE ); ?>" type="submit" /></div>
	        </div>
			<?php if ( isset( $_REQUEST['settings-updated'] ) == true ) : ?>
				<div class="ae-notice ae-notice-success">
					<?php if ( isset( $_REQUEST['reset'] ) == true ) : ?>
			       		<?php printf( '<strong>%s</strong>', __( 'Options reset', AE ) ); ?>
					<?php else : ?>
			       		<?php printf( '<strong>%s</strong>', __( 'Options saved', AE ) ); ?>
					<?php endif; ?>
					<span class="dashicons dashicons-yes" style="color:#46b450;"></span>
				</div>
		 	<?php endif; ?>
		 	<?php if ( isset( $_REQUEST['reset'] ) == true ) : ?>
			 	<?php delete_option( AE ); ?>
			<?php endif; ?>
			<div id="dashboard-widgets" class="metabox-holder ae">
				<p class="ae-field-group">
					<?php
						ae_field_html(
							'text',
							'google_api',
							array(
								'label' => __( 'Google Maps API Key', AE ),
								'description' => __( 'Google requires an API key to retrieve location information. Acquire an API key from the <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key">Google Maps API developer site</a>. Leave it empty if disabled and you already have in the site.', AE ),
							)
						);
					?>
				</p>
				<?php ae_option_tab( __( 'WordPress', AE ), AE_ADMIN_PATH . '/wordpress.php', false ); ?>
				<?php ae_option_tab( __( 'WP Mail Notification', AE ), AE_ADMIN_PATH . '/wp-mail.php' ); ?>
				<?php ae_option_tab( __( 'WP Login', AE ), AE_ADMIN_PATH . '/wp-login.php' ); ?>
				<?php ae_option_tab( __( 'Gravity Form Activation', AE ), AE_ADMIN_PATH . '/gf-activation.php' ); ?>
				<?php ae_option_tab( __( 'Fix Gravity Form Errors', AE ), AE_ADMIN_PATH . '/gf-errors.php', false ); ?>
				<?php do_action( 'ae_add_options' ); ?>
				<div class="ae-table ae-footer">
					<div class="submit-wrap align-left ae-table-cell">
						<input name="submit" id="reset" class="transition button" value="<?php _e( 'Reset', AE ); ?>" type="submit" />
					</div>
					<div class="author ae-center ae-table-cell"><?php _e( 'Developed by', AE ); ?> <a href="https://www.anthonycarbon.com/"><strong>Anthony Carbon</strong></a></div>
					<div class="submit-wrap ae-table-cell align-right"><input name="submit" id="submit" class="transition button" value="<?php _e( 'Save Changes', AE ); ?>" type="submit" /></div>
				</div>
			</div>
		</form>
	</div>
</div>