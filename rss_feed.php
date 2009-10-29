<?php

  class RSS
  {
    public function RSS()
    {
        require_once ('cfg.php');
    }

    public function GetFeed()
    {
        return $this->getDetails() . $this->getItems();
    }

    private function getDetails()
    {
        $detailsTable = "website_blog";
        $query = "SELECT * FROM ". $detailsTable;
        $result = mysql_query ($query);

        while($row = mysql_fetch_array($result))
        {
            $details = '<?xml version="1.0" encoding="ISO-8859-1" ?>
                <rss version="2.0">
                    <channel>
                        <title>'. $row['sujet'] .'</title>
                        <link>http://cpassman.net23.net/blog-'. $row['id'] .'</link>
                        <description>'. $row['texte'] .'</description>
                        <language>EN</language>';
                        
                        /*<image>
                            <title>'. $row['image_title'] .'</title>
                            <url>'. $row['image_url'] .'</url>
                            <link>'. $row['image_link'] .'</link>
                            <width>'. $row['image_width'] .'</width>
                            <height>'. $row['image_height'] .'</height>
                        </image>*/
        }
        return $details;
    }

    private function getItems()
    {
        $itemsTable = "website_blog";
        $query = "SELECT * FROM ". $itemsTable;
        $result = mysql_query ($query);
        $items = '';
        while($row = mysql_fetch_array($result))
        {
            $items .= '<item>
                <title>'. $row["sujet"] .'</title>
                <link>http://cpassman.net23.net/blog-'. $row["id"] .'</link>
                <description><![CDATA['. $row["texte"] .']]></description>
            </item>';
        }
        $items .= '</channel>
                </rss>';
        return $items;
    }

}

?>
