<?php
require_once('includes/config.php');

$make_id = $_POST['make_id'];

$models = retrieve_models($make_id, 1);

echo json_encode($models);