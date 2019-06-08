<?php
require_once('includes/config.php');

$result_id = $_POST['result_id'];

$sql = "SELECT url FROM saved_results WHERE result_id = :result_id";
$query = DB::query($sql, array("result_id" => $result_id));
$result = $query->fetch(PDO::FETCH_ASSOC);
$url = $result['url'];

$params = array("url" => $url, "insert_time" => time());
$sql = "INSERT INTO removed_urls (url, insert_time) VALUES (:url, :insert_time)";
$query = DB::query($sql, $params);

if($query !== false) {

    $sql = "DELETE FROM saved_results WHERE result_id = :result_id";
    $query = DB::query($sql, array("result_id" => $result_id));

    if ($query !== false) {
        $output['flag'] = 1;
        $output['message'] = "Removed ID " . $result_id . ".";
        echo json_encode($output);
        exit;
    }
}


$output['flag'] = 0;
$output['message'] = "Error";
echo json_encode($output);
exit;