<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2010
 */

global $k, $settings;
include('../includes/settings.php');
header("Content-type: text/x-json; charset=".$k['charset']);

require_once("class.database.php");
$db = new Database($server, $user, $pass, $database, $pre);
$db->connect();

$sql = "SELECT id, category FROM ".$pre."kb_categories";

//manage filtering
if (!empty($_GET['term'])) {
	$sql .= " WHERE category LIKE '%".$_GET['term']."%'";
}

$sql .= " ORDER BY category ASC";

$sOutput = '';

$rows = $db->fetch_all_array($sql);
if ($rows[0]>0) {
	foreach($rows as $reccord){
		if (empty($sOutput)) {
			$sOutput = '"'.$reccord['category'].'"';
		}else{
			$sOutput .= ', "'.$reccord['category'].'"';
		}
	}

	//Finish the line
	echo '[ '.$sOutput.' ]';
}



?>