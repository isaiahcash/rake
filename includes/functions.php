<?php
/* template */
function start_page()
{
    ?>
    <!DOCTYPE HTML>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Web Scraper</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://isaiahcash.com/business/includes/source/datatables/datatables.js">
        <link rel="stylesheet" href="https://isaiahcash.com/business/includes/source/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="https://isaiahcash.com/rake/includes/css/style.css">
    </head>
    <body>
    <div class="d-block" style="background-color: #e9ecef">
        <a class="btn bg-purple btn-large m-1" href="/home/projects.php"><i class="fas fa-arrow-left"></i> Return to Isaiah's Website</a>
    </div>
    <?php
}

function script_includes()
{
    ?>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://isaiahcash.com/business/includes/source/datatables/datatables.js"></script>
    <script src="https://isaiahcash.com/business/includes/source/datatables/DataTables-1.10.18/js/dataTables.bootstrap4.js"></script>
    <script src="https://isaiahcash.com/rake/includes/js/scripts.js"></script>
    <?php
}

function end_page()
{
    ?>
    <button onclick="topFunction()" id="top_button" title="Go to top"> <i class="fas fa-arrow-up"></i></button>
    </body>
    </html>
    <?php
}


/* update.php */



/******************************/
function update_database($make_id, $model_id, $limit)
{
    $msg = "";

    if (!is_numeric($model_id) || !is_numeric($make_id)) {
        $msg = "Error with make/model options.";
        return $msg;
    }

    $search_make_id = $make_id;
    $search_model_id = $model_id;

    if (!is_numeric($limit)) $limit = 25;

    $query = strtolower(convert_make($make_id)) . " " . strtolower(convert_model($model_id));
    $query = str_replace(" ", "%20", $query);

    $removed = retrieve_removed_links();
    $cities = retrieve_cities();
    $saved_links = retrieve_saved_links();
    $ignored = retrieve_ignored_links($make_id, $model_id);
    $all_links = [];

    $i = 0;
    $l = 0;
    foreach ($cities as $city) {

        $search_url = "https://" . $city['search_value'] . ".craigslist.org/search/cta?sort=date&query=" . $query;

        $html = file_get_html($search_url);

        $j = 0;
        $k = 0;
        foreach ($html->find('li.result-row') as $result) {
            $link_div = $result->find('a.result-image', 0);
            $link = $link_div->href;

            // Skip if this link belongs to another city (from the 'nearby results')
            if (strpos($link, $city['search_value']) === false) continue;

            // Skip if this is a removed link
            if (in_array($link, $removed) !== false) continue;

            // Skip if this is an ignored link (a different make and model)
            if (in_array($link, $ignored) !== false) continue;

            // Skip if this is link has already been found in the current session
            if (in_array($link, $all_links) !== false) continue;

            // Skip if this link is already in the database
            if (in_array($link, $saved_links) !== false) {
                $k++;
                continue;
            }

            $i++;
            if ($i > $limit) break;

            $j++;

            $all_links[] = $link;
        }
        $l = $l + $j;

        $msg .= $city['city_name'] . " - Saved: " . $k . " | New: " . $j . "<br>";

    }

    $msg .="<br>Total New Results: " . $l . "<br><br>";

    $results = array();
    $i = 0;
    foreach ($all_links as $link) {

        $page = parse_page($link);
        $check = save_result($page['year_id'], $page['make_id'], $page['model_id'], $page['link'], $page['images'][0], $page['title'], $page['price'], $page['post_date'], $page['post'], $page['found_date'], $page['attr'], $search_make_id, $search_model_id);
        if ($check) $msg .= "Saved result! <br>";
        else $msg .= "Not saved. (" . convert_make($page['make_id']) . " - $" . $page['price'] . " - " . $page['attr']['odo'] . " mi. )<br>";

    }

    return $msg;
}
/*****************************/


function retrieve_cities()
{
    $results = array();
    $sql = "SELECT * FROM search_cities";
    $query = DB::query($sql);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $results[] = $result;
    }

    return $results;
}

