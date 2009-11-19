<?php
####################################################################################################
## File : items.php
## Author : Nils Laumaillé
## Description : Items page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>
<script src="includes/js/jquery.search.js" type="text/javascript"></script>
<script src="includes/js/jquery.copy.js" type="text/javascript"></script>
<script src="includes/js/jquery.treeview.js" type="text/javascript"></script>
<script src="includes/js/jquery.contextMenu/jquery.contextMenu.js" type="text/javascript"></script>
<script src="includes/zeroclipboard/ZeroClipboard.js" type="text/javascript"></script>

<script type="text/javascript">    
    function AddNewNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).addNode(1,'A New Node')   
    }

    
    function EditNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).addNode(1,'A New Node')   
    }
    
    function DeleteNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).delNode()   
    }
    
    function showItemsInTree(type){
        if ( document.getElementById('img_funnel').src == "includes/images/funnel_plus.png" )
            document.getElementById('img_funnel').src="includes/images/funnel_minus.png"
        else
            document.getElementById('img_funnel').src="includes/images/funnel_plus.png"
    }
    
</script>

<?php
# Launch the copy in clipboard script
echo '
<script type="text/javascript">
    ZeroClipboard.setMoviePath( "'.$url_passman.'/includes/zeroclipboard/ZeroClipboard.swf");
</script>';

require_once ("sources/NestedTree.class.php");

//Définir liste des utilisateurs existants
$liste_utilisateurs = array();
$tmp = "";
$res_utilistateurs = mysql_query("SELECT id,login,email FROM ".$k['prefix']."users ORDER BY login ASC");
while($data_utilisateur=mysql_fetch_row($res_utilistateurs)){
    $liste_utilisateurs[$data_utilisateur[1]] = array(
        "id" => $data_utilisateur[0],
        "login" => $data_utilisateur[1],
        "email" => $data_utilisateur[2],
    );
    $tmp .= $data_utilisateur[0].'.'.$data_utilisateur[1].";";
}

    
//Choses cachées
echo '  
<input type="hidden" name="hid_cat" id="hid_cat" />
<input type="hidden" id="complexite_groupe" />
<input type="text" style="display:none;" name="selected_items" id="selected_items" />
<input type="hidden" name="input_liste_utilisateurs" id="input_liste_utilisateurs" value="'.$tmp.'" />
<input type="hidden" id="bloquer_creation_complexite" />
<input type="hidden" id="bloquer_modification_complexite" />';

//Afficher mdp suite à recherche
if ( isset($_GET['group']) && isset($_GET['id']) ){
    echo '<input type="hidden" name="recherche_groupe" id="recherche_groupe" value="'.$_GET['group'].'" />';
    echo '<input type="hidden" name="recherche_id" id="recherche_id" value="'.$_GET['id'].'" />';
}elseif ( isset($_GET['group']) && !isset($_GET['id']) ){
    echo '<input type="hidden" name="recherche_groupe" id="recherche_groupe" value="'.$_GET['group'].'" />';
    echo '<input type="hidden" name="recherche_id" id="recherche_id" value="" />';
}else{
    echo '<input type="hidden" name="recherche_groupe" id="recherche_groupe" value="" />';
    echo '<input type="hidden" name="recherche_id" id="recherche_id" value="" />';
}

