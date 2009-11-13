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
session_start();

//Manage Language
if ( !isset($_SESSION['user_language']) ){
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
    else $_SESSION['user_language'] = "english";
}else{
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
}
include('includes/language/'.$_SESSION['user_language'].'.php'); 

//Include files
include('includes/settings.php');
include('sources/main.functions.php');  

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
    
    //$_SESSION['autoriser'] = "";
    header("Location:index.php");
}  
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Collaborative Passwords Manager</title>
        <link rel="stylesheet" href="includes/css/passman.css" type="text/css" />
        <script type="text/javascript" src="includes/js/functions.js"></script> 
        
        <script type="text/javascript" src="includes/jquery-ui/js/jquery-<?php echo $k['jquery-version'];?>.min.js"></script>
        <script type="text/javascript" src="includes/jquery-ui/js/jquery-ui-<?php echo $k['jquery-ui-version'];?>.custom.min.js"></script>
        <link rel="stylesheet" href="includes/jquery-ui/css/<?php echo $k['jquery-ui-theme'];?>/jquery-ui-<?php echo $k['jquery-ui-version'];?>.custom.css" type="text/css" /> 
        
        <script language="JavaScript" type="text/javascript" src="includes/js/jquery.tooltip.js"></script>
        <link rel="stylesheet" href="includes/css/jquery.tooltip.css" type="text/css" />
        
        <script language="JavaScript" type="text/javascript" src="includes/js/pwd_strength.js"></script>        
        
        <script type="text/javascript" src="includes/js/fg-menu/fg.menu.js"></script>    
        <link type="text/css" href="includes/js/fg-menu/fg.menu.css" media="screen" rel="stylesheet" />
        
        <?php
        //For ITEMS page, load specific CSS files for treeview
        if ( isset($_GET['page']) && $_GET['page'] == "items")
            echo '
                <link rel="stylesheet" type="text/css" href="includes/css/jquery.treeview.css" />
                <link rel="stylesheet" type="text/css" href="includes/css/mdp.css" />
                <link href="includes/js/jquery.contextMenu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />';
        ?>
        
        <script type="text/javascript">
            //deconnexion
            function MenuAction(val){
                if ( val == "deconnexion" ) {
                    document.getElementById('menu_action').value = val;
                    document.main_form.submit();
                }
                else {
                    if ( val == "") document.location.href="index.php";
                    else document.location.href="index.php?page="+val;
                }                
            }
            
            //Changer le MDP
            function ChangerMdp(old_pw){
                if ( document.getElementById('new_pw').value != "" && document.getElementById('new_pw').value == document.getElementById('new_pw2').value ){
                    
                    var data = "type=change_pw&new_pw="+document.getElementById('new_pw').value+"&old_pw="+old_pw;
                    httpRequest("sources/main.queries.php",data);
                }else{
                    alert('Les mots de passe doivent etre identiques !');
                }
            }
            
            //Identifier l'utilisateur
            function identifyUser(){
                if ( document.getElementById('login').value != "" && document.getElementById('pw').value != "" ){
                    var data = "type=identify_user"+
                                "&login="+document.getElementById('login').value+
                                "&pw="+document.getElementById('pw').value+
                                "&duree_session="+document.getElementById('duree_session').value+
                                "&hauteur_ecran="+window.innerHeight;
                    httpRequest("sources/main.queries.php",data);
                    
                    
                }else{
                    alert("Vous devez renseigner votre login et mot de passe !");   
                }
            }
            
            function ouvrir_div(div){
                $('#'+div).toggle("slow");
            }
            
            $(function() {
                //TOOLTIPS
                $('#main *, #footer *').tooltip({
                    delay: 0,
                    showURL: false
                });
                
                //BUTTON
                $('#but_identify_user').hover(
                    function(){ 
                        $(this).addClass("ui-state-hover"); 
                    },
                    function(){ 
                        $(this).removeClass("ui-state-hover"); 
                    }
                ).mousedown(function(){
                    $(this).addClass("ui-state-active"); 
                })
                .mouseup(function(){
                        $(this).removeClass("ui-state-active");
                });
                
                //END SESSION DIALOG BOX
                $("#div_fin_session").dialog({
                    bgiframe: true,
                    modal: true,
                    autoOpen: false,
                    width: 400,
                    height: 90,
                    title: "<?php echo $txt['index_alarm'];?>",
                    buttons: {
                        "<?php echo $txt['index_add_one_hour'];?>": function() {
                            AugmenterSession();
                            document.getElementById('div_fin_session').style.display='none';
                            document.getElementById('countdown').style.color='black';
                            $(this).dialog('close');
                        }
                    }
                });
                
                //MENU
                    $('.fg-button').hover(
                        function(){ $(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
                        function(){ $(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
                    );
                    $('#cpm_menu').menu({
                        content: $('#menu_content').html(),
                        flyOut: true,
                        crossSpeed: 50
                    });
            });
            
            //Add 1 hour to session duration
            function AugmenterSession(){
                var data = "type=augmenter_session";
                httpRequest("sources/main.queries.php",data);
                document.getElementById('countdown').style.color="black"; 
            }
            
            //Countdown before session expiration
            function countdown()
            {
                var DayTill
                var theDay =  document.getElementById('temps_restant').value;
                var today = new Date() //Create an Date Object that contains today's date.
                var second = Math.floor(theDay - (today.getTime()/1000))
                var minute = Math.floor(second/60) //Devide "second" into 60 to get the minute
                var hour = Math.floor(minute/60) //Devide "minute" into 60 to get the hour
                CHour= hour % 24 //Correct hour, after devide into 24, the remainder deposits here.
                if (CHour<10) {CHour = "0" + CHour}
                CMinute= minute % 60 //Correct minute, after devide into 60, the remainder deposits here.
                if (CMinute<10) {CMinute = "0" + CMinute}
                CSecond= second % 60 //Correct second, after devide into 60, the remainder deposits here.
                if (CSecond<10) {CSecond = "0" + CSecond}
                DayTill = CHour+":"+CMinute+":"+CSecond
                
                //Avertir de la fin imminante de la session
                if ( DayTill == "00:01:00" ){
                    $('#div_fin_session').dialog('open');
                    document.getElementById('countdown').style.color="red"; 
                }
                
                //Gérer la fin de la session
                if ( DayTill == "00:00:00" )
                    document.location = "index.php?session=expiree";
                
                //Rewrite the string to the correct information.
                if ( document.getElementById('countdown') )
                    document.getElementById('countdown').innerHTML = DayTill //Make the particular form chart become "Daytill"
                var counter = setTimeout("countdown()", 1000) //Create the timer "counter" that will automatic restart function countdown() again every second.
            }
            
            //Change language using icon flags
            function ChangeLanguage(lang){
                document.getElementById('language').value = lang;
                document.temp_form.submit();
            }
            
        </script>
    </head>
    
    <body onload="countdown()">
    <?php
    
    ## HEADER ##
    echo '
    <div id="top">
        <div id="logo"><img src="includes/images/logo.png" /></div>
        
        <div id="title">'.$k['tool_name'].'</div>',
        
        isset($_SESSION['login']) ? '
            <div style="float:left;margin-left:20px; margin-top:3px;">
                <a tabindex="0" href="#menu_content" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="cpm_menu"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span>Menu</a>
            </div>
            <div style="float:left;font-size:12px;margin-left:20px;margin-top:6px;">
                <b>'.$_SESSION['login'].'</b> - '.$txt['index_expiration_in'].' <div style="display:inline;" id="countdown"></div>
                &nbsp;<img src="includes/images/clock__plus.png" style="cursor:pointer;" onclick="AugmenterSession()" title="'.$txt['index_add_one_hour'].'" />
            </div>' : '','
                   
        <div style="float:right;margin-left:30px;margin-top:12px;">
            <img src="includes/images/flag_fr.png" style="cursor:pointer;" onclick="ChangeLanguage(\'french\')" />
            <img src="includes/images/flag_us.png" style="cursor:pointer;" onclick="ChangeLanguage(\'english\')" />
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
                        <li><a href="#" onclick="MenuAction(\'manage_groups\');">'.$txt['admin_groups'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_functions\');">'.$txt['admin_functions'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_users\');">'.$txt['admin_users'].'</a></li>
                        <li><a href="#" onclick="MenuAction(\'manage_views\');">'.$txt['admin_views'].'</a></li>
                    </ul>
                </li>';
            }
            echo '
            <li>
                <a href="#" onclick="MenuAction(\'deconnexion\');">'.$txt['disconnect'].'</a>
            </li>
        </ul>
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
            include("items.php");
        }else if ( $_GET['page'] == "find"){
            include("find.php");
        }else if ( in_array($_GET['page'],array_keys($mng_pages)) ){
            if ($_SESSION['user_admin'] == 1 || $_SESSION['user_gestionnaire'] == 1) include($mng_pages[$_GET['page']]);
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
                    </div>
                    <div id="erreur_connexion" style="color:red;display:none;text-align:center;margin:5px;"></div>
                    
                    <label for="login" class="form_label">'.$txt['index_login'].' : </label>
                    <input type="text" size="10" id="login" name="login" />
                    <br />
                    
                    <label for="pw" class="form_label">'.$txt['index_password'].' : </label>
                    <input type="password" size="10" id="pw" name="pw" onKeyPress="if (event.keyCode == 13) identifyUser()" />
                    <br />
                    
                    <label for="duree_session" class="form_label">'.$txt['index_session_duration'].' : </label>
                    <input type="text" size="4" id="duree_session" name="duree_session" value="60" onKeyPress="if (event.keyCode == 13) identifyUser()" /> minutes
                    <br />
                    
                    <div style="text-align:center;margin-top:15px;">
                        <input type="button" id="but_identify_user" onClick="identifyUser()" style="padding:3px;cursor:pointer;" class="ui-state-default ui-corner-all" value="'.$txt['index_identify_button'].'" />
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
            cPassMan '.$k['version'].' © copyright 2009
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
            <img src="includes/images/ajax-loader_2.gif" />
        </div>
    </div>';
    
    //ENDING SESSION WARNING
    echo '
    <div id="div_fin_session" style="display:none;">
        <div style="padding:10px;text-align:center;">
            <img src="includes/images/alarm-clock.png" /> <b> '.$txt['index_session_ending'].'</b>
        </div>
    </div>
    ';
    ?>
    </body>
</html>
