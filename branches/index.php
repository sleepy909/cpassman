<?php
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
####################################################################################################
## File : index.php
## Author : Nils Laumaillé
## Description : main page
## 
## DON'T CHANGE !!!
## 
####################################################################################################

//Manage Language
if ( !isset($_SESSION['user_language']) ){
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
    else $_SESSION['user_language'] = "english";
}else{
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
}
require_once('includes/language/'.$_SESSION['user_language'].'.php'); 

//Include files
require_once('includes/settings.php');
require_once('sources/main.functions.php');  

//Logout
if ( (isset($_POST['menu_action']) && $_POST['menu_action'] == "deconnexion") || (isset($_GET['session']) && $_GET['session'] == "expiree") ){
    // Update table by deleting ID
    $sql = "UPDATE ".$k['prefix']."users SET key_tempo='' WHERE id=".$_SESSION['user_id'];
    $query = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
    
    // erase session table
    $_SESSION = array();

    // Kill session
    session_destroy();

    // REDIRECTION PAGE ERREUR
    header("Location:index.php");
    exit;
}

//Check PW validity
if ( isset($_SESSION['last_pw_change']) ){
    $nb_jours_avant_expiration_du_mdp = $k['user_password_limit'] - round( (mktime(0,0,0,date('m'),date('d'),date('y'))-$_SESSION['last_pw_change'])/(24*60*60) );
    if ( $nb_jours_avant_expiration_du_mdp <= 0 )
        $_SESSION['validite_pw'] = false;
    else
        $_SESSION['validite_pw'] = true;
}else
    $_SESSION['validite_pw'] = false;
    
//Check if session exists or is okay
if ( !empty($_SESSION['fin_session']) ) {
    $res = mysql_query("SELECT key_tempo FROM ".$k['prefix']."users WHERE id=".$_SESSION['user_id']);
    $data_session = mysql_fetch_row($res);
}else $data_session[0] = "";

if ( isset($_SESSION['user_id']) && ( empty($_SESSION['fin_session']) || $_SESSION['fin_session'] < time() || empty($_SESSION['cle_session']) || $_SESSION['cle_session'] != $data_session[0] ) ){
    // Update table by deleting ID
    $sql = "UPDATE ".$k['prefix']."users SET key_tempo='' WHERE id=".$_SESSION['user_id'];
    $query = mysql_query($sql);
    
    // erase session table
    $_SESSION = array();
    
    //Redirection
    header("Location:index.php");
}  

