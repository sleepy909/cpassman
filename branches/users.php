<?php
####################################################################################################
## File : users.php
## Author : Nils Laumaillé
## Description : Users page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>
<script src="includes/js/jquery.jeditable.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $("#change_user_functions").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 400,
        title: "<?php echo $txt['change_user_functions_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                Change_user_rights(document.getElementById("selected_user").value,"functions");
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    
    $("#change_user_autgroups").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 400,
        title: "<?php echo $txt['change_user_autgroups_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                Change_user_rights(document.getElementById("selected_user").value,"autgroups");
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    
    $("#change_user_forgroups").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 400,
        title: "<?php echo $txt['change_user_forgroups_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                Change_user_rights(document.getElementById("selected_user").value,"forgroups");
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
});
</script>



<?php

require_once ("sources/NestedTree.class.php");
$tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
$tree_desc = $tree->getDescendants();

//Build FUNCTIONS list
$liste_fonctions = array();
$res = mysql_query("SELECT * FROM ".$k['prefix']."functions ORDER BY title ASC");
while($data = mysql_fetch_row($res)){
    $liste_fonctions[$data[0]] = array('id'=>$data[0],'title'=>$data[1]);
}

//Display list of USERS
echo '
<div style="margin-top:10px;">
    <h3>'.$txt['admin_users'].'<img src="includes/images/user__plus.png" title="Ajouter un Compte" onclick="ajouter_user()" style="cursor:pointer;" /></h3>';
    
echo '
    <form name="form_utilisateurs" method="post" action="">
        <div style="width:800px;margin:auto; line-height:20px;">
            <table cellspacing="0" cellpadding="2">
                <thead>
                    <tr>
                        <th>ID</th><th>'.$txt['index_login'].'</th><th>'.$txt['functions'].'</th><th>'.$txt['authorized_groups'].'</th><th>'.$txt['forbidden_groups'].'</th><th title="'.$txt['god'].'"><img src="includes/images/user-black.png" /></th><th title="'.$txt['gestionnaire'].'"><img src="includes/images/folder-bookmark.png" /></th><th title="'.$txt['user_del'].'"><img src="includes/images/user--minus.png" /></th><th title="'.$txt['pw_change'].'"><img src="includes/images/lock__pencil.png" /></th><th title="'.$txt['email_change'].'"><img src="includes/images/mail.png" /></th>
                    </tr>
                </thead>
                <tbody>';
            $x = 0;
            //Get through all users
            $res = mysql_query("SELECT * FROM ".$k['prefix']."users");
            while ($data=mysql_fetch_array($res)){
                echo '<tr class="ligne'.($x%2).'">
                        <td align="center">'.$data['id'].'</td>
                        <td align="center"><p class="editable_textarea" id="login_'.$data['id'].'">'.$data['login'].'</p></td>
                        <td>
                            <div id="list_function_user_'.$data['id'].'" style="display:inline;">';
                                if ( count($liste_fonctions) > 0 ){
                                    foreach($liste_fonctions as $fonction){
                                        if ( in_array($fonction['id'],explode(";",$data['fonction_id'])) ) echo htmlspecialchars($fonction['title'],ENT_COMPAT,$k['charset'])."<br />";
                                    }
                                }
                            echo '
                            </div>
                            <div style="text-align:center;"><img src="includes/images/cog_edit.png" style="cursor:pointer;" onclick="Open_Div_Change(\''.$data['id'].'\',\'functions\')" title="'.$txt['change_function'].'" /></div>
                        </td>
                        <td>
                            <div id="list_autgroups_user_'.$data['id'].'" style="display:inline;">';
                                if ( count($tree_desc) > 0 ){
                                    foreach($tree_desc as $t){
                                        if ( @!in_array($t->id,$_SESSION['groupes_interdits']) && in_array($t->id,$_SESSION['groupes_visibles']) ){
                                            $ident="";
                                            //for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                                            if ( in_array($t->id,explode(";",$data['groupes_visibles'])) ) echo htmlspecialchars($ident.$t->title,ENT_COMPAT,$k['charset']).'<br />';
                                            $prev_level = $t->nlevel;
                                        }
                                    }
                                }
                            echo '
                            </div>
                            <div style="text-align:center;"><img src="includes/images/cog_edit.png" style="cursor:pointer;" onclick="Open_Div_Change(\''.$data['id'].'\',\'autgroups\')" title="'.$txt['change_authorized_groups'].'" /></div>
                        </td>
                        <td>
                            <div id="list_forgroups_user_'.$data['id'].'" style="display:inline;">';
                                if ( count($tree_desc) > 0 ){
                                    foreach($tree_desc as $t){
                                        $ident="";
                                        //for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                                        if ( in_array($t->id,explode(";",$data['groupes_interdits'])) ) echo htmlspecialchars($ident.$t->title,ENT_COMPAT,$k['charset']).'<br />';
                                        $prev_level = $t->nlevel;
                                    }
                                }
                            echo '
                            </div>
                            <div style="text-align:center;"><img src="includes/images/cog_edit.png" style="cursor:pointer;" onclick="Open_Div_Change(\''.$data['id'].'\',\'forgroups\')" title="'.$txt['change_forbidden_groups'].'" /></div>
                        </td>
                        <td align="center">
                            <input type="checkbox" id="cb_admin_'.$data['id'].'" onchange="Changer_Droit_Admin(\''.$data['id'].'\')"', $data['admin']==1 ? 'checked' : '', ' ', $_SESSION['user_gestionnaire'] == 1 ? 'disabled':'' , ' />
                        </td>
                        <td align="center">
                            <input type="checkbox" id="cb_gest_groupes_'.$data['id'].'" onchange="Changer_Droit_Groupes(\''.$data['id'].'\')"', $data['gestionnaire']==1 ? 'checked' : '', ' />
                        </td>
                        <td align="center">
                            <img src="includes/images/user--minus.png" onclick="supprimer_user(\''.$data['id'].'\')" style="cursor:pointer;" />
                        </td>
                        <td align="center">
                            &nbsp;<img src="includes/images/lock__pencil.png" onclick="mdp_user(\''.$data['id'].'\')" style="cursor:pointer;" />
                        </td>
                        <td align="center">
                            &nbsp;<img src="includes/images/', empty($data['email']) ? 'mail--exclamation.png':'mail--pencil.png', '" onclick="mail_user(\''.$data['id'].'\')" style="cursor:pointer;" title="'.$data['email'].'" />
                        </td>
                    </tr>';
                    $x++;
            }
            echo '
                </tbody>
            </table>
        </div>
    </form>
