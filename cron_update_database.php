<?php
session_start();
date_default_timezone_set("America/New_York");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/mysql.php');
require_once(__DIR__ . '/simplehtmldom/simple_html_dom.php');

$msg = "";

$sql = "SELECT * FROM cron_updates";
$query = DB::query($sql);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

foreach($result as $row)
{
    if(time() > $row['update_interval'] + $row['last_updated'])
    {
        $msg .= "Updating database: " . convert_make($row['make_id']) . " " . convert_model($row['model_id']) . "<br><br>";
        $sql = "UPDATE cron_updates SET last_updated = :last_updated WHERE u_id = :u_id";
        $query = DB::query($sql, ["last_updated" => time(), "u_id" => $row['u_id']]);


        $msg .= update_database($row['make_id'], $row['model_id'], 200);


    }
}

if($msg != "")
{
    $subject = "Database Updated - " . date("m/d/y h:i:s a");
    send_mail($subject, $msg);
}



