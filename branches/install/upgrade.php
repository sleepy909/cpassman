<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>cPassMan Installation</title>
        <link rel="stylesheet" href="install.css" type="text/css" />
        <script type="text/javascript" src="../includes/js/functions.js"></script>
        <script type="text/javascript" src="gauge/gauge.js"></script>
        
        <script type="text/javascript">
        if(typeof $=='undefined') {function $(v) {return(document.getElementById(v));}}
        window.onload = function () { 
            if ( document.getElementById("progressbar") ){
                gauge.add($("progressbar"), { width:600, height:30, name: 'pbar', limit: true, gradient: true, scale: 10, colors:['#ff0000','#00ff00']});
                if ( document.getElementById("step").value == "1" ) gauge.modify($('pbar'),{values:[0.25,1]});
                else if ( document.getElementById("step").value == "2" ) gauge.modify($('pbar'),{values:[0.50,1]});
                else if ( document.getElementById("step").value == "3" ) gauge.modify($('pbar'),{values:[1,1]});
            }
        }
        
        function goto_next_page(page){
            document.getElementById("step").value=page;
            document.install.submit();
        }
        
        function Check(step){
            if ( step != "" ){
                if ( step == "step1" ){
                    document.getElementById("loader").style.display = "";
                    var data = "type="+step+
                    "&db_host="+document.getElementById("db_host").value+
                    "&db_login="+document.getElementById("db_login").value+
                    "&db_password="+document.getElementById("db_pw").value+
                    "&db_bdd="+document.getElementById("db_bdd").value;
                }else
                if ( step == "step2" ){
                    var data = "type="+step;
                    document.getElementById("loader").style.display = "";
                }
                httpRequest("upgrade_ajax.php",data)
            }   
        }
        </script>
    </head>
    <body>
<?php
require_once("../includes/language/english.php");
require_once("../includes/include.php");

## LOADER
echo '
    <div style="position:absolute;top:49%;left:49%;display:none;" id="loader"><img src="images/ajax-loader.gif" /></div>';

## HEADER ##
echo '
        <div id="top">
            <div id="logo"><img src="../includes/images/logo.png" /></div>
            
            <div id="title">Collaborative Passwords Manager</div>
        </div>
        <div id="content">
            <div id="center" class="ui-corner-bottom">
                <form name="install" method="post" action="">';
    
#Elements cachés
echo '
                    <input type="hidden" id="step" name="step" value="', isset($_POST['step']) ? $_POST['step']:'', '" />'; 

if ( !isset($_GET['step']) && !isset($_POST['step'])  ){   
   //ETAPE O 
    echo '
                    <h2>This page will help you to upgrade the cPassMan\'s database</h2>

                    Before starting, be sure to:<br />
                    - upload the complete package on the server,<br />
                    - have the database connection informations,<br />
                    - get some CHMOD rights on the server.<br />';
   

}else if ( (isset($_POST['step']) && $_POST['step'] == 1) || (isset($_GET['step']) && $_GET['step'] == 1) ){
   //ETAPE 1 
   echo '
                    <h3>Step 1</h3>
                    <fieldset><legend>Database Informations</legend>
                    <label for="db_host">Host :</label><input type="text" id="db_host" name="db_host" class="step" /><br />
                    <label for="db_db">Database name :</label><input type="text" id="db_bdd" name="db_bdd" class="step" /><br />
                    <label for="db_login">Login :</label><input type="text" id="db_login" name="db_login" class="step" /><br />
                    <label for="db_pw">Password :</label><input type="text" id="db_pw" name="db_pw" class="step" /><br />
                    <label for="tbl_prefix">Table prefix :</label><input type="text" id="tbl_prefix" name="tbl_prefix" class="step" value="cpassman_" />
                    </fieldset>

                    <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step1"></div>   
                    <input type="hidden" id="step1" name="step1" value="" />'; 
   
   
}else if ( (isset($_POST['step']) && $_POST['step'] == 2) || (isset($_GET['step']) && $_GET['step'] == 2) ){
   //ETAPE 2 
   $_SESSION['db_host'] = $_POST['db_host'];
    $_SESSION['db_bdd'] = $_POST['db_bdd'];
    $_SESSION['db_login'] = $_POST['db_login'];
    $_SESSION['db_pw'] = $_POST['db_pw'];
    $_SESSION['tbl_prefix'] = $_POST['tbl_prefix'];
   echo '
                    <h3>Step 2</h3>
                    
                    The upgrader will now update your database.
                    <table>
                        <tr><td>Misc table will be populated with new values</td><td><span id="tbl_1"></span></td></tr>
                        <tr><td>Users table will be altered with news fields</td><td><span id="tbl_2"></span></td></tr>
                    </table>

                    <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step2"></div>   
                    <input type="hidden" id="step2" name="step2" value="" />'; 
}

else if ( (isset($_POST['step']) && $_POST['step'] == 3) || (isset($_GET['step']) && $_GET['step'] == 3) ){
    
   //ETAPE 6
   echo '
                    <h3>Step 3</h3>
                    Upgrade is now finished!<br />
                    You can delete "Install" directory from your server for more security.<br /><br />
                    For news, help and information, visit the <a href="http://cpassman.net23.net" target="_blank">cPassMan website</a>.';
}

//buttons
if ( !isset($_POST['step']) ){
       echo '   
                    <div id="buttons_bottom">
                        <input type="button" id="but_next" onclick="goto_next_page(\'1\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="NEXT" />
                    </div>';
}elseif ( $_POST['step'] == 3 ){
       echo '   
                    <div id="buttons_bottom">
                        <input type="button" id="but_next" onclick="javascript:window.location.href=\'http://' . $_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/')-8) . '\';" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="Open cPassMan" />
                    </div>';
}else{
   echo '   
                    <div style="width:900px;margin:auto;margin-top:30px;">        
                        <div id="progressbar" style="float:left;margin-top:9px;"></div>
                        <div id="buttons_bottom">
                            <input type="button" id="but_launch" onclick="Check(\'step'.$_POST['step'] .'\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="LAUNCH" />
                            <input type="button" id="but_next" onclick="goto_next_page(\''.(intval($_POST['step'])+1).'\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="NEXT" disabled="disabled" />
                        </div>
                    </div>';
}
    
echo '
                </form>
            </div>
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
?>
    </body>
</html>