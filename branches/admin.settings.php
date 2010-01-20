<?php
####################################################################################################
## File : admin.settings.php
## Author : Nils Laumaillé
## Description : Settings page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
?>

<?php

//SAVE CHANGES
if ( isset($_POST['save_button']) ){
    mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['max_last_items']."' WHERE type='admin' AND intitule = 'max_latest_items'");
    $_SESSION['max_latest_items'] = $_POST['max_last_items'];
    
    mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['enable_favourites']."' WHERE type='admin' AND intitule = 'enable_favourites'");
    $_SESSION['enable_favourites'] = $_POST['enable_favourites'];
    
    mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['show_last_items']."' WHERE type='admin' AND intitule = 'show_last_items'");
    $_SESSION['show_last_items'] = $_POST['show_last_items'];
}

echo '
<div style="margin-top:10px;">
    <h3>'.$txt['admin_settings_title'].'</h3>    
    <form name="form_settings" method="post" action="">
        <div style="width:100%;margin:auto; line-height:20px; padding:10px;">
            <div>
                <label for="max_last_items" class="form_label_500">'.$txt['max_last_items'].'</label>
                <input type="text" size="5" id="max_last_items" name="max_last_items" value="', isset($_SESSION['max_latest_items']) ? $_SESSION['max_latest_items'] : '', '" />
                <br />
                <label for="enable_favourites" class="form_label_500">'.$txt['enable_favourites'].'</label>
                <select id="enable_favourites" name="enable_favourites">
                    <option value="1"', isset($_SESSION['enable_favourites']) && $_SESSION['enable_favourites'] == 1 ? ' selected="selected"' : '', '>'.$txt['yes'].'</option>
                    <option value="0"', isset($_SESSION['enable_favourites']) && $_SESSION['enable_favourites'] != 1 ? ' selected="selected"' : '', '>'.$txt['no'].'</option>
                </select>
                <br />
                <label for="show_last_items" class="form_label_500">'.$txt['show_last_items'].'</label>
                <select id="show_last_items" name="show_last_items">
                    <option value="1"', isset($_SESSION['show_last_items']) && $_SESSION['show_last_items'] == 1 ? ' selected="selected"' : '', '>'.$txt['yes'].'</option>
                    <option value="0"', isset($_SESSION['show_last_items']) && $_SESSION['show_last_items'] != 1 ? ' selected="selected"' : '', '>'.$txt['no'].'</option>
                </select>
                <br />
            </div>
            <div style="">
                <input type="submit" name="save_button" value="'.$txt['save_button'].'">
            </div>
        </div>
    </form>
</div>';
?>