<?php
//include("d:/sites_php/test/smf2_vt2/Themes/default/mod/settings.php");
include('../includes/settings.php'); 

$page = $_GET['page']; 

// get how many rows we want to have into the grid - rowNum parameter in the grid 
$limit = $_GET['rows']; 

// get index row - i.e. user click to sort. At first time sortname parameter -
// after that the index from colModel 
$sidx = $_GET['sidx']; 

// sorting order - at first time sortorder 
$sord = $_GET['sord']; 

// if we not pass at first time index use the first column for the index or what you want
if(!$sidx) $sidx =1; 

switch ( $_REQUEST['oper'] ){
    case "edit" :
        $SQL = "UPDATE ".$k['prefix']."users SET
                login = '".addslashes($_REQUEST['login'])."',
                groupes = '".addslashes($_REQUEST['groupes'])."',
                categories = '".addslashes($_REQUEST['categories'])."'
                WHERE id = '".$_REQUEST['id']."'";
        $result = mysql_query( $SQL ) or die("Edit : Couldn't execute query.".mysql_error()); 
        echo $SQL;
        break;
    
    case "add" :
        $SQL = "INSERT INTO ".$k['prefix']."users VALUES (NULL,'".$_REQUEST['login']."','".md5($_REQUEST['login'])."','".$_REQUEST['groupes']."','".$_REQUEST['categories']."','','".mktime(0,0,0,date('m'),date('d'),date('y'))."','',0)";
        $result = mysql_query( $SQL ) or die("ADD : Couldn't execute query.".mysql_error()); 
        echo $SQL;
        break;
    
    case "del" :
        $SQL = "DELETE FROM ".$k['prefix']."users WHERE id='".$_REQUEST['id']."'";
        $result = mysql_query( $SQL ) or die("ADD : Couldn't execute query.".mysql_error()); 
        echo $SQL;
        break;
    
    default:
        
        // calculate the number of rows for the query. We need this for paging the result 
        $result = mysql_query("SELECT COUNT(*) AS count FROM ".$k['prefix']."users"); 
        $row = mysql_fetch_array($result,MYSQL_ASSOC); 
        $count = $row['count']; 

        // calculate the total pages for the query 
        if( $count > 0 ) { 
                      $total_pages = ceil($count/$limit); 
        } else { 
                      $total_pages = 0; 
        } 

        // if for some reasons the requested page is greater than the total 
        // set the requested page to total page 
        if ($page > $total_pages) $page=$total_pages;

        // calculate the starting position of the rows 
        $start = $limit*$page - $limit;

        // if for some reasons start position is negative set it to 0 
        // typical case is that the user type 0 for the requested page 
        if($start <0) $start = 0; 

        // the actual query for the grid data 
        $SQL = "SELECT * FROM ".$k['prefix']."users ORDER BY $sidx $sord LIMIT $start , $limit"; 
        $result = mysql_query( $SQL ) or die($_REQUEST['oper']."Default : Couldn't execute query.".mysql_error()); 

        // we should set the appropriate header information
        if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                      header("Content-type: application/xhtml+xml;charset=iso-8859-1"); 
        } else {
                  header("Content-type: text/xml;charset=iso-8859-1");
        }
        echo "<?xml version='1.0' encoding='utf-8'?>";
        echo "<rows>";
        echo "<page>".$page."</page>";
        echo "<total>".$total_pages."</total>";
        echo "<records>".$count."</records>";

        // be sure to put text data in CDATA
        while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
            
            echo "<row id='". $row[id]."'>";     
                echo "<cell>". $row[id]."</cell>";       
                echo "<cell>". $row[login]."</cell>";   
                echo "<cell>". $row[pw]."</cell>";   
                echo "<cell>". $row[groupes]."</cell>";   
                echo "<cell><![CDATA[". $row[categories]."]]></cell>"; 
            echo "</row>";
        }
        echo "</rows>"; 
}


function Strip($value)
{
    if(get_magic_quotes_gpc() != 0)
      {
        if(is_array($value))  
            if ( array_is_associative($value) )
            {
                foreach( $value as $k=>$v)
                    $tmp_val[$k] = stripslashes($v);
                $value = $tmp_val; 
            }                
            else  
                for($j = 0; $j < sizeof($value); $j++)
                    $value[$j] = stripslashes($value[$j]);
        else
            $value = stripslashes($value);
    }
    return $value;
}
?>
