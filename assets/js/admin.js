jQuery(document).ready(function($){
	var to=(function(){var timers={};return function(callback,ms,x_id){if(!x_id){x_id='';}if(timers[x_id]){clearTimeout(timers[x_id]);}timers[x_id]=setTimeout(callback,ms);};})(),ae,aeim,ae_title,ae_multiple,ae_field_id,ae_upload_type,ae_key,ae_loading = false,
	get = function get(key) {
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
		};
	if( $( '.dashboard-up-down .toggle-indicator' ).length ){
		$( document ).delegate( '.dashboard-up-down', 'click', function(){
			var postbox = [];
			$( '#ae-settings .postbox' ).each(function(index, element) {
				if( ! $( this ).hasClass( 'closed' ) ){
					postbox[postbox.length] = $( this ).find( '.toggle-indicator' ).data( 'section' );
				}
            });
			$( 'input#recent_active' ).val( postbox );
		});
	}
	if( $( '#apmediaupload' ).length ){
		$( document ).delegate( '#apmediaupload', 'click', function(){
			if( ae_loading ){ return; }
			ae_field_id = $( this ).attr( 'gf-hidden-id' );
			ae_title = String( $( this ).attr( 'gf-title' ) );
			ae_multiple = $( this ).attr( 'gf-multiple' );
			ae_upload_type = $( this ).attr( 'gf-type' );
			ae_media_type = $( this ).attr( 'file-type' );
			ae_label = $( this ).attr( 'gf-label' );
			ae_key = $( this ).attr( 'gf-label' );
			if( $( this ).attr( 'gf-multiple' ) == 'false' ){
				ae_multiple = false;
			}else if( $( this ).attr( 'gf-multiple' ) == 'true' ){
				ae_multiple = true;
			}
			if( ae_media_type == 'image' ){
				aeim = wp.media.controller.Library.extend(
					{
						defaults :  _.defaults(
							{
								title: ae_title,
								multiple: ae_multiple,
							},
						wp.media.controller.Library.prototype.defaults
						)
					}
				);
				ae = wp.media(
					{ 
						type: 'image',
						library: { type: 'image' },
						button : { text : ae_title },
						states : [
							new aeim()
						]			
					}
				);
				ae.on( 'select', function() {
					attachment = ae.state().get( 'selection' ).toJSON();
					var ids = [];
					var images = [];
					for (i = 0; i < attachment.length; i++) {
						ids[i] = attachment[i]['id'];
						images[i] = attachment[i]['url'];
					}
					if( ! ids ){ return; }
					if( ae_upload_type == 'single' ){
						ids = ids[0];
					}
					$.ajax({
						type	: "POST",
						url		: ae.ajaxurl,
						data	: {
							action		: 'ae_ajax',
							ids			: ids,
							multiple	: ae_multiple,
							type		: ae_upload_type,
							ae_media_type	: ae_media_type,
						},
						beforeSend: function( response ) {
							ae_loading = true;
							if( ae_upload_type == 'single' ){
								$( '.gf-' + ae_field_id.replace('#','').replace(' ','') ).css({
									'background-image' : "url('"+ae.spinner2x+"')",
									'background-position' : 'center center',
									'background-repeat' : 'no-repeat',
									'background-size' : 'auto',
								});
							}		
						},
						success: function( response ){
							if( response && ae_upload_type == 'single' ){
								$( ae_field_id ).val( response );
							}	
							if( response && ae_upload_type == 'gallery' ){
								var data = JSON.parse(response);
								$( ae_field_id ).val( data.ids );
							}
							if( response && ae_upload_type == 'single' ){
								$( '.gf-' + ae_field_id.replace('#','').replace(' ','') ).css({
									'background-image' : 'url('+images[0]+')',
									'background-size' : 'auto',
								});
							}
							if( response && ae_upload_type == 'gallery' ){
								var data = JSON.parse(response);
								$( '#gallery-' + ae_field_id.replace( '#', '' ).replace( ' ', '' ) ).html( data.preview );
							}
							setTimeout( function(){
								ae_loading = false;
							}, 300 );
						},		
					});				
				});
				ae.on( 'open', function() {
					var selection =  ae.state().get( 'selection' );
					ids = $( 'input' + ae_field_id ).val();
					console.log(ids);
					if( ids ){
						ids = ids.split( "," );
						if( ids ){
							ids.forEach( function( id ) {
								attachments = wp.media.attachment( id );
								attachments.fetch();
								selection.add( attachments ? [ attachments ] : [] );
							});
						}
					}
				});
				ae.open();
			}
			if( ae_media_type == 'video' ){
				if( ae ) {
					ae.open();
					return;
				}
				ae = wp.media.frames.file_frame = wp.media({
					multiple: ae_upload_type,
					title: ae_title,
					type: ae_media_type,
					library: { type: ae_media_type },
					button: { text : ae_label },
				});
				ae.on( 'select', function() {
					attachment = ae.state().get( 'selection' ).toJSON();
					var ids = [], nonvideoids = [], urls = [], count_1 = 0, count_2 = 0;
					for (i = 0; i < attachment.length; i++) {
						if( attachment[i]['type'] == 'video' ){
							count_1++;
							ids[count_1] = attachment[i]['id'];
						}else{
							count_2++;
							nonvideoids[count_2] = attachment[i]['id'];
						}
						urls[i] = attachment[i]['url'];
					}
					if( ! ids ){ return; }
					if( count_1 ) {
						$.ajax({
							type	: "POST",
							url		: ae.ajaxurl,
							data	: {
								action		: 'ae_ajax',
								ids			: ids,
								multiple	: ae_multiple,
								type		: ae_upload_type,
								ae_media_type	: ae_media_type,
							},
							beforeSend: function( response ) {
								ae_loading = true;
								if( ae_upload_type == 'single' ){
									$( '.gf-' + ae_field_id.replace( '#', '' ).replace( ' ', '' ) ).css({
										'background-image' : "url('" + ae.homeurl + "/wp-includes/images/spinner-2x.gif')",
										'background-position' : 'center center',
										'background-repeat' : 'no-repeat',
										'background-size' : 'auto',
									});
								}		
							},
							success: function( response ){
								if( response && ae_upload_type == 'single' ){
									$( ae_field_id ).val( response );
								}	
								if( response && ae_upload_type == 'gallery' ){
									var data = JSON.parse(response);
									$( ae_field_id ).val( data.ids );
								}
								if( response && ae_upload_type == 'single' ){
									$( '.gf-' + ae_field_id.replace('#','').replace(' ','') ).css({
										'background-image' : 'url('+images[0]+')',
										'background-size' : 'auto',
									});
								}
								if( response && ae_upload_type == 'gallery' ){
									var data = JSON.parse(response);
									$( '#gallery-' + ae_field_id.replace( '#', '' ).replace( ' ', '' ) ).html( data.preview );
								}
								setTimeout( function(){
									ae_loading = false;
								}, 300 );
							},		
						});	
					}					
					if( count_2 ){
						setTimeout(function(){
							$.ajax({
								url	: ae.ajaxurl,
								type	: 'post',
								data	: {
											action		: 'ae_delete_image',
											mediaid 	: nonvideoids,
										},
								success	: function(data){
								}
							});
						}, 500 );
					}
				});
				ae.on( 'open', function() {
					var selection =  ae.state().get( 'selection' );
					ids = $( 'input' + ae_field_id ).val();
					if( ids ){
						ids = ids.split( "," );
						if( ids ){
							ids.forEach( function( id ) {
								attachments = wp.media.attachment( id );
								attachments.fetch();
								selection.add( attachments ? [ attachments ] : [] );
							});
						}
					}
				});	
				ae.open();
				ae.on( 'close', function() {
					attachment = ae.state().get( 'selection' ).toJSON();
					var ids = [], nonvideoids = [], urls = [], count_1 = 0, count_2 = 0;
					for (i = 0; i < attachment.length; i++) {
						if( attachment[i]['type'] == 'video' ){
							count_1++;
							ids[count_1] = attachment[i]['id'];
						}else{
							count_2++;
							nonvideoids[count_2] = attachment[i]['id'];
						}
						urls[i] = attachment[i]['url'];
					}
					if( ! ids ){ return; }					
					if( count_2 ){
						setTimeout(function(){
							$.ajax({
								url	: ae.ajaxurl,
								type	: 'post',
								data	: {
											action		: 'ae_delete_image',
											mediaid 	: nonvideoids,
										},
								success	: function(data){
								}
							});
						}, 500 );
					}
				});
			}
		});
	}
	if( get( 'page' ) == 'ae' ){
		$( '.ae button.handlediv' ).on( 'click', function(){
			$( this ).parent( '.postbox' ).toggleClass( 'closed' );
		});
		$( '#ae-settings input[name="_wp_http_referer"]' ).val( $( '#ae-settings input[name="_wp_http_referer"]' ).val().replace( '&reset=true', '' ) );
		$( '#import-popup' ).click(function() {
			window.location.replace(window.location.href.replace('&reset=true',''));
		});
		$( '#ae-settings input#reset' ).click(function(e) {
			$( '#ae-settings input[name="_wp_http_referer"]' ).val( $( '#ae-settings input[name="_wp_http_referer"]' ).val() + '&reset=true' );
		});		
		$( '#ae-settings input#submit' ).click(function(e) {
			$( '#ae-settings input[name="_wp_http_referer"]' ).val( $( '#ae-settings input[name="_wp_http_referer"]' ).val().replace( '&reset=true', '' ) );
		});
		if( get( 'import' ) == 'true' ){
			$( '#ae-settings input[name="_wp_http_referer"]' ).val( $( '#ae-settings input[name="_wp_http_referer"]' ).val().replace( '&import=true', '' ) );
		}
	}
	var data_id = '',
	attach_id = '',
	uploadwidth = parseInt( $( '.wh-wrap .width' ).val() ),
	uploadwidth = ( uploadwidth ? uploadwidth : 200 ),
	uploadheight = parseInt( $( '.wh-wrap .height' ).val() ),
	uploadheight = ( uploadheight ? uploadheight : 100 );
 	$("#upload-i").width( uploadwidth ).height( uploadheight );
	$uploadCrop = $( '#upload-wrap #upload-crop' ).croppie({
	    enableExif: true,
	    viewport: {
	        width: uploadwidth,
	        height: uploadheight,
	        type: 'square'
	    },
	    boundary: {
	        width: 500,
	        height: 300
	    }
	});
	$('#upload-wrap #upload').on('change', function () {
		data_id = $( this ).attr( 'data-id' );
	  var reader = new FileReader();
	    reader.onload = (function (e) {
	      $uploadCrop.croppie('bind', {
	        url: e.target.result
	      }).then(function(){
	      });	      
	    });
	    reader.readAsDataURL(this.files[0]);
	});
	$('#upload-wrap .wh-wrap input').on('change', function () {
		uploadwidth = parseInt( $( '.wh-wrap .width' ).val() ),
		uploadwidth = ( uploadwidth ? uploadwidth : 200 ),
		uploadheight = parseInt( $( '.wh-wrap .height' ).val() ),
		uploadheight = ( uploadheight ? uploadheight : 100 );
		$( '#upload-wrap #upload-crop' ).croppie('destroy').width( uploadwidth + 100 );
 		$("#upload-i").width( uploadwidth ).height( uploadheight );
		$uploadCrop = $( '#upload-wrap #upload-crop' ).croppie({
		    enableExif: true,
		    viewport: {
		        width: uploadwidth,
		        height: uploadheight,
		        type: 'square'
		    },
		    boundary: {
		        width: uploadwidth + 100,
		        height: uploadheight + 100
		    }
		});
		$('#upload-wrap #upload').change();
 	});
	$('#upload-wrap .upload-crop').on('click', function (e) {
		if( $('#upload-wrap #upload').val() ){
			$('#upload-wrap .upload-edit-image').addClass( 'has-image' );
		}else{
			$('#upload-wrap .upload-edit-image').removeClass( 'has-image' );
		}
	  	$uploadCrop.croppie( 'result', {
	    	type: 'base64',
	    	size: 'original'
	  	}).then(function (resp) {
			if( $('#upload-wrap #upload').val() ){
				$( "#upload-i" ).html( '<img src="' + ae.spinner2x + '" />' );
				$.ajax({
					type	: "POST",
					url		: ae.ajaxurl,
					data	: {
						action		: 'croppie',
						base64		: resp,
						file		: $('#upload-wrap #upload').val(),
						attach_id	: attach_id,
						key	: $('#upload-wrap .ae-croppie-url').attr( 'id' ),
					},
					beforeSend: function( response ) {
					},
					success: function( response ){
						var data = JSON.parse(response);
						attach_id = data.attach_id;
						if( data.attach_id && $( data_id ).length ){
					        $("#upload-i").html( '<b style="color:red;"><img src="' + resp + '" /></b>' );
					        $(data_id).val(data.attach_url);
						}else{
							$("#upload-i").html( '<b style="color:red;">Upload Error</b>' );
						}
					},		
				});
			}else{
				$( "#upload-i" ).html( '<b style="color:red;">No Image!!!<b>' );
			}
	  });
	});
 	wdes_color_picker();
	 
	function wdes_color_picker() {
		if( $( '#ae-settings .ae-color-picker' ).length ){
			Color.prototype.toString = function() {
				if (this._alpha < 1) {
					return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
				}
				var hex = parseInt(this._color, 10).toString(16);
				if (this.error) return '';
				if (hex.length < 6) {
					for (var i = 6 - hex.length - 1; i >= 0; i--) {
						hex = '0' + hex;
					}
				}
				return '#' + hex;
			};
			$('#ae-settings .ae-color-picker').each(function( index ) {
				var $control = $(this),
					value = $control.val().replace(/\s+/g, ''),
					alpha_val = 100,
					$alpha, $alpha_output;
				if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
					alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
				}
				$control.wpColorPicker({
					clear: function(event, ui) {
						$alpha.val(100);
						$alpha_output.val(100 + '%');
					}
				});
				$('<div class="ae-alpha-wrap" style="display:none;">' + '<label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label>' + '<input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="ae-alpha-field">' + '</div>').appendTo($control.parents('.wp-picker-container:first').addClass('ae-color-picker-group').find('.wp-picker-holder')); 
				
				$alpha = $control.parents('.wp-picker-container:first').find('.ae-alpha-field');
				$alpha_output = $control.parents('.wp-picker-container:first').find('.ae-alpha-wrap output');
				$alpha.bind('change keyup', function() {
					var alpha_val = parseFloat($alpha.val()),
						iris = $control.data('a8cIris'),
						color_picker = $control.data('wpWpColorPicker');
					$alpha_output.val($alpha.val() + '%');
					iris._color._alpha = alpha_val / 100.0;
					$control.val(iris._color.toString());
					color_picker.toggler.css({
						backgroundColor: $control.val()
					});
				}).val(alpha_val).trigger('change');
			});
		}
	}
});