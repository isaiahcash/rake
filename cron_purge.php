<?php
require_once('includes/config.php');

$msg = "Start! <br>";

// Delete expired results that were found more than 30 days ago
$msg .= "Deleting expired results that were found more than 30 days ago... <br><br>";
$thirty_days_ago = time() - 60*60*24*30;
$i = 0;
$sql = "SELECT * FROM saved_results WHERE expired = 1 AND found_date < :past_time";
$query = DB::query($sql, ["past_time" => $thirty_days_ago]);
while($result = $query -> fetch(PDO::FETCH_ASSOC))
{
    $i++;
    $sql_delete = "DELETE FROM saved_results WHERE result_id = :result_id";
    $query_delete = DB::query($sql_delete, ["result_id" => $result['result_id']]);
}

$msg .= "There were " . $i . " results removed from the database. <br><br><hr>";

// Purge all images that are no longer in the database or expired
$msg .= "Deleting images from directory... <br>";
$msg .= purge_images();
$msg .= "<br><hr>";

// Delete all removed urls that are older than 45 days
$msg .= "Deleting the 'removed_urls' that were found more than 45 days ago... <br><br>";
$forty_five_days_ago = time() - 60*60*24*45;
$i = 0;
$sql = "SELECT * FROM removed_urls WHERE insert_time < :past_time";
$query = DB::query($sql, ["past_time" => $forty_five_days_ago]);
while($result = $query -> fetch(PDO::FETCH_ASSOC))
{
    $i++;
    $sql_delete = "DELETE FROM removed_urls WHERE r_id = :r_id";
    $query_delete = DB::query($sql_delete, ["r_id" => $result['r_id']]);
}
$msg .= "There were " . $i . " urls removed from the database. <br><br>";
$msg .= "End";

$subject = "Directory and Database Deletion - " . date("m/d/y h:i:s a");
send_mail($subject, $msg);

