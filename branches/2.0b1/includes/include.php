<?php
/**
 * @file 		include.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-ND (http://creativecommons.org/licenses/by-nd/3.0/legalcode)
 * @link		http://www.teampass.net
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

//DONT'T CHANGE BELOW THIS LINE
global $settings;

$k['version'] = "2.0";
$k['tool_name'] = "TeamPass";
$k['jquery-version'] = "1.6.2";
$k['jquery-ui-version'] = "1.8.16";
$k['jquery-ui-theme'] = "overcast";
$k['one_month_seconds'] = 2592000;
$k['image_file_ext'] = array('jpg','gif','png','jpeg','tiff','bmp');
$k['office_file_ext'] = array('xls','xlsx','docx','doc','csv','ppt','pptx');

//Management Pages
$mngPages = array(
    'manage_users' => 'users.php',
    'manage_folders' => 'folders.php',
    'manage_roles' => 'roles.php',
    'manage_views' => 'views.php',
    'manage_main' => 'admin.php',
    'manage_settings' => 'admin.settings.php'
);

//languages
$k['langs'] = array(
	'english'=>'en',
	'french'=>'fr',
	'spanish'=>'es',
	'czech'=>'cs',
	'german'=>'de',
'russian'=>'ru',
'japanese'=>'ja',
'portuguese'=>'pr',
'norwegian'=>'no'
);

//pw complexity levels
$pw_complexity = array(
    0=>array(0,$txt['complex_level0']),
    25=>array(25,$txt['complex_level1']),
    50=>array(50,$txt['complex_level2']),
    60=>array(60,$txt['complex_level3']),
    70=>array(70,$txt['complex_level4']),
    80=>array(80,$txt['complex_level5']),
    90=>array(90,$txt['complex_level6'])
);
?>