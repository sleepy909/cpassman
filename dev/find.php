<script src="includes/js/jquery.search.js" type="text/javascript"></script>

<?php
require_once ("sources/NestedTree.class.php");
$tree = new NestedTree('nested_tree', 'id', 'parent_id', 'title');
  echo '
<div style="padding-left:25px; margin-top:10px;">
    <table id="t_items" style="empty-cells: show;margin-top:5px;" cellspacing="0" cellpadding="5">
        <thead><tr>
            <th></th><th>'.$txt['label'].'</th><th>'.$txt['description'].'</th><th>'.$txt['group'].'</th>
        </tr></thead>
        <tbody>
        ';
        $cpt= 0 ;
        $res = mysql_query("SELECT * FROM items WHERE inactif=0 ORDER BY label ASC");
        while ( $data = mysql_fetch_array($res) ){
            //vérifier si l'utilisateur à le droit de voir cet élément
            //$tmp = explode(';',$data['id_tree']);
            if ( in_array($data['id_tree'],$_SESSION['groupes_visibles']) || $_SESSION['is_admin'] == 1 ) $affich_elem = true;
            else $affich_elem = false;
            
            //afficher l'elément
            if ( $affich_elem == true ){
                echo '<tr class="ligne'.($cpt%2).'">
                <td><img src="includes/images/key__arrow.png" onClick="javascript:window.location.href = \'index.php?page=items&amp;group='.$data['id_tree'].'&amp;id='.$data['id'].'\';" style="cursor:pointer;" /></td>
                <td>'.$data['label'].'</td>
                <td>', $data['perso']==1 || !empty($data['restricted_to']) ? "<img src='includes/images/lock.png' />" : $data['description'] ,'</td>
                <td>';
                //préparer l'arborescence
                $arbo = $tree->getPath($data['id_tree'], true);
                $arbo_elem = "";
                foreach($arbo as $elem){
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
</div>
  ';

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