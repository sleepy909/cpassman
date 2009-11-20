<?php
####################################################################################################
## File : home.php
## Author : Nils Laumaillé
## Description : Home page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
echo '
            <form method="post" name="form_pw" action="">
            <div style="line-height: 24px;margin-top:10px;min-height:220px;">
            <span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;">&nbsp;</span>
            '.$txt['index_welcome'].' <b>'.$_SESSION['login'].'</b><br />';
            //Check if password is valid
            if ( empty($_SESSION['last_pw_change']) OR $_SESSION['validite_pw'] == false ){
                echo '<b>'.$txt['index_change_pw'].'</b>
                <div style="margin:5px;border:1px solid #FF0000;background-color:#FFFFC0;padding:4px;width:300px;text-align:center;"  class="ui-state-highlight ui-corner-all" id="div_changer_mdp">
                    <table>
                        <tr><td>'.$txt['index_new_pw'].' :</td><td><input type="password" size="10" name="new_pw" id="new_pw" onkeyup="runPassword(this.value, \'mypassword\', \''.$_SESSION['user_language'].'\');" />
                            <div style="width: 100px; display:inline;"> 
                                <div id="mypassword_text" style="font-size: 10px;"></div>
                                <div id="mypassword_bar" style="font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></div> 
                            </div>
                            </td>
                        </tr>
                        <tr><td>'.$txt['index_change_pw_confirmation'].' :</td><td><input type="password" size="10" name="new_pw2" id="new_pw2" /></td></tr>
                        <tr><td colspan="2"><input type="button" onClick="ChangerMdp(\''.$_SESSION['last_pw'].'\')" value="'.$txt['index_change_pw_button'].'" /></td></tr>
                    </table>                    
                </div>';
            }elseif ( !empty($_SESSION['derniere_connexion']) ){                 
                //Last items created block
                echo '
                <div style="position:relative;float:right;margin-top:-25px;padding:4px;width:250px;" class="ui-state-highlight ui-corner-all">
                    <span class="ui-icon ui-icon-comment" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <span style="font-weight:bold;margin-bottom:10px;">'.$txt['block_last_created'].'</span><br />';
                    $res = query("SELECT 
                    i.label AS label, i.id AS id, i.id_tree AS id_tree  
                    FROM ".$k['prefix']."log_items l
                    INNER JOIN ".$k['prefix']."items i                 
                    WHERE l.action = 'Creation' 
                        AND l.id_item = i.id 
                        AND i.id_tree IN (".$_SESSION['groupes_visibles_list'].") 
                    ORDER BY l.date DESC
                    LIMIT 0,5
                    ");
                    while( $data = mysql_fetch_array($res) )
                        echo '<span class="ui-icon ui-icon-triangle-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>
                        <a href="#" onClick="javascript:window.location.href =\'index.php?page=items&amp;group='.$data['id_tree'].'&amp;id='.$data['id'].'\';" style="cursor:pointer;">'.$data['label'].'</a><br />';
                    echo '
                </div>';
                
                //some informations               
                echo '
                   <span class="ui-icon ui-icon-calendar" style="float: left; margin-right: .3em;">&nbsp;</span>
                   '.$txt['index_last_seen'].' '.date("d/m/Y",$_SESSION['derniere_connexion']).$txt['at'].date("H:i:s",$_SESSION['derniere_connexion']).'.
                <br />';
                
                //change the password
                echo '
                <div>
                    <span class="ui-icon ui-icon-key" style="float: left; margin-right: .3em;">&nbsp;</span>
                '.$txt['index_last_pw_change'].' '.date("d/m/Y",$_SESSION['last_pw_change']).'. '.$txt['index_pw_expiration'].' '.$nb_jours_avant_expiration_du_mdp.' '.$txt['days'].'.<br />
                    <div onclick="ouvrir_div(\'div_changer_mdp\')" style="cursor:pointer"><u>'.$txt['index_change_pw'].'</u></div>
                    <div id="div_changer_mdp" style="display:none;margin:5px;padding:4px;width:300px;" class="ui-state-highlight ui-corner-all">
                        <label for="new_pw" class="form_label">'.$txt['index_new_pw'].' :</label>
                        <input type="password" size="15" name="new_pw" id="new_pw" onkeyup="runPassword(this.value, \'mypassword\');" />
                        
                        <div style="width: 100px; display:inline;" id="div_tmp"> 
                            <div id="mypassword_text" style="font-size: 10px;"></div>
                            <div id="mypassword_bar" style="font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></div> 
                        </div>
                        
                        <label for="new_pw2" class="form_label">'.$txt['index_change_pw_confirmation'].' :</label>   
                        <input type="password" size="15" name="new_pw2" id="new_pw2" />
                        <br /><br />
                        <input type="button" onClick="ChangerMdp(\''.$_SESSION['last_pw'].'\')" value="'.$txt['index_change_pw_button'].'" />                 
                    </div>
                </div>';
            }else{
                
            }
            echo '
            </div>
            </form>';

  
?>