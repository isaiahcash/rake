<?php
require_once('includes/config.php');

$result_id = $_POST['result_id'];
$year_id = $_POST['year_id'];
$model_id = $_POST['model_id'];
$make_id = $_POST['make_id'];

$params = array(
    "result_id" => $result_id,
    "year_id" => $year_id,
    "model_id" => $model_id,
    "make_id" => $make_id
);
$sql = "UPDATE saved_results SET year_id = :year_id, model_id = :model_id, make_id = :make_id WHERE result_id = :result_id";
$query = DB::query($sql, $params);

if($query !== false) {
    $output['flag'] = 1;
    $output['message'] = "Updated assignment!";
    echo json_encode($output);
    exit;
}


$output['flag'] = 0;
$output['message'] = "Error";
echo json_encode($output);
exit;