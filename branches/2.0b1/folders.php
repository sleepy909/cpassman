<?php
####################################################################################################
## File : roles.php
## Author : Nils Laumaillé
## Description : Groups page
##
## DON'T CHANGE !!!
##
####################################################################################################

/* load help*/
require_once('includes/language/'.$_SESSION['user_language'].'_admin_help.php');

/* Get full tree structure */
require_once ("sources/NestedTree.class.php");
$tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');
$tst = $tree->getDescendants();

/* Build list of all folders */
if ($_SESSION['is_admin'] == 1 || $_SESSION['can_create_root_folder'] == 1) {
    $folders_list = "\'0\':\'".$txt['root']."\'";
}else{
    $folders_list = "";
}
foreach($tst as $t){
    if (in_array($t->id,$_SESSION['groupes_visibles']) && !in_array($t->id, $_SESSION['personal_visible_groups'])) {
        if ( $t->nlevel == 1 ) $ident = ">";
        if ( $t->nlevel == 2 ) $ident = "->";
        if ( $t->nlevel == 3 ) $ident = "-->";
        if ( $t->nlevel == 4 ) $ident = "--->";
        if ( $t->nlevel == 5 ) $ident = "---->";
        $folders_list .= ','."\'".$t->id.'\':\''.$ident." ".addslashes(addslashes($t->title))."\'";
    }
}

/* Build complexity level list */
$complexity_list = "";
foreach($mdp_complexite as $comp){
    if ( empty($complexity_list) ) $complexity_list = "\'".$comp[0].'\':\''.$comp[1].'\'';
    else $complexity_list .= ",\'".$comp[0].'\':\''.$comp[1].'\'';
}

/* Display header */
echo '
<div class="title ui-widget-content ui-corner-all">'.
    $txt['admin_groups'].'&nbsp;&nbsp;&nbsp;<img src="includes/images/folder--plus.png" id="open_add_group_div" title="'.$txt['item_menu_add_rep'].'" style="cursor:pointer;" />
    <span style="float:right;margin-right:5px;"><img src="includes/images/question-white.png" style="cursor:pointer" title="'.$txt['show_help'].'" onclick="OpenDialog(\'help_on_folders\')" /></span>
</div>';

echo '
<form name="form_groupes" method="post" action="">
    <div style="width:700px;margin:auto; line-height:20px;">
    <table cellspacing="0" style="margin-top:10px;">
        <thead><tr>
            <th>ID</th>
            <th>'.$txt['group'].'</th>
            <th>'.$txt['complexity'].'</th>
            <th>'.$txt['group_parent'].'</th>
            <th>'.$txt['level'].'</th>
            <th title="'.$txt['group_pw_duration_tip'].'">'.$txt['group_pw_duration'].'</th>
            <th title="'.$txt['del_group'].'"><img src="includes/images/folder--minus.png" /></th>
            <th title="'.$txt['auth_creation_without_complexity'].'"><img src="includes/images/auction-hammer.png" /></th>
            <th title="'.$txt['auth_modification_without_complexity'].'"><img src="includes/images/alarm-clock.png" /></th>
        </tr></thead>
        <tbody>';
        $x = 0;
        $arr_ids = array();
        foreach($tst as $t){
            if ( in_array($t->id,$_SESSION['groupes_visibles'])  && !in_array($t->id, $_SESSION['personal_visible_groups']) ) {
                //r?cup $t->parent_id
                $data = $db->fetch_row("SELECT title FROM ".$pre."nested_tree WHERE id = ".$t->parent_id);
                if ( $t->nlevel == 1 ) {
                    $data[0] = $txt['root'];
                }

                //r?cup les droits associ?s ? ce groupe
                $tab_droits=array();
                $rows = $db->fetch_all_array("SELECT fonction_id  FROM ".$pre."rights WHERE authorized=1 AND tree_id = ".$t->id);
                foreach( $rows as $reccord ){
                    array_push($tab_droits,$reccord['fonction_id']);
                }
                //g?rer l'identation en fonction du niveau
                $ident = "";
                for($l=1;$l<$t->nlevel;$l++) $ident .= "&nbsp;&nbsp;";

                //Get some elements from DB concerning this node
                $node_data = $db->fetch_row(
                    "SELECT m.valeur AS valeur, n.renewal_period AS renewal_period
                    FROM ".$pre."misc AS m,
                    ".$pre."nested_tree AS n
                    WHERE m.type='complex'
                    AND m.intitule = n.id
                    AND m.intitule = ".$t->id
                );


                echo '<tr class="ligne0" id="row_'.$t->id.'">
                        <td align="center">'.$t->id.'</td>
                        <td width="50%">
                            '.$ident.'<span class="editable_textarea" style="" id="title_'.$t->id.'">'.$t->title.'</span>
                        </td>
                        <td align="center">
                            <span class="editable_select" id="complexite_'.$t->id.'">'.$mdp_complexite[$node_data[0]][1].'</span>
                        </td>
                        <td align="center">
                            <span class="editable_select" id="parent_'.$t->id.'">'.$data[0].'</span>
                        </td>
                        <td align="center">
                            '.$t->nlevel.'
                        </td>
                        <td align="center">
                            <span class="renewal_textarea" id="renewal_'.$t->id.'">'.$node_data[1].'</span>
                        </td>
                        <td align="center">
                            <img src="includes/images/folder--minus.png" onclick="supprimer_groupe(\''.$t->id.'\')" style="cursor:pointer;" />
                        </td>';

                        $data3 = $db->fetch_row("SELECT bloquer_creation,bloquer_modification FROM ".$pre."nested_tree WHERE id = ".$t->id);
                        echo '
                        <td align="center">
                            <input type="checkbox" id="cb_droit_'.$t->id.'" onchange="Changer_Droit_Complexite(\''.$t->id.'\',\'creation\')"', isset($data3[0]) && $data3[0]==1 ? 'checked' : '', ' />
                        </td>
                        <td align="center">
                            <input type="checkbox" id="cb_droit_modif_'.$t->id.'" onchange="Changer_Droit_Complexite(\''.$t->id.'\',\'modification\')"', isset($data3[1]) && $data3[1]==1 ? 'checked' : '', ' />
                        </td>
                </tr>';
                array_push($arr_ids,$t->id);
                $x++;
            }
        }
        echo '
        </tbody>
    </table>
    <div style="font-size:11px;font-style:italic;margin-top:5px;">
        <img src="includes/images/information-white.png" alt="" />&nbsp;'.$txt['info_click_to_edit'].'
    </div>
    </div>