function retrieve_removed_links()
{
    $results = array();
    $sql = "SELECT url FROM removed_urls";
    $query = DB::query($sql);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $results[] = $result['url'];
    }

    return $results;
}

function retrieve_ignored_links($make_id, $model_id)
{
    $results = array();

    $params = ["make_id" => $make_id, "model_id" => $model_id];
    $sql = "SELECT url FROM ignored_urls WHERE make_id = :make_id AND model_id = :model_id";
    $query = DB::query($sql, $params);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $results[] = $result['url'];
    }

    return $results;
}


function retrieve_saved_links()
{
    $results = array();
    $sql = "SELECT url FROM saved_results";
    $query = DB::query($sql);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $results[] = $result['url'];
    }

    return $results;
}

function retrieve_title_statuses()
{
    $unique_statuses = [];

    $sql = "SELECT title_status FROM saved_results";
    $query = DB::query($sql);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $status = ucfirst($result['title_status']);

        if(!in_array($status, $unique_statuses))
        {
            $unique_statuses[] = $status;
        }
    }

    return $unique_statuses;
}

function retrieve_trans()
{
    $unique_trans = [];

    $sql = "SELECT trans FROM saved_results";
    $query = DB::query($sql);
    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $trans = ucfirst($result['trans']);

        if(!in_array($trans, $unique_trans))
        {
            $unique_trans[] = $trans;
        }
    }

    return $unique_trans;
}

function check_attr($attr_formatted)
{

    $attr_type_array = array(
        "vin" => "VIN: ",
        "cond" => "condition: ",
        "cyl" => "cylinders: ",
        "drive" => "drive: ",
        "fuel" => "fuel: ",
        "odo" => "odometer: ",
        "color" => "paint color: ",
        "size" => "size: ",
        "title_status" => "title status: ",
        "trans" => "transmission: ",
        "type" => "type: "
    );

    // Search for an attribute
    foreach($attr_type_array as $type => $string)
    {
        if(strpos($attr_formatted, $string) !== false)
        {
            return $type;
        }
    }

    // Search for a model (defined by a year [number])
    $model_search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    foreach($model_search_array as $string)
    {
        if(strpos($attr_formatted, $string) !== false)
        {
            return "found_model";
        }
    }

    return false;
}

function trim_attr($attr_formatted)
{
    $index = strpos($attr_formatted, ":");
    if($index !== false) $attr_formatted = substr($attr_formatted, $index + 1);

    return $attr_formatted;
}

function ignore_result($search_make_id, $search_model_id, $url)
{
    $params = [
        "make_id" => $search_make_id,
        "model_id" => $search_model_id,
        "url" => $url,
        "insert_time" => time()
    ];


    $sql = "INSERT INTO ignored_urls (make_id, model_id, url, insert_time) VALUES (:make_id, :model_id, :url, :insert_time)";
    $query = DB::query($sql, $params);

    if($query) return true;
}


