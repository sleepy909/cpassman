<?php
####################################################################################################
## File : find.php
## Author : Nils Laumaillé
## Description : Find page
##
## DON'T CHANGE !!!
##
####################################################################################################

//load the full Items tree
require_once ("sources/NestedTree.class.php");
$tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');

//Show the Items in a table view
echo '
    <div class="title ui-widget-content ui-corner-all">'.$txt['find'].'</div>
<div style="margin:10px auto 25px auto;min-height:250px;" id="find_page">
<table id="t_items" cellspacing="0" cellpadding="5" width="100%">
    <thead><tr>
        <th style="width:16px;"></th>
        <th style="width:15%;">'.$txt['label'].'</th>
		<th style="width:20%;">'.$txt['login'].'</th>
        <th style="width:30%;">'.$txt['description'].'</th>
        <th style="width:15%;">'.$txt['tags'].'</th>
        <th style="width:20%;">'.$txt['group'].'</th>
    </tr></thead>
    <tbody>
    	<tr><td></td></tr>
    </tbody>
</table>
</div>';
?>