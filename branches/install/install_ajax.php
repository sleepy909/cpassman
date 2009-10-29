<?php
session_start();
if ( isset($_POST['type']) ){
    switch( $_POST['type'] ){
        case "step1":
            $abspath = str_replace('\\','/',$_POST['abspath']);
            if ( substr($abspath,strlen($abspath)-1) == "/" ) $abspath = substr($abspath,0,strlen($abspath)-1);
            $ok_writable = true;
            $ok_extensions = true;
            $txt = "";
            $x=1;
            $tab = array($abspath."/install/settings.php",$abspath."/install/",$abspath."/includes/",$abspath."/files/");
            foreach($tab as $elem){
                if ( is_writable($elem) )
                    $txt .= '<span style=\"padding-left:30px;font-size:13pt;\">'.$elem.'&nbsp;&nbsp;<img src=\"images/tick-circle.png\"></span><br />';
                else{
                    $txt .= '<span style=\"padding-left:30px;font-size:13pt;\">'.$elem.'&nbsp;&nbsp;<img src=\"images/minus-circle.png\"></span><br />';
                    $ok_writable = false;
                }
                $x++;
            }
            
            if (!extension_loaded('mcrypt')) {
                $ok_extensions = false;
                $txt .= '<span style=\"padding-left:30px;font-size:13pt;\">PHP extension \"mcrypt\"&nbsp;&nbsp;<img src=\"images/minus-circle.png\"></span><br />';
            }else{
                $txt .= '<span style=\"padding-left:30px;font-size:13pt;\">PHP extension \"mcrypt\"&nbsp;&nbsp;<img src=\"images/tick-circle.png\"></span><br />';
            }
            
            if ( $ok_writable == true && $ok_extensions == true ) {
                echo 'document.getElementById("but_next").disabled = "";';
                echo 'document.getElementById("status_step1").innerHTML = "Elements are OK.";';
                echo 'gauge.modify($("pbar"),{values:[0.20,1]});';
            }else{
                echo 'document.getElementById("but_next").disabled = "disabled";';
                echo 'document.getElementById("status_step1").innerHTML = "Correct the shown errors and click on button Launch to refresh";';
                echo 'gauge.modify($("pbar"),{values:[0.10,1]});';
            }
            
            echo 'document.getElementById("res_step1").innerHTML = "'.$txt.'";'; 
            echo 'document.getElementById("loader").style.display = "none";';
        break;   
        
        #==========================
        case "step2":
            $res = "";
            // connexion
            if ( @mysql_connect($_POST['db_host'],$_POST['db_login'],$_POST['db_password']) ){
                if ( @mysql_select_db($_POST['db_bdd']) ){
                    echo 'gauge.modify($("pbar"),{values:[0.40,1]});';
                    $res = "Connection is successfull";
                    echo 'document.getElementById("but_next").disabled = "";';
                }else{
                    echo 'gauge.modify($("pbar"),{values:[0.30,1]});';
                    $res = "Impossible to get connected to table";
                    echo 'document.getElementById("but_next").disabled = "disabled";';
                }
            }else{
                echo 'gauge.modify($("pbar"),{values:[0.30,1]});';
                $res = "Impossible to get connected to server";
                echo 'document.getElementById("but_next").disabled = "disabled";';
            }
            echo 'document.getElementById("res_step2").innerHTML = "'.$res.'";'; 
            echo 'document.getElementById("loader").style.display = "none";';
        break;
        
        #==========================
        case "step4":
            // Populate Database
            $res = "";
            
            @mysql_connect($_SESSION['db_host'],$_SESSION['db_login'],$_SESSION['db_pw']);
            @mysql_select_db($_SESSION['db_bdd']);
            $db_tmp = mysql_connect($_SESSION['db_host'], $_SESSION['db_login'], $_SESSION['db_pw']);
            mysql_select_db($_SESSION['db_bdd'],$db_tmp);
            
            ## TABLE 1
            $res1 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."functions` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `title` varchar(50) NOT NULL,
                  `groupes_visibles` varchar(255) NOT NULL,
                  `groupes_interdits` varchar(255) NOT NULL,
                  PRIMARY KEY (`id`)
                );");
            if ( $res1 ){
                echo 'document.getElementById("tbl_1").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_1").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 2
            $res2 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."items` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `label` varchar(100) NOT NULL,
                  `description` text NOT NULL,
                  `pw` varchar(100) NOT NULL,
                  `url` varchar(250) DEFAULT NULL,
                  `id_tree` varchar(10) DEFAULT NULL,
                  `perso` tinyint(1) NOT NULL DEFAULT '0',
                  `login` varchar(200) DEFAULT NULL,
                  `inactif` tinyint(1) NOT NULL DEFAULT '0',
                  `restricted_to` varchar(200) NOT NULL,
                  PRIMARY KEY (`id`)
                );");
            if ( $res2 ){
                echo 'document.getElementById("tbl_2").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_2").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 3
            $res3 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."log_items` (
                  `id_item` int(8) NOT NULL,
                  `date` varchar(50) NOT NULL,
                  `id_user` tinyint(4) NOT NULL,
                  `action` varchar(250) NOT NULL,
                  `raison` text NOT NULL
                );");
            if ( $res3 ){
                echo 'document.getElementById("tbl_3").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_3").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 4
            $res4 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."misc` (
                  `type` varchar(50) NOT NULL,
                  `intitule` varchar(100) NOT NULL,
                  `valeur` varchar(100) NOT NULL
                );");
            if ( $res4 ){
                echo 'document.getElementById("tbl_4").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_4").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 5
            $res5 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."nested_tree` (
                  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                  `parent_id` int(11) NOT NULL,
                  `title` varchar(255) NOT NULL,
                  `nleft` int(11) NOT NULL,
                  `nright` int(11) NOT NULL,
                  `nlevel` int(11) NOT NULL,
                  `bloquer_creation` tinyint(1) NOT NULL DEFAULT '0',
                  `bloquer_modification` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `id` (`id`),
                  KEY `nested_tree_parent_id` (`parent_id`),
                  KEY `nested_tree_nleft` (`nleft`),
                  KEY `nested_tree_nright` (`nright`),
                  KEY `nested_tree_nlevel` (`nlevel`)
                );");
            if ( $res5 ){
                echo 'document.getElementById("tbl_5").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_5").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 6
            $res6 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."rights` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `tree_id` int(12) NOT NULL,
                  `fonction_id` int(12) NOT NULL,
                  `authorized` tinyint(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
                );");
            if ( $res6 ){
                echo 'document.getElementById("tbl_6").innerHTML = "<img src=\"images/tick.png\">";'; 
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_6").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            ## TABLE 7
            $res7 = mysql_query("
                CREATE TABLE IF NOT EXISTS `".$_SESSION['tbl_prefix']."users` (
                  `id` int(12) NOT NULL AUTO_INCREMENT,
                  `login` varchar(50) NOT NULL,
                  `pw` varchar(50) NOT NULL,
                  `groupes_visibles` varchar(250) NOT NULL,
                  `derniers` text NOT NULL,
                  `key_tempo` varchar(100) NOT NULL,
                  `last_pw_change` varchar(30) NOT NULL,
                  `last_pw` text NOT NULL,
                  `admin` tinyint(1) NOT NULL DEFAULT '0',
                  `fonction_id` varchar(255) NOT NULL,
                  `groupes_interdits` varchar(255) NOT NULL,
                  `last_connexion` varchar(30) NOT NULL,
                  `gestionnaire` int(11) NOT NULL DEFAULT '0',
                  `email` varchar(300) NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `login` (`login`)
                );");
            if ( $res7 ){
                echo 'document.getElementById("tbl_7").innerHTML = "<img src=\"images/tick.png\">";'; 
                
                //vérifier que l'admin n'existe pas
                $tmp = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `".$_SESSION['tbl_prefix']."users` WHERE login = 'admin'"));
                if ( $tmp[0] == 0 ){
                    $res8 = mysql_query("
                        INSERT INTO `".$_SESSION['tbl_prefix']."users` (`id`, `login`, `pw`, `groupes_visibles`, `derniers`, `key_tempo`, `last_pw_change`, `last_pw`, `admin`, `fonction_id`, `groupes_interdits`, `last_connexion`, `gestionnaire`, `email`) VALUES (NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', '', 1, '', '', '', 0, '');");
                    if ( $res8 ){
                        echo 'document.getElementById("tbl_8").innerHTML = "<img src=\"images/tick.png\">";';
                    }else{
                        echo 'document.getElementById("res_step4").innerHTML = "Could not import admin account!";'; 
                        echo 'document.getElementById("tbl_8").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                        echo 'document.getElementById("loader").style.display = "none";';
                        mysql_close($db_tmp);
                        break;  
                    }
                }else echo 'document.getElementById("tbl_8").innerHTML = "<img src=\"images/tick.png\">";';
            }else{
                echo 'document.getElementById("res_step4").innerHTML = "An error appears on a table!";'; 
                echo 'document.getElementById("tbl_7").innerHTML = "<img src=\"images/exclamation-red.png\">";'; 
                echo 'document.getElementById("loader").style.display = "none";';
                mysql_close($db_tmp);
                break;   
            }
            
            echo 'gauge.modify($("pbar"),{values:[0.80,1]});';
            echo 'document.getElementById("but_next").disabled = "";';
            echo 'document.getElementById("res_step4").innerHTML = "Database has been populated";'; 
            echo 'document.getElementById("loader").style.display = "none";';
            mysql_close($db_tmp);
        break;
        
        
        case "step5":
            $filename = "../includes/settings.php";
            $events = "";
            if (file_exists($filename)) {
                if ( !copy($filename, $filename.'.'.date("Y_m_d",mktime(0,0,0,date('m'),date('d'),date('y')))) ) {
                    echo 'document.getElementById("res_step4").innerHTML = "Setting.php file already exists and cannot be renamed. Please do it by yourself and click on button Launch.";'; 
                    echo 'document.getElementById("loader").style.display = "none";';
                    break;
                }else{
                    $events .= "The file $filename already exist. A copy has been created.<br />";
                }
            } 
            
            $fh = fopen($filename, 'w') or die("can't open file");
            
            fwrite($fh, "<?php
global \$lang, \$txt, \$k, \$chemin_passman, \$url_passman, \$mdp_complexite, \$mng_pages;
global \$smtp_server, \$smtp_auth, \$smtp_auth_username, \$smtp_auth_password, \$email_from,\$email_from_name;

include('include.php');

\$k['user_password_limit'] = ".$_SESSION['pw_validity']."; //Number of days the user's password is available. After this limit, user has to enter a new password.
\$k['charset'] = \"".$_SESSION['charset']."\";  //the charset you want to use    : French => ISO-8859-15
\$k['prefix'] = \"".$_SESSION['tbl_prefix']."\";  //tables prefix
@define('SALT', '".$_SESSION['encrypt_key']."'); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
\$smtp_server = \"".$_SESSION['smtp_server']."\";
\$smtp_auth = ".$_SESSION['smtp_auth']."; //false or true
\$smtp_auth_username = \"".$_SESSION['smtp_auth_username']."\";
\$smtp_auth_password = \"".$_SESSION['smtp_auth_password']."\";
\$email_from = \"".$_SESSION['email_from']."\";
\$email_from_name = \"".$_SESSION['email_from_name']."\";

### DATABASE connexion parameters ###
\$db_host = \"".$_SESSION['db_host']."\";
\$db_login = \"".$_SESSION['db_login']."\";
\$db_password = \"".$_SESSION['db_pw']."\";
\$db_bdd = \"".$_SESSION['db_bdd']."\";

### connexion ###
@mysql_connect(\$db_host,\$db_login,\$db_password)
   or die(\"Impossible to get connected to server\");
@mysql_select_db(\"\$db_bdd\")
   or die(\"Impossible to get connected to table\");

\$db = mysql_connect(\$db_host, \$db_login, \$db_password);
mysql_select_db(\$db_bdd,\$db);

?>");            
            
            fclose($fh);
            echo 'gauge.modify($("pbar"),{values:[1,1]});';
            echo 'document.getElementById("but_next").disabled = "";';
            echo 'document.getElementById("res_step5").innerHTML = "Setting.php file has created.";'; 
            echo 'document.getElementById("loader").style.display = "none";';
        break;
    }
}
?>
