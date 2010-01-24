<?php
####################################################################################################
## File : load.php
## Author : Nils Laumaillé
## Description : Loads things depending on the pages. It is called by index.php page.
## 
## DON'T CHANGE !!!
## 
####################################################################################################

//Common elements
$html_headers = '
<link rel="stylesheet" href="includes/css/passman.css" type="text/css" />
<script type="text/javascript" src="includes/js/functions.js"></script> 

<script type="text/javascript" src="includes/jquery-ui/js/jquery-'.$k['jquery-version'].'.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui/js/jquery-ui-'.$k['jquery-ui-version'].'.custom.min.js"></script>
<link rel="stylesheet" href="includes/jquery-ui/css/'.$k['jquery-ui-theme'].'/jquery-ui-'.$k['jquery-ui-version'].'.custom.css" type="text/css" /> 

<script language="JavaScript" type="text/javascript" src="includes/js/jquery.tooltip.js"></script>
<link rel="stylesheet" href="includes/css/jquery.tooltip.css" type="text/css" />

<script language="JavaScript" type="text/javascript" src="includes/js/pwd_strength.js"></script>';


//For ITEMS page, load specific CSS files for treeview
if ( isset($_GET['page']) && $_GET['page'] == "items"){
    $html_headers .= '
        <link rel="stylesheet" type="text/css" href="includes/css/jquery.treeview.css" />
        <link rel="stylesheet" type="text/css" href="includes/css/items.css" />
        <link href="includes/js/jquery.contextMenu/jquery.contextMenu.css" rel="stylesheet" type="text/css" />';
}

$html_headers .= '
<script type="text/javascript">
<!-- // --><![CDATA[
    //deconnexion
    function MenuAction(val){
        if ( val == "deconnexion" ) {
            document.getElementById("menu_action").value = val;
            document.main_form.submit();
        }
        else {
            if ( val == "") document.location.href="index.php";
            else document.location.href="index.php?page="+val;
        }                
    }
    
    //Identifier l"utilisateur
    function identifyUser(){
        if ( document.getElementById("login").value != "" && document.getElementById("pw").value != "" ){
            document.getElementById("erreur_connexion").innerHTML = "";
            document.getElementById("ajax_loader_connexion").style.display = "";
            var data = "type=identify_user"+
                        "&login="+document.getElementById("login").value+
                        "&pw="+document.getElementById("pw").value+
                        "&duree_session="+document.getElementById("duree_session").value+
                        "&hauteur_ecran="+window.innerHeight;
            httpRequest("sources/main.queries.php",data);
            
            
        }else{
            alert("Vous devez renseigner votre login et mot de passe !");   
        }
    }
    
    function ouvrir_div(div){
        $("#"+div).slideToggle("slow");
    }
    
    function OpenDialogBox(id){
        $("#"+id).dialog("open");
    }
    
    $(function() {
        //TOOLTIPS
        $("#main *, #footer *, #icon_last_items *, #top *").tooltip({
            delay: 0,
            showURL: false
        });
        
        //BUTTON
        $("#but_identify_user").hover(
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
            title: "'.$txt['index_alarm'].'",
            buttons: {
                "'.$txt['index_add_one_hour'].'": function() {
                    AugmenterSession();
                    document.getElementById("div_fin_session").style.display="none";
                    document.getElementById("countdown").style.color="black";
                    $(this).dialog("close");
                }
            }
        });
        
        $(".button_menu").hover(
            function(){ 
                $(this).addClass("ui-state-hover"); 
            },
            function(){ 
                $(this).removeClass("ui-state-hover"); 
            }
        )
    });';
    
if ( !isset($_GET['page']) ){
    $html_headers .= '
    $(function() {
        // DIALOG BOX FOR CHANGING PASSWORD
        $("#div_changer_mdp").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 300,
            height: 190,
            title: "'.$txt['index_change_pw'].'",
            buttons: {
                "'.$txt['index_change_pw_button'].'": function() {
                    ChangerMdp("'. (isset($_SESSION['last_pw']) ? $_SESSION['last_pw'] : ''). '");
                    //$(this).dialog("close");
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });
    })
    
    //Change the Users password when he asks for
    function ChangerMdp(old_pw){
        if ( document.getElementById("new_pw").value != "" && document.getElementById("new_pw").value == document.getElementById("new_pw2").value ){            
            var data = "type=change_pw&new_pw="+document.getElementById("new_pw").value+"&old_pw="+old_pw;
            httpRequest("sources/main.queries.php",data);
        }else{
            $("#change_pwd_error").addClass("ui-state-error ui-corner-all");
            document.getElementById("change_pwd_error").innerHTML = "'.$txt['index_pw_error_identical'].'";
        }
    }';
}

if ( isset($_GET['page']) && $_GET['page'] == "administration" ){
    $html_headers .= '
            //Function loads informations from cpassman FTP
            function LoadCPMInfo(){
                var data = "type=cpm_status";
                httpRequest("sources/admin.queries.php",data);
            }
            //Load function on page load
            $(function() {
                LoadCPMInfo();
            });';
        
}


$html_headers .= '
// ]]>
</script>';

//Load some PHP elements

?>