function save_result($year_id, $make_id, $model_id, $url, $image_src, $title, $price, $post_date, $post, $found_date, $attr, $search_make_id, $search_model_id)
{
    if($make_id != "")
    {
        if(!check_active_make_id($make_id))
        {
            $check = ignore_result($search_make_id, $search_model_id, $url);
            return false;
        }
    }

    if($model_id != "")
    {
        if(!check_active_model_id($model_id))
        {
            $check = ignore_result($search_make_id, $search_model_id, $url);
            return false;
        }
    }


    if(isset($attr['odo'])) {
        if ($attr['odo'] > 150000)
        {
            $check = ignore_result($search_make_id, $search_model_id, $url);
            return false;
        }
    }

    if(isset($price)) {
        if ($price > 20000)
        {
            $check = ignore_result($search_make_id, $search_model_id, $url);
            return false;
        }
    }


    $attr_types = array(
        "found_model",
        "vin",
        "cond",
        "cyl",
        "drive",
        "fuel",
        "odo",
        "color",
        "size",
        "title_status",
        "trans",
        "type",
        "latitude",
        "longitude"
    );

    if(!isset($image_src)) $image_src = "";
    else $image_src = save_image($image_src);

    if(!isset($title)) $title = "";
    if(!isset($price)) $price = "";
    if(!isset($post)) $post = "";

    $expired_last_check = time();

    $params = array(
        "year_id" => $year_id,
        "make_id" => $make_id,
        "model_id" => $model_id,
        "url" => $url,
        "image_src" => $image_src,
        "title" => $title,
        "price" => $price,
        "post_date" => $post_date,
        "post" => $post,
        "found_date" => $found_date,
        "expired" => 0,
        "expired_last_check" => $expired_last_check,
        "favorite" => 0
    );
    $sql = "INSERT INTO saved_results (year_id, make_id, model_id, url, image_src, title, price, post_date, post, found_date, expired, expired_last_check, favorite) VALUES (:year_id, :make_id, :model_id, :url, :image_src, :title, :price, :post_date, :post, :found_date, :expired, :expired_last_check, :favorite)";
    $query = DB::query($sql, $params);

    if($query != false)
    {


        $query = DB::query($sql, $params);

        $result_id = DB::last_insert_id();
        foreach($attr_types as $attr_type) {
            if(isset($attr[$attr_type])) {
                $sql = "UPDATE saved_results SET " . $attr_type . " = :" . $attr_type . " WHERE result_id = :result_id";
                $query = DB::query($sql, array($attr_type => $attr[$attr_type], "result_id" => $result_id));
            }
            else
            {
                $sql = "UPDATE saved_results SET " . $attr_type . " = '' WHERE result_id = :result_id";
                $query = DB::query($sql, array("result_id" => $result_id));
            }
        }

        return $result_id;
    }

    return false;
}

/* page parsing */
function parse_page($link)
{
    $page = array();
    $attr = array();

    $html = file_get_html($link);

    $page['link'] = $link;

    $i = 0;
    $images = array();
    foreach ($html->find('img') as $image) {
        $i++;
        if ($i > 10) break;

        if (strpos($image->src, "x50") === false) {
            $images[] = $image->src;
        }
    }
    if(!isset($images[0])) $images[0] = "";
    $page['images'] = $images;

    $title_div = $html->find('span#titletextonly', 0);
    if($title_div) $page['title'] = clean($title_div->innertext);
    else $page['title'] = "";


    $price_div = $html->find('span.price', 0);
    if($price_div) $page['price'] = clean_price(clean($price_div->innertext));
    else $page['price'] = "";

    $post_date_div = $html->find('time.date', 0);
    if($post_date_div) $page['post_date'] = strtotime(clean($post_date_div->datetime));
    else $page['post_date'] = "";

    $post_div = $html->find('section#postingbody', 0);
    if($post_div) {
        $post = $post_div->innertext;
        $post = str_replace("QR Code Link to This Post", "", $post);
        $post = clean($post);
        $post = strlen($post) > 1000 ? substr($post, 0, 1000) . "..." : $post;
        $page['post'] = $post;
    }
    else $page['post'] = "";

    $map_div = $html->find('div#map', 0);
    if($map_div) {
        $attr['latitude'] = $map_div->{'data-latitude'};
        $attr['longitude'] = $map_div->{'data-longitude'};
    }
    else
    {
        $attr['latitude'] = "";
        $attr['longitude'] = "";
    }

    foreach ($html->find('p.attrgroup') as $attr_div) {
        if($attr_div) {
            foreach ($attr_div->find('span') as $attr_div_item) {
                $attr_unformatted = $attr_div_item->innertext;
                $attr_formatted = clean($attr_unformatted);
                $attr_type = check_attr($attr_formatted);
                if ($attr_type != false) $attr[$attr_type] = clean(trim_attr($attr_formatted));
            }
        }
    }
    $page['attr'] = $attr;

    if(!isset($page['attr']['found_model'])) $page['attr']['found_model'] = "";
    if(!isset($page['attr']['odo'])) $page['attr']['odo'] = "";


    $page['year_id'] = parse_year_id($page['attr']['found_model']);
    $page['make_id'] = parse_make_id($page['attr']['found_model']);
    $page['model_id']= parse_model_id($page['attr']['found_model'], $page['make_id']);

    $page['found_date'] = time();

    return $page;
}

