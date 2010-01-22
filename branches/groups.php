<?php
####################################################################################################
## File : groups.php
## Author : Nils Laumaillé
## Description : Groups page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>
<script src="includes/js/jquery.jeditable.js" type="text/javascript"></script>
<?php
require_once ("sources/NestedTree.class.php");
$tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
$tst = $tree->getDescendants();

//faire une liste des groupes
$liste_groupes = "\'0\':\'".$txt['root']."\'";
foreach($tst as $t){
    if ( $t->nlevel == 1 ) $ident = ">";
    if ( $t->nlevel == 2 ) $ident = "->";
    if ( $t->nlevel == 3 ) $ident = "-->";
    if ( $t->nlevel == 4 ) $ident = "--->";
    if ( $t->nlevel == 5 ) $ident = "---->";
    $liste_groupes .= ','."\'".$t->id.'\':\''.$ident." ".addslashes(addslashes($t->title))."\'";;
}

//construire la liste des niveaux de complexités
$liste_complexite = "";
foreach($mdp_complexite as $comp){
    if ( empty($liste_complexite) ) $liste_complexite = "\'".$comp[0].'\':\''.$comp[1].'\'';
    else $liste_complexite .= ",\'".$comp[0].'\':\''.$comp[1].'\'';
}

//construire la liste des FONCTIONS
$liste_fonctions = "";
$res = mysql_query("SELECT * FROM ".$k['prefix']."functions ORDER BY title ASC");
while($data = mysql_fetch_row($res)){
    $liste_fonctions[$data[0]] = array('id'=>$data[0],'title'=>$data[1]);
}

echo '
<div style="margin-top:10px;">
    <h3>'.$txt['admin_groups'].'&nbsp;&nbsp;&nbsp;<img src="includes/images/blog__plus.png" id="open_add_group_div" title="'.$txt['item_menu_add_rep'].'" style="cursor:pointer;" />
    </h3>';

