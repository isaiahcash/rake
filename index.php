<?php
require_once('includes/config.php');
start_page();

$years = retrieve_years();
$makes = retrieve_makes(1);
$title_statuses = retrieve_title_statuses();
$trans = retrieve_trans();
?>

<div class="container-fluid pt-3">
    <div id="alert_div"></div>
    <div class="row">
        <div class="col-12">
            <a href="update.php" class="btn btn-outline-secondary mb-2">Update Database</a>
            <a href="check_expired.php" class="btn btn-outline-secondary mb-2">Check Expired Posts</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            Search all saved results.
            <button class="btn btn-sm btn-warning float-right" onclick="reset_filters()">Reset Filters</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <label for="filter_preset">Preset Search</label>
            <select class="custom-select inactive" id="filter_preset" name="filter_preset">
                <option value="All">All</option>
                <option value="Found3Hours">Recently Found (3 hours)</option>
                <option value="Found12Hours">Recently Found (12 hours)</option>
                <option value="Favorites">Favorites</option>
                <option value="UnknownFilter">Unknown Year/Make/Model</option>
                <option value="UnknownPrice">Unknown Price</option>
                <option value="UnknownMiles">Unknown Miles</option>
                <option value="PossibleDuplicates">Possible Duplicates</option>
            </select>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <label for="filter_year_id">Year</label>
            <select class="custom-select" id="filter_year_id" name="filter_year_id">
                <option value="All">All</option>
                <?php
                foreach($years as $year)
                {
                    print "<option value='" . $year['year_id'] . "'>" . $year['year_value'] . "</option>";
                }
                ?>
                <option value="Unknown">Unknown</option>
            </select>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_make_id">Make</label>
            <select class="custom-select" id="filter_make_id" name="filter_make_id">
                <option value="All">All</option>
                <?php
                foreach($makes as $make)
                {
                    print "<option value='" . $make['make_id'] . "'>" . $make['make_name'] . "</option>";
                }
                ?>
                <option value="Unknown">Unknown</option>
            </select>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_model_id">Model</label>
            <select class="custom-select" id="filter_model_id" name="filter_model_id">
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-2">
            <label for="filter_location">Distance From</label>
            <select class="custom-select" id="filter_location" name="filter_location">
                <option value='35.978963$$$-83.948455'>Knoxville, TN</option>
            </select>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_price">Min to Max Price ($)</label>
            <div class="input-group">
                <input type="number" id='filter_min_price' class="form-control">
                <div class="input-group-prepend">
                    <span class="input-group-text">-</span>
                </div>
                <input type="number" id='filter_max_price' class="form-control">
            </div>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_miles">Min to Max Miles</label>
            <div class="input-group">
                <input type="number" id='filter_min_miles' class="form-control">
                <div class="input-group-prepend">
                    <span class="input-group-text">-</span>
                </div>
                <input type="number" id='filter_max_miles' class="form-control">
            </div>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_title_status">Title Status</label>
            <select class="custom-select" id="filter_title_status" name="filter_title_status">
                <option value="All">All</option>
                <?php
                foreach($title_statuses as $title_status)
                {
                    print "<option value='" .$title_status . "'>" . $title_status . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3 col-lg-2">
            <label for="filter_trans">Transmission</label>
            <select class="custom-select" id="filter_trans" name="filter_trans">
                <option value="All">All</option>
                <?php
                foreach($trans as $tran)
                {
                    print "<option value='" .$tran . "'>" . $tran . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <hr>
            View results in a table or grid. (Grid is preferred in mobile)
            <br>

            <button class="btn btn-secondary radio_option" id="table_option" value="Table">Table</button>
            <button class="btn btn-secondary radio_option" id="grid_option" value="Grid">Grid</button>
            <hr>
            <div id="show_hide_toggles">
                <label>Show/Hide Toggles</label>
                <br>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_1" data-column="1">Image
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_3" data-column="3">Title
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_4" data-column="4">Found Model
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_9" data-column="9">Distance
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_10" data-column="10">Posted
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_11" data-column="11">Found
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_12" data-column="12">VIN
                    </label>
                </div>
                <div class="btn-group btn-group-toggle " role="group" data-toggle="buttons">
                    <label class="btn btn-sm btn-outline-secondary mb-1">
                        <input type="checkbox" class="toggle-visible" autocomplete="off" id="column_checkbox_13" data-column="13">Trans
                    </label>
                </div>

                <button class="btn btn-sm btn-danger mb-1 float-right" onclick="remove_multi_row()">Remove selected rows</button>
                <hr>
                <br>
            </div>
            <div id="saved_results_table" class="table-responsive">
                <table id="saved_results" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Title</th>
                        <th>Found Model</th>
                        <th>Assigned Model</th>
                        <th>Price</th>
                        <th>Title Status</th>
                        <th>Miles</th>
                        <th>Distance</th>
                        <th>Posted</th>
                        <th>Found</th>
                        <th>VIN</th>
                        <th>Trans.</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Title</th>
                        <th>Found Model</th>
                        <th>Assigned Model</th>
                        <th>Price</th>
                        <th>Title Status</th>
                        <th>Miles</th>
                        <th>Distance</th>
                        <th>Posted</th>
                        <th>Found</th>
                        <th>VIN</th>
                        <th>Trans.</th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div id="grid_sorting" class="row">
                <div class="col-12 col-md-3 col-lg-2">
                    <label for="grid_sort_select">Sort</label>
                    <select class="custom-select" id="grid_sort_select">
                        <option value="foundrecent" selected>Most Recently Found</option>
                        <option value="postedrecent">Most Recently Posted</option>
                        <option value="priceup">Price (Low to High)</option>
                        <option value="pricedown">Price (High to Low)</option>
                        <option value="milesup">Miles (Low to High)</option>
                        <option value="milesdown">Miles (High to Low)</option>
                    </select>
                </div>
            </div>
            <div id="saved_results_grid">


            </div>
        </div>
    </div>
</div>

<?php
script_includes();
?>
<script>
    var saved_results = null;

    $(document).ready(function() {
        if(localStorage.getItem('table_selector')){
            var value = localStorage.getItem('table_selector');
            table_selector_set(value);
        }
        if(localStorage.getItem('filter_preset')){
            $('#filter_preset').val(localStorage.getItem('filter_preset'));
            check_preset();
        }
        if(localStorage.getItem('filter_year_id')){
            $('#filter_year_id').val(localStorage.getItem('filter_year_id'));
        }
        if(localStorage.getItem('filter_make_id')){
            $('#filter_make_id').val(localStorage.getItem('filter_make_id'));
        }
        fill_models($("#filter_make_id").val(), "use_storage");
        if(localStorage.getItem('filter_location')){
            $('#filter_location').val(localStorage.getItem('filter_location'));
        }
        if(localStorage.getItem('filter_min_price')){
            $('#filter_min_price').val(localStorage.getItem('filter_min_price'));
        }
        if(localStorage.getItem('filter_max_price')){
            $('#filter_max_price').val(localStorage.getItem('filter_max_price'));
        }
        if(localStorage.getItem('filter_min_miles')){
            $('#filter_min_miles').val(localStorage.getItem('filter_min_miles'));
        }
        if(localStorage.getItem('filter_max_miles')){
            $('#filter_max_miles').val(localStorage.getItem('filter_max_miles'));
        }
        if(localStorage.getItem('filter_title_status')){
            $('#filter_title_status').val(localStorage.getItem('filter_title_status'));
        }
        if(localStorage.getItem('filter_trans')){
            $('#filter_trans').val(localStorage.getItem('filter_trans'));
        }

        check_column_visible(1);
        check_column_visible(3);
        check_column_visible(4);
        check_column_visible(9);
        check_column_visible(10);
        check_column_visible(11);
        check_column_visible(12);
        check_column_visible(13);

        saved_results = $("#saved_results").DataTable({
            "ajax" : {
                "url": "ajax_saved_results.php",
                "type": "POST",
                "data": function (d) {
                    d.preset = $("#filter_preset").val();
                    d.year_id = $("#filter_year_id").val();
                    d.make_id = $("#filter_make_id").val();
                    d.model_id = $("#filter_model_id").val();
                    d.location = $("#filter_location").val();
                    d.min_price = $("#filter_min_price").val();
                    d.max_price = $("#filter_max_price").val();
                    d.min_miles = $("#filter_min_miles").val();
                    d.max_miles = $("#filter_max_miles").val();
                    d.title_status = $("#filter_title_status").val();
                    d.trans = $("#filter_trans").val();
                }
            },
            "columns": [
                {
                    "data": "result_id",
                    "width": "1px",

                },
                {
                    "data": "image",
                    "sorting": "false"
                },
                {
                    "data": "url",
                    "width": "1px",
                    "sorting": "false"
                },
                {"data": "title"},
                {"data": "found_model"},
                {
                    "data": "assignment",
                    "type": "html",
                    "render": {
                        "_": "display",
                        "sort": "value",
                        "search": "value"
                    }
                },
                {
                    "data": "alt_price",
                    "type": "num",
                    "render": {
                        "_": "display",
                        "sort": "value"
                    }
                },
                {"data": "title_status"},
                {"data": "odo"},
                {
                    "data": "distance",
                    "type": "num",
                    "render": {
                        "_": "display",
                        "sort": "value"
                    }
                },
                {
                    "data": "alt_post_date",
                    "type": "num",
                    "render": {
                        "_": "display",
                        "sort": "value"
                    }
                },
                {
                    "data": "alt_found_date",
                    "type": "num",
                    "render": {
                        "_": "display",
                        "sort": "value"
                    }
                },
                {"data": "vin"},
                {"data": "trans"},
                {
                    "data": "buttons",
                    "width": "1px",
                    "sorting": "false"
                }
            ],
            "stateSave": true,
            "sScrollX": "100%",
            "responsive": true
        });

        $('input.toggle-visible').change(function() {
            // Get the column API object
            var col_num = $(this).attr('data-column');
            var column = saved_results.column(col_num);
            var flag;

            // Set the check flagged
            if($(this).is(':checked')) flag = false;
            else flag = true;

            console.log(flag);

            column.visible(flag);

            localStorage.setItem('column_' + col_num, flag);

            console.log(localStorage.getItem('column_' + col_num));

        });

        load_results_grid();

        table_selector_action(table_selector_value());
    });

    function load_results_grid()
    {

        $.post('ajax_saved_results.php',
            {
                preset: $("#filter_preset").val(),
                sorting: $("#grid_sort_select").val(),
                year_id:  $("#filter_year_id").val(),
                make_id: $("#filter_make_id").val(),
                model_id: $("#filter_model_id").val(),
                location: $("#filter_location").val(),
                min_price: $("#filter_min_price").val(),
                max_price: $("#filter_max_price").val(),
                min_miles: $("#filter_min_miles").val(),
                max_miles: $("#filter_max_miles").val(),
                title_status: $("#filter_title_status").val(),
                trans: $("#filter_trans").val()
            },
            function(data){
                console.log("returned grid");
                data = JSON.parse(data);
                data = data['data'];
                console.log(data);
                var i;
                var html = "";
                html += "<hr><br><div class='row'>";
                for (i = 0; i < data.length; i++) {
                    html += grid_block(data[i]);
                }
                html += "</div>";
                $("#saved_results_grid").html(html);
            }
        );

    }

    function grid_block(block)
    {
        var html = "";

        html += "<div class='col-12 col-md-6 col-lg-3 col-xl-2'>" +
            "<div class='card mb-3'>" +
            "<a href='" + block['url_raw'] + "' target='_blank'>" +
            "<img class='card-img-top' style='object-fit: cover; height: 300px;' src='"+ block['image_src'] + "' alt='"+ block['title'] + "'>" +
            "</a>" +
            "<div class='card-body'>" +
            "<div class='row'>" +
            "<div class='col-12'>" + block['assignment']['display'] + "</div>" +
            "<div class='col-6'>" + block['alt_price']['display'] + "</div>" +
            "<div class='col-6'>" + block['odo'] + " mi.</div>" +
            "<div class='col-6'>" + block['title_status'] + " title</div>" +
            "<div class='col-6'>" + block['trans'] + "</div>" +
            "<div class='col-12'>" + block['distance']['display'] + " mi. away</div>" +
            "<div class='col-12'>Found: " + block['alt_found_date']['display'] + "</div>" +
            "<div class='col-12'>Posted: " + block['alt_post_date']['display'] + "</div>" +
            block['buttons_grid'] +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";

        return html;

    }

    function check_column_visible(col_num)
    {
        if(localStorage.getItem('column_' + col_num)){
            var storage = localStorage.getItem('column_' + col_num);
            if(storage == 'true') $('#column_checkbox_' + col_num).parent().removeClass('active');
            else $('#column_checkbox_' + col_num).parent().addClass('active');
        }
    }

    function reset_filters()
    {
        table_selector_value();
        $("#filter_year_id").val("All");
        $("#filter_make_id").val("All");
        $("#filter_model_id").val("All");
        $("#filter_location").val("35.978963$$$-83.948455");
        $("#filter_min_price").val("");
        $("#filter_max_price").val("");
        $("#filter_min_miles").val("");
        $("#filter_max_miles").val("");
        $("#filter_title_status").val("All");
        $("#filter_trans").val("All");

        localStorage.setItem('filter_preset', "All");
        localStorage.setItem('filter_year_id', "All");
        localStorage.setItem('filter_make_id', "All");
        localStorage.setItem('filter_model_id', "All");
        localStorage.setItem('filter_location', "35.978963$$$-83.948455");
        localStorage.setItem('filter_min_price', "");
        localStorage.setItem('filter_max_price', "");
        localStorage.setItem('filter_min_miles', "");
        localStorage.setItem('filter_max_miles', "");
        localStorage.setItem('filter_title_status', "All");
        localStorage.setItem('filter_trans', "All");

        saved_results.ajax.reload(null, false);
        load_results_grid();

        show_alert(1, "Reset Filters!");
    }

    function check_preset()
    {
        if($("#filter_preset").val() == "All")
        {
            $("#filter_year_id").prop('disabled', false);
            $("#filter_make_id").prop('disabled', false);
            $("#filter_model_id").prop('disabled', false);
            $("#filter_location").prop('disabled', false);
            $("#filter_min_price").prop('disabled', false);
            $("#filter_max_price").prop('disabled', false);
            $("#filter_min_miles").prop('disabled', false);
            $("#filter_max_miles").prop('disabled', false);
            $("#filter_title_status").prop('disabled', false);
            $("#filter_trans").prop('disabled', false);
        }
        else
        {
            $("#filter_year_id").prop('disabled', true);
            $("#filter_make_id").prop('disabled', true);
            $("#filter_model_id").prop('disabled', true);
            $("#filter_location").prop('disabled', true);
            $("#filter_min_price").prop('disabled', true);
            $("#filter_max_price").prop('disabled', true);
            $("#filter_min_miles").prop('disabled', true);
            $("#filter_max_miles").prop('disabled', true);
            $("#filter_title_status").prop('disabled', true);
            $("#filter_trans").prop('disabled', true);
        }
        $("#filter_year_id").val("All");
        $("#filter_make_id").val("All");
        $("#filter_model_id").val("All");
        $("#filter_location").val("35.978963$$$-83.948455");
        $("#filter_min_price").val("");
        $("#filter_max_price").val("");
        $("#filter_min_miles").val("");
        $("#filter_max_miles").val("");
        $("#filter_title_status").val("All");
        $("#filter_trans").val("All");
    }

    function table_selector_value()
    {
        var value = "Table";
        if($("#table_option").hasClass("active")){
            value = "Table";
        }
        if($("#grid_option").hasClass("active")){
            value = "Grid";
        }
        console.log(value);
        return value;
    }

    function table_selector_set(value)
    {
        if(value == "Table")
        {
            $("#table_option").addClass("active");
            $("#grid_option").removeClass("active");

        }
        else
        {
            $("#table_option").removeClass("active");
            $("#grid_option").addClass("active");
        }
        localStorage.setItem('table_selector', value);
    }

    function table_selector_action(value)
    {
        if(value == "Table")
        {
            $("#saved_results_table").show();
            $("#show_hide_toggles").show();
            $("#saved_results_grid").hide();
            $("#grid_sorting").hide();
            saved_results.draw();
        }
        else
        {
            $("#saved_results_table").hide();
            $("#show_hide_toggles").hide();
            $("#saved_results_grid").show();
            $("#grid_sorting").show();
        }
    }

    $(".radio_option").click(function(){
        table_selector_set(this.value);
        table_selector_action(this.value)
    });

    $("#grid_sort_select").change(function(){
        console.log("sorting changed.");
        load_results_grid();
    });


    $("#filter_preset").change(function(){
        localStorage.setItem('filter_preset', this.value);
        check_preset();

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_year_id").change(function(){
        localStorage.setItem('filter_year_id', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_make_id").change(function(){
        fill_models($("#filter_make_id").val(), "ignore_storage");
        localStorage.setItem('filter_make_id', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_model_id").change(function(){
        localStorage.setItem('filter_model_id', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_location").change(function(){
        localStorage.setItem('filter_location', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_min_price").change(function(){
        localStorage.setItem('filter_min_price', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_max_price").change(function(){
        localStorage.setItem('filter_max_price', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_min_miles").change(function(){
        localStorage.setItem('filter_min_miles', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_max_miles").change(function(){
        localStorage.setItem('filter_max_miles', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });



    $("#filter_title_status").change(function(){
        localStorage.setItem('filter_title_status', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    $("#filter_trans").change(function(){
        localStorage.setItem('filter_trans', this.value);

        saved_results.ajax.reload(null, false);
        load_results_grid();
    });

    function fill_models(make_id, flag)
    {
        $('#filter_model_id').find('option').remove();
        $.post('ajax_models.php', {make_id: make_id},
            function(data){
                data = JSON.parse(data);
                $("#filter_model_id").append("<option value='All'>All</option>");
                var i;
                for (i = 0; i < data.length; i++) {
                    $("#filter_model_id").append("<option value='" + data[i]['model_id'] + "'>" + data[i]['model_name'] + "</option>");
                }
                $("#filter_model_id").append("<option value='Unknown'>Unknown</option>");

                if(flag === 'use_storage' && data.length !== 0) {
                    if (localStorage.getItem('filter_model_id')) {
                        $('#filter_model_id').val(localStorage.getItem('filter_model_id'));
                    }
                }
            }
        );

    }

    function edit_assignment(result_id)
    {
        $("#assignment_current_" + result_id).hide();
        $("#assignment_new_" + result_id).show();
    }

    function cancel_assignment(result_id)
    {
        $("#assignment_current_" + result_id).show();
        $("#assignment_new_" + result_id).hide();
    }

    function fill_assignment_model(result_id)
    {
        var make_id = $("#assignment_new_make_" + result_id).val();
        $('#assignment_new_model_' + result_id).find('option').remove();
        $.post('ajax_models.php', {make_id: make_id},
            function(data){
                data = JSON.parse(data);
                var i;
                for (i = 0; i < data.length; i++) {
                    $('#assignment_new_model_' + result_id).append("<option value='" + data[i]['model_id'] + "'>" + data[i]['model_name'] + "</option>");
                }
            }
        );
    }

    function save_assignment(result_id)
    {
        var year_id = $("#assignment_new_year_" + result_id).val();
        var make_id = $("#assignment_new_make_" + result_id).val();
        var model_id = $("#assignment_new_model_" + result_id).val();

        $.post('ajax_save_assignment.php', {result_id: result_id, year_id: year_id, make_id: make_id, model_id: model_id},
            function(data){
                data = JSON.parse(data);
                show_alert(data['flag'], data['message']);

                saved_results.ajax.reload(null, false);
                load_results_grid();
            }
        );
    }

    function remove_id(result_id, reload_flag)
    {
        $.post('ajax_remove_url.php', {result_id: result_id},
            function(data){
                data = JSON.parse(data);
                show_alert(data['flag'], data['message']);

                if(reload_flag === true)
                {
                    saved_results.ajax.reload(null, false);
                    load_results_grid();
                }

            }
        );
    }

    $('#saved_results tbody').on('click', 'tr', function () {
        $(this).toggleClass('selected');
        $(this).toggleClass('active');
    });

    function remove_multi_row()
    {
        var selected_rows = saved_results.rows('.selected').data().toArray();
        var i = 0;
        for(i = 0; i < selected_rows.length; i++)
        {
            if(i === selected_rows.length - 1) remove_id(selected_rows[i]['result_id'], true);
            else remove_id(selected_rows[i]['result_id'], false);
        }
    }

    function like_id(result_id)
    {
        $.post('ajax_like_id.php', {result_id: result_id},
            function(data){
                data = JSON.parse(data);
                show_alert(data['flag'], data['message']);

                saved_results.ajax.reload(null, false);
                load_results_grid();
            }
        );
    }

    function dislike_id(result_id)
    {
        $.post('ajax_dislike_id.php', {result_id: result_id},
            function(data){
                data = JSON.parse(data);
                show_alert(data['flag'], data['message']);

                saved_results.ajax.reload(null, false);
                load_results_grid();
            }
        );
    }




</script>
<?php
end_page();
?>
