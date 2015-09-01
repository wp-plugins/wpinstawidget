<?php
add_action( 'admin_enqueue_scripts', 'igw_add_head_back' );

function igw_register_settings() {
	register_setting( IGW_REF_SETTINGS_GROUP, IGW_OPT_PREF . 'account' );
}

function igw_add_head_back() {
	/* JS BACK */
	wp_enqueue_script( 'igw-js-back', IGW_FILE_JS_BACK, array('jquery') );
	/* CSS BACK */
	echo "<link href=\"" . IGW_URL_CSS_BACK . "\" rel=\"stylesheet\" type=\"text/css\"/>";
}

function igw_add_pages() {
	add_options_page('WpInstaWidget. Settings', 'Wp InstaWidget', 8, IGW_PAGE_GENERAL, 'igw_page_general');
	
	add_action('admin_init', 'igw_register_settings' ); /* Регистрация настроек */
}

function igw_start_form( $title = '', $func = null ) {
?>
<div id="igw_overlay"></div>
<div id="igw_frm_modal"></div>

<div id="wpinstawidget_admin" class="wrap">
	<h2><?=$title?></h2>
	<?php if( $func ) $func(); ?>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
<?php
}

function igw_end_form( $submitBtn = true ) {
?>
	<input type="hidden" name="action" value="update" />
	<p class="submit">
		<?php if( $submitBtn ) { ?><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /><?php } ?>
		<button class="button" onclick="igwUpdateThemes( this ); return false;">Update themes</button>
		<?php /*<input type="hidden" name="page_options" value="<?=implode(',', fl_get_options())?>" />*/ ?>
		<?php settings_fields( IGW_REF_SETTINGS_GROUP ); ?>
	</p>
</form>
<?php
}

function igw_getThemes() {
	$fileName = 'themes.json';
	$fullName = IGW_PATH_ROOT . '/' . $fileName;
	if( file_exists( $fullName ) ) {
		if( $content = @file_get_contents( $fullName ) ) {
			if( $json = @json_decode( $content, true ) ) {
				return $json;
			}
		}
	}
	
	return false;
}

function igw_updateThemes() {
	if( $content = @file_get_contents( IGW_THEMES_IMPORT_FILE ) ) {
		if( $themes = @json_decode( $content, true ) ) {
			$fileName = 'themes.json';
			$fullName = IGW_PATH_ROOT . '/' . $fileName;
			if( @file_put_contents( $fullName, json_encode($themes) ) ) {
				return true;
			}
		}
	}
	
	return false;
}

