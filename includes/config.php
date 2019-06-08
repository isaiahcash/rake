<?php
session_start();
date_default_timezone_set("America/New_York");

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/../simplehtmldom/simple_html_dom.php');

$include_flag = include(__DIR__ . '/../../home/includes/restrict.php');
if($include_flag === 1) restrict_access();