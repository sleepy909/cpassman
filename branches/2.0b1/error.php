<?php
/**
 * @file 		error.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-NC-ND (http://creativecommons.org/licenses/by-nc-nd/3.0/legalcode)
 * @link		http://cpassman.org
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

session_start();
if ($_SESSION['CPM'] != 1)
	die('Hacking attempt...');


echo '
<div style="width:800px;margin:auto;">';
if ( $_SESSION['error'] == 1000 ){
    echo '
    <div class="ui-state-error ui-corner-all error" >'.$txt['error_not_authorized'].'</div>';
}else if ( $_SESSION['error'] == 1001 ){
    echo '
    <div class="ui-state-error ui-corner-all error" >'.$txt['error_not_exists'].'</div>';
}
echo '
</div>';
?>