function igw_getConstructor() {
	if( !$themes = igw_getThemes() ) {
		echo 'ERROR: themes file non found or damaged';
		return false;
	}
?>
<div id="igwconst">
	<form id="igwconst_form" method="post" onsubmit="igwResult(this); return false;">
		<h2>Shortcode options</h2>
		<div class="igwc-row">
			<div class="igwc-col igwc-col-left">
				<label class="igwc-lbl" for="igwc_theme">Theme</label>
				<select id="igwc_theme" class="igwc-sel" name="igwc[theme]">
					<?php foreach( $themes as $theme=>$data ) : ?>
					<option value="<?=$theme?>"<?php if( $data ) foreach( $data as $key=>$val ) echo " {$key}=\"{$val}\""; ?><?php if( in_array($theme, array('profileOne')) ) echo ' selected'?>><?=$theme?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="igwc-col igwc-col-right">
				<label class="igwc-lbl" for="igwc_count">Posts count</label>
				<input id="igwc_count" class="igwc-inp" type="number" name="igwc[count]" value="1" min="1" max="10" step="1"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="igwc-row">
			<div class="igwc-col igwc-col-left">
				<label class="igwc-lbl" for="igwc_width">Width</label>
				<input id="igwc_width" class="igwc-inp" type="number" name="igwc[w]" value="150" min="150" max="1200" step="10"/>
			</div>
			<div class="igwc-col igwc-col-right">
				<label class="igwc-lbl" for="igwc_height">Height</label>
				<input id="igwc_height" class="igwc-inp" type="number" name="igwc[h]" value="250" min="250" max="1000" step="10"/>
			</div>
			<div class="clear"></div>
		</div>
		<div class="igwc-row">
			<button class="button-primary igwc-submit" type="submit">Show result</button>
		</div>
	</form>
</div>

<div id="igwconst_result" class="igwconst-result">
	<div class="igwconst-result-block">
		<h2 class="igwconst-result-title">Shortcode</h2>
		<input type="text" readonly id="igwconst_result_code" class="igwconst-result-code" />
		<h2 class="igwconst-result-title">Preview</h2>
		<div id="igwconst_result_wrap" class="igwconst-result-wrap"></div>
	</div>
</div>

<script type="text/javascript">
	var igwJ = jQuery.noConflict();
	
	var elThemes = igwJ('#igwc_theme');
	var elW = igwJ('#igwc_width');
	var elH = igwJ('#igwc_height');
	var elPosts = igwJ('#igwc_count');
	var elCount = igwJ('#igwc_max');
	
	elThemes.change(function(){
		el = igwJ(this); opt = igwJ(':selected', this).get(0); val = el.val(); data = opt.dataset;
		if( data.width ) {
			elW.val(data.width).prop({'readonly': true, 'disabled': false});
		}
		if( data.widthMin ) {
			elW.prop({'min': data.widthMin, 'readonly': false});
		}
		if( data.widthStep ) {
			elW.prop({'step': data.widthStep, 'readonly': false});
		}
		
		if( data.height ) {
			elH.val(data.height).prop({'readonly': true});
		}
		if( data.heightMin ) {
			elH.prop({'min': data.heightMin, 'readonly': false});
		}
		if( data.heightStep ) {
			elH.prop({'step': data.heightStep, 'readonly': false});
		}
		
		if( data.postsPer ) {
			elPosts.val(data.postsPer).prop({'readonly': false});
		}
		if( data.postsPerMin ) {
			elPosts.prop({'min':data.postsPerMin, 'readonly': false});
		}
		if( data.postsPerMax ) {
			elPosts.prop({'max':data.postsPerMax, 'readonly': false});
		}
		
		if( data.postsCount ) {
			elCount.val(data.postsCount).prop({'readonly': true});
		}
	});
	
	elW.prop({'readonly': false});
	elH.prop({'readonly': false});
	elPosts.prop({'readonly': false});
	elCount.prop({'readonly': false});
	
	elThemes.change();
	
	function igwShowResult( show ) {
		var block = igwJ('#igwconst_result');
		if( !block.length ) return false;
		if( show ) {
			block.slideDown(200);
		} else {
			block.slideUp(0);
		}
	}
	
	function igwResult( el ) {
		form = igwJ(el);
		var elWrap = igwJ('#igwconst_result_wrap');
		var elCode = igwJ('#igwconst_result_code');
		
		var data = form.serializeArray();
		data.push({'name':'igwc_query', 'value':'show_result'});
		
		igwJ.ajax({
			data: data, type: 'post', dataType: 'json', url: '/wp-admin/admin-ajax.php',
			beforeSend:function(){
				igwShowResult( false );
				elWrap.empty();
				elCode.empty();
			},
			success:function(res){
				if( res ) {
					if( res.status ) {
						igwShowResult( true );
						elWrap.html( res.html );
						elCode.val( res.code );
					}
				}
			},
			error:function(){},
			complete:function(){}
		});
		
		return false;
	}
	
	function igwUpdateThemes( el ) {
		el = igwJ(el);
		if( el.hasClass('igw-update-success') ) return false;
		elText = el.html();
		var data = [{'name':'igwc_query', 'value':'update_themes'}];
		
		igwJ.ajax({
			data: data, type: 'post', dataType: 'json', url: '/wp-admin/admin-ajax.php',
			beforeSend:function(){
				el.text('Update starting');
			},
			success:function(res){
				if( res ) {
					if( res.status ) {
						el.text('Update successfully complete').addClass('igw-update-success');
						setTimeout(function(){
							el.html( elText );
						}, 2000);
					}
				}
			},
			error:function(){
				el.text('ERROR: Update failed');
			},
			complete:function(){}
		});
		
		return false;
	}
</script>
<?php
}

function igwGetShortcode( $t, $c, $w, $h ) {
	$shortcode = IGW_SHORTCODE_NAME;
	return "[{$shortcode} t=\"{$t}\" c=\"{$c}\" w=\"{$w}\" h=\"{$h}\"]";
}

function igw_getHtmlCode( $args = null ) {
	if( !$username = get_option( IGW_OPT_PREF . 'account' ) ) return false;
	
	$args = shortcode_atts( array(
		't'=>'profileOne',
		'w'=>150,
		'h'=>410,
		'c'=>1
	), $args );

	if( !empty($args['t']) ) $theme = trim($args['t']); else return false;
	
	if( !empty($args['c']) && (int) $args['c'] ) $count = (int) $args['c']; else return false;
	
	if( !empty($args['w']) && (int) $args['w'] ) $width = (int) $args['w']; else return false;
	if( !empty($args['h']) && (int) $args['h'] ) $height = (int) $args['h']; else return false;
	
	$body = array();
	$body[] = '<div id="igwidget" style="width:'.$width.'px;text-align:center;">';
	$body[] = '<span style="font-size:8px !important;display:block !important;padding:2px !important;width:100% !important;">powered by <a id="igwlink" href="http://starsinstagram.ru/" target="_blank" style="color:#346088 !important;text-decoration:none !important;">starsinstagram.ru</a></span>';
	$body[] = '<script id="igwscript" type="text/javascript" src="http://starsinstagram.ru/igw/igw_js.php?name='.$username.'&amp;theme='.$theme.'&amp;h='.$height.'&amp;count='.$count.'" async></script>';
	$body[] = '</div>';
	
	return implode('', $body);
}


add_shortcode( IGW_SHORTCODE_NAME, 'igw_getHtmlCode' );