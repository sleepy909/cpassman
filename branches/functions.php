<?php
####################################################################################################
## File : functions.php
## Author : Nils Laumaillé
## Description : Function page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>
<script src="includes/js/jquery.jeditable.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
    $("#change_group_autgroups").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 400,
        title: "<?php echo $txt['change_user_autgroups_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                LoadingPage();  //show loading div
                Change_groups(document.getElementById("selected_function").value,"autgroups");
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    
    $("#change_group_forgroups").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 400,
        title: "<?php echo $txt['change_user_forgroups_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                LoadingPage();  //show loading div
                Change_groups(document.getElementById("selected_function").value,"forgroups");
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    
    $("#add_new_function").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 100,
        title: "<?php echo $txt['give_function_title'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                LoadingPage();  //show loading div
                var data = "type=add_new_function&"+
                    "&name="+document.getElementById("new_function").value;
                httpRequest("sources/functions.queries.php",data);
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    
    refresh_matrice();
});
</script>
<?php
//Get full list of groups
$arr_groups = array();
$data_groups = mysql_query("SELECT id,title FROM ".$k['prefix']."nested_tree");
while( $res_groups = mysql_fetch_row($data_groups) )
    $arr_groups[$res_groups[0]] = $res_groups[1];

//display     
  echo '
<div style="margin-top:10px;">
    <h3>'.$txt['admin_functions'].'&nbsp;&nbsp;&nbsp;<img src="includes/images/card__plus.png" title="Ajouter une Fonction" onclick="OpenDialog(\'add_new_function\')" style="cursor:pointer;" /></h3>    
    <form name="form_fonctions" method="post" action="">
        <div style="width:600px;margin:auto; line-height:20px;">
            <table style="margin-top:10px;">
                <thead>
                    <tr>
                        <th>ID</th><th>'.$txt['label'].'</th><th>'.$txt['assoc_authorized_groups'].'</th><th>'.$txt['assoc_forbidden_groups'].'</th><th title="'.$txt['del_function'].'"><img src="includes/images/card__minus.png" /></th>
                    </tr>
                </thead>
                <tbody>';
            $x = 0;
            $res = mysql_query("SELECT * FROM ".$k['prefix']."functions");
            while ($data=mysql_fetch_array($res)){
                //Get list of allowed groups
                    $list_allo_grps = "";
                    $list = explode(';',$data['groupes_visibles']);
                    foreach($list as $elem){
                        if ( !empty($elem) ) $list_allo_grps .= '<img src="includes/images/arrow-000-small.png" />'.$arr_groups[$elem]."<br />";
                    }
                    if ( empty($list_allo_grps) ) $list_allo_grps = '<img src="includes/images/error.png" title="'.$txt['function_alarm_no_group'].'" />';
                
                //Get list of forbiden groups
                    $list_forb_grps = "";
                    $list = explode(';',$data['groupes_visibles']);
                    foreach($list as $elem){
                        if ( !empty($elem) ) $list_forb_grps .= '<img src="includes/images/arrow-000-small.png" />'.$arr_groups[$elem]."<br />";
                    }
                    
                //Display Grid
                echo '
                    <tr class="ligne'.($x%2).'">
                        <td align="center">'.$data['id'].'</td>
                        <td align="center"><p class="editable_textarea" id="title_'.$data['id'].'">'.str_replace('&','&amp;',$data['title']).'</p></td>
                        <td>
                            <div id="list_autgroups_function_'.$data['id'].'" style="text-align:center;">'
                            .$list_allo_grps.'
                            </div>
                            <div style="text-align:center;"><img src="includes/images/cog_edit.png" style="cursor:pointer;" onclick="Open_Div_Change(\''.$data['id'].'\',\'autgroups\')" title="'.$txt['change_authorized_groups'].'" /></div>
                        </td>
                        <td>
                            <div id="list_forgroups_function_'.$data['id'].'" style="text-align:center;">'
                            .$list_forb_grps.'
                            </div>
                            <div style="text-align:center;"><img src="includes/images/cog_edit.png" style="cursor:pointer;" onclick="Open_Div_Change(\''.$data['id'].'\',\'forgroups\')" title="'.$txt['change_forbidden_groups'].'" /></div>
                        </td>
                        <td align="center">
                            <img src="includes/images/card__minus.png" onclick="supprimer_fonction(\''.$data['id'].'\')" style="cursor:pointer;" />
                        </td>
                    </tr>';
                    $x++;
                }
                echo '
                </tbody>
            </table>
            
            <br /><br />
            
            <h3>'.$txt['rights_matrix'].'   
                <a onClick="refresh_matrice()"><img src="includes/images/arrow_refresh.png" style="cursor:pointer" /></a>
                &nbsp;<img id="ajax_loader_matrix" style="display:none;" src="includes/images/ajax-loader.gif" alt="" />
            </h3>
            <div id="matrice_droits"></div>
            <br />
            
            <input type="hidden" id="selected_function" />
        </div>
    </form>
</div>';

// DIV FOR CHANGING AUTHORIZED GROUPS
echo '
<div id="change_group_autgroups">'.
$txt['change_group_autgroups_info'].'
<form name="tmp_autgroups" action="">
<div id="change_group_autgroups_list" style="margin-left:15px;"></div>
</form>
</div>';

// DIV FOR CHANGING FUNCTIONS
echo '
<div id="change_group_forgroups">'.
$txt['change_group_forgroups_info'].'
<form name="tmp_forgroups" action="">
<div id="change_group_forgroups_list" style="margin-left:15px;"></div>
</form>
</div>';

// DIV FOR ADDING A FUNCTION
echo '
<div id="add_new_function" style="">
    <label for="new_function" class="form_label_100">'.$txt['name'].'</label><input type="text" id="new_function" size="40" />
</div>';

?>
<script type="text/javascript">
$(function() {     
    //inline editing
    $(".editable_textarea").editable("sources/functions.queries.php", { 
          indicator : "<img src='includes/images/loading.gif'>",
          type   : "textarea",
          select : true,
          submit : " <img src='includes/images/disk_black.png'>",
          cancel : " <img src='includes/images/cross.png'>",
          name : "edit_fonction"

      });
});

function Open_Div_Change(id,type){    
    var data = "type=open_div_"+type+"&id="+id;
    httpRequest("sources/functions.queries.php",data);  
}

function Change_groups(id,type){
    var list = "";
    if ( type == "autgroups" ) var form = document.forms.tmp_autgroups;
    if ( type == "forgroups" ) var form = document.forms.tmp_forgroups;
    
    for (i=0 ; i<= form.length-1 ; i++){
        if (form[i].type == 'checkbox' && form[i].checked){
            function_id = form[i].id.split('-')
            if ( list == "" ) list = function_id[1];
            else list = list + ";" + function_id[1];
        }
    }
    if ( type == "autgroups" ) var data = "type=change_function_autgroups&id="+id+"&list="+list;
    if ( type == "forgroups" ) var data = "type=change_function_forgroups&id="+id+"&list="+list;
    httpRequest("sources/functions.queries.php",data);  
}

function supprimer_fonction(id){
    if ( confirm("<?php echo $txt['confirm_function_deletion'];?>") ){
        var data = "type=supprimer_fonction&id="+id;
        httpRequest("sources/functions.queries.php",data);
        setTimeout('RefreshPage("form_fonctions")',500);
    }
}  

function refresh_matrice(){
    var data = "type=rafraichir_matrice";
        httpRequest("sources/functions.queries.php",data);
}
</script>