</form>';

// DIV FOR HELP
echo '
<div id="help_on_folders" style="">
    <div>'.$txt['help_on_folders'].'</div>
</div>';

//Launch Editable script on DOM elements
foreach($arr_ids as $id)
    echo '
    <script type="text/javascript">
        $("#complexite_'.$id.'").editable("sources/folders.queries.php", {
            indicator : "<img src=\'includes/images/loading.gif\' />",
            data   : "{'.$complexity_list.', \'selected\':\''.$mdp_complexite[$node_data[0]][1].'\'}",
            type   : "select",
            submit : "<img src=\'includes/images/disk_black.png\' />",
            cancel : " <img src=\'includes/images/cross.png\' />",
            name : "changer_complexite"
          });
          $("#parent_'.$id.'").editable("sources/folders.queries.php", {
            indicator : "<img src=\'includes/images/loading.gif\' />",
            data   : "{'.$folders_list.', \'selected\':\''.$data[0].'\'}",
            type   : "select",
            submit : "<img src=\'includes/images/disk_black.png\' />",
            cancel : " <img src=\'includes/images/cross.png\' />",
            name : "newparent_id"
          });
    </script>';

/* Form Add a folder */
echo '
<div id="div_add_group" style="display:none;">
    <div id="addgroup_show_error" style="text-align:center;margin:2px;display:none;" class="ui-state-error ui-corner-all"></div>

    <label for="ajouter_groupe_titre" class="label_cpm">'.$txt['group_title'].' :</label>
    <input type="text" id="ajouter_groupe_titre" class="input_text text ui-widget-content ui-corner-all" />

    <label for="parent_id" class="label_cpm">'.$txt['group_parent'].' :</label>
    <select id="parent_id" class="input_text text ui-widget-content ui-corner-all">';
        echo '<option value="na">---'.$txt['select'].'---</option>';
        if ($_SESSION['is_admin'] == 1 || $_SESSION['can_create_root_folder'] == 1) {
            echo '<option value="0">'.$txt['root'].'</option>';
        }
        $prev_level = 0;
        foreach($tst as $t){
            if ( in_array($t->id,$_SESSION['groupes_visibles']) && !in_array($t->id, $_SESSION['personal_visible_groups'])) {
                $ident="";
                for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                if ( $prev_level < $t->nlevel ){
                    echo '<option value="'.$t->id.'">'.$ident.$t->title.'</option>';
                }else if ( $prev_level == $t->nlevel ){
                   echo '<option value="'.$t->id.'">'.$ident.$t->title.'</option>';
                }else{
                    echo '<option value="'.$t->id.'">'.$ident.$t->title.'</option>';
                }
                $prev_level = $t->nlevel;
            }
        }
    echo '
    </select>

    <label for="new_rep_complexite" class="label_cpm">'.$txt['complex_asked'].' :</label>
    <select id="new_rep_complexite" class="input_text text ui-widget-content ui-corner-all">
        <option value="">---</option>';
        foreach($mdp_complexite as $complex)
            echo '<option value="'.$complex[0].'">'.$complex[1].'</option>';
    echo '
    </select>

    <label for="add_node_renewal_period" class="label_cpm">'.$txt['group_pw_duration'].' :</label>
    <input type="text" id="add_node_renewal_period" value="0" class="input_text text ui-widget-content ui-corner-all" />
</div>';
?>