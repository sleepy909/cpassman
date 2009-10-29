<?php
include("cfg.php");
if (isset($_POST['type'])){
   switch($_POST['type']){
        case "poster_comment":
            mysql_query("INSERT INTO website_comments VALUES (
                NULL,
                '".$_POST['id']."',
                '".mysql_real_escape_string(stripslashes($_POST['name']))."',
                '".mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('y'))."',
                '".$_POST['email']."',
                '".$_POST['web']."',
                '".mysql_real_escape_string(stripslashes($_POST['comment']))."'
            )");
            //echo 'document.getElementById(\'form\').submit();';
        break;
        
        
        case 'send_email':
            $headers ='From: '.$_POST['email'];
            $header.= "MIME-Version: 1.0".'\n';
            $header.= 'Content-Type: text/plain; charset="iso-8859-1"\n';
             
            $message = (addslashes($_POST['message'])).'\r\n'.mysql_real_escape_string(addslashes($_POST['name']));
            mail('nils@cpassman.net23.net',  'Contact depuis cPassMan', $message, $headers); 
        break;
   }
}
?>
