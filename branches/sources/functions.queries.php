<?php
####################################################################################################
## File : views.queries.php
## Author : Nils Laumaillé
## Description : File contains queries for ajax
## 
## DON'T CHANGE !!!
## 
####################################################################################################

session_start();

include('../includes/language/'.$_SESSION['user_language'].'.php'); 
include('../includes/settings.php');
header("Content-type: text/html; charset=".$k['charset']); 

// Construction de la requête en fonction du type de valeur
if ( !empty($_POST['type']) ){
    switch($_POST['type'])
    {
        #CASE adding a new function
        case "ajouter_fonction":
            $sql = "INSERT INTO ".$k['prefix']."functions VALUES (NULL,'".mysql_real_escape_string(stripslashes(($_POST['fonction'])))."','','')";
            mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
        break;
        
        #-------------------------------------------
        #CASE delete a function
        case "supprimer_fonction":
            mysql_query("DELETE FROM ".$k['prefix']."functions WHERE id = ".$_POST['id']);
        break;
        
        #-------------------------------------------
        #CASE update allowed/forbidden groups for a Function
        case "groupes_visibles":
        case "groupes_interdits":
            $val = explode(';',$_POST['valeur']);
            $valeur = $_POST['valeur'];
            //vérifier si l'id est déjà présent
            $res = mysql_query("SELECT ".$_POST['type']." FROM ".$k['prefix']."functions WHERE id = $val[0]");
            $data = mysql_fetch_row($res);
            $new_groupes = $data[0];
            if ( !empty($data[0]) ){
                $groupes = explode(';',$data[0]);
                if ( in_array($val[1],$groupes ) ) $new_groupes = str_replace($val[1],"",$new_groupes);
                else $new_groupes .= ";".$val[1];
            }else{
                $new_groupes = $val[1];
            }
            while ( substr_count($new_groupes,";;") > 0 ) $new_groupes = str_replace(";;",";",$new_groupes);
            mysql_query("UPDATE ".$k['prefix']."functions SET ".$_POST['type']." = '".$new_groupes."' WHERE id = ".$val[0]);
        break;
                
        #-------------------------------------------
        #CASE refresh the matrix
        case "rafraichir_matrice": 
            echo '$("#refresh_loader").show();';
            echo 'document.getElementById(\'matrice_droits\').innerHTML = "";';
            require_once ("NestedTree.class.php");
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tst = $tree->getDescendants();
            $texte = '<table><thead><tr><th>'.$txt['group'].'s</th>';
            $gpes_ok = array();
            $gpes_nok = array();
            $tab_fonctions = array();
            $res = mysql_query("SELECT title,id,groupes_visibles,groupes_interdits FROM ".$k['prefix']."functions ORDER BY title ASC");
            while ($data = mysql_fetch_row($res) ){
                $texte .= '<th style="font-size:10px;">'.$data[0].'</th>';
                //Get all descendents groups
                $gpok = $data[2];
                $gpnok = $data[3];
                $tmp_ok = explode(';',$data[2]);
                $tmp_nok = explode(';',$data[3]);
                foreach($tmp_ok as $t){
                    if ( !empty($t) ){
                        $desc = $tree->getDescendants($t);
                        foreach($desc as $d)
                            $gpok .= ';'.$d->id;
                    }
                }
                foreach($tmp_nok as $t){
                    if ( !empty($t) ){
                        $desc = $tree->getDescendants($t);
                        foreach($desc as $d)
                            $gpnok .= ';'.$d->id;
                    }
                }
                //save into database
                $tab_fonctions[$data[1]] = array(
                    "ok" => $gpok,
                    "nok" => $gpnok,
                    "id" => $data[1],
                    "titre" => $data[0]
                );
            }
            $texte .= '</tr></thead><tbody>';
            //construire tableau des groupes
            $tab_groupes = array();
            foreach($tst as $t){
                $ident="";
                for($a=1;$a<$t->nlevel;$a++) $ident .= "&nbsp;&nbsp;";
                $tab_groupes[$t->id] = array(
                        'id' => $t->id,
                        'titre' => $t->title,
                        'ident' => $ident
                        );
            } 
            
            //afficher
            foreach ($tab_groupes as $groupe){
                $visibilite = "";
                $texte .= '<tr><td style="font-size:10px; font-family:arial;">'.$groupe['ident'].$groupe['titre'].'</td>';
                foreach ($tab_fonctions as $fonction){  
                    if ( !empty($fonction) ){          
                        if ( !empty($fonction['ok']) ) $gpes_ok = explode(';',$fonction['ok']);else $gpes_ok = array();
                        if ( !empty($fonction['nok']) ) $gpes_nok = explode(';',$fonction['nok']);else $gpes_nok = array();
                        if ( in_array($groupe['id'],$gpes_ok) ) $couleur = '#008000';
                        else $couleur = '#FF0000';
                        if ( count($gpes_nok)>0 && in_array($groupe['id'],$gpes_nok) ) $couleur = '#FF0000';
                        $texte .= '<td align="center" style="background-color:'.$couleur.'"></td>';
                        if ( $couleur != '#FF0000') $visibilite .= $fonction['id'].";";
                    }
                }
                $texte .= '</tr>';
                
                //sauvegarder en BdD la synthèse (pratique pour exploiter plus tard)
                $res = mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."misc WHERE type='visibilite' AND intitule = '".$groupe['id']."'");
                $data = mysql_fetch_row($res);
                if ( $data[0] == 0 ){
                    mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('visibilite','".$groupe['id']."','".$visibilite."')");
                }else{
                    mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$visibilite."' WHERE type='visibilite' AND intitule = '".$groupe['id']."'");
                }
                
            }
            $texte .= '</tbody></table>';
            echo 'document.getElementById(\'matrice_droits\').innerHTML = "'.addslashes($texte).'";';
            echo '$("#refresh_loader").hide();';
        break;
        
        #-------------------------------------------
        #CASE display the div for allowed groups
        case "open_div_autgroups";
            $text = "";
            
            //get list of authorized/forbidden groups for this Function
            $data_group = mysql_fetch_row(mysql_query("SELECT groupes_visibles FROM ".$k['prefix']."functions WHERE id = ".$_POST['id']));
            $autgroups = explode(';',$data_group[0]);
            
            //Refresh list of existing groups
            require_once ("NestedTree.class.php");
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $descendants = $tree->getDescendants();
            
            foreach($descendants as $t){
                if ( !in_array($t->id,$_SESSION['groupes_interdits']) ){
                    $ident="";
                    for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                    
                    $text .= '<input type=\"checkbox\" id=\"cb_change_group-'.$t->id.'\"';
                    if ( in_array($t->id,$autgroups) )  $text .= ' checked';
                    $text .= '>'.$ident.addslashes($t->title).'<br />';
                         
                    $prev_level = $t->nlevel;
                }
            }
            
            echo 'document.getElementById("change_group_autgroups_list").innerHTML = "'.$text.'";';
            echo 'document.getElementById("selected_function").value = "'.$_POST['id'].'";';
                        
            //display dialogbox
            echo '$("#change_group_autgroups").dialog("open");';
        break;
        
        #-------------------------------------------
        #CASE change the allowed groups
        case "change_function_autgroups";
            //save data
            mysql_query("UPDATE ".$k['prefix']."functions SET groupes_visibles = '".$_POST['list']."' WHERE id = ".$_POST['id']);                        
            //display information
            $text = "";
            $val = str_replace(';',',',$_POST['list']);
            $data = mysql_query("SELECT title FROM ".$k['prefix']."nested_tree WHERE id IN (".$val.")");
            while ( $res = mysql_fetch_row($data) ){
                $text .= $res[0]."<br />";
            }
             echo 'document.getElementById("list_autgroups_function_'.$_POST['id'].'").innerHTML = "'.$text.'";';
        break;
        
        #-------------------------------------------
        #CASE display the list of forbidden groups
        case "open_div_forgroups";
            $text = "";            
            
            //get list of forbidden groups for this Function
            $data_group = mysql_fetch_row(mysql_query("SELECT groupes_interdits FROM ".$k['prefix']."functions WHERE id = ".$_POST['id']));
            $autgroups = explode(';',$data_group[0]);
                        
            //Refresh list of existing groups
            require_once ("NestedTree.class.php");
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $descendants = $tree->getDescendants();
            
            foreach($descendants as $t){
                if ( !in_array($t->id,$_SESSION['groupes_interdits']) ){
                    $ident="";
                    for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                    
                    $text .= '<input type=\"checkbox\" id=\"cb_change_group-'.$t->id.'\"';
                    if ( in_array($t->id,$autgroups) )  $text .= ' checked';
                    $text .= '>'.$ident.addslashes($t->title).'<br />';
                         
                    $prev_level = $t->nlevel;
                }
            }
            
            echo 'document.getElementById("change_group_forgroups_list").innerHTML = "'.$text.'";';
            echo 'document.getElementById("selected_function").value = "'.$_POST['id'].'";';
                        
            //display dialogbox
            echo '$("#change_group_forgroups").dialog("open");';
        break;
        
        #-------------------------------------------
        #CASE change the forbidden groups
        case "change_function_forgroups";
            //save data
            mysql_query("UPDATE ".$k['prefix']."functions SET groupes_interdits = '".$_POST['list']."' WHERE id = ".$_POST['id']);
                        
            //display information
            $text = "";
            $val = str_replace(';',',',$_POST['list']);
            $data = mysql_query("SELECT title FROM ".$k['prefix']."nested_tree WHERE id IN (".$val.")");
            while ( $res = mysql_fetch_row($data) ){
                $text .= $res[0]."<br />";
            }
             echo 'document.getElementById("list_forgroups_function_'.$_POST['id'].'").innerHTML = "'.$text.'";';
        break;
    }
}else if ( !empty($_POST['edit_fonction']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."functions SET title = '".mysql_real_escape_string(stripslashes(utf8_decode($_POST['edit_fonction'])))."' WHERE id = ".$id[1]);
    echo $_POST['edit_fonction'];
}
?>
