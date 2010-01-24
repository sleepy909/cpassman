<?php
####################################################################################################
## File : favourites.php
## Author : Nils Laumaillé
## Description : My favourites page
## 
## DON'T CHANGE !!!
## 
####################################################################################################

echo '
        <h2 style="margin-top:15px;">'.$txt['my_favourites'].'</h2>

        <div style="width:900px;margin-left:50px; line-height:25px;height:100%;overflow:auto;">';
        if ( empty($_SESSION['favourites']) )
            echo '
            ';
        else{
            echo '
            <table id="t_items" style="empty-cells: show;margin-top:5px;" cellspacing="0" cellpadding="5">
                <thead><tr>
                    <th></th><th>'.$txt['label'].'</th><th>'.$txt['description'].'</th><th>'.$txt['group'].'</th>
                </tr></thead>
                <tbody>';
                //Get favourites
                foreach($_SESSION['favourites'] as $fav){
                    if ( !empty($fav) ){
                        $data = mysql_fetch_array(mysql_query(
                            "SELECT i.label, i.description, i.id, i.id_tree, t.title 
                            FROM ".$k['prefix']."items AS i
                            INNER JOIN ".$k['prefix']."nested_tree AS t ON (t.id = i.id_tree)
                            WHERE i.id = ".$fav));
                        
                        echo '
                            <tr>
                                <td><img src="includes/images/key__arrow.png" onClick="javascript:window.location.href = \'index.php?page=items&amp;group='.$data['id_tree'].'&amp;id='.$data['id'].'\';" style="cursor:pointer;" /></td>
                                <td>'.$data['label'].'</td>
                                <td>'.$data['description'].'</td>
                                <td>'.$data['title'].'</td>';
                    }
                }
                echo '
                </tbody>
            </table>';
        }
        echo '
        </div>';
?>
