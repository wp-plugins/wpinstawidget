<?php
/*
	* Plugin Name: Wp InstaWidget
	* Plugin URI: http://starsinstagram.ru
	* Description: Display Your Instagram Profile into WordPress Site.
	* Version: 1.1
	* Author: THomZone, alexcraft
*/
require_once('core.sys.php');
add_action('admin_menu', 'igw_add_pages');