</div>
<input type="hidden" id="selected_user" />';

// DIV FOR CHANGING FUNCTIONS
echo '
<div id="change_user_functions" style="">'.
$txt['change_user_functions_info'].'
<form name="tmp_functions" action="">
<div id="change_user_functions_list" style="margin-left:15px;"></div>
</form>
</div>';

// DIV FOR CHANGING AUTHORIZED GROUPS
echo '
<div id="change_user_autgroups" style="">'.
$txt['change_user_autgroups_info'].'
<form name="tmp_autgroups" action="">
<div id="change_user_autgroups_list" style="margin-left:15px;"></div>
</form>
</div>';

// DIV FOR CHANGING FUNCTIONS
echo '
<div id="change_user_forgroups" style="">'.
$txt['change_user_forgroups_info'].'
<form name="tmp_forgroups" action="">
<div id="change_user_forgroups_list" style="margin-left:15px;"></div>
</form>
</div>';

?>

<script type="text/javascript">
$(function() {     
    //inline editing
    $(".editable_textarea").editable("sources/users.queries.php", { 
          indicator : "<img src='includes/images/loading.gif' />",
          type   : "textarea",
          select : true,
          submit : " <img src='includes/images/disk_black.png' />",
          cancel : " <img src='includes/images/cross.png' />",
          name : "newlogin"

      });
});

function ajouter_user(){
    var login = prompt("<?php echo $txt['give_new_login'];?>");
    if ( login != null && login != "" ){
        var data = "type=ajouter_user&login="+login;
        httpRequest("sources/users.queries.php",data);
        setTimeout('RefreshPage("form_utilisateurs")',500);
    }
}

function supprimer_user(id){
    if ( confirm("<?php echo $txt['confirm_del_account'];?>") ){
        var data = "type=supprimer_user&id="+id;
        httpRequest("sources/users.queries.php",data);
        setTimeout('RefreshPage("form_utilisateurs")',500);
    }
}      

function mdp_user(id){
    var new_mdp = prompt("<?php echo $txt['give_new_pw'];?>");
    if ( new_mdp != null && new_mdp != "" ){
        var data = "type=modif_mdp_user&id="+id+"&newmdp="+new_mdp;
        httpRequest("sources/users.queries.php",data);
        alert('Mot de passe changé !');
    }
}  

function mail_user(id){
    var new_mail = prompt("<?php echo $txt['give_new_email'];?>");
    if ( new_mail != null && new_mail != "" ){
        var data = "type=modif_mail_user&id="+id+"&newmail="+new_mail;
        httpRequest("sources/users.queries.php",data);
        alert('Email changé !');
    }
}

function Changer_Droit_Groupes(id){
    var droit = 0;
    if ( document.getElementById('cb_gest_groupes_'+id).checked == true ) droit = 1;
    var data = "type=modif_droit_gest_groupes_user&id="+id+"&gest_groupes="+droit;
    httpRequest("sources/users.queries.php",data);   
}

function Changer_Droit_Admin(id){
    var admin = 0;
    if ( document.getElementById('cb_admin_'+id).checked == true ) admin = 1;
    var data = "type=modif_droit_admin_user&id="+id+"&admin="+admin;
    httpRequest("sources/users.queries.php",data);   
}

function Open_Div_Change(id,type){    
    var data = "type=open_div_"+type+"&id="+id;
    httpRequest("sources/users.queries.php",data);  
}

function Change_user_rights(id,type){
    var list = "";
    if ( type == "functions" ) var form = document.forms.tmp_functions;
    if ( type == "autgroups" ) var form = document.forms.tmp_autgroups;
    if ( type == "forgroups" ) var form = document.forms.tmp_forgroups;
    
    for (i=0 ; i<= form.length-1 ; i++){
        if (form[i].type == 'checkbox' && form[i].checked){
            function_id = form[i].id.split('-')
            if ( list == "" ) list = function_id[1];
            else list = list + ";" + function_id[1];
        }
    }
    if ( type == "functions" ) var data = "type=change_user_functions&id="+id+"&list="+list;
    if ( type == "autgroups" ) var data = "type=change_user_autgroups&id="+id+"&list="+list;
    if ( type == "forgroups" ) var data = "type=change_user_forgroups&id="+id+"&list="+list;
    httpRequest("sources/users.queries.php",data);  
}


</script>