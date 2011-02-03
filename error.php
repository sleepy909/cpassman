<?php
####################################################################################################
## File : error.php
## Author : Nils Laumaillé
## Description : Error page
## 
## DON'T CHANGE !!!
## 
####################################################################################################
echo '
<div style="width:800px;margin:auto;">';
if ( $_SESSION['error'] == 1000 ){
    echo '
    <div class="ui-state-error ui-corner-all error" >'.$txt['error_not_authorized'].'</div>';
}else if ( $_SESSION['error'] == 1001 ){
    echo '
    <div class="ui-state-error ui-corner-all error" >'.$txt['error_not_exists'].'</div>';
}
echo '
</div>';
?>
