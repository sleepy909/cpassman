<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>cPassMan Installation</title>
        <link rel="stylesheet" href="install.css" type="text/css" />
        <!--
        <script type="text/javascript" src="../includes/jquery-ui/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../includes/jquery-ui/js/jquery-ui-1.7.2.custom.min.js"></script>
        <link rel="stylesheet" href="../includes/jquery-ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css" />
        -->
        <script type="text/javascript" src="../includes/js/functions.js"></script>
        <script type="text/javascript" src="gauge/gauge.js"></script>
        
        <script type="text/javascript">
        /*$(function() {
            //BUTTONS
            $('#but_next, #but_launch').hover(
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
        });*/
        if(typeof $=='undefined') {function $(v) {return(document.getElementById(v));}}
        window.onload = function () { 
            if ( document.getElementById("progressbar") ){
                gauge.add($("progressbar"), { width:600, height:30, name: 'pbar', limit: true, gradient: true, scale: 10, colors:['#ff0000','#00ff00']});
                if ( document.getElementById("step").value == "1" ) gauge.modify($('pbar'),{values:[0.10,1]});
                else if ( document.getElementById("step").value == "2" ) gauge.modify($('pbar'),{values:[0.30,1]});
                else if ( document.getElementById("step").value == "3" ) gauge.modify($('pbar'),{values:[0.50,1]});
                else if ( document.getElementById("step").value == "4" ) gauge.modify($('pbar'),{values:[0.70,1]});
                else if ( document.getElementById("step").value == "5" ) gauge.modify($('pbar'),{values:[0.90,1]});
                else if ( document.getElementById("step").value == "6" ) gauge.modify($('pbar'),{values:[1,1]});
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
                    "&abspath="+escape(document.getElementById("root_path").value);
                }else
                if ( step == "step2" ){
                    document.getElementById("loader").style.display = "";
                    var data = "type="+step+
                    "&db_host="+document.getElementById("db_host").value+
                    "&db_login="+document.getElementById("db_login").value+
                    "&db_password="+document.getElementById("db_pw").value+
                    "&db_bdd="+document.getElementById("db_bdd").value;
                }else
                if ( step == "step3" ){
                    document.getElementById("loader").style.display = "";
                    var status = true;
                    if ( document.getElementById("pw_validity").value != "" ) 
                    document.getElementById("pw_validity_res").innerHTML = "<img src='images/tick.png'>";
                    else {
                        document.getElementById("pw_validity_res").innerHTML = "<img src='images/exclamation-red.png'>";
                        status = false;
                    }
                    if ( document.getElementById("tbl_prefix").value != "" ) 
                    document.getElementById("tbl_prefix_res").innerHTML = "<img src='images/tick.png'>";
                    else{
                        document.getElementById("pw_validity_res").innerHTML = "<img src='images/exclamation-red.png'>";
                        status = false;
                    }
                    if ( document.getElementById("charset").value != "" ) 
                    document.getElementById("charset_res").innerHTML = "<img src='images/tick.png'>";
                    else{
                        document.getElementById("charset_res").innerHTML = "<img src='images/exclamation-red.png'>";
                        status = false;
                    }
                    if ( document.getElementById("encrypt_key").value != "" ) 
                    document.getElementById("encrypt_key_res").innerHTML = "<img src='images/tick.png'>";
                    else{
                        document.getElementById("encrypt_key_res").innerHTML = "<img src='images/exclamation-red.png'>";
                        status = false;
                    }
                    
                    if ( status == true ){
                        gauge.modify($('pbar'),{values:[0.60,1]});
                        document.getElementById("but_next").disabled = "";
                    }
                }else 
                if ( step == "step4" ){
                    document.getElementById("loader").style.display = "";
                    var data = "type="+step;
                }else
                if ( step == "step5" ){
                    document.getElementById("loader").style.display = "";
                    var data = "type="+step;
                }
                httpRequest("install_ajax.php",data)
            }   
        }
        </script>
    </head>
    <body>
<?php
session_start();

## LOADER
echo '<div style="position:absolute;top:49%;left:49%;display:none;" id="loader"><img src="images/ajax-loader.gif"></div>';

## HEADER ##
echo '
    <div id="top">
        <div id="logo"><img src="../includes/images/logo.png" /></div>
        
        <div id="title">Collaborative Passwords Manager</div>
    </div>
    <div id="content">';

