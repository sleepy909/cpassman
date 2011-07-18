<?php
/**
 * @file 		find.load.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-ND (http://creativecommons.org/licenses/by-nd/3.0/legalcode)
 * @link		http://cpassman.org
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if (!isset($_SESSION['CPM'] ) || $_SESSION['CPM'] != 1)
	die('Hacking attempt...');


?>

<script type="text/javascript">

/*
* Copying an item from find page
*/
function copy_item(item_id) {
	LoadingPage();
	//Send query
	$.post(
	"sources/items.queries.php",
	{
		type    : "copy_item",
		item_id : item_id
	},
		function(data){
			//if OK
			if (data[0].status == "ok") {
				window.location.href = "index.php?page=find";
			}
			LoadingPage();
		},
		"json"
	);
}
</script>