<?php

global $current_user;

wp_enqueue_style( AE );
wp_enqueue_script( AE . '-croppie' );
wp_enqueue_script( AE );

$avatar = AE_IMG_URL . '/avatar.png';
if( wpkeyvalue( $r, 'default_image' ) ){
	// http://1.gravatar.com/avatar/a03b1619f6ad2177b8f1995b00f36d62?s=96&d=mm&r=g
	$avatar = wpkeyvalue( $r, 'default_image' );
}
if( get_user_meta( wpstdclass( $current_user, 'ID' ), wpkeyvalue( $r, 'meta_key' ), true ) ){
	$image_src = wp_get_attachment_image_src( get_user_meta( wpstdclass( $current_user, 'ID' ), wpkeyvalue( $r, 'meta_key' ), true ), 'full' );
	if( $image_src ){
		$avatar = wpkeyvalue( $image_src, 0 );
		$class = 'ae-img-active';
	}
}

?>
<div class="ae-inline ae-upload-wrap ae-ua-upload">
	<input
    	type="file"
        class="ae-upload"
        data-width="<?php wpkeyvalue( $r, 'width', true ); ?>"
        data-height="<?php wpkeyvalue( $r, 'height', true ); ?>"
        data-id="#<?php wpkeyvalue( $r, 'field_id', true ); ?>"
        data-key="<?php wpkeyvalue( $r, 'meta_key', true ); ?>"
        style="display:none;"
    />  
	<div class="ae-inline ae-img-wrap ae-relative <?php echo $class; ?>" style="line-height:0;">
		<i class="edit-image ae-absolute ae-top-0 ae-left-0 ae-fit ae-z-index-2 ae-hide ae-bg ae-cursor"></i>
		<img 
            class="image ae-z-index-1 ae-relative" 
            src="<?php echo $avatar; ?>" 
            alt="" 
            width="<?php wpkeyvalue( $r, 'width', true ); ?>" 
            height="<?php wpkeyvalue( $r, 'height', true ); ?>" 
            style="width:<?php wpkeyvalue( $r, 'width', true ); ?>px;height:<?php wpkeyvalue( $r, 'height', true ); ?>px;display: inline-block;background-color: rgba(0, 0, 0, 0.3);" 
        />
	</div>
	<div class="ae-crop-wrap" style="display:none;">
		<div class="ae-crop-inner">
            <div class="ae-crop-entry">
            	<div class="ae-crop"></div>
            	<div class="button ae-new" style="display:none;"><?php wpkeyvalue( $r, 'newimage', true ); ?></div>
            	<div class="button ae-save"><?php wpkeyvalue( $r, 'savetext', true ); ?></div>
            	<div class="button ae-cancel"><?php wpkeyvalue( $r, 'cancel', true ); ?></div>
	        </div>
	    </div>
	</div>
</div>
<div class="ae-inline ae-relative ae-description"><?php wpkeyvalue( $r, 'description', true ); ?></div>