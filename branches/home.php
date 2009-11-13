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
            <div style="line-height: 24px;margin-top:10px;">
            <span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;">&nbsp;</span>
            '.$txt['index_welcome'].' <b>'.$_SESSION['login'].'</b><br />';
            //Vérifier la validité du mdp
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
            }else{
                if (!empty($_SESSION['derniere_connexion']))
                echo '
                   <span class="ui-icon ui-icon-calendar" style="float: left; margin-right: .3em;">&nbsp;</span>
                   '.$txt['index_last_seen'].' '.date("d/m/Y",$_SESSION['derniere_connexion']).$txt['at'].date("H:i:s",$_SESSION['derniere_connexion']).'.
                <br />
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
            }
            echo '
            </div>
            </form>';

  
?>