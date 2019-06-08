<?php
require_once('includes/config.php');
$output['data'] = [];

$preset = $_POST['preset'];
$year_id = $_POST['year_id'];
$make_id = $_POST['make_id'];
$model_id = $_POST['model_id'];
$location = $_POST['location'];
$min_price = $_POST['min_price'];
$max_price = $_POST['max_price'];
$min_miles = $_POST['min_miles'];
$max_miles = $_POST['max_miles'];
$title_status = $_POST['title_status'];
$trans = $_POST['trans'];
if(isset($_POST['sorting'])) $sorting = $_POST['sorting'];
else $sorting = "";

//$preset = "All";
//$year_id = "All";
//$make_id = "All";
//$model_id = "All";
//$location = "35.978963$$$-83.948455";
//$min_price = "";
//$max_price = "";
//$min_miles = "";
//$max_miles = "";
//$title_status = "All";
//$trans = "All";

$tmp = explode("$$$", $location);
$from_lon = $tmp[1];
$from_lat = $tmp[0];

$results = retrieve_saved_results($year_id, $make_id, $model_id, $sorting);

$results = narrow_price($results, $min_price, $max_price);

$results = narrow_miles($results, $min_miles, $max_miles);
$results = narrow_title_status($results, $title_status);

$results = narrow_trans($results, $trans);

$results = narrow_preset($results, $preset);

$makes = retrieve_makes(1);
$years = retrieve_years();

foreach($results as $row)
{

    $row['image'] = "<img src='" . $row['image_src'] . "' style='object-fit: cover; height: 150px; width: 150px;' alt='" . $row['title'] . "'></img>";

    $row['url_raw'] = $row['url'];
    $row['url'] = "<a href='" . $row['url'] . "' target='_blank'>Click</a>";


    $assignment = "<div id='assignment_current_" . $row['result_id'] . "'>";
    $assignment .= convert_year($row['year_id']) . " " . convert_make($row['make_id']) . " " . convert_model($row['model_id']);
    $assignment .="<button class='btn btn-sm btn-info ml-2' style='padding: 2px 6px;' onclick='edit_assignment(" . $row['result_id'] . ")'><i class='fas fa-pencil-alt fa-xs'></i></button>";
    $assignment .= "</div>";
    $assignment .= "<div id='assignment_new_" . $row['result_id'] . "' style='display: none'>";
    $assignment .= "<select class='custom-select my-1' id='assignment_new_year_" . $row['result_id'] . "'>";
    foreach($years as $year)
    {
        if($year['year_id'] == $row['year_id']) $flag = "selected";
        else $flag = "";
        $assignment .= "<option value='" . $year['year_id'] . "' " . $flag . ">" . $year['year_value'] . "</option>";
    }
    $assignment .= "</select>";
    $assignment .= "<select class='custom-select my-1' id='assignment_new_make_" . $row['result_id'] . "' onchange='fill_assignment_model(" . $row['result_id'] . ")'>";
    foreach($makes as $make)
    {
        if($make['make_id'] == $row['make_id']) $flag = "selected";
        else $flag = "";
        $assignment .= "<option value='" . $make['make_id'] . "' " . $flag . ">" . $make['make_name'] . "</option>";
    }

    $assignment .= "</select>";
    $assignment .= "<select class='custom-select my-1' id='assignment_new_model_" . $row['result_id'] . "'>";
    if($row['make_id'] != 1000)
    {
        $models = retrieve_models($row['make_id'], 1);
        foreach($models as $model)
        {
            if($model['model_id'] == $row['model_id']) $flag = "selected";
            else $flag = "";
            $assignment .= "<option value='" . $model['model_id'] . "' " . $flag . ">" . $model['model_name'] . "</option>";
        }
    }
    else
    {
        $assignment .= "<option value='1000'>Unknown</option>";
    }

    $assignment .= "</select>";
    $assignment .= "<button class='btn btn-sm btn-success my-1' style='padding: 2px 6px' onclick='save_assignment(" . $row['result_id'] . ")'><i class='far fa-save fa-xs'></i></button>";
    $assignment .= "<button class='btn btn-sm btn-danger my-1 ml-1' style='padding: 2px 6px' onclick='cancel_assignment(" . $row['result_id'] . ")'><i class='fas fa-window-close fa-xs'></i></button>";

    $assignment .= "</div>";

    $row['assignment']['display'] = $assignment;
    $row['assignment']['value'] = convert_year($row['year_id']) . " " . convert_make($row['make_id']) . " " . convert_model($row['model_id']);

    $row['alt_price']['value'] = $row['price'];
    if($row['price'] == "") $row['alt_price']['display'] = "";
    else $row['alt_price']['display'] = "$" . $row['price'];


    $row['title_status'] = ucfirst($row['title_status']);

    $to_lon = $row['longitude'];
    $to_lat = $row['latitude'];
    $distance = distance($from_lat, $from_lon, $to_lat, $to_lon, "M");

    if($distance == "Unknown") $distance_value = 0;
    else $distance_value = $distance;

    $row['distance']['display'] = $distance;
    $row['distance']['value'] = $distance_value;


    $row['alt_post_date']['display'] = time_elapsed($row['post_date']);
    $row['alt_post_date']['value'] = $row['post_date'];

    $row['alt_found_date']['display'] = time_elapsed($row['found_date']);
    $row['alt_found_date']['value'] = $row['found_date'];

    $row['vin'] = strtoupper($row['vin']);

    $row['trans'] = ucfirst($row['trans']);

    $buttons = "<button class='btn btn-danger btn-sm' style='width: 80px' id='remove_button_" . $row['result_id'] . "' onclick='remove_id(" . $row['result_id'] . ", true)'>Remove</button>";
    if($row['favorite'] == 0) $buttons .= "<button class='btn btn-info btn-sm mt-2' style='width: 80px' id='like_button_" . $row['result_id'] . "' onclick='like_id(" . $row['result_id'] . ")'>Favorite</button>";
    else $buttons .= "<button class='btn btn-success btn-sm mt-2' style='width: 80px' id='dislike_button_" . $row['result_id'] . "' onclick='dislike_id(" . $row['result_id'] . ")'>Saved! <i class='fas fa-heart fa-xs'></i></button>";
    $row['buttons'] = $buttons;

    $buttons_grid = "<div class='col-6'><button class='btn btn-danger btn-sm mt-2 w-100' id='remove_button_" . $row['result_id'] . "' onclick='remove_id(" . $row['result_id'] . ", true)'>Remove</button></div>";
    if($row['favorite'] == 0) $buttons_grid .= "<div class='col-6'><button class='btn btn-info btn-sm mt-2 w-100' id='like_button_" . $row['result_id'] . "' onclick='like_id(" . $row['result_id'] . ")'>Favorite</button></div>";
    else $buttons_grid .= "<div class='col-6'><button class='btn btn-success btn-sm mt-2 w-100' id='dislike_button_" . $row['result_id'] . "' onclick='dislike_id(" . $row['result_id'] . ")'>Saved! <i class='fas fa-heart fa-xs'></i></button></div>";
    $row['buttons_grid'] = $buttons_grid;

    $output['data'][] = $row;



}

echo json_encode($output);


?>
