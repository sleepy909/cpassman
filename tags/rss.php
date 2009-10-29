<?php
  header("Content-Type: application/xml; charset=ISO-8859-1");
  include("includes/rss_feed.php");
  $rss = new RSS();
  echo $rss->GetFeed();
?>