function check_active_make_id($make_id)
{
    $sql = "SELECT * FROM car_makes WHERE make_id = :make_id AND make_active = 1";
    $query = DB::query($sql, array("make_id" => $make_id));

    if($result = $query->fetch(PDO::FETCH_ASSOC)) return true;
    else return false;
}

function check_active_model_id($model_id)
{
    $sql = "SELECT * FROM car_models WHERE model_id = :model_id AND model_active = 1";
    $query = DB::query($sql, array("model_id" => $model_id));

    if($result = $query->fetch(PDO::FETCH_ASSOC)) return true;
    else return false;
}


function parse_year_id($tag)
{
    $tag = strtolower($tag);

    $makes = retrieve_years();

    foreach($makes as $make)
    {
        $name = strtolower($make['year_value']);
        if(strpos($tag, $name) !== false) return $make['year_id'];
    }

    return "";
}

function parse_make_id($tag)
{
    $tag = strtolower($tag);

    $makes = retrieve_makes();

    foreach($makes as $make)
    {
        $name = strtolower($make['make_name']);
        if(strpos($tag, $name) !== false) return $make['make_id'];
    }

    return "";
}

function parse_model_id($tag, $make_id)
{
    $tag = strtolower($tag);


    $models = retrieve_models($make_id);
    if(count($models) > 0) {
        foreach ($models as $model) {
            $name = strtolower($model['model_name']);
            if (strpos($tag, $name) !== false) return $model['model_id'];
        }
    }

    return "";
}

