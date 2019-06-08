<?php
require_once('includes/config.php');

$result_id = $_POST['result_id'];

$sql = "UPDATE saved_results SET favorite = 1 WHERE result_id = :result_id";
$query = DB::query($sql, array("result_id" => $result_id));

if ($query !== false) {
    $output['flag'] = 1;
    $output['message'] = "Favorited ID " . $result_id. "!";
    echo json_encode($output);
    exit;
}


$output['flag'] = 0;
$output['message'] = "Error";
echo json_encode($output);
exit;