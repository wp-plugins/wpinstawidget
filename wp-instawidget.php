<?php
/*
	* Plugin Name: WpInstaWidget
	* Plugin URI: http://starsinstagram.ru
	* Description: Display Your Instagram Profile into WordPress Site.
	* Version: 1.1
	* Author: THZ
*/
require_once('core.sys.php');
add_action('admin_menu', 'igw_add_pages');