echo '
<div id="div_items">';
    
    // MAIN ITEMS TREE
    echo '
    <div style="height:20px;background:#FF8000;width:300px;border-right:1px solid #FF8000;" class="">
        <div style="display:inline;margin:3px;font-weight:bold;border-right:1px solid #FF8000;">'.$txt['items_browser_title'].'</div>
    </div>
    <div id="sidebar" class="sidebar" style="border-right:1px solid #FF8000;overflow-y:auto;">';
        $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
        $tree->rebuild();
        $tst = $tree->getDescendants();
        
        $tab_items = array();
        $cpt_total = 0;
        $folder_cpt = 1;
        $prev_level = 1;
        $first_group = "";
        echo '
        <ul id="browser" class="filetree" style="margin-top:3px;">';
        foreach($tst as $t){
            //S'assurer que l'utilisateur ne voit que ce qu'il peut voir
            $res = mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."items WHERE inactif=0 AND id_tree = ".$t->id);
            $data=mysql_fetch_row($res);
            $nb_items = $data[0];
            
            //get 1st group
            if (empty($first_group)) $first_group = $t->id;
            
            //Construire l'arborescence
            if ( $cpt_total == 0 ) {
                 echo '
                 <li id="li_'.$t->id.'"><span class="folder">&nbsp;</span>
                    <div style="display:inline;cursor:pointer;">', 
                    in_array($t->id,$_SESSION['groupes_visibles']) ?
                    '<a class="folder_item" onclick="ListerItems(\''.$t->id.'\');">'.str_replace("&","&amp;",$t->title).'</a> ('.$nb_items.')' : 
                    '<span class="folder_item">'.str_replace("&","&amp;",$t->title).'</span>',
                    '</div>';
                 //sauver les items de ce groupe
                 //$tab_items[$t->nlevel] = $text_items;
            }else{                                           
                //Construire l'arborescence
                if ( $prev_level < $t->nlevel ){
                    echo '<ul id="folder'.$folder_cpt.'">';
                    echo '
                    <li id="li_'.$t->id.'"><span class="folder">&nbsp;</span>
                    <div style="display:inline;cursor:pointer;">', 
                    in_array($t->id,$_SESSION['groupes_visibles']) ?
                    '<a class="folder_item" onclick="ListerItems(\''.$t->id.'\');">'.str_replace("&","&amp;",$t->title).'</a> ('.$nb_items.')' : 
                    '<span class="folder_item">'.str_replace("&","&amp;",$t->title).'</span>',
                    '</div>';
                    
                    //sauver les items de ce groupe
                    //$tab_items[$t->nlevel] = $text_items;
                    
                    $folder_cpt++;
                }else if ( $prev_level == $t->nlevel ){
                    //ecrire la structure
                    echo '
                    </li>
                    <li id="li_'.$t->id.'"><span class="folder">&nbsp;</span>
                    <div style="display:inline;cursor:pointer;">', 
                    in_array($t->id,$_SESSION['groupes_visibles']) ?
                    '<a class="folder_item" onclick="ListerItems(\''.$t->id.'\');">'.str_replace("&","&amp;",$t->title).'</a> ('.$nb_items.')' : 
                    '<span class="folder_item">'.str_replace("&","&amp;",$t->title).'</span>',
                    '</div>';
                }else{
                    //Afficher les items de la dernièeres cat s'ils existent
                    for($x=$t->nlevel;$x<$prev_level;$x++){
                        echo "
                        </li>
                        </ul>";
                    }
                    echo '</li>
                    <li id="li_'.$t->id.'"><span class="folder">&nbsp;</span>
                    <div style="display:inline;cursor:pointer;">', 
                    in_array($t->id,$_SESSION['groupes_visibles']) ?
                    '<a class="folder_item" onclick="ListerItems(\''.$t->id.'\');">'.str_replace("&","&amp;",$t->title).'</a> ('.$nb_items.')' : 
                    '<span class="folder_item">'.str_replace("&","&amp;",$t->title).'</span>',
                    '</div>';
                    $folder_cpt++;
                }
                $prev_level = $t->nlevel;
            }
            $cpt_total++;
        }
            
        //clore toutes les balises de l'arbo
        for($x=1;$x<$prev_level;$x++)
                        echo "</li>
                        </ul>";
        echo '</li></ul>
    </div>';
    
    ## 
    echo '
    <div id="content" style="float:left;width:660px;margin-left:10px;margin-top:-20px;">
        <div id="content_1" style="border-left:1px solid #FF8000;">
            <div id="arborescence" style="font-size: 15px; font-family:arial; color:#000080; margin-left:10px;">
            </div>
            <div id="liste_des_items" style="display:none;float:left;width:100%;padding-left:0px;overflow:auto ;">            
            </div>
        </div>';
    
    ## ITEM DETAIL
    echo '       
        <div id="item_details_ok" style="width:660px;height:250px;border:1px solid #FF8000; background:url(includes/images/stripe_orange.png) repeat;" class="">
            <div style="height:20px;border-right:1px solid #FF8000;background:#FF8000;width:654px;font-weight:bold;padding-left:5px;">
            Description
            </div>
            
            <input type="hidden" id="id_categorie" value="" />
            <input type="hidden" id="id_item" value="" />    
            <div style="height:230px;overflow-y:auto;">
                <table>
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['label'].' :</td>
                    <td>
                        <input type="hidden" id="hid_label" value="', isset($data_item) ? $data_item['label'] : '', '" />
                        <div id="id_label" style="display:inline;"></div>
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['description'].' :</td>
                    <td>
                        <div id="id_desc" style="font-style:italic;display:inline;"></div><input type="hidden" id="hid_desc" value="', isset($data_item) ? $data_item['description'] : '', '" />
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['pw'].' :</td>
                    <td>
                        <div id="id_pw" style="float:left;"></div>
                        <div id="pw_clipboard" style="display:none;">
                            <div style="display:inline;margin-left:10px; witdh:25px;" id="div_copy_pw"><img src="includes/images/clipboard__plus.png" id="copy_pw" title="'.$txt['pw_copy_clipboard'].'" /></div>
                            <div style="display:inline;color:#008000;font-weight:bold;" id="copy_pw_done">'.$txt['pw_copied_clipboard'].'</div>
                        </div>
                        <input type="hidden" id="hid_pw" value="" />
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['index_login'].' :</td>
                    <td>
                        <div id="id_login" style="float:left;"></div>
                        <div id="login_clipboard" style="display:none;">
                            <div style="display:inline;margin-left:10px; witdh:25px;" id="div_copy_login"><img src="includes/images/clipboard__plus.png" style="cursor:pointer;" id="copy_login" title="'.$txt['login_copy'].'" /></div>
                            <div style="display:inline;color:#008000;font-weight:bold;" id="copy_login_done">'.$txt['pw_copied_clipboard'].'</div>
                        </div>
                        <input type="hidden" id="hid_login" value="" />
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['url'].' :</td>
                    <td>
                        <div id="id_url" style="display:inline;"></div><input type="hidden" id="hid_url" value="" />
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['restricted_to'].' :</td>
                    <td>
                        <div id="id_restricted_to" style="display:inline;"></div><input type="hidden" id="hid_restricted_to" />
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" class="td_title"><span class="ui-icon ui-icon-carat-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>'.$txt['history'].' :</td>
                    <td>
                        <div onclick="ouvrir_div(\'id_info\')" style="cursor:pointer">
                            <img src="includes/images/layout_split_vertical.png" />
                        </div>
                        <div id="id_info" style="font-size:8pt;margin-top:4px;display:none;"></div>
                    </td>
                </tr>
                </table>
            </div>
        </div>';
        
        ## NOT ALLOWED
        echo '        
        <div id="item_details_nok" style="display:none;float:left;width:550px;padding-left: 20px;background-color:white;">
        <b>'.$txt['not_allowed_to_see_pw'].'</b>
        </div>';
    echo ' 
    </div>
