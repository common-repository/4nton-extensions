<?php

function ae_option_tab( $name, $template, $enable = true ){
    $closed = 'closed';
    $recent_active = explode( ',', ae_get_option( 'recent_active', false ) );
    if( in_array( ae_string( $name, false, '_' ), (array) $recent_active ) ){
        $closed = '';
    }
    ?>
    <div id="normal-sortables" class="meta-box-sortables">
        <div id="dashboard_right_now" class="postbox <?php echo $closed; ?>" >
            <button type="button" class="handlediv dashboard-up-down" aria-expanded="true"><span class="screen-reader-text">Toggle panel: <?php echo $name; ?></span><span class="toggle-indicator" aria-hidden="true" data-section="<?php echo ae_string( $name, false, '_' ); ?>"></span></button><h2 class='hndle'><span><?php echo $name; ?></span></h2>
            <div class="inside">
                <div class="main"><?php include( $template ); ?></div>
                <div class="sub align-right">
					<?php if( $enable ) { ae_field_html( 'checkbox', ae_string( $name, false, '_' ), array( 'class' => 'on-off' ) ); } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
                
function ae_field_html( $type, $key = null, $field = null ){
	$defaults = array(
        'min' => '',
		'max' => '',
		'label' => '',
		'default' => '',
		'description' => '',
		'wrap' => false,
		'wrap_id' => '',
		'wrap_class' => '',
		'cols' => 100,
		'rows' => 8
	);
	$args = wp_parse_args( (array) $field, $defaults );
	if( $args['wrap'] ){ printf( '<%s id="%s" class="%s">', $args['wrap'], $args['wrap_id'], $args['wrap_class'] ); }
	switch ( $type ){
		case 'text' :
			?><label><strong><?php echo $args['label']; ?></strong> <input type="text" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" value="<?php ae_get_option( $key ); ?>" placeholder="<?php echo $args['default']; ?>" /></label><br /><i><?php echo $args['description']; ?></i><?php
			break;
		case 'url' :
			?><label><?php echo $args['label']; ?> <input type="url" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" value="<?php ae_get_option( $key ); ?>" placeholder="<?php echo $args['default']; ?>" /></label><?php
			break;
		case 'number' :
			?><label><?php echo $args['label']; ?> <input type="number" name="<?php ae_field_name( $key ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" value="<?php ae_get_option( $key ); ?>" min="<?php echo $args['min']; ?>" max="<?php echo $args['max']; ?>" placeholder="<?php echo $args['default']; ?>" /> px</label><?php
			break;
		case 'checkbox' :
			?><label><input type="checkbox" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" value="1" <?php checked( 1, ae_get_option( $key, false ) ); ?> /> <?php echo $args['label']; ?></label><br /><i><?php echo $args['description']; ?></i><?php
			break;
		case 'radio' :
			?><label><?php echo $args['label']; ?> 
                <?php if( (array) $args['choices'] ) : ?>
                    <?php foreach( $args['choices'] as $id => $option ) : ?>
                        <input type="radio" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" value="<?php echo $id; ?>" <?php checked( ae_get_option( $key, false ), $id ); ?> /> <?php echo $option; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </label><?php
			break;
        case 'color' :
            ?><div class="color-wrap"><div style="vertical-align:middle;"><?php echo $args['label']; ?></div> <input type="text" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="<?php ae_x( 'color-picker' ); ?> <?php echo $args['class']; ?>" value="<?php ae_get_option( $key ); ?>" placeholder="<?php echo $args['default']; ?>" /> <i><?php echo $args['description']; ?></i></div><?php
            break;
		case 'select' :
			?><label><?php echo $args['label']; ?> 
            <select name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>">
            	<option value="" <?php selected( ae_get_option( $key, false ), '' ); ?>>Select Options</option>
             	<?php foreach( $args['choices'] as $id => $option ) : ?>
                	<option value="<?php echo $id; ?>" <?php selected( ae_get_option( $key, false ), $id ); ?>><?php echo $option; ?></option>
            	<?php endforeach; ?>
            </select></label><?php
			break;
		case 'textarea' :
			?><label style="display: block;"><strong><?php echo $args['label']; ?></strong></label><br /><textarea name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field <?php echo $args['class']; ?>" cols="<?php echo $args['cols']; ?>" rows="<?php echo $args['rows']; ?>" placeholder="<?php echo $args['default']; ?>" style="max-width: 100%;"><?php echo esc_textarea( ae_get_option( $key, false ) ); ?></textarea><br />
            <?php if( isset( $args['description'] ) ) : ?>
            	<i class="description"><?php echo $args['description']; ?></i>
            <?php endif; ?>
            <?php
			break;
        case 'image' :
            ?>
            <div id="upload-wrap">
                <p style="margin-bottom:20px !important;"><strong><?php echo $args['label']; ?></strong> <input type="file" id="upload" data-id="#<?php echo $key; ?>" /> <i><?php _e( 'Select image from your local drive.', AE ); ?></i></p>
                <p style="margin-bottom:20px !important;color: blue;"><strong>NOTE:</strong> <i><?php _e( 'Your image should be bigger or equal on the required <a target="_blank" href="https://www.google.com.ph/search?q=image+dimension" style="font-weight:bold;color: red;">image dimension</a>.', AE ); ?></i></p>
                <p class="wh-wrap" style="margin-bottom:20px !important;"><input type="number" name="<?php ae_field_name( $key . '_width', true ); ?>" id="<?php echo $key . '_width'; ?>" class="ae-field width <?php echo $args['class']; ?>" value="<?php ae_get_option( $key . '_width' ); ?>" min="1" max="9999" placeholder="200" /> x <input type="number" name="<?php ae_field_name( $key . '_height' ); ?>" id="<?php echo $key . '_height'; ?>" class="ae-field height <?php echo $args['class']; ?>" value="<?php ae_get_option( $key . '_height' ); ?>" min="1" max="9999" placeholder="100" /> <i><?php _e( 'Width and height of your logo.', AE ); ?></i></p> 
                <div class="ae-columnwrap">
                    <div class="aecolumn onehalf" style="display: inline-block;vertical-align:top;">
                        <div class="upload-group upload-crop-wrap">
                            <div id="upload-crop">
                            </div>
                            <div class="button upload-crop">DONE</div>
                        </div>
                    </div>
                    <div class="aecolumn onehalf" style="padding: 20px;display: inline-block;vertical-align:top;background-color: rgba(0, 0, 0, 0.5);color: #fff;">
                        <div class="upload-group upload-preview">
                            <h3 style="margin-bottom:20px !important;"><strong style="color: #fff;"><?php _e( 'Preview Image', AE ); ?></strong></h3>
                            <div class="upload-wrap">
                                <p><label><strong style="color: #fff;"><?php echo $args['label']; ?></strong> <input type="text" name="<?php ae_field_name( $key, true ); ?>" id="<?php echo $key; ?>" class="ae-field ae-croppie-url <?php echo $args['class']; ?>" value="<?php ae_get_option( $key ); ?>" placeholder="<?php echo $args['default']; ?>" /></label></p>
                                <p><i style="color: #fff;"><?php echo $args['description']; ?></i></p>
                                <div style="clear:both;"></div>
                                <div id="upload-i">                                
                                    <?php if( ae_get_option( $key, false ) ) : ?>
                                        <img src="<?php ae_get_option( $key ); ?>" alt="" />
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
		default :
			return;
			break;	
	}
	if( $args['wrap'] ){ printf( '</%s>', $args['wrap'] ); }
}