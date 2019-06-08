<?php
require_once('includes/config.php');


start_page();
$makes = retrieve_makes(1);
?>
<div class="container pt-3">
    <div class="row">
        <div class="col-8">
            <a href="index.php" class="btn btn-outline-secondary">Return Home</a>
            <br>
            <br>
            <h4>Update Database</h4>
            <div class="text-muted">Select the search make/model to update the database.</div>
            <form action="update.php" method="post">
                <div class="form-group">
                    <label for="update_make_id">Make</label>
                    <select class="custom-select" id="update_make_id" name="update_make_id" required>
                        <?php
                        foreach($makes as $make)
                        {
                            print "<option value='" . $make['make_id'] . "'>" . $make['make_name'] . "</option>";
                        }
                        ?>
                    </select>
                    <label for="update_model_id">Model</label>
                    <select class="custom-select" id="update_model_id" name="update_model_id" required>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div class="col-8">
<?php

if(isset($_POST['update_make_id']) && isset($_POST['update_model_id'])) {
    $msg = update_database($_POST['update_make_id'], $_POST['update_model_id'], 100);

    print $msg;

}
?>
        </div>
    </div>
</div>


<?php
script_includes();
?>
<script>

    $(document).ready(function() {
        if(localStorage.getItem('update_make_id')){
            $('#update_make_id').val(localStorage.getItem('update_make_id'));
        }

        fill_models($("#update_make_id").val(), "use_storage");

    });

    $("#update_make_id").change(function(){
        fill_models($("#update_make_id").val(), "ignore_storage");
        localStorage.setItem('update_make_id', this.value);
    });

    $("#update_model_id").change(function(){
        localStorage.setItem('update_model_id', this.value);
    });

    function fill_models(make_id, flag)
    {
        $('#update_model_id').find('option').remove();
        $.post('ajax_models.php', {make_id: make_id},
            function(data){
                data = JSON.parse(data);
                var i;
                for (i = 0; i < data.length; i++) {
                    $("#update_model_id").append("<option value='" + data[i]['model_id'] + "'>" + data[i]['model_name'] + "</option>");
                }
                if(flag === 'use_storage' && data.length !== 0) {
                    if (localStorage.getItem('update_model_id')) {
                        $('#update_model_id').val(localStorage.getItem('update_model_id'));
                    }
                }
            }
        );
    }

</script>

<?php
end_page();
?>