echo '
    <div id="center" class="ui-corner-bottom">
    <form name="install" method="post">';
    
#Elements cachés
echo '
    <input type="hidden" id="step" name="step" value="'.$_POST['step'].'" />'; 

if ( !isset($_GET['step']) && !isset($_POST['step'])  ){   
   //ETAPE O 
    echo '
    <h2>This page will help you through the installation process of cPassMan</h2>

    Before starting, be sure to:<br />
    - upload the complete package on the server,<br />
    - have the database connection informations,<br />
    - get some CHMOD rights on the server.<br />
   ';
   

}else if ( $_POST['step'] == 1 ||  $_GET['step'] == 1 ){
   //ETAPE 1 
   echo '
   <h3>Step 1 - Check server</h3>
   
   <fieldset><legend>Please give me</legend>
   <label for="root_path" style="width:300px;">Absolute path to cPassMan folder :</label><input type="text" id="root_path" name="root_path" class="step" style="width:560px;" /><br />
   </fieldset>
   
   <h4>Next elements will be checked.</h4>
   <div style="margin:15px;" id="res_step1">
    <span style=\"padding-left:30px;font-size:13pt;\">File "settings.php" is writable</span><br />
    <span style=\"padding-left:30px;font-size:13pt;\">Directory "/install/" is writable</span><br />
    <span style=\"padding-left:30px;font-size:13pt;\">Directory "/includes/<" is writable/span><br />
    <span style=\"padding-left:30px;font-size:13pt;\">Directory "/files/" is writable</span><br />
    <span style=\"padding-left:30px;font-size:13pt;\">PHP extension "mcrypt" is loaded</span><br />
   </div> 
   <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="status_step1"></div>   
   ';   
   
   
}else if ( $_POST['step'] == 2 ||  $_GET['step'] == 2 ){
    $_SESSION['root_path'] = $_POST['root_path'];
   //ETAPE 2 
   echo '
   <h3>Step 2</h3>
   <fieldset><legend>Database Informations</legend>
   <label for="db_host">Host :</label><input type="text" id="db_host" name="db_host" class="step" /><br />
   <label for="db_db">Database name :</label><input type="text" id="db_bdd" name="db_bdd" class="step" /><br />
   <label for="db_login">Login :</label><input type="text" id="db_login" name="db_login" class="step" /><br />
   <label for="db_pw">Password :</label><input type="text" id="db_pw" name="db_pw" class="step" />
   </fieldset>
   
   <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step2"></div>   
   <input type="hidden" id="step2" name="step2" value="" />'; 
    
    
}else if ( $_POST['step'] == 3 ||  $_GET['step'] == 3 ){
    $_SESSION['db_host'] = $_POST['db_host'];
    $_SESSION['db_bdd'] = $_POST['db_bdd'];
    $_SESSION['db_login'] = $_POST['db_login'];
    $_SESSION['db_pw'] = $_POST['db_pw'];
    
   //ETAPE 3
   echo '
   <h3>Step 3</h3>
   <fieldset><legend>Give me some informations</legend>
   <label for="tbl_prefix" style="width:320px;">Table prefix :</label><input type="text" id="tbl_prefix" name="tbl_prefix" class="step" value="cpassman_" onblur /><span style="padding-left:10px;" id="tbl_prefix_res"></span><br />
   
   <label for="pw_validity" style="width:320px;">User password validity period (days):</label><input type="text" id="pw_validity" name="pw_validity" class="step" value="30" /><span style="padding-left:10px;" id="pw_validity_res"></span><br />
   
   <label for="charset" style="width:320px;">Charset :</label><input type="text" id="charset" name="charset" class="step" value="" /><span style="padding-left:10px;" id="charset_res"></span><br />
   
   <label for="encrypt_key" style="width:320px;">Encryption key:<span style="font-size:9pt;font-weight:normal;"><br />for passwords encryptionin database</span></label><input type="text" id="encrypt_key" name="encrypt_key" class="step" value="whateveryouwant" /><span style="padding-left:10px;" id="encrypt_key_res"></span><br />
   
   <label for="smtp_server" style="width:320px;">SMTP server :<span style="font-size:9pt;font-weight:normal;"><br />Email server configuration</span></label><input type="text" id="smtp_server" name="smtp_server" class="step" value="smtp.my_domain.com" /><br />
   
   <label for="smtp_auth" style="width:320px;">SMTP authorization:<span style="font-size:9pt;font-weight:normal;"><br />false or true</span></label><input type="text" id="smtp_auth" name="smtp_auth" class="step" value="false" /><br />
   
   <label for="smtp_auth_username" style="width:320px;">SMTP authorization username :</label><input type="text" id="smtp_auth_username" name="smtp_auth_username" class="step" value="" /><br />
   
   <label for="smtp_auth_password" style="width:320px;">SMTP authorization password :</label><input type="text" id="smtp_auth_password" name="smtp_auth_password" class="step" value="" /><br />
   
   <label for="email_from" style="width:320px;">Email from :</label><input type="text" id="email_from" name="email_from" class="step" value=""  /><br />
   
   <label for="email_from_name" style="width:320px;">Email from name :</label><input type="text" id="email_from_name" name="email_from_name" class="step" value="" />
   </fieldset>
   
   <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step3"></div>  '; 
   
    
}else if ( $_POST['step'] == 4 ||  $_GET['step'] == 4 ){
    $_SESSION['tbl_prefix'] = $_POST['tbl_prefix'];
    $_SESSION['pw_validity'] = $_POST['pw_validity'];
    $_SESSION['charset'] = $_POST['charset'];
    $_SESSION['encrypt_key'] = $_POST['encrypt_key'];
    $_SESSION['smtp_server'] = $_POST['smtp_server'];
    $_SESSION['smtp_auth'] = $_POST['smtp_auth'];
    $_SESSION['smtp_auth_username'] = $_POST['smtp_auth_username'];
    $_SESSION['smtp_auth_password'] = $_POST['smtp_auth_password'];
    $_SESSION['email_from'] = $_POST['email_from'];
    $_SESSION['email_from_name'] = $_POST['email_from_name'];
    
   //ETAPE 4
   echo '
   <h3>Step 4</h3>
   <fieldset><legend>Populate the Database</legend>
   The installer will now update your database.
   <table>
        <tr><td>Add table "functions"</td><td><span id="tbl_1"></span></td></tr>
        <tr><td>Add table "items"</td><td><span id="tbl_2"></span></td></tr>
        <tr><td>Add table "log_items"</td><td><span id="tbl_3"></span></td></tr>
        <tr><td>Add table "misc"</td><td><span id="tbl_4"></span></td></tr>
        <tr><td>Add table "nested_tree"</td><td><span id="tbl_5"></span></td></tr>
        <tr><td>Add table "rights"</td><td><span id="tbl_6"></span></td></tr>
        <tr><td>Add table "users"</td><td><span id="tbl_7"></span></td></tr>  
        <tr><td>Add Admin account</td><td><span id="tbl_8"></span></td></tr>    
   </table>
   </fieldset>
   
   <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step4"></div>  '; 
   
    
}else if ( $_POST['step'] == 5 ||  $_GET['step'] == 5 ){    
   //ETAPE 5
   echo '
   <h3>Step 5 - Update setting file</h3>
   This step will write the new setting.php file for your server configuration.<br />
   Click on the button when ready.
   
   <div style="margin-top:20px;font-weight:bold;text-align:center;height:27px;" id="res_step5"></div>  '; 
   
    
}

else if ( $_POST['step'] == 6 ||  $_GET['step'] == 6 ){
    
   //ETAPE 6
   echo '
   <h3>Step 6</h3>
   Installation is now finished!<br />
   You can log as an Administrator by using login <b>admin</b> and password <b>admin</b>.<br />
   You can delete "Install" directory from your server for more security, and change the CHMOD on the "/includes" directory.<br /><br />
   For news, help and information, visit the <a href="http://cpassman.net23.net" target="_blank">cPassMan website</a>.';
   
    
}



//buttons
if ( !isset($_POST['step']) ){
       echo '   
    <div id="buttons_bottom">
        <input type="button" id="but_next" onclick="goto_next_page(\'1\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="NEXT" />
    </div>';
}elseif ( $_POST['step'] == 6 ){
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
    
    
?>
    </div>
    </form>
    </body>
</html>
