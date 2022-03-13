<?php
function navigate_home($alternate_url = "")
{
    ?>
    <style>
        #navigate_home
        {
            position: fixed; /* Fixed/sticky position */
            height: 45px;
            width: 160px;
            top: 5px; /* Place the button at the bottom of the page */
            left: 10px; /* Place the button 30px from the left */
            z-index: 99; /* Make sure it does not overlap */
            border: none;
            outline: none;
            background-color: rgba(0, 0, 0, 0.4);
            color: white;
            cursor: pointer;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;

            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: baseline;

        }

        #navigate_home_angle, #navigate_home_text
        {
            align-self: center;
        }

        #navigate_home_angle
        {
            font-size: 25px;
        }


        #navigate_home:hover {
            background-color: rgba(0, 0, 0, 0.7);
        }

    </style>
    <button onclick="navigateHome('<?php echo $alternate_url; ?>')" id="navigate_home" title="isaiahcash.com"><i class="fa fa-angle-left" id="navigate_home_angle"></i><span id="navigate_home_text">isaiahcash.com</span></button>
    <script>
        // When the user clicks on the button, return to the home site or an alternate url
        function navigateHome(alternate_url = "") {
            if(alternate_url === "") window.location.href = 'https://isaiahcash.com';
            else window.location.href = alternate_url;
        }
    </script>
    <?php
}