<?php
/* DEFINES */
define('IGW_OPT_PREF', 'igw_');
define('IGW_REF_SETTINGS_GROUP', 'wp-igw');

define('IGW_SHORTCODE_NAME', 'igw');

define('IGW_PATH_ROOT', __DIR__);
define('IGW_PATH_INCS', IGW_PATH_ROOT . '/includes');
define('IGW_PATH_PAGES', IGW_PATH_ROOT . '/pages');

define('IGW_URL_ROOT', plugins_url('', __FILE__));
define('IGW_URL_JS', IGW_URL_ROOT . '/js');
define('IGW_URL_CSS', IGW_URL_ROOT . '/css');
define('IGW_URL_CSS_BACK', IGW_URL_ROOT . '/css/back.css');

define('IGW_PAGE_GENERAL', IGW_PATH_PAGES . '/general.page.php');

define('IGW_FILE_JS_BACK', FL_URL_JS . '/seo-firelinks_back.js' );

define('IGW_THEMES_IMPORT_FILE', 'http://starsinstagram.ru/igw/themes.json.php');
/* //DEFINES */

/* VARS */
/*define('IGW_METAPARENT', 'parent_');
$FL_OPTIONS = array(
	array('label'=>'Включить', 'name'=>FL_OPT_PREF . 'active', 'desc'=>'Включить обработку ссылок.'),
	array('label'=>'href="#..."', 'name'=>FL_OPT_PREF . 'has_sharp', 'desc'=>'Не обрабатывать ссылки у которых аттрибут "<b>HREF</b>" начинается с "<b>#</b>".'),
	array('label'=>'Сама-на-себя"', 'name'=>FL_OPT_PREF . 'on_itself', 'desc'=>'Закрывать ссылку ведущую саму на себя.'),
	array('label'=>'Подсветить ссылки', 'name'=>FL_OPT_PREF . 'show_open', 'desc'=>'Подсветить ссылки:<br/><span sfl-type="open">&emsp;&emsp;</span> &ndash; открыто<br/><span sfl-type="close">&emsp;&emsp;</span> &ndash; закрыто<br/><span sfl-type="closed">&emsp;&emsp;</span> &ndash; перезакрыто (перебиты следующим правилом)<br/><span sfl-type="itself">&emsp;&emsp;</span> &ndash; сама-на-себя'),
);*/

/* //VARS */

/* INCLUDES */
$includeFiles = scandir( IGW_PATH_INCS );
foreach( $includeFiles as $includeFile ) {
	if( in_array( $includeFile, array('.','..')) || !preg_match('/\.inc\.php$/i', $includeFile) ) continue;
	$file = IGW_PATH_INCS . '/' . $includeFile;
	include_once( $file );
}
/* //INCLUDES */
/* PAGES */
include_once( IGW_PAGE_GENERAL );
/* //PAGES */