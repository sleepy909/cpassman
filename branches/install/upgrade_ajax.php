<?php
session_start();
if ( isset($_POST['type']) ){
    switch( $_POST['type'] ){
        case "step1":
            $res = "";
            // connexion
            if ( @mysql_connect($_POST['db_host'],$_POST['db_login'],$_POST['db_password']) ){
                if ( @mysql_select_db($_POST['db_bdd']) ){
                    echo 'gauge.modify($("pbar"),{values:[0.50,1]});';
                    $res = "Connection is successfull";
                    echo 'document.getElementById("but_next").disabled = "";';
                }else{
                    echo 'gauge.modify($("pbar"),{values:[0.35,1]});';
                    $res = "Impossible to get connected to table";
                    echo 'document.getElementById("but_next").disabled = "disabled";';
                }
            }else{
                echo 'gauge.modify($("pbar"),{values:[0.35,1]});';
                $res = "Impossible to get connected to server";
                echo 'document.getElementById("but_next").disabled = "disabled";';
            }
            echo 'document.getElementById("res_step1").innerHTML = "'.$res.'";'; 
            echo 'document.getElementById("loader").style.display = "none";';
        break;
        
        #==========================
        case "step2":
            // Database
            $res = "";
            
            @mysql_connect($_SESSION['db_host'],$_SESSION['db_login'],$_SESSION['db_pw']);
            @mysql_select_db($_SESSION['db_bdd']);
            $db_tmp = mysql_connect($_SESSION['db_host'], $_SESSION['db_login'], $_SESSION['db_pw']);
            mysql_select_db($_SESSION['db_bdd'],$db_tmp);
            
            ## Populate table MISC
            $res1 = mysql_query("
                INSERT INTO `".$_SESSION['tbl_prefix']."misc` (`type`, `intitule`, `valeur`) VALUES
                ('admin', 'max_latest_items', '10'),
                ('admin', 'enable_favourites', '1'),
                ('admin', 'show_last_items', '1');"
            );
            if ( $res1 ){
                echo 'document.getElementById("tbl_1").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step2").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_1").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## Alter USERS table
            $res2 = mysql_query("ALTER TABLE `".$_SESSION['tbl_prefix']."users` ADD COLUMN favourites VARCHAR(300);");
            $res3 = mysql_query("ALTER TABLE `".$_SESSION['tbl_prefix']."users` ADD COLUMN latest_items VARCHAR(300);");
            $res3 = mysql_query("ALTER TABLE `".$_SESSION['tbl_prefix']."users` ADD COLUMN personal_folder INT(1);");
            $res3 = mysql_query("ALTER TABLE `".$_SESSION['tbl_prefix']."nested_tree` ADD COLUMN personal_folder TINYINT(1);");
            if ( $res2 && $res3 ){
                echo 'document.getElementById("tbl_2").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step2").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_2").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            echo 'gauge.modify($("pbar"),{values:[1,1]});';
            echo 'document.getElementById("but_next").disabled = "";';
            echo 'document.getElementById("res_step2").innerHTML = "Database has been populated";'; 
            echo 'document.getElementById("loader").style.display = "none";';
            mysql_close($db_tmp);
        break;
    }
}
?>