/* common */
function retrieve_makes($active = -1)
{
    if($active == -1) $filter = "";
    else $filter = " WHERE make_active = '" . $active . "'";

    $sql = "SELECT * FROM car_makes" . $filter . " ORDER BY make_name ASC";
    $query = DB::query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function retrieve_models($make_id, $active = -1)
{
    if($active == -1) $filter = "";
    else $filter = "  AND model_active = '" . $active . "'";

    $sql = "SELECT * FROM car_models WHERE make_id = :make_id " . $filter;
    $query = DB::query($sql, array("make_id" => $make_id));
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function retrieve_years()
{
    $sql = "SELECT * FROM car_years";
    $query = DB::query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function convert_year($year_id)
{
    $sql = "SELECT year_value FROM car_years WHERE year_id = :year_id";
    $query = DB::query($sql, array("year_id" => $year_id));
    if($result = $query->fetch(PDO::FETCH_ASSOC)) return $result['year_value'];
    else return "Unknown";

}

function convert_model($model_id)
{
    $sql = "SELECT model_name FROM car_models WHERE model_id = :model_id";
    $query = DB::query($sql, array("model_id" => $model_id));
    if($result = $query->fetch(PDO::FETCH_ASSOC)) return $result['model_name'];
    else return "Unknown";
}

function convert_make($make_id)
{
    $sql = "SELECT make_name FROM car_makes WHERE make_id = :make_id";
    $query = DB::query($sql, array("make_id" => $make_id));
    if($result = $query->fetch(PDO::FETCH_ASSOC)) return $result['make_name'];
    else return "Unknown";

}



function retrieve_saved_results($year_id, $make_id, $model_id, $sorting = "")
{
    if(is_numeric($make_id)) $filter = "make_id = '" . $make_id . "'";
    elseif($make_id == "Unknown") $filter = "make_id = ''";
    else $filter = "result_id > 0";

    if(is_numeric($model_id)) $filter2 = "model_id = '" . $model_id . "'";
    elseif($model_id == "Unknown") $filter2 = "model_id = ''";
    else $filter2 = "result_id > 0";

    if(is_numeric($year_id)) $filter3 = "year_id = '" . $year_id . "'";
    elseif($year_id == "Unknown") $filter3 = "year_id = ''";
    else $filter3 = "result_id > 0";

    if($sorting != "")
    {
        if($sorting == "foundrecent") $filter4 = " ORDER BY cast(found_date as unsigned) DESC";
        elseif($sorting == "postedrecent") $filter4 = " ORDER BY cast(post_date as unsigned) DESC";
        elseif($sorting == "priceup") $filter4 = " ORDER BY cast(price as unsigned) ASC";
        elseif($sorting == "pricedown") $filter4 = " ORDER BY cast(price as unsigned) DESC";
        elseif($sorting == "milesup") $filter4 = " ORDER BY cast(odo as unsigned) ASC";
        elseif($sorting == "milesdown") $filter4 = " ORDER BY cast(odo as unsigned) DESC";



        else $filter4 = "";
    }
    else $filter4 = "";

    $sql = "SELECT * FROM saved_results WHERE expired != 1 AND " . $filter . " AND " . $filter2 . " AND " . $filter3 . $filter4;
    $query = DB::query($sql);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

function search_duplicates($result_id)
{
    $duplicates = [];
    $test_columns = array("price", "found_model", "title_status", "trans", "latitude", "longitude", "odo");

    $sql = "SELECT * FROM saved_results WHERE result_id = :result_id";
    $query = DB::query($sql, array("result_id" => $result_id));
    $primary = $query->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT * FROM saved_results WHERE result_id != :result_id AND expired != 1";
    $query = DB::query($sql, array("result_id" => $result_id));
    $secondary_array = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach($secondary_array as $secondary)
    {
        $i = 0;
        foreach($test_columns as $column)
        {
            if($primary[$column] == $secondary[$column]) $i++;
        }
        if($i > 5) $duplicates[] = $secondary['result_id'];
    }

    return $duplicates;

}


/* image saving */
function save_image($image_url)
{
    $server_path = false;

    $target_dir = "/var/www/html/isaiahcash/rake/images/";

    $content = curl_get_contents($image_url);

    $file_name = random_string(10) . '.jpg';

    file_put_contents( $target_dir . $file_name, $content);

    $server_path = "https://isaiahcash.com/rake/images/" . $file_name;

    return $server_path;
}

function update_saved_images()
{
    $sql = "SELECT * FROM saved_results";
    $query = DB::query($sql);
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    $i = 0;
    foreach($results as $result)
    {
        $i++;
        print $i . "<br>";
        $image_url = $result['image_src'];

        $image_url = save_image($image_url);

        $params = array(
            "image_src" => $image_url,
            "result_id" => $result['result_id']
        );

        $sql = "UPDATE saved_results SET image_src = :image_src WHERE result_id = :result_id";
        $query = DB::query($sql, $params);

    }
}

function random_string($length = 10)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    $chars_len = strlen($chars);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $chars[rand(0, $chars_len - 1)];
    }
    return $random_string;
}

function curl_get_contents($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function purge_images()
{
    $msg = "";
    $k = 0;
    $i = 0;
    $j = 0;

    $image_directory = "/var/www/html/isaiahcash/rake/images/";

    foreach(glob($image_directory . '*.*') as $file) {
        $file = str_replace($image_directory, "", $file);
        $msg .= $file . " - ";

        $file_wildcard = "%" . $file . "%";

        $sql = "SELECT result_id FROM saved_results WHERE image_src LIKE :file AND expired = 0";
        $query = DB::query($sql, array("file" => $file_wildcard));

        if($result = $query -> fetch(PDO::FETCH_ASSOC)) {
            $msg .= "Active.<br>";
            $k++;
        }
        else{
            if (file_exists($image_directory . $file)) {
                unlink($image_directory . $file);
                $msg .= "Removed! <br>";
                $i++;
            } else {
                $msg .= "Not found. <br>";
                $j++;
            }
        }
    }

    $msg .= "<br><br>" . $k . " images active.<br>";
    $msg .= "<br>" . $i . " images removed.<br>";
    $msg .= "<br>" . $j . " images not found.<br>";

    return $msg;
}


/* cleaning */
function clean($string)
{
    return trim(strip_tags(preg_replace('/[\x00-\x1F\x7F]/u', '', (mb_convert_encoding(iconv('UTF-8', 'us-ascii//IGNORE', $string), 'UTF-8', 'UTF-8')))));
}

function clean_database()
{
    $sql_cols = array(
        "make_id",
        "model_id",
        "url",
        "image_src",
        "title",
        "price",
        "post_date",
        "post",
        "found_model",
        "vin",
        "cond",
        "cyl",
        "drive",
        "fuel",
        "odo",
        "color",
        "size",
        "title_status",
        "trans",
        "type",
        "latitude",
        "longitude",
        "found_date"
    );


    $sql1 = "SELECT result_id FROM saved_results";
    $query1 = DB::query($sql1);


    while($row_full = $query1->fetch(PDO::FETCH_ASSOC)) {
        $row = $row_full['result_id'];
        print $row . "<br>";
        foreach ($sql_cols as $col) {
            $sql = "SELECT " . $col . " FROM saved_results WHERE result_id  = :result_id";
            $query = DB::query($sql, array("result_id" => $row));
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $value = $result[$col];

            $value = clean($value);

            $params = array(
                $col => $value,
                "result_id" => $row
            );

            $sql = "UPDATE saved_results SET " . $col . " = :" . $col . " WHERE result_id  = :result_id";
            $query = DB::query($sql, $params);
        }
    }
}

function clean_price($price)
{
    $price = round(preg_replace('/[^0-9.]/', '', $price), 0);

    if($price == 0) return "";
    else return $price;
}

/* narrowing functions */
function narrow_price($results, $min, $max)
{
    if(!is_numeric($min)) $min = 0;
    if(!is_numeric($max)) $max = 10000000000000000000000000000000000;

    foreach($results as $key => $row)
    {
        if($row['price'] != "")
        {
            if($row['price'] > $max || $row['price'] < $min) unset($results[$key]);
        }
    }

    return $results;
}

function narrow_miles($results, $min, $max)
{
    if(!is_numeric($min)) $min = 0;
    if(!is_numeric($max)) $max = 10000000000000000000000000000000000;

    foreach($results as $key => $row)
    {
        if($row['odo'] != "")
        {
            if($row['odo'] > $max || $row['odo'] < $min) unset($results[$key]);
        }
    }

    return $results;
}

function narrow_title_status($results, $title_status)
{
    if($title_status == "All") return $results;

    $title_status = lcfirst($title_status);

    foreach($results as $key => $row)
    {
        if($title_status == "unknown" && $row['title_status'] == "") continue;

        if($row['title_status'] != "")
        {
            if($row['title_status'] != $title_status) unset($results[$key]);
        }
    }

    return $results;
}

function narrow_trans($results, $trans)
{
    if($trans == "All") return $results;

    $trans = lcfirst($trans);

    foreach($results as $key => $row)
    {
        if($trans == "unknown" && $row['trans'] == "") continue;

        if($row['trans'] != "")
        {
            if($row['trans'] != $trans) unset($results[$key]);
        }
    }

    return $results;
}

function narrow_preset($results, $preset)
{
    if($preset == "All") return $results;

    if($preset == "Found3Hours")
    {
        foreach($results as $key => $row)
        {
            if($row['found_date'] < time() - 10800) unset($results[$key]);
        }
    }
    elseif($preset == "Found12Hours")
    {
        foreach($results as $key => $row)
        {
            if($row['found_date'] < time() - 43200) unset($results[$key]);
        }
    }
    elseif($preset == "Favorites")
    {
        foreach($results as $key => $row)
        {
            if($row['favorite'] != 1) unset($results[$key]);
        }
    }
    elseif($preset == "UnknownFilter")
    {
        foreach($results as $key => $row)
        {
            if($row['year_id'] != "" && $row['make_id'] != "" && $row['model_id'] != "") unset($results[$key]);
        }
    }
    elseif($preset == "UnknownPrice")
    {
        foreach($results as $key => $row)
        {
            if($row['price'] != "") unset($results[$key]);
        }
    }
    elseif($preset == "UnknownMiles")
    {
        foreach($results as $key => $row)
        {
            if($row['odo'] != "") unset($results[$key]);
        }
    }
    elseif($preset == "PossibleDuplicates")
    {
        foreach($results as $key => $row) {
            $duplicates = search_duplicates($row['result_id']);
            if (count($duplicates) < 1) unset($results[$key]);
        }
    }

    return $results;
}
/* check */
function check_expired()
{
    $msg = "";

    $sql = "SELECT * FROM saved_results WHERE expired != 1";
    $query = DB::query($sql);

    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
        $expired = false;
        $check_flag = false;

        $result_id = $result['result_id'];
        $url = $result['url'];

        // If nothing is in the expired last check column, check it
        $last_check = $result['expired_last_check'];
        if($last_check == "") $check_flag = true;

        // If it was last checked prior to two hours ago, don't check again
        $buffer_time = time() - 7200;
        if($last_check < $buffer_time) $check_flag = true;

        // Checking result
        if($check_flag == true) {
            $msg .= "Checking result_id, url: <br>" . $result_id . "<br>" . $url . "<br>";

            // Get the html
            $html = file_get_html($url);
            // If the page was able to load
            if ($html) {

                // If the removed div is present, its expired
                $removed_div = $html->find('div.removed', 0);
                if ($removed_div) $expired = true;

                // If the not found div is present, it is expired
                $not_found_div = $html->find('div.post-not-found', 0);
                if ($not_found_div) $expired = true;
            }
            // If the page could not be loaded, it is expired
            else $expired = true;

            // If the page is expired, update the database
            if ($expired == true) {
                $msg .= "EXPIRED<br>";

                $sql_update = "UPDATE saved_results SET expired = 1 WHERE result_id = :result_id";
                $query_update = DB::query($sql_update, array("result_id" => $result_id));
            }
            else $msg .= "Active<br>";

            // Update the last checked time
            $sql_update = "UPDATE saved_results SET expired_last_check = :current_check_time WHERE result_id = :result_id";
            $query_update = DB::query($sql_update, array("current_check_time" => time(), "result_id" => $result_id));

            $msg .= "<hr>";
        }
    }

    return $msg;
}

/* resync images */
function resync_images()
{
    $j = 0;
    $sql = "SELECT * FROM saved_results WHERE expired = 0";
    $query = DB::query($sql);

    while($result = $query->fetch(PDO::FETCH_ASSOC))
    {
//    print $j . "<br>";
//    $j++;
//    if($j > 10) continue;

        $link = $result['url'];

        $html = file_get_html($link);

        $i = 0;
        $images = array();
        foreach ($html->find('img') as $image) {
            $i++;
            if ($i > 10) break;

            if (strpos($image->src, "x50") === false) {
                $images[] = $image->src;
            }
        }
        if(!isset($images[0])) $images[0] = "";
        $page['images'] = $images;

        print "Updating result_id: " . $result['result_id'] . "<br>";

        $server_path = save_image($page['images'][0]);

        $params = array(
            "image_src" => $server_path,
            "result_id" => $result['result_id']
        );
        $sql2 = "UPDATE saved_results SET image_src = :image_src WHERE result_id = :result_id";
        $query2 = DB::query($sql2, $params);
    }
}

/* other */
function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    if(!is_numeric($lat1) || !is_numeric($lon1) || !is_numeric($lat2) || !is_numeric($lon2)) {
        return "Unknown";
    }

    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
        return 0;
    }
    else {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round($miles * 1.609344);
        } else if ($unit == "N") {
            return round($miles * 0.8684);
        } else {
            return round($miles);
        }
    }
}

function time_elapsed($ptime) {

    if(!is_numeric($ptime)) return "Unknown";

    $estimate_time = time() - $ptime;

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array(
        12 * 30 * 24 * 60 * 60  =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }

    return "Unknown";
}

function send_mail($subject, $msg)
{
    $headers = "";
    $headers .= "Reply-To: Cron Job <isaiahcash.web@gmail.com>\r\n";
    $headers .= "Return-Path: Cron Job <isaiahcash.web@gmail.com>\r\n";
    $headers .= "From: Cron Job <isaiahcash.web@gmail.com>\r\n";

    $headers .= "Organization: isaiahcash.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: PHP". phpversion() ."\r\n";

    $to = "isaiahcash@gmail.com";

    $check = mail($to, $subject, $msg, $headers);
}