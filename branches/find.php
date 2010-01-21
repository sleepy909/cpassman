<?php
####################################################################################################
## File : find.php
## Author : Nils Laumaillé
## Description : Find page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>
<script src="includes/js/jquery.search.js" type="text/javascript"></script>
<?php
//load the full Items tree
require_once ("sources/NestedTree.class.php");
$tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');

//Show the Items in a table view
echo '
<div style="padding-left:25px; margin-top:10px;">
    <table id="t_items" style="empty-cells: show;margin-top:5px;" cellspacing="0" cellpadding="5">
        <thead><tr>
            <th></th><th>'.$txt['label'].'</th><th>'.$txt['description'].'</th><th>'.$txt['group'].'</th>
        </tr></thead>
        <tbody>';
        //get list of personal folders
        $list_pf = array();
        $res = mysql_query("SELECT id FROM ".$k['prefix']."nested_tree WHERE personal_folder=1");
        while ( $data = mysql_fetch_row($res) ){
            if ( !in_array($data[0],$list_pf) ) array_push($list_pf,$data[0]);
        }
        
        $cpt= 0 ;
        $res = mysql_query("SELECT * FROM ".$k['prefix']."items WHERE inactif=0 ORDER BY label ASC");
        while ( $data = mysql_fetch_array($res) ){
            //Check if user can see the ITEM
            if ( in_array($data['id_tree'],$_SESSION['groupes_visibles']) ||( $_SESSION['is_admin'] == 1 && !in_array($data['id_tree'],$list_pf) ) ) $affich_elem = true;
            else $affich_elem = false;
            
            //Display the Item
            if ( $affich_elem == true ){
                echo '<tr class="ligne'.($cpt%2).'">
                <td><img src="includes/images/key__arrow.png" onClick="javascript:window.location.href = \'index.php?page=items&amp;group='.$data['id_tree'].'&amp;id='.$data['id'].'\';" style="cursor:pointer;" /></td>
                <td>'.$data['label'].'</td>
                <td>', $data['perso']==1 || !empty($data['restricted_to']) || !in_array($_SESSION['user_id'],explode(';',$data['restricted_to'])) != $_SESSION['user_id'] ? "<img src='includes/images/lock.png' />" : $data['description'] ,'</td>
                <td>';
                //Prepare the Treegrid
                $arbo = $tree->getPath($data['id_tree'], true);
                $arbo_elem = "";
                foreach($arbo as $elem){
                    if ( $elem->title == $_SESSION['user_id'] && $elem->nlevel == 1 ) $elem->title = $_SESSION['login'];
                    $arbo_elem .= $elem->title." > ";
                }
                echo $arbo_elem.'</td>
                </tr>';
                $cpt++;
            }
        }
        echo '
        </tbody>
    </table>
</div>';

//Prepare the javascript for QuickSearch
echo '
<script type="text/javascript">
$(document).ready(function () {                
    $("table#t_items tbody tr").quicksearch({
        stripeRowClass: ["odd", "even"],
        position: "before",
        attached: "table#t_items",
        labelText: "<span class=\"ui-icon ui-icon-search\" style=\"float: left; margin-right: .3em;\"><\/span>'.$txt['find_text'].'",
        loaderImg: "includes/images/ajax-loader.gif",
        delay: 100
    });
});
</script>';
?>