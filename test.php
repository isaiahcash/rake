<?php
require_once('includes/config.php');
start_page();

// Use this to redownload images from the ad link in the database. New images will be saved in the directory.
//resync_images();

// Just testing a link
//$link = "https://nashville.craigslist.org/ctd/d/mount-juliet-2016-subaru-brz-2dr-coupe/6950640664.html";
//$html = file_get_html($link);
//var_dump($html);

script_includes();
end_page();

?>