// Load links, css and javascripts
include("load.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=<?php echo $k['charset'];?>" />
        <title>Collaborative Passwords Manager</title>
        <?php
        echo $html_headers;
        ?>
    </head>
    
    <body onload="countdown()">
    <?php
    ## HEADER ##
    echo '
    <div id="top">
        <div id="logo"><img src="includes/images/logo.png" alt="" /></div>
        
        <div id="title">'.$k['tool_name'].'</div>',
        
        isset($_SESSION['login']) ? '
            <div style="float:left;margin-left:20px; margin-top:3px;">
                <a tabindex="0" href="#menu_content" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="cpm_menu"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span>Menu</a>
            </div>
            <div style="float:left;font-size:12px;margin-left:20px;margin-top:6px;">
                <b>'.$_SESSION['login'].'</b> - '.$txt['index_expiration_in'].' <div style="display:inline;" id="countdown"></div>
                &nbsp;<img src="includes/images/clock__plus.png" style="cursor:pointer;" onclick="AugmenterSession()" title="'.$txt['index_add_one_hour'].'" />
            </div>' : '','
                   
        <div style="float:right;margin-left:30px;margin:auto 0 auto 0;">
            <div style="margin-bottom:2px;"><img src="includes/images/flag_fr.png" style="cursor:pointer;" onclick="ChangeLanguage(\'french\')" alt="" /></div>
            <div style="margin-bottom:2px;"><img src="includes/images/flag_us.png" style="cursor:pointer;" onclick="ChangeLanguage(\'english\')" alt="" /></div>
            <div style=""><img src="includes/images/flag_es.png" style="cursor:pointer;" onclick="ChangeLanguage(\'spanish\')" alt="" /></div>
        </div>
    </div>';
    
    ## MENU ##
    echo '    
    <div id="menu_content" style="display:none;">
        <ul>
            <li><a href="#" onclick="MenuAction(\'\');">'.$txt['home'].'</a></li>';
            if ($_SESSION['validite_pw'] == true ) {
                echo '
                <li><a href="#" onclick="MenuAction(\'items\');">'.$txt['pw'].'</a>
                    <ul>
                        <li><a href="#" onclick="MenuAction(\'items\');">'.$txt['show'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'find\');">'.$txt['find'].'</a></li>
                    </ul>
                </li>';
                if ($_SESSION['user_admin'] == 1 || $_SESSION['user_gestionnaire'] == 1) 
                    echo '
                <li><a href="#" onclick="MenuAction(\'administration\');" class="accessible">'.$txt['admin'].'</a>
                    <ul>
                        <li><a href="#" onclick="MenuAction(\'administration\');">'.$txt['admin_main'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_settings\');">'.$txt['admin_settings'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_groups\');">'.$txt['admin_groups'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_functions\');">'.$txt['admin_functions'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_users\');">'.$txt['admin_users'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_views\');">'.$txt['admin_views'].'</a></li>
                    </ul>
                </li>';
                //add Favourites
                if ( isset($_SESSION['favourites']) && count($_SESSION['favourites']) > 0 && $_SESSION['enable_favourites'] == 1 ){
                    echo '
                <li><a href="#" onclick="MenuAction(\'items\');">'.$txt['my_favourites'].'</a>
                    <ul>';
                        foreach($_SESSION['favourites_tab'] as $fav){
                            if ( !empty($fav) )
                                echo 
                        '<li><a href="#" onclick="javascript:window.location.href = \''.$fav['url'].'\'">'.$fav['label'].'</a></li>';
                        }
                        echo '
                    </ul>
                </li>';
                }
            }
            echo '
            <li>
                <a href="#" onclick="MenuAction(\'deconnexion\');">'.$txt['disconnect'].'</a>
            </li>
        </ul>
    </div>';
    
    ## LAST SEEN ##
    echo '
    <div style="cursor:pointer;float:right;margin:-3px -20px;" onclick="ouvrir_div(\'div_last_items\')" id="icon_last_items">
        <img src="includes/images/tag_blue.png" alt="" title="'.$txt['last_items_icon_title'].'" />
    </div>
    <div style="display:none;" id="div_last_items" class="ui-corner-bottom">'.$txt['last_items_title'].":&nbsp;";
        if ( isset($_SESSION['latest_items_tab']) ){
            foreach($_SESSION['latest_items_tab'] as $item){
                if ( !empty($item) )
                    echo '
                    <span style="cursor:pointer;" onclick="javascript:window.location.href = \''.$item['url'].'\'"><img src="includes/images/tag_small.png" alt="" />'.$item['label'].'</span>&nbsp;';
            }
        }else echo $txt['no_last_items'];
    echo '
    </div>';
    
    ## MAIN PAGE ##
    echo '
    <form action="" name="temp_form" method="post">
        <input type="text" style="display:none;" id="temps_restant" value="', isset($_SESSION['fin_session']) ? $_SESSION['fin_session'] : '', '" />
        <input type="hidden" name="language" id="language" value="" />
    </form>
    <div id="main">';
    //Main page
    if ( isset($_SESSION['autoriser']) && $_SESSION['autoriser'] == true ){
        //Show menu
        echo '    
        <form method="post" name="main_form" action="">
            <input type="hidden" name="menu_action" id="menu_action" value="" />
            <input type="hidden" name="changer_pw" id="changer_pw" value="" />    
            <input type="hidden" name="form_user_id" id="form_user_id" value="'.$_SESSION['user_id'].'" />
            <input type="hidden" name="is_admin" id="is_admin" value="'.$_SESSION['is_admin'].'" /> 
        </form>'; 
    }
    
    //Display pages
    if ( isset($_SESSION['validite_pw']) && $_SESSION['validite_pw'] == true && !empty($_GET['page']) ) {
        if ( $_GET['page'] == "items"){
            //SHow page with Items
            include("items.php");
        }else if ( $_GET['page'] == "find"){
            //Show page for items findind
            include("find.php");
        }else if ( in_array($_GET['page'],array_keys($mng_pages)) ){
            //Define if user is allowed to see management pages
            if ($_SESSION['user_admin'] == 1 || $_SESSION['user_gestionnaire'] == 1) 
                include($mng_pages[$_GET['page']]);
            else {
                $_SESSION['error'] = "1000";    //not allowed page
                include("error.php");
            }
        }else{
            $_SESSION['error'] = "1001";    //page don't exists
            include("error.php");
        }
    }else if ( empty($_SESSION['user_id']) ){
        //SESSION FINISHED => RECONNECTION ASKED
        echo '
            <div style="text-align:center;margin-top:30px;margin-bottom:20px;padding:10px;" class="ui-state-error ui-corner-all">
                <b>'.$txt['index_session_expired'].'</b>
            </div>
            <form method="post" name="form_identify" action="">
                <div style="width:300px; margin-left:auto; margin-right:auto;margin-bottom:50px;padding:25px;" class="ui-state-highlight ui-corner-all">
                    <div style="text-align:center;font-weight:bold;margin-bottom:20px;">
                        '.$txt['index_get_identified'].'
                        &nbsp;<img id="ajax_loader_connexion" style="display:none;" src="includes/images/ajax-loader.gif" alt="" />
                    </div>
                    <div id="erreur_connexion" style="color:red;display:none;text-align:center;margin:5px;"></div>
                    
                    <label for="login" class="form_label">'.$txt['index_login'].' : </label>
                    <input type="text" size="10" id="login" name="login" />
                    <br />
                    
                    <label for="pw" class="form_label">'.$txt['index_password'].' : </label>
                    <input type="password" size="10" id="pw" name="pw" onkeypress="if (event.keyCode == 13) identifyUser()" />
                    <br />
                    
                    <label for="duree_session" class="form_label">'.$txt['index_session_duration'].' : </label>
                    <input type="text" size="4" id="duree_session" name="duree_session" value="60" onkeypress="if (event.keyCode == 13) identifyUser()" /> minutes
                    <br />
                    
                    <div style="text-align:center;margin-top:15px;">
                        <input type="button" id="but_identify_user" onclick="identifyUser()" style="padding:3px;cursor:pointer;" class="ui-state-default ui-corner-all" value="'.$txt['index_identify_button'].'" />
                    </div>
                </div>
            </form>
            <script type="text/javascript">
                document.getElementById("login").focus();
            </script>';
    }else{
        //PAGE BY DEFAULT
        include("home.php");
    }
    echo '
    </div>';
    
    //FOOTER 
    ## DON'T MODIFY THE FOOTER ###
    echo '
    <div id="footer">
        <div style="width:500px;">
            cPassMan '.$k['version'].' © copyright 2009-2010
        </div>
        <div style="float:right;margin-top:-15px;">
            <!--<a href="http://sourceforge.net/projects/communitypasswo" target="_blank"><img src="http://sflogo.sourceforge.net/sflogo.php?group_id=280505&amp;type=10" width="80" height="15" alt="Get Collaborative Passwords Manager at SourceForge.net. Fast, secure and Free Open Source software downloads" /></a>-->
            <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/" title="Collaborative Passwords Manager by Nils Laumaill&#233; is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 2.0 France License"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/2.0/fr/80x15.png" /></a>
        </div>
    </div>';
    

    //PAGE LOADING 
    echo '
    <div id="div_loading" style="display:none;">
        <div style="border:2px solid #969696; padding:5px; background-color:#B8C2E7;">
            <img src="includes/images/ajax-loader_2.gif" alt="" />
        </div>
    </div>';
    
    //ENDING SESSION WARNING
    echo '
    <div id="div_fin_session" style="display:none;">
        <div style="padding:10px;text-align:center;">
            <img src="includes/images/alarm-clock.png" alt="" /> <b> '.$txt['index_session_ending'].'</b>
        </div>
    </div>
    ';
    ?>
    </body>
</html>
