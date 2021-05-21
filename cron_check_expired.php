<?php
session_start();
date_default_timezone_set("America/New_York");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/includes/functions.php');
require_once(__DIR__ . '/includes/mysql.php');
require_once(__DIR__ . '/simplehtmldom/simple_html_dom.php');

$msg = "";
$msg = check_expired();
$subject = "Checked Expired Posts - " . date("m/d/y h:i:s a");
send_mail($subject, $msg);
