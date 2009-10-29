<?php
require_once ('cfg.php');
//  Begin Function
function createRSSFile($post_title,$post_description,$post_link)
{
	$returnITEM = "<item>\n";
	# this will return the Title of the Article.
	$returnITEM .= "<title>".$post_title."</title>\n";
	# this will return the Description of the Article.
	$returnITEM .= "<description>".$post_description."</description>\n";
	# this will return the URL to the post.
	$returnITEM .= "<link>".$post_link."</link>\n";
	$returnITEM .= "</item>\n";
	return $returnITEM;
}
// Lets build the page
$filename = "/home/a5336948/public_html/feeds/index.xml";
$rootURL = "http://cpassman.net23.net/feeds/";
/*$filename = "http://localhost/main/feeds/index.xml";
$rootURL = "http://localhost/main/feeds/";*/
$latestBuild = date("r");
// Lets define the the type of doc we're creating.
$createXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$createXML .= "<rss version=\"0.92\">\n";
$createXML .= "<channel>
	<title>cPassMan Feed</title>
	<link>$rootURL</link>
	<description>Subscribe to cPassMan RSS feed and stay up to date with latest news and releases</description>
	<lastBuildDate>$latestBuild</lastBuildDate>
	<docs>http://backend.userland.com/rss092</docs>
	<language>en</language>
";

// Lets Get the News Articles
$content_search = "SELECT * FROM website_blog ORDER BY date DESC";
$content_results = mysql_query($content_search);
// Lets get the results
while ($articleInfo = mysql_fetch_object($content_results))
{
	$page = $rootURL."blog-". $row["id"] .".".prepare_title($articleInfo->sujet);
	$description = nl2br($articleInfo->rss_desc);
	$title = "$articleInfo->sujet";
	$createXML .= createRSSFile($title,$description,$page);
}
$createXML .= "</channel>\n </rss>";
// Finish it up
$filehandle = fopen($filename,"w") or die("Can't open the file");
fwrite($filehandle,$createXML);
fclose($filehandle);
//echo "XML Sitemap updated!";
?>