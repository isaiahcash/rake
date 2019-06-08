<?php
require_once('includes/config.php');

$msg = "";
$msg = check_expired();
$subject = "Checked Expired Posts - " . date("m/d/y h:i:s a");
send_mail($subject, $msg);
