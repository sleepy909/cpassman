<?php
    session_start();
    require_once('../includes/language/'.$_SESSION['user_language'].'.php');
    include('../includes/settings.php');    
    header("Content-type: text/html; charset=".$k['charset']);  
    include('main.functions.php'); 

// Construction de la requête en fonction du type de valeur
switch($_POST['type'])
{    
    case "new_item":
        //vérifier si l'élément n'existe pas déjà
        $res = mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."items WHERE label = '".mysql_real_escape_string(stripslashes(($_POST['label'])))."' AND inactif=0");
        $data = mysql_fetch_row($res);
        if ( $data[0] != 0 ) 
            echo 'alert(\''.$txt['error_item_exists'].'\');';
        else {
            //ajouter élément
            $sql = "INSERT INTO ".$k['prefix']."items VALUES (NULL,'".mysql_real_escape_string(stripslashes($_POST['label']))."','".addslashes($_POST['desc'])."','".encrypt($_POST['pw'])."','".mysql_real_escape_string(stripslashes(($_POST['url'])))."','".$_POST['categorie']."','".$_POST['personnel']."','".mysql_real_escape_string(stripslashes(($_POST['login'])))."','0','".$_POST['restricted_to']."')";
            $res = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            $new_id=mysql_insert_id();
            //log
            mysql_query("INSERT INTO ".$k['prefix']."log_items VALUES ('".$new_id."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Création','')");
            //annoncer par email
            if ( $_POST['annonce'] == 1 ){
                require_once("class.phpmailer.php");
                //envoyer email
                $destinataire= explode(';',$_POST['diffusion']);
                foreach($destinataire as $mail_destinataire){
                    //envoyer ay destinataire
                    $mail = new PHPMailer();                    
                    $mail->SetLanguage("en","../includes/phpmailer/language");
                    //$mail->SMTPDebug=true;
                    $mail->IsSMTP();                                   // send via SMTP
                    $mail->Host     = $smtp_server; // SMTP servers
                    $mail->SMTPAuth = $smtp_auth;     // turn on SMTP authentication
                    $mail->Username = $smtp_auth_username;  // SMTP username
                    $mail->Password = $smtp_auth_password; // SMTP password
                    
                    
                    $mail->From     = $email_from;
                    $mail->FromName = $email_from_name;
                    
                    $mail->AddAddress($mail_destinataire);     //Destinataire     $mailAdresses[$i]                
                    
                    $mail->WordWrap = 80;                              // set word wrap
                    $mail->IsHTML(true);                               // send as HTML
                    
                    $mail->Subject  =  $txt['email_subject'];
                    $mail->AltBody     =  $txt['email_altbody_1']." ".mysql_real_escape_string(stripslashes(($_POST['label'])))." ".$txt['email_altbody_2'];
                    
                    
                    $corpsDeMail = $txt['email_body_1'].mysql_real_escape_string(stripslashes(($_POST['label']))).$txt['email_body_2'].
                    $url_passman."/index.php?page=items&group=".$_POST['categorie']."&id=".$new_id.$txt['email_body_3'];
                    
                    $mail->Body  =  $corpsDeMail;
                            
                    $mail->Send();
                }
            }
            //rafrachir la page
            echo 'window.location.href = "index.php?page=items&group='.$_POST['categorie'].'&id='.$new_id.'";';
        }
    break;
    
    #===========#
    
    case "update_item":
        //récup des valeurs existantes
        $sql = "SELECT * FROM ".$k['prefix']."items WHERE id=".$_POST['id'];
        $res = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
        $data = mysql_fetch_array($res);
        
        //Gérer le cas du caractère &
        $patterns = array('/ETCOMMERCIAL/','/SIGNEPLUS/');
        $remplacements = array('&','+');
        $pw_recu = $_POST['pw'];
        $pw_recu = preg_replace($patterns,$remplacements,$pw_recu);
        
        
        //MAJ l'item 
        $sql = "UPDATE ".$k['prefix']."items SET label = '".mysql_real_escape_string(stripslashes(($_POST['label'])))."', description = '".addslashes($_POST['description'])."', pw = '".encrypt($pw_recu)."', login = '".mysql_real_escape_string(stripslashes(($_POST['login'])))."', url = '".mysql_real_escape_string(stripslashes(($_POST['url'])))."', id_tree = '".mysql_real_escape_string($_POST['categorie'])."', perso = '".$_POST['perso']."', restricted_to = '".$_POST['restricted_to']."' WHERE id='".$_POST['id']."'";
        mysql_query($sql) or die($sql.'  =>  '.mysql_error());
        
        //identifier les différences
        if ( $data['label'] != $_POST['label'] ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Label : ".$data['label']." => ".mysql_real_escape_string(stripslashes(($_POST['label'])))."')");
        if ( $data['login'] != $_POST['login'] ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','login : ".$data['login']." => ".mysql_real_escape_string(stripslashes(($_POST['login'])))."')");
        if ( $data['url'] != $_POST['url'] ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Url : ".$data['url']." => ".mysql_real_escape_string(stripslashes($_POST['url']))."')");
        if ( $data['description'] != $_POST['description'] ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Description.')");
        if ( $data['perso'] != $_POST['perso'] ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Personnel : ".$data['perso']." => ".$_POST['perso']."')");
        if ( $data['id_tree'] != mysql_real_escape_string($_POST['categorie']) ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Catégorie : ".$data['id_tree']." => ".mysql_real_escape_string($_POST['categorie'])."')");
        if ( $data['pw'] != encrypt($pw_recu) ) mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Modification','Mot de passe changé')");
        
        //recharger les nouvelles données
            $sql = "SELECT * FROM ".$k['prefix']."items i, ".$k['prefix']."log_items l WHERE i.id=".$_POST['id']." AND l.id_item = i.id AND l.action = 'Création'";
            $res = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            $data_item = mysql_fetch_array($res);
            $historique = "";
            $res1 = mysql_query("SELECT * FROM ".$k['prefix']."log_items WHERE id_item=".$_POST['id']);
            while ( $data1 = mysql_fetch_array($res1) ){
                $res2 = mysql_query("SELECT login FROM ".$k['prefix']."users WHERE id=".$data1['id_user']);
                $data2 = mysql_fetch_row($res2);
            
                if ( empty($historique) ) $historique = date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison'];
                else
                 $historique .= "<br />".date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison']; 
            }
            
            //recup liste de restriction
            $liste = explode(";",$data_item['restricted_to']);
            $liste_restriction = "";
            foreach($liste as $elem){
                if ( !empty($elem) ){
                    $data2 = mysql_fetch_row(mysql_query("SELECT login FROM ".$k['prefix']."users WHERE id=".$elem));
                    $liste_restriction .= $data2[0].";";
                }
            }
        
        
            echo 'document.getElementById(\'id_label\').innerHTML = "'.$data_item['label'].'";';
            echo 'document.getElementById(\'id_pw\').innerHTML = "'.htmlentities(decrypt($data_item['pw']),ENT_QUOTES).'";';
            echo 'document.getElementById(\'id_url\').innerHTML = "'.$data_item['url'].'";';
            echo 'document.getElementById(\'id_desc\').innerHTML = "'.str_replace('\n','<br>',(mysql_real_escape_string($data_item['description']))).'";';
            echo 'document.getElementById(\'id_login\').innerHTML = "'.$data_item['login'].'";';
            //if ( $data_item['perso'] == 0 ) $perso = "Non"; else $perso = "Oui";
            //echo 'document.getElementById(\'id_perso\').innerHTML = "'.$perso.'";';
            echo 'document.getElementById(\'id_info\').innerHTML = "'.$historique.'";';
            echo 'document.getElementById(\'id_restricted_to\').innerHTML = "'.$liste_restriction.'";';
            
            //renseigner les champs masqués
            echo 'document.getElementById(\'hid_label\').value = "'.$data_item['label'].'";';
            echo 'document.getElementById(\'hid_pw\').value = "'.htmlentities(decrypt($data_item['pw']),ENT_QUOTES).'";';
            echo 'document.getElementById(\'hid_url\').value = "'.$data_item['url'].'";';
            echo 'document.getElementById(\'hid_desc\').value = "'.mysql_real_escape_string($data_item['description']).'";';
            echo 'document.getElementById(\'hid_login\').value = "'.$data_item['login'].'";';
            echo 'document.getElementById(\'id_categorie\').value = "'.$data_item['id_tree'].'";';
            echo 'document.getElementById(\'id_item\').value = "'.$data_item['id'].'";';
            //echo 'document.getElementById(\'hid_perso\').value = "'.$data_item['perso'].'";';
            echo 'document.getElementById(\'hid_restricted_to\').value = "'.$data_item['restricted_to'].'";';
            
            //rafrachir la page
            #echo "document.new_item.submit();";
            
            require_once("class.phpmailer.php");
            //envoyer email
            $destinataire= explode(';',$_POST['diffusion']);
            foreach($destinataire as $mail_destinataire){
                //envoyer ay destinataire
                $mail = new PHPMailer();                    
                $mail->SetLanguage("en","../includes/phpmailer/language");
                //$mail->SMTPDebug=true;
                $mail->IsSMTP();                                   // send via SMTP
                $mail->Host     = "mail.clinsight.eu"; // SMTP servers
                $mail->SMTPAuth = false;     // turn on SMTP authentication
                $mail->Username = "";  // SMTP username
                $mail->Password = ""; // SMTP password
                
                
                $mail->From     = "nils.laumaille@clinsight.fr";
                $mail->FromName = "PassMan";
                
                $mail->AddAddress($mail_destinataire);     //Destinataire     $mailAdresses[$i]                
                
                $mail->WordWrap = 80;                              // set word wrap
                $mail->IsHTML(true);                               // send as HTML
                
                $mail->Subject  =  "Mise à jour d'un mot de passe";
                $mail->AltBody     =  "Le mot de passe de ".mysql_real_escape_string(stripslashes(($_POST['label'])))." a été mis à jour.";
                
                
                $corpsDeMail = "Bonjour,<br><br>Le mot de passe de '" .mysql_real_escape_string(stripslashes(($_POST['label'])))."' a été mis à jour.<br /><br />".
                "Vous pouvez le consulter <a href=\"".$url_passman."/index.php?page=items&group=".$_POST['categorie']."&id=".$_POST['id']."\">ICI</a><br /><br />".
                "A bientot";
                
                $mail->Body  =  $corpsDeMail;
                        
                $mail->Send();
            }
    break;
    
    case "load_item":
        $sql = "SELECT * FROM ".$k['prefix']."items WHERE id=".$_POST['id'];
        $res = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
        $data = mysql_fetch_array($res);
        echo 'document.getElementById(\'item_details\').style.display = "";';
        echo 'document.getElementById(\'item_menu\').style.display = "";';
        echo 'document.getElementById(\'id_label\').innerHTML = "'.$data['label'].'";';
        echo 'document.getElementById(\'id_pw\').innerHTML = "'.decrypt($data['pw']).'";';
        echo 'document.getElementById(\'id_url\').innerHTML = "'.$data['url'].'";';
        echo 'document.getElementById(\'id_desc\').innerHTML = "'.$data['description'].'";';
        echo 'document.getElementById(\'id_login\').innerHTML = "'.$data['login'].'";';
        //if ( $data['perso'] == 0 ) $perso = "Non"; else $perso = "Oui";
        //echo 'document.getElementById(\'id_perso\').innerHTML = "'.$perso.'";';
        
        //renseigner les champs masqués
        echo 'document.getElementById(\'hid_label\').value = "'.$data['label'].'";';
        echo 'document.getElementById(\'hid_pw\').value = "'.decrypt($data['pw']).'";';
        echo 'document.getElementById(\'hid_url\').value = "'.$data['url'].'";';
        echo 'document.getElementById(\'hid_desc\').value = "'.$data['description'].'";';
        echo 'document.getElementById(\'hid_login\').value = "'.$data['login'].'";';
        echo 'document.getElementById(\'id_categorie\').value = "'.$data['id_tree'].'";';
        echo 'document.getElementById(\'id_item\').value = "'.$data['id'].'";';
        //echo 'document.getElementById(\'hid_perso\').value = "'.$data['perso'].'";';
        
        $historique = "";
        $res1 = mysql_query("SELECT * FROM ".$k['prefix']."log_items WHERE id_item=".$_POST['id']);
        while ( $data1 = mysql_fetch_array($res1) ){
            $res2 = mysql_query("SELECT login FROM ".$k['prefix']."users WHERE id=".$data1['id_user']);
            $data2 = mysql_fetch_row($res2);
        
            if ( empty($historique) ) $historique = date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison'];
            else
             $historique .= "<br />".date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison'];
        }  
        
        echo 'document.getElementById(\'id_info\').innerHTML = "'.$historique.'";';
         
    break;
    
    case "show_details_item":
        //changer la class de l'élément sélectionné
        echo 'var tmp = \'fileclass\'+document.getElementById(\'selected_items\').value;';
        echo 'if ( tmp != "fileclass") document.getElementById(tmp).className = "file";';
        echo 'document.getElementById(\'selected_items\').value = "'.$_POST['id'].'";';
        
        //charger les données de ce mdp
        $sql = "SELECT * FROM ".$k['prefix']."items i, ".$k['prefix']."log_items l WHERE i.id=".$_POST['id']." AND l.id_item = i.id AND l.action = 'Création'";
        $res = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
        $data_item = mysql_fetch_array($res);
        
        //vérifier que l'utilisateur actuel a le droit de consulter ce mdp
        $access = explode(';',$data_item['id_tree']);
        $restriction_active = true;
        $restricted_to = explode(';',$data_item['restricted_to']);
        if ( in_array($_SESSION['user_id'],$restricted_to) ) $restriction_active = false;
        if ( empty($data_item['restricted_to']) ) $restriction_active = false;
        
        if ( ( in_array($access[0],$_SESSION['groupes_visibles']) OR $_SESSION['is_admin'] == 1 ) 
            AND  ( $data_item['perso']==0 OR ($data_item['perso']==1 AND $data_item['id_user'] == $_SESSION['user_id'] ) )  
            AND $restriction_active == false
        ){    
            $historique = "";
            $res1 = mysql_query("SELECT * FROM ".$k['prefix']."log_items WHERE id_item=".$_POST['id']);
            while ( $data1 = mysql_fetch_array($res1) ){
                $res2 = mysql_query("SELECT login FROM ".$k['prefix']."users WHERE id=".$data1['id_user']);
                $data2 = mysql_fetch_row($res2);
            
                if ( empty($historique) ) $historique = date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison'];
                else
                 $historique .= "<br />".date("d/m/Y H:i:s",$data1['date'])." - ". $data2[0] ." - ".$data1['action']." - ".$data1['raison']; 
            }
            
            //recup liste de restriction
            $liste = explode(";",$data_item['restricted_to']);
            $liste_restriction = "";
            foreach($liste as $elem){
                if ( !empty($elem) ){
                    $data2 = mysql_fetch_row(mysql_query("SELECT login FROM ".$k['prefix']."users WHERE id=".$elem));
                    $liste_restriction .= $data2[0].";";
                }
            }

            echo 'document.getElementById(\'item_details_nok\').style.display="none";';
            echo 'document.getElementById(\'item_details_ok\').style.display = "";';
            echo 'document.getElementById(\'fileclass'.$_POST['id'].'\').className = "fileselected";';
            
            echo 'document.getElementById(\'id_label\').innerHTML = "'.$data_item['label'].'";';
            echo 'document.getElementById(\'id_pw\').innerHTML = \''.addslashes(decrypt($data_item['pw'])).'\';';
            if ( substr($data_item['url'],0,7) == "http://" || substr($data_item['url'],0,8) == "https://" ) $lien = $data_item['url'];
            else $lien = "http://".$data_item['url'];
            echo 'document.getElementById(\'id_url\').innerHTML = "'.$data_item['url'].'',!empty($data_item['url'])?'&nbsp;<a href=\''. $lien.'\' target=\'_blank\'><img src=\'includes/images/arrow_skip.png\' style=\'border:0px;\' title=\'Ouvrir la page\'></a>':'','";';
            echo 'document.getElementById(\'id_desc\').innerHTML = "'.str_replace('\n','<br>',(mysql_real_escape_string($data_item['description']))).'";';
            echo 'document.getElementById(\'id_login\').innerHTML = "'.$data_item['login'].'";';
            if ( $data_item['perso'] == 0 ) $perso = "Non"; else $perso = "Oui";
            echo 'document.getElementById(\'id_info\').innerHTML = "'.$historique.'";';
            echo 'document.getElementById(\'id_restricted_to\').innerHTML = "'.$liste_restriction.'";';
            
            //renseigner les champs masqués
            echo 'document.getElementById(\'hid_label\').value = "'.$data_item['label'].'";';
            echo 'document.getElementById(\'hid_pw\').value = \''.addslashes(decrypt($data_item['pw'])).'\';';
            echo 'document.getElementById(\'hid_url\').value = "'.$data_item['url'].'";';
            echo 'document.getElementById(\'hid_desc\').value = "'.mysql_real_escape_string($data_item['description']).'";';
            echo 'document.getElementById(\'hid_login\').value = "'.$data_item['login'].'";';
            echo 'document.getElementById(\'id_categorie\').value = "'.$data_item['id_tree'].'";';
            echo 'document.getElementById(\'id_item\').value = "'.$data_item['id'].'";';
            echo 'document.getElementById(\'hid_restricted_to\').value = "'.$data_item['restricted_to'].'";';
            
            if ( decrypt($data_item['pw']) != "" ) echo 'document.getElementById(\'pw_clipboard\').style.display = "inline";';
            else echo 'document.getElementById(\'pw_clipboard\').style.display = "none";';
            if ( $data_item['login'] != "" ) echo 'document.getElementById(\'login_clipboard\').style.display = "inline";';
            else echo 'document.getElementById(\'login_clipboard\').style.display = "none";';
            
            //afficher l'icone pour suppression si user = createur
            if ($data_item['id_user'] == $_SESSION['user_id'] || $_SESSION['is_admin'] == 1 )
                echo '$(\'#contextMenuContent\').enableContextMenuItems(\'#del_item,#edit_item\');';//echo 'document.getElementById(\'icon_del_mdp\').style.display = "";';
            else
                echo '$("#contextMenuContent").disableContextMenuItems("#del_item,#edit_item");';//echo 'document.getElementById(\'icon_del_mdp\').style.display = "none";';
        }else{
            echo 'document.getElementById(\'item_details_nok\').style.display="";';
            echo 'document.getElementById(\'item_details_ok\').style.display = "none";';
            if ( $_SESSION['is_admin'] != 1 )
            echo '$("#contextMenuContent").disableContextMenuItems("#del_item,#edit_item");';//echo 'document.getElementById(\'icon_del_mdp\').style.display = "none";';
        }        
    break;
    
    case "pw_generate":
        $size = $_POST['size'];
        $letters = "abcdefghijklmnopqrstuvwxyz";
        $key = "";
        if ( $_POST['num'] == "true" ) $letters .= "0123456789";
        if ( $_POST['maj'] == "true" ) $letters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ( $_POST['symb'] == "true" ) $letters .= "_-&#+§?+@";
        srand(time());
        for ($i=0;$i<$size;$i++)
        {
            $key.=substr($letters,(rand()%(strlen($letters))),1);
        }
        echo 'document.getElementById(\''.$_POST['elem'].'pw1\').value = "'.$key.'";';
        echo 'runPassword(document.getElementById(\''.$_POST['elem'].'pw1\').value, \''.$_POST['elem'].'mypassword\');';
    break;
    
    case "del_item":
        $sql = "UPDATE ".$k['prefix']."items SET inactif = '1' WHERE id = ".$_POST['id'];
        mysql_query($sql) or die($sql.'  =>  '.mysql_error());
        //log
        mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Suppression','')");
        //recharger
        echo 'window.location.href = "index.php?page=items&group='.$_POST['groupe'].'";';
    break;
    
    case "new_rep":        
        $res = mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."nested_tree WHERE title = '".mysql_real_escape_string(stripslashes(($_POST['title'])))."'");
        $data = mysql_fetch_row($res);
        if ( $data[0] != 0 ) 
            echo 'alert(\'Ce groupe existe déjà !\');';
        else {
            $sql = "INSERT INTO ".$k['prefix']."nested_tree VALUES (NULL,'".$_POST['groupe']."','".mysql_real_escape_string(stripslashes(($_POST['title'])))."','','','','0','0')";
            mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            $new_id=mysql_insert_id();
            
            //ajouter complexité
            mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('complex','".$new_id."','".$_POST['complexite']."')");
            
            require_once('NestedTree.class.php');
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree->rebuild();
            
            //Rafraichir les droits de l'utilsiateur            
            IdentificationDesDroits($_SESSION['groupes_visibles'].';'.$new_id,$_SESSION['groupes_interdits'],$_SESSION['is_admin'],$_SESSION['fonction_id'],true);
            
            //lancer la page
            echo 'window.location.href = "index.php?page=items";';
        }
        
    break;
    
    case "update_rep":    
        $sql = "UPDATE ".$k['prefix']."nested_tree SET title = '".mysql_real_escape_string(stripslashes(($_POST['title'])))."' WHERE id='".$_POST['groupe']."'";
        mysql_query($sql) or die($sql.'  =>  '.mysql_error());
        
        //editer complexité
        mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['complexite']."' WHERE intitule = '".$_POST['groupe']."' AND type = 'complex'");
        
        require_once('NestedTree.class.php');
        $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
        $tree->rebuild();
        
        echo 'window.location.href = "index.php?page=items";';
    break;
    
    case "delete_rep":
        $tmp = explode(';',$_POST['groupe']);
        if ( count($tmp) == 1 ){            
            //supprimer le groupe
            mysql_query("DELETE FROM ".$k['prefix']."nested_tree WHERE id='".$tmp[0]."'");
            
            //supprimer les catégories associées
           $res = mysql_query("SELECT id FROM ".$k['prefix']."nested_tree WHERE parent_id=".$tmp[0]);
           while ( $data = mysql_fetch_row($res) ){
                mysql_query("DELETE FROM ".$k['prefix']."nested_tree WHERE id='".$data[0]."'");
                //suprimer toutes les entrées associées
                $res = mysql_query("SELECT id FROM ".$k['prefix']."items WHERE id_tree='".$tmp[0].";".$data[0]."'");
                while ( $data = mysql_fetch_row($res) ){
                    $sql = "UPDATE ".$k['prefix']."items SET inactif = '1' WHERE id = ".$data[0];
                    mysql_query($sql) or die($sql.'  =>  '.mysql_error());
                    //log
                    mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$data[0]."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Suppression','')");
                }
            } 
        }else{
            mysql_query("DELETE FROM ".$k['prefix']."categories WHERE id='".$tmp[1]."'");
            //suprimer toutes les entrées associées
            $res = mysql_query("SELECT id FROM ".$k['prefix']."items WHERE id_categorie='".$tmp[0].";".$tmp[1]."'");
            while ( $data = mysql_fetch_row($res) ){
                $sql = "UPDATE ".$k['prefix']."items SET inactif = '1' WHERE id = ".$data[0];
                    mysql_query($sql) or die($sql.'  =>  '.mysql_error());
                    //log
                    mysql_query("INSERT INTO ".$k['prefix']." VALUES ('".$data[0]."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','Suppression','')");
            }
        }
        echo 'window.location.href = "index.php?page=items";';
    break;
        
        
    case 'save_position':
        require_once ("NestedTree.class.php");
        mysql_query("UPDATE ".$k['prefix']."nested_tree SET parent_id = '".$_POST['destination']."' WHERE id='".$_POST['source']."'");
       // echo "UPDATE ".$k['prefix']."nested_tree SET parent_id = '".$_POST['destination']."' WHERE id='".$_POST['source']."'";
        $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
        $tree->rebuild();
    break;
    
    
    case 'lister_items_groupe':
        //préparer l'arborescence
        require_once ("NestedTree.class.php");
        $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
        $arbo = $tree->getPath($_POST['id'], true);
        $arbo_html = "";
        foreach($arbo as $elem){
            $arbo_html .= $elem->title." > ";
        }
        
        //lister les Items
        $html = '<ul class="liste_items">';
        $res1 = mysql_query("SELECT * FROM ".$k['prefix']."items WHERE inactif = 0 AND id_tree=".$_POST['id']." ORDER BY label");
        while ( $data1 = mysql_fetch_array($res1) ){
            if ( $data1['perso'] == 1 || !empty($data1['restricted_to']) ) $perso = '<img src="includes/images/tag__exclamation.png">';
            else $perso = '<img src="includes/images/tag.png">';
            $html .= '<li class="item">'.$perso.'&nbsp;<a id="fileclass'.$data1['id'].'" class="file" onclick="AfficherDetailsItem(\''.$data1['id'].'\')">'.$data1['label'].'</a></li>';            
        }
        $html .= '</ul>';
        echo 'document.getElementById(\'liste_des_items\').style.display = "";';
        echo 'document.getElementById(\'liste_des_items\').innerHTML = "'.addslashes($html).'";';
        echo 'document.getElementById(\'arborescence\').innerHTML = "'.addslashes(substr($arbo_html,0,strlen($arbo_html)-3)).'";';
        echo 'document.getElementById(\'selected_items\').value = "";';
        echo 'document.getElementById(\'hid_cat\').value = "'.$_POST['id'].'";';
        
        RecupDroitCreationSansComplexite($_POST['id']);
    break;
    
    
    case "recup_complex":
        $res = mysql_query("SELECT valeur FROM ".$k['prefix']."misc WHERE type='complex' AND intitule = '".$_POST['groupe']."'");
        $data = mysql_fetch_row($res);
        echo 'document.getElementById("complexite_groupe").value = "'.$data[0].'";'; 
        //aficher la complexité attendue
        if ( $_POST['edit']==1 ) $div = "edit_complex_attendue"; else $div = "complex_attendue";
        echo 'document.getElementById("'.$div.'").innerHTML = "<b>'.$mdp_complexite[$data[0]][1].'</b>";';
        //afficher la visibilité
        $visibilite = "";
        $res = mysql_query("SELECT valeur FROM ".$k['prefix']."misc WHERE type='visibilite' AND intitule = '".$_POST['groupe']."'");
        $data = mysql_fetch_row($res);
        $tab = explode(';',$data[0]);
        foreach($tab as $elem){
            //rechercher l'itnitulé du groupe
            $data = mysql_fetch_row(mysql_query("SELECT title FROM ".$k['prefix']."functions WHERE id = '".$elem."'"));
            if ( !empty($data[0]) ){
                if ( empty($visibilite) ) $visibilite = $data[0];
                else $visibilite .= " - ".$data[0];
            }
        }
        if ( $_POST['edit']==1 ) $div = "edit_afficher_visibilite"; else $div = "afficher_visibilite";
        echo 'document.getElementById("'.$div.'").innerHTML = "<img src=\'includes/images/users.png\'>&nbsp;<b>'.$visibilite.'</b>";';
        
        RecupDroitCreationSansComplexite($_POST['groupe']);
    break;
    
}
//masquer image de chargement
echo 'document.getElementById(\'div_loading\').style.display = "none";';


function RecupDroitCreationSansComplexite($groupe){
    //savoir le groupe autorise la création d'item sans que le niveau de complexité soit atteinte
    $data = mysql_fetch_row(mysql_query("SELECT bloquer_creation,bloquer_modification FROM ".$k['prefix']."nested_tree WHERE id = '".$groupe."'"));
    echo 'document.getElementById("bloquer_creation_complexite").value = "'.$data[0].'";';
    echo 'document.getElementById("bloquer_modification_complexite").value = "'.$data[1].'";';
}

function RechargerArbo(){
    require_once('NestedTree.class.php');
    $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
    $tree->rebuild();
    $tst = $tree->getDescendants();
    
    
    $nouvel_arbo = "";
    $tab_items = array();
    $cpt_total = 0;
    $folder_cpt = 1;
    $prev_level = 1;
    $nouvel_arbo = '<ul id="browser" class="filetree">';
    foreach($tst as $t){
        //S'assurer que l'utilisateur ne voit que ce qu'il peut voir
        if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
            //récupérer les ITEMS liés à ce groupe
                $items = array();
                $text_items = "";
                $res = mysql_query("SELECT * FROM ".$k['prefix']."items where inactif=0 AND id_tree = ".$t->id);
                while($data=mysql_fetch_array($res)){
                    if ( $data['id']==$_GET['id'] ) $class_file = "fileselected"; else $class_file = "file";
                    if ( empty($text_items) ){
                        //$text_items = '<li id="file'.$data['id'].'"><a class="'.$class_file.'" onclick="window.location.href = \'index.php?page=mdp&id='.$data['id'].'\'">'.$data['label'].'</a></li>';
                        $text_items = '<li id="file'.$data['id'].'"><a class="'.$class_file.'" onclick="AfficherDetailsItem(\''.$data['id'].'\')">'.addslashes($data['label']).'</a></li>';
                    }else{
                       //$text_items .= '<li id="file'.$data['id'].'"><a class="'.$class_file.'" onclick="window.location.href = \'index.php?page=mdp&id='.$data['id'].'\'">'.$data['label'].'</a></li>';
                       $text_items .= '<li id="file'.$data['id'].'"><a class="'.$class_file.'" onclick="AfficherDetailsItem(\''.$data['id'].'\')">'.addslashes($data['label']).'</a></li>';
                    }
                } 
            
            //Construire l'arborescence
            if ( $cpt_total == 0 ) {
                 $nouvel_arbo .= '<li id="'.$t->id.'"><span class="folder">'.$t->title.'</span>';
                 //sauver les items de ce groupe
                 $tab_items[$t->nlevel] = $text_items;
            }else{                                           
                //Construire l'arborescence
                if ( $prev_level < $t->nlevel ){
                    $nouvel_arbo .= '<ul id="folder'.$folder_cpt.'">';
                    $nouvel_arbo .= '<li id="'.$t->id.'"><span class="folder">'.$t->title.'</span>';
                    
                    //sauver les items de ce groupe
                    $tab_items[$t->nlevel] = $text_items;
                    
                    $folder_cpt++;
                }else if ( $prev_level == $t->nlevel ){
                    //écrire les items du groupe précédent
                    if ( !empty($tab_items[$t->nlevel]) ){
                            $nouvel_arbo .= '<ul>'.$tab_items[$t->nlevel].'</ul>';
                            $tab_items[$t->nlevel] = "";
                        }
                    //ecrire la structure
                    $nouvel_arbo .= '</li><li id="'.$t->id.'"><span class="folder">'.$t->title.'</span>';
                    //écrire les items du groupe en cours
                    if (!empty($text_items) )
                       $nouvel_arbo .= '<ul>'.$text_items.'</ul>';
                }else{
                    //Afficher les items de la dernièeres cat s'ils existent
                    if ( !empty($tab_items[$prev_level]) ){
                        $nouvel_arbo .= '<ul>'.$tab_items[$prev_level].'</ul>';
                        $tab_items[$prev_level] = "";
                    }
                    for($x=$t->nlevel;$x<$prev_level;$x++){
                        //afficher des items
                        if ( !empty($tab_items[$x]) ){
                            echo $tab_items[$x];
                            $tab_items[$x] = "";
                        }
                        $nouvel_arbo .= "</li></ul>";
                    }
                    $nouvel_arbo .=  '</li><li id="'.$t->id.'"><span class="folder">'.$t->title.'</span>';
                    $tab_items[$t->nlevel] = $text_items;
                    $folder_cpt++;
                }
                $prev_level = $t->nlevel;
                $cpt++;
            }
            $cpt_total++;
        }
    }
    //Afficher les items de la dernièere  cat s'ils existent
    if ( !empty($tab_items[$prev_level]) ){
        $nouvel_arbo .= '<ul>'.$tab_items[$prev_level].'</ul>';
        $tab_items[$prev_level] = "";
    }

    //clore toutes les balises de l'arbo
    for($x=1;$x<$prev_level;$x++)
                    $nouvel_arbo .=  "</li></ul>";
    $nouvel_arbo .=  '</li></ul>';   
    
    echo 'document.getElementById(\'sidebar\').innerHTML = "'.addslashes($nouvel_arbo).'";';
}
?>