</div>';
  
//Formulaire NOUVEAU
echo '
<div id="div_formulaire_saisi" style="display:none;"> 
    <form method="post" name="new_item" action="">
        <div id="afficher_visibilite" style="text-align:center;margin-bottom:6px;"></div>
        <table>
            <tr>
                <td>'.$txt['label'].' : </td>
                <td><input type="text" size="50" name="label" id="label" /></td>
            </tr>
            <tr>
                <td>'.$txt['description'].' :</td>
                <td><textarea rows="3" cols="40" name="desc" id="desc"></textarea></td>
            </tr>
            <tr>
                <td>'.$txt['group'].' : </td>
                <td><select name="categorie" id="categorie" onChange="RecupComplexite(this.value,0)">
                    <option value="na">---'.$txt['select'].'---</option>';
                    foreach($tst as $t){
                        if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                            $ident="";
                            for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                            if ( $prev_level < $t->nlevel ){
                                echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }else if ( $prev_level == $t->nlevel ){
                               echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }else{
                                echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }
                            $prev_level = $t->nlevel;
                        }
                    }
                echo '
                </select>
                &nbsp;&nbsp;'.$txt['complex_asked'].' : <span id="complex_attendue"></span>
                </td>
            </tr>
            <tr>
                <td>'.$txt['used_pw'].' :</td>
                <td>
                    <input type="text" size="30" id="pw1" onkeyup="runPassword(this.value, \'mypassword\');" title="" />                    
                    <img src="includes/images/bricks.png" onClick="pwOptions(\'\')" style="cursor:pointer;" title="'.$txt['generation_options'].'" />&nbsp;
                    <img src="includes/images/arrow_refresh.png" onClick="pwGenerate(\'\');" style="cursor:pointer;" title="'.$txt['pw_generate'].'" />&nbsp;
                    <img src="includes/images/paste_plain.png" onClick="pwCopy(\'\')" style="cursor:pointer;" title="'.$txt['copy'].'" />
                    <div style="width: 50px; display:inline;"> 
                        <div id="mypassword_text" style="font-size: 10px;"></div><input type="hidden" id="mypassword_complex" />
                        <div id="mypassword_bar" style="font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></div> 
                    </div>
                    
                    <div style="display:none; font-size:9px;" id="custom_pw">
                        <label for="pw_numerics">'.$txt['numbers'].' : </label><input type="checkbox" id="pw_numerics" />&nbsp;
                        <label for="pw_maj">'.$txt['maj'].' : </label><input type="checkbox" id="pw_maj" />&nbsp;
                        <label for="pw_symbols">'.$txt['symbols'].' : </label><input type="checkbox" id="pw_symbols" />&nbsp;
                        <label for="pw_size">'.$txt['size'].' : </label><input type="text" size="1" id="pw_size" value="8" />
                    </div>
                </td>
            </tr>
            <tr>
                <td>'.$txt['index_change_pw_confirmation'].' :</td>
                <td><input type="text" size="30" name="pw2" id="pw2" /></td>
            </tr>
            <tr>
                <td>'.$txt['login'].' : </td>
                <td><input type="text" size="50" name="item_login" id="item_login" /></td>
            </tr>
            <tr>
                <td>'.$txt['url'].' :</td>
                <td><input type="text" size="50" name="url" id="url" /></td>
            </tr>
            <tr>
                <td>'.$txt['restricted_to'].' :</td>
                <td><select name="restricted_to_list" id="restricted_to_list" size="3" multiple="multiple"><option value="">-- '.$txt['all'].' --</option>';   
                    foreach($liste_utilisateurs as $user){
                        echo '<option value="'.$user['id'].'">'.$user['login'].'</option>';
                    }
                echo '
                </select>  
                <input type="hidden" size="50" name="restricted_to" id="restricted_to" />           
                </td>
            </tr>
            <tr style="display:none;">
                <td>Personnel :</td>
                <td><input type="checkbox" name="perso" id="perso" /></td>
            </tr>
            <tr>
                <td colspan="2">'.$txt['email_announce'].' : <input type="checkbox" name="annonce" id="annonce" onChange="AfficherCacher(\'annonce_liste\')" />
                    <div style="display:none; border:1px solid #808080; margin-left:30px; margin-top:3px;padding:5px;" id="annonce_liste">
                        <h3>'.$txt['email_select'].'</h3>
                        <select id="annonce_liste_destinataires" multiple="multiple" size="10">';
                        foreach($liste_utilisateurs as $user){
                            echo '<option value="'.$user['email'].'">'.$user['login'].'</option>';
                        }
                        echo '
                        </select>
                    </div>                
                </td>
            </tr>
        </table>
    </form>
