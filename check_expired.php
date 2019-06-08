<?php
require_once('includes/config.php');
ini_set('display_errors', 0);
start_page();
?>
<div class="container pt-3">
    <div class="row">
        <div class="col-8">
            <a href="index.php" class="btn btn-outline-secondary">Return Home</a>
            <br>
            <br>
            <h4>Check Expired Posts</h4>
            <form action="check_expired.php" method="post">
                <input name="check" value="1" hidden readonly>
                <button type="submit" class="btn btn-primary">Check</button>
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div class="col-8">
            <?php

            if(isset($_POST['check'])) {
                $msg = check_expired();
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

</script>

<?php
end_page();
?>