echo '
    <form name="form_groupes" method="post" action="">
        <div style="width:700px;margin:auto; line-height:20px;">
        <table cellspacing="0" style="margin-top:10px;">
            <thead><tr>
                <th>ID</th><th>'.$txt['level'].'</th><th>'.$txt['group'].'</th><th>'.$txt['complexity'].'</th><th>'.$txt['group_parent'].'</th><th title="'.$txt['del_group'].'"><img src="includes/images/blog__minus.png" /></th><th title="'.$txt['auth_creation_without_complexity'].'"><img src="includes/images/auction-hammer.png" /></th><th title="'.$txt['auth_modification_without_complexity'].'"><img src="includes/images/alarm-clock.png" /></th>
            </tr></thead>
            <tbody>';   
            $x = 0;    
            $arr_ids = array();          
            foreach($tst as $t){
                if ( in_array($t->id,$_SESSION['groupes_visibles']) ) {
                    //récup $t->parent_id
                    $res = mysql_query("SELECT title FROM ".$k['prefix']."nested_tree WHERE id = ".$t->parent_id);
                    $data = mysql_fetch_row($res);
                    if ( $t->nlevel == 1 ) $data[0] = "Racine";
                    
                    //récup les droits associés à ce groupe
                    $tab_droits=array();
                    $res1 = mysql_query("SELECT fonction_id  FROM ".$k['prefix']."rights WHERE authorized=1 AND tree_id = ".$t->id);
                    while($data1 = mysql_fetch_row($res1)){
                        array_push($tab_droits,$data1[0]);
                    }
                    //gérer l'identation en fonction du niveau
                    $ident = "";  
                    for($l=1;$l<$t->nlevel;$l++) $ident .= "&nbsp;&nbsp;";
                    
                    //récup le niveau de complexité
                    $res = mysql_query("SELECT valeur FROM ".$k['prefix']."misc WHERE type='complex' AND intitule = ".$t->id);
                    $complexite = mysql_fetch_row($res);
                    
                    
                    echo '<tr class="ligne'.($x%2).'">
                            <td align="center">'.$t->id.'</td>
                            <td align="center">'.$t->nlevel.'</td>
                            <td width="50%">
                                '.$ident.'<span class="editable_textarea" style="" id="title_'.$t->id.'">'.$t->title.'</span>
                            </td>
                            <td align="center">
                                <span class="editable_select" id="complexite_'.$t->id.'">'.$mdp_complexite[$complexite[0]][1].'</span>
                            </td>               
                            <td align="center">
                                <span class="editable_select" id="parent_'.$t->id.'">'.$data[0].'</span>
                            </td>
                            <td align="center">
                                <img src="includes/images/blog__minus.png" onclick="supprimer_groupe(\''.$t->id.'\')" style="cursor:pointer;" />
                            </td>';
                            
                            $data3 = mysql_fetch_row(mysql_query("SELECT bloquer_creation,bloquer_modification FROM ".$k['prefix']."nested_tree WHERE id = ".$t->id));
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
            <img src="includes/images/information.png" alt="" />&nbsp;'.$txt['info_click_to_edit'].'
        </div>
        </div>
    </form>
</div>';

//Launch Editable script on DOM elements
foreach($arr_ids as $id)
    echo '   
    <script type="text/javascript">
        $("#complexite_'.$id.'").editable("sources/groups.queries.php", { 
            indicator : "<img src=\'includes/images/loading.gif\' />",
            data   : "{'.$liste_complexite.', \'selected\':\''.$mdp_complexite[$complexite[0]][1].'\'}",
            type   : "select",
            submit : "<img src=\'includes/images/disk_black.png\' />",
            cancel : " <img src=\'includes/images/cross.png\' />",
            name : "changer_complexite"
          });
          $("#parent_'.$id.'").editable("sources/groups.queries.php", { 
            indicator : "<img src=\'includes/images/loading.gif\' />",
            data   : "{'.$liste_groupes.', \'selected\':\''.$data[0].'\'}",
            type   : "select",
            submit : "<img src=\'includes/images/disk_black.png\' />",
            cancel : " <img src=\'includes/images/cross.png\' />",
            name : "newparent_id"
          });
    </script>';

//Formulaire Ajouter GROUPE
echo '
<div id="div_add_group" style="display:none;"> 
    <label for="ajouter_groupe_titre" class="form_label_120">'.$txt['group_title'].'</label>
    <input type="text" size="30" id="ajouter_groupe_titre" />
    <br />
    
    <label for="parent_id" class="form_label_120">'.$txt['group_parent'].'</label>
    <select id="parent_id">';
        echo '<option value="na">---'.$txt['select'].'---</option>';
        echo '<option value="0">'.$txt['root'].'</option>';
        $prev_level = 0;
        foreach($tst as $t){
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
    echo '
    </select>
    <br />
    
    <label for="new_rep_complexite" class="form_label_120">'.$txt['complex_asked'].'</label>
    <select id="new_rep_complexite">
        <option value="">---</option>';
        foreach($mdp_complexite as $complex)
            echo '<option value="'.$complex[0].'">'.$complex[1].'</option>';
    echo '
    </select>
</div>';
?>
<script type="text/javascript">
$(function() {
    //inline editing
    $(".editable_textarea").editable("sources/groups.queries.php", { 
          indicator : "<img src='includes/images/loading.gif' />",
          type   : "textarea",
          select : true,
          submit : " <img src='includes/images/disk_black.png' />",
          cancel : " <img src='includes/images/cross.png' />",
          name : "newtitle",
            width : "240"
      });
      
      //Prepare creation dialogbox
      $('#open_add_group_div').click(function() {
            $('#div_add_group').dialog('open');
      });

      $("#div_add_group").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 350,
        height: 200,
        title: "<?php echo $txt['add_new_group'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                ajouter_groupe()
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
});



function ajouter_groupe(){
    if ( document.getElementById("new_rep_complexite").value == "" ) alert("<?php echo $txt['error_group_complex'];?>");
    else{
        if ( document.getElementById('ajouter_groupe_titre').value != "" && document.getElementById('parent_id').value != "na" ){
            var data = "type=ajouter_groupe"+
                        "&titre="+escape(document.getElementById('ajouter_groupe_titre').value)+
                        "&complex="+document.getElementById('new_rep_complexite').value+
                        "&parent_id="+document.getElementById('parent_id').value;
            httpRequest("sources/groups.queries.php",data);
        }else{
            alert('<?php echo $txt['error_fields_2'];?>');
        }
    }
}

function supprimer_groupe(id){
    if ( confirm("<?php echo $txt['confirm_delete_group'];?>") ){
        var data = "type=supprimer_groupe&id="+id;
        httpRequest("sources/groups.queries.php",data);
    }
}   

function Changer_Droit_Complexite(id,type){
    var droit = 0;
    if ( type == "creation" ){
        if ( document.getElementById('cb_droit_'+id).checked == true ) droit = 1;
        var data = "type=modif_droit_autorisation_sans_complexite&id="+id+"&droit="+droit;
    }else if ( type == "modification" ){
        if ( document.getElementById('cb_droit_modif_'+id).checked == true ) droit = 1;
        var data = "type=modif_droit_modification_sans_complexite&id="+id+"&droit="+droit;
    }
    httpRequest("sources/groups.queries.php",data);   
}
</script>