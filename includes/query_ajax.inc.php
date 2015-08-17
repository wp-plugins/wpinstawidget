<?php
if( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['igwc_query']) ) :
	header('Content-Type: text/html; charset=utf-8', true);
	$IGWC_QUERY = strip_tags( trim($_POST['igwc_query']) );

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	$IGWC_RESULT = array();
	if( $IGWC_QUERY == 'show_result' ) :
		
		$theme = ( !empty($_POST['igwc']['theme']) ) ? strip_tags(trim($_POST['igwc']['theme'])) : null;
		$count = ( !empty($_POST['igwc']['count']) ) ? (int) strip_tags(trim($_POST['igwc']['count'])) : null;
		$w = ( !empty($_POST['igwc']['w']) ) ? (int) strip_tags(trim($_POST['igwc']['w'])) : null;
		$h = ( !empty($_POST['igwc']['w']) ) ? (int) strip_tags(trim($_POST['igwc']['h'])) : null;
		
		if( $theme && $count && $w && $h ) {
			$igwcShortcode = igwGetShortcode( $theme, $count, $w, $h );
			
			$IGWC_RESULT['status'] = true;
			$IGWC_RESULT['html'] = do_shortcode( $igwcShortcode );
			$IGWC_RESULT['code'] = $igwcShortcode;
		} else {
			$IGWC_RESULT['status'] = false;
		}
	endif;
	
	if( $IGWC_QUERY == 'update_themes' ) :
			$IGWC_RESULT['status'] = igw_updateThemes();
	endif;
	
	echo json_encode( $IGWC_RESULT );

exit;
endif; /* ( $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sfl_query']) ) */