</div>';

//Formulaire EDITION ITEM
echo '
<div id="div_formulaire_edition_item" style="display:none;"> 
    <form method="post" name="form_edit" action="">
        <div id="edit_afficher_visibilite" style="text-align:center;margin-bottom:6px;"></div>
    <table>
        <tr>
            <td>'.$txt['label'].' : </td>
            <td><input type="text" size="50" id="edit_label" /></td>
        </tr>
        <tr>
            <td>'.$txt['description'].' :</td>
            <td><textarea rows="3" cols="40"id="edit_desc"></textarea></td>
        </tr>
        <tr>
            <td>'.$txt['group'].' : </td>
            <td><select id="edit_categorie" onChange="RecupComplexite(this.value,1)">';
                    foreach($tst as $t){
                        if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                            $ident="";
                            for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                            if ( $prev_level < $t->nlevel ){
                                echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }else if ( $prev_level == $t->nlevel ){
                               echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }else{
                                echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                            }
                            $prev_level = $t->nlevel;
                        }
                    }
            echo '
            </select>
            &nbsp;&nbsp;'.$txt['complex_asked'].' : <span id="edit_complex_attendue"></span>
            </td>
        </tr>
        <tr>
            <td>'.$txt['used_pw'].' :</td>
            <td>
                <input type="text" size="30" id="edit_pw1"  onkeyup="runPassword(this.value, \'edit_mypassword\');" />
                <img src="includes/images/bricks.png" onClick="pwOptions(\'edit\')" style="cursor:pointer;" title="'.$txt['used_pw'].'" />&nbsp;
                <img src="includes/images/arrow_refresh.png" onClick="pwGenerate(\'edit\');" style="cursor:pointer;" title="'.$txt['pw_generate'].'" />&nbsp;
                <img src="includes/images/paste_plain.png" onClick="pwCopy(\'edit\')" style="cursor:pointer;" title="'.$txt['copy'].'" />
                <div style="width: 100px; display:inline;"> 
                    <div id="edit_mypassword_text" style="font-size: 10px;"></div><input type="hidden" id="edit_mypassword_complex" />
                    <div id="edit_mypassword_bar" style="font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></div> 
                </div>
                <script type="text/javascript">
                runPassword(document.getElementById(\'edit_pw1\').value, \'edit_mypassword\');
                </script>
                
                <div style="display:none; font-size:9px;" id="edit_custom_pw">
                    <label for="edit_pw_numerics">'.$txt['numbers'].' : </label><input type="checkbox" id="edit_pw_numerics" />&nbsp;
                    <label for="edit_pw_maj">'.$txt['maj'].' : </label><input type="checkbox" id="edit_pw_maj" />&nbsp;
                    <label for="edit_pw_symbols">'.$txt['symbols'].' : </label><input type="checkbox" id="edit_pw_symbols" />&nbsp;
                    <label for="edit_pw_size">'.$txt['size'].' : </label><input type="text" size="1" id="edit_pw_size" value="8" />
                </div>
            </td>
        </tr>
        <tr>
            <td>'.$txt['confirm'].' :</td>
            <td><input type="text" size="30" id="edit_pw2" /></td>
        </tr>
        <tr>
            <td>'.$txt['login'].' : </td>
            <td><input type="text" size="50" id="edit_item_login" /></td>
        </tr>
        <tr>
            <td>'.$txt['url'].' :</td>
            <td><input type="text" size="50" id="edit_url" /></td>
        </tr>
        <tr>
            <td>'.$txt['restricted_to'].' :</td>
            <td><select name="edit_restricted_to_list" id="edit_restricted_to_list" size="3" multiple="multiple"><option value="">-- '.$txt['all'].' --</option>
            </select>  
            <input type="hidden" size="50" name="edit_restricted_to" id="edit_restricted_to" />           
            </td>
        </tr>
        <tr style="display:none;">
            <td>Personnel :</td>
            <td><input type="checkbox" id="edit_perso" /></td>
        </tr>
        <tr>
            <td colspan="2">'.$txt['email_announce'].' : <input type="checkbox" name="edit_annonce" id="edit_annonce" onChange="AfficherCacher(\'edit_annonce_liste\')" />
                <div style="display:none; border:1px solid #808080; margin-left:30px; margin-top:3px;padding:5px;" id="edit_annonce_liste">
                    <h3>'.$txt['email_select'].'</h3>
                    <select id="edit_annonce_liste_destinataires" multiple="multiple" size="10">';
                    foreach($liste_utilisateurs as $user){
                        echo '<option value="'.$user['email'].'">'.$user['login'].'</option>';
                    }
                    echo '
                    </select>
                </div>                
            </td>
        </tr>
    </table>
    </form>
</div>';

//Formulaire AJOUT REPERTORIE
echo '
<div id="div_ajout_rep" style="display:none;"> 
    <table>
        <tr>
            <td>'.$txt['label'].' : </td>
            <td><input type="text" size="20" id="new_rep_titre" /></td>
        </tr>
        <tr>
            <td>'.$txt['sub_group_of'].' : </td>
            <td><select id="new_rep_groupe">';
                echo '<option value="0">---</option>';                
                foreach($tst as $t){
                    if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                        $ident="";
                        for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                        if ( $prev_level < $t->nlevel ){
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else if ( $prev_level == $t->nlevel ){
                           echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else{
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }
                        $prev_level = $t->nlevel;
                    }
                }
            echo '
            </select></td>
        </tr>
        <tr>
            <td>'.$txt['complex_asked'].' : </td>
            <td><select id="new_rep_complexite">
                <option value="">---</option>';
                foreach($mdp_complexite as $complex)
                    echo '<option value="'.$complex[0].'">'.$complex[1].'</option>';
            echo '
            </select>
        </tr>
    </table>
</div>';

//Formulaire EDITER REPERTORIE
echo '
<div id="div_editer_rep" style="display:none;"> 
    <table>
        <tr>
            <td>'.$txt['new_label'].' : </td>
            <td><input type="text" size="20" id="edit_rep_titre" /></td>
        </tr>
        <tr>
            <td>'.$txt['group_select'].' : </td>
            <td><select id="edit_rep_groupe">';
                echo '<option value="0">-choisir-</option>';
                foreach($tst as $t){
                    if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                        $ident="";
                        for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                        if ( $prev_level < $t->nlevel ){
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else if ( $prev_level == $t->nlevel ){
                           echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else{
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }
                        $prev_level = $t->nlevel;
                    }
                }
            echo '
            </select></td>
        </tr>
        <tr>
            <td>'.$txt['complex_asked'].' : </td>
            <td><select id="edit_rep_complexite">
                <option value="">---</option>';
                foreach($mdp_complexite as $complex)
                    echo '<option value="'.$complex[0].'">'.$complex[1].'</option>';
            echo '
            </select>
        </tr>
    </table>
</div>';

//Formulaire SUPPRIMER REPERTORIE
echo '
<div id="div_supprimer_rep" style="display:none;"> 
    <table>
        <tr>
            <td>'.$txt['group_select'].' : </td>
            <td><select id="delete_rep_groupe">';
                echo '<option value="0">-choisir-</option>';
                foreach($tst as $t){
                    if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                        $ident="";
                        for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                        if ( $prev_level < $t->nlevel ){
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else if ( $prev_level == $t->nlevel ){
                           echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }else{
                            echo '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",$t->title).'</option>';
                        }
                        $prev_level = $t->nlevel;
                    }
                }
            echo '
            </select></td>
        </tr>
    </table>
</div>';

//SUPPRIMER UN ELEMENT
echo '
<div id="div_del_item" style="display:none;"> 
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;">&nbsp;</span>'.$txt['confirm_deletion'].'</p>
</div>';



//Context menu on Browser
echo '
<ul id="contextMenuBrowser" class="contextMenu">', 
    ($_SESSION['user_admin'] == 1 || $_SESSION['user_gestionnaire'] == 1 ) ? 
    '<li class="add_rep"><a href="#add_rep">'.$txt['item_menu_add_rep'].'</a></li>
    <li class="edit_rep"><a href="#edit_rep">'.$txt['item_menu_edi_rep'].'</a></li>
    <li class="del_rep"><a href="#del_rep">'.$txt['item_menu_del_rep'].'</a></li>' : '',
    '<li class="find"><a href="#find">'.$txt['item_menu_find'].'</a></li>
    <li class="refresh"><a href="#" onclick="javascript:document.new_item.submit();">'.$txt['item_menu_refresh'].'</a></li>
    <li class="quit separator"><a href="#quit">'.$txt['cancel'].'</a></li>
</ul>';

//Context menu on Items
echo '
<ul id="contextMenuContent" class="contextMenu">
    <li class="add_item"><a href="#add_item">'.$txt['item_menu_add_elem'].'</a></li>
    <li class="edit_item"><a href="#edit_item">'.$txt['item_menu_edi_elem'].'</a></li>
    <li class="del_item"><a href="#del_item">'.$txt['item_menu_del_elem'].'</a></li>
    <li class="quit separator"><a href="#quit">Quit</a></li>
</ul>';

?>



<script type="text/javascript">

    function ListerItems(groupe_id){
        LoadingPage();  //afficher image de chargement
        //aficher le bon menu des items
        //document.getElementById('item_details_ok').style.display="none";
        /*if ( document.getElementById('item_menu_notactivated').style.display=="none" ){
            document.getElementById('item_menu').style.display="none";
            document.getElementById('item_menu_notactivated').style.display="inline";
        }*/
        //clean form
        document.getElementById('id_label').innerHTML = "";
        document.getElementById('id_pw').innerHTML = "";
        document.getElementById('id_url').innerHTML = "";
        document.getElementById('id_desc').innerHTML = "";
        document.getElementById('id_login').innerHTML = "";
        document.getElementById('id_info').innerHTML = "";
        document.getElementById('id_restricted_to').innerHTML = "";
        document.getElementById('pw_clipboard').style.display = "none";
        document.getElementById('login_clipboard').style.display = "none";
            
        $('#contextMenuContent').disableContextMenuItems('#edit_item,#del_item');
        var data = "type=lister_items_groupe"+
                    "&id="+groupe_id;
        httpRequest("sources/items.queries.php",data);
    }
    
    function pwOptions(elem){
        if ( elem != "" ) elem = elem+"_custom_pw";
        else elem = "custom_pw";
        if ( document.getElementById(elem).style.display=="")
            document.getElementById(elem).style.display="none";
        else 
            document.getElementById(elem).style.display="";        
    }
    
    function pwGenerate(elem){ 
        if ( elem != "" ) elem = elem+"_";  
        
        var data = "type=pw_generate"+
                    "&size="+document.getElementById(elem+'pw_size').value+
                    "&num="+document.getElementById(elem+'pw_numerics').checked+
                    "&maj="+document.getElementById(elem+'pw_maj').checked+
                    "&symb="+document.getElementById(elem+'pw_symbols').checked+
                    "&elem="+elem;            
        httpRequest("sources/items.queries.php",data+"&force=false");
    }
    
    function pwCopy(elem){
        if ( elem != "" ) elem = elem+"_";
        document.getElementById(elem+'pw2').value = document.getElementById(elem+'pw1').value;
    }
    
    function catSelected(val){
        document.getElementById("hid_cat").value= val;   
    }
    
    function RecupComplexite(val,edit){
        var data = "type=recup_complex"+
                    "&groupe="+val+
                    "&edit="+edit;
        httpRequest("sources/items.queries.php",data);
    }
    
    function AjouterItem(){
        var erreur = "";
        if ( document.getElementById("label").value == "" ) erreur = "<?php echo $txt['error_label'];?>";
        else if ( document.getElementById("pw1").value == "" ) erreur = "<?php echo $txt['error_pw'];?>";
        else if ( document.getElementById("categorie").value == "na" ) erreur = "<?php echo $txt['error_group'];?>";
        else if ( document.getElementById("pw1").value != document.getElementById("pw2").value ) erreur = "<?php echo $txt['error_confirm'];?>";
        else{
            //vérifier le niveau de complexité du mdp
            if ( 
                ( document.getElementById("bloquer_creation_complexite").value == 0 && parseInt(document.getElementById("mypassword_complex").value) >= parseInt(document.getElementById("complexite_groupe").value) )
                ||
                ( document.getElementById("bloquer_creation_complexite").value == 1 )
                ){
            
                LoadingPage();  //afficher image de chargement
                var perso = annonce = 0;
                if ( document.getElementById('perso').checked ) perso = 1;
                if ( document.getElementById('annonce').checked ) annonce = 1;
                
                //gérer les restrictions
                var myselect = document.getElementById('restricted_to_list');
                var restriction = "";
                for (var loop=0; loop < myselect.options.length; loop++) {
                    if (myselect.options[loop].selected == true) restriction = restriction + myselect.options[loop].value + ";";
                }
                if ( restriction != "" && restriction.indexOf(document.getElementById('form_user_id').value) == "-1" )
                    restriction = document.getElementById('form_user_id').value+";"+restriction
                if ( restriction == ";" ) restriction = "";
                
                //gérer la liste de diffusion
                var myselect = document.getElementById('annonce_liste_destinataires');
                var diffusion = "";
                for (var loop=0; loop < myselect.options.length; loop++) {
                    if (myselect.options[loop].selected == true) diffusion = diffusion + myselect.options[loop].value + ";";
                }
                if ( diffusion == ";" ) diffusion = "";
                
                
                var data = "type=new_item"+
                            "&pw="+escape(document.getElementById('pw1').value)+
                            "&label="+escape(document.getElementById('label').value)+
                            "&desc="+escape(document.getElementById('desc').value)+
                            "&url="+escape(document.getElementById('url').value)+
                            "&login="+document.getElementById('item_login').value+
                            "&personnel="+perso+
                            "&annonce="+annonce+
                            "&diffusion="+diffusion+
                            "&categorie="+document.getElementById('categorie').value+
                            "&restricted_to="+restriction;
                httpRequest("sources/items.queries.php",data);
            }else
                alert("<?php echo $txt['error_complex_not_enought'];?>");
        }        
        if ( erreur != "") alert(erreur);
    }
    
    function EditerItem(){
        var erreur = "";
        if ( document.getElementById("edit_label").value == "" ) erreur = "<?php echo $txt['error_label'];?>";
        else if ( document.getElementById("edit_pw1").value == "" ) erreur = "<?php echo $txt['error_pw'];?>";
        else if ( document.getElementById("edit_pw1").value != document.getElementById("edit_pw2").value ) erreur = "<?php echo $txt['error_confirm'];?>";
        else{
            //vérifier le niveau de complexité du mdp
            if ( ( document.getElementById("bloquer_modification_complexite").value == 0 && parseInt(document.getElementById("edit_mypassword_complex").value) >= parseInt(document.getElementById("complexite_groupe").value) )
                ||
                ( document.getElementById("bloquer_modification_complexite").value == 1 )
            ){
                LoadingPage();  //afficher image de chargement
                var perso = annonce = 0;
                if ( document.getElementById('edit_perso').checked ) perso = 1;
                if ( document.getElementById('edit_annonce').checked ) annonce = 1;
                
                //gérer les restrictions
                var myselect = document.getElementById('edit_restricted_to_list');
                var restriction = "";
               for (var loop=0; loop < myselect.options.length; loop++) {
                    if (myselect.options[loop].selected == true) restriction = restriction + myselect.options[loop].value + ";";
                }
                if ( restriction == ";" ) restriction = "";
                
                //gérer la liste de diffusion
                var myselect = document.getElementById('edit_annonce_liste_destinataires');
                var diffusion = "";
                for (var loop=0; loop < myselect.options.length; loop++) {
                    if (myselect.options[loop].selected == true) diffusion = diffusion + myselect.options[loop].value + ";";
                }
                if ( diffusion == ";" ) diffusion = "";
                
                //changer le caractere & 
                var pw = document.getElementById('edit_pw1').value;
                if ( pw.indexOf('&') != 0 ){
                    pw = pw.replace('&','ETCOMMERCIAL');
                }
                if ( pw.indexOf('+') != 0 ){
                    pw = pw.replace('+','SIGNEPLUS');
                }
                
                //envoyer la requete
                var data = "type=update_item"+
                            "&pw="+pw+
                            "&label="+escape(document.getElementById('edit_label').value)+
                            "&description="+escape(document.getElementById('edit_desc').value)+
                            "&url="+escape(document.getElementById('edit_url').value)+
                            "&login="+escape(document.getElementById('edit_item_login').value)+
                            "&categorie="+escape(document.getElementById('edit_categorie').value)+
                            "&perso="+perso+
                            "&annonce="+annonce+
                            "&diffusion="+diffusion+
                            "&id="+document.getElementById('id_item').value+
                            "&restricted_to="+restriction; //document.getElementById('edit_restricted_to').value;
                httpRequest("sources/items.queries.php",data);
            }else
                alert("<?php echo $txt['error_complex_not_enought'];?>");
        }        
        if ( erreur != "") alert(erreur);
    }
    
    function AjouterFolder(){
        if ( document.getElementById("new_rep_titre").value == "0" ) alert("<?php echo $txt['error_group_label'];?>");
        else if ( document.getElementById("new_rep_complexite").value == "" ) alert("<?php echo $txt['error_group_complex'];?>");
        else{
            var data = "type=new_rep"+
                        "&title="+escape(document.getElementById('new_rep_titre').value)+
                        "&complexite="+escape(document.getElementById('new_rep_complexite').value)+
                        "&groupe="+document.getElementById("new_rep_groupe").value;
            httpRequest("sources/items.queries.php",data);
        }
    }
    
    function EditerFolder(){
        if ( document.getElementById("edit_rep_titre").value == "" ) alert("<?php echo $txt['error_group_label'];?>");
        else if ( document.getElementById("edit_rep_groupe").value == "0" ) alert("<?php echo $txt['error_group'];?>");
        else if ( document.getElementById("edit_rep_complexite").value == "" ) alert("<?php echo $txt['error_group_complex'];?>");
        else{
            var data = "type=update_rep"+
                        "&title="+escape(document.getElementById('edit_rep_titre').value)+
                        "&complexite="+escape(document.getElementById('edit_rep_complexite').value)+
                        "&groupe="+document.getElementById("edit_rep_groupe").value;
            httpRequest("sources/items.queries.php",data);
        }
    }
    
    function SupprimerFolder(){
        if ( document.getElementById("delete_rep_groupe").value == "0" ) alert("<?php echo $txt['error_group'];?>");
        else if ( confirm("<?php echo $txt['confirm_delete_group'];?>") ) {
            var data = "type=delete_rep"+
                        "&groupe="+document.getElementById("delete_rep_groupe").value;
            httpRequest("sources/items.queries.php",data);
        }
    }
    
    function AfficherDetailsItem(id){
        LoadingPage();  //afficher image de chargement
        if ( document.getElementById("is_admin").value == "1" )
            $('#contextMenuContent').enableContextMenuItems('#edit_item,#del_item');
            
        var data = "type=show_details_item&id="+id;
        httpRequest("sources/items.queries.php",data);
    }
    
    function ShowSearchDiv(){
        $('#for_searchtext').toggle("medium");
    }
    
    $(function() {
        
        $("#browser").treeview({
            collapsed: false,
            animated: "fast",
            control:"#browsercontrol",
            persist: "location"
        });
        
        
        $("#add_folder").click(function() {
            var posit = document.getElementById('item_selected').value;
            alert($("ul").text());
        });
        
        $('a.folder_item').quicksearch({
            position: 'before',
            formId: 'for_searchtext',
            attached: '#div_searchtext',
            focusOnLoad: false,
            labelText: '<img src=\'includes/images/pencil_arrow.png\'>',
            delay: 300,
            loaderImg: 'includes/images/ajax-loader.gif'
        });  
        
        $("#for_searchtext").hide();
        $("#copy_pw_done").hide();
        $("#copy_login_done").hide();
        
        //PREPARE DIALOGBOXES
            //=> ADD A NEW GROUP
            function open_add_group_div() {
                $('#div_ajout_rep').dialog('open');
            }
            $("#div_ajout_rep").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 300,
                height: 200,
                title: "<?php echo $txt['item_menu_add_rep'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        AjouterFolder();
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            //=> EDIT A GROUP
            function open_edit_group_div() {
                $('#div_editer_rep').dialog('open');
            }
            $("#div_editer_rep").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 300,
                height: 200,
                title: "<?php echo $txt['item_menu_edi_rep'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        EditerFolder();
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            //=> DELETE A GROUP
            function open_del_group_div() {
                $('#div_supprimer_rep').dialog('open');
            }
            $("#div_supprimer_rep").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 300,
                height: 200,
                title: "<?php echo $txt['item_menu_del_rep'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        SupprimerFolder();
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            //=> ADD A NEW ITEM
            function open_add_item_div() {
                //préselectionner le groupe dans la liste déroulante
                var liste = document.getElementById('categorie');
                for (var i=0; i<liste.length; i++) {
                   if ( liste.options[i].value == document.getElementById('hid_cat').value ) {
                    liste.options[i].selected = true;
                    RecupComplexite(document.getElementById('hid_cat').value,0);
                    break;
                   }
               }
               //Afficher popup 
                $('#div_formulaire_saisi').dialog('open');
            }
            $("#div_formulaire_saisi").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 520,
                height: 470,
                title: "<?php echo $txt['item_menu_add_elem'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        AjouterItem();
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            //=> EDITER UN ELEMENT
            function open_edit_item_div() {
                document.getElementById('edit_label').value = document.getElementById('hid_label').value;
               document.getElementById('edit_desc').value = document.getElementById('hid_desc').value;
               document.getElementById('edit_pw1').value = document.getElementById('hid_pw').value;
               document.getElementById('edit_pw2').value = document.getElementById('hid_pw').value;
               document.getElementById('edit_item_login').value = document.getElementById('hid_login').value;
               document.getElementById('edit_url').value = document.getElementById('hid_url').value;
               //if ( document.getElementById('hid_perso').value == "1" ) document.getElementById('edit_perso').checked;
               document.getElementById('edit_categorie').value = document.getElementById('id_categorie').value;
               document.getElementById('edit_restricted_to').value = document.getElementById('hid_restricted_to').value;
               
               //recharger la complexité du mdp affiché
               runPassword(document.getElementById('edit_pw1').value, 'edit_mypassword');
               
               //récupérer la complexité des mdp de ce groupe
               RecupComplexite(document.getElementById('hid_cat').value,1);
               
               //charger la liste des personnes dans la liste de restriction
               var myselect = document.getElementById('edit_restricted_to_list');
               myselect.options.length = 0;
               var liste = document.getElementById('input_liste_utilisateurs').value.split(';');
               for (var i=0; i<liste.length; i++) {
                   var elem = liste[i].split('.');
                   if ( elem[0] != "" ){
                       myselect.options[myselect.options.length] = new Option(elem[1], elem[0]);
                       var index = document.getElementById('edit_restricted_to').value.lastIndexOf(elem[0]+";");
                       if ( index != -1 ) {
                           myselect.options[i].selected = true;
                       }else myselect.options[i].selected = false;
                   }
               }
               
               //Display popup
                $('#div_formulaire_edition_item').dialog('open');
            }
            $("#div_formulaire_edition_item").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 520,
                height: 470,
                title: "<?php echo $txt['item_menu_edi_elem'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        EditerItem();
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            //=> SUPPRIMER UN ELEMENT
            function open_del_item_div() {
                $('#div_del_item').dialog('open');
            }
            $("#div_del_item").dialog({
                bgiframe: true,
                modal: true,
                autoOpen: false,
                width: 300,
                height: 100,
                title: "<?php echo $txt['item_menu_del_elem'];?>",
                buttons: {
                    "<?php echo $txt['save_button'];?>": function() {
                        var data = "type=del_item"+
                                    "&groupe="+document.getElementById('hid_cat').value+
                                    "&id="+document.getElementById('id_item').value;
                        httpRequest("sources/items.queries.php",data);
                        $(this).dialog('close');
                    },
                    "<?php echo $txt['cancel_button'];?>": function() {
                        $(this).dialog('close');
                    }
                }
            });
            //<=
            
        
        
        //automatic height
        var hauteur = $(window).height();
        $("#div_items, #content").height( (hauteur-150) );
        $("#content_1").height( (hauteur-400) );
        $("#liste_des_items").height(hauteur-420);
        $("#sidebar").height(hauteur-170);
        
        //launch context menu
        $("#browser").contextMenu({
            menu: 'contextMenuBrowser'
            },
            function(action, el, pos) {
                if ( action == "add_rep" ) open_add_group_div();
                if ( action == "del_rep" ) open_del_group_div();
                if ( action == "edit_rep" ) open_edit_group_div();
                if ( action == "find" ) document.location.href="index.php?page=find";
            }
        );
        $("#content").contextMenu({
            menu: 'contextMenuContent'
            },
            function(action, el, pos) {
                if ( action == "add_item" ) open_add_item_div();
                if ( action == "del_item" ) open_del_item_div();
                if ( action == "edit_item" ) open_edit_item_div();
            }
        );
        $('#contextMenuContent').disableContextMenuItems('#edit_item,#del_item');
        
        //display first group items
        AfficherRecherche();
    });

    
    //Gérer l'affichage d'une recherche
    function AfficherRecherche(){
        if ( document.getElementById('recherche_id').value != "" ){
            ListerItems(document.getElementById('recherche_groupe').value);
            AfficherDetailsItem(document.getElementById('recherche_id').value);
        }else if ( document.getElementById('recherche_groupe').value != "" ){
            ListerItems(document.getElementById('recherche_groupe').value);
        }else
            ListerItems(<?php echo $first_group;?>);
    }

    
</script>