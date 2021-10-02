<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["refence_no"])) {
    $reference_no = $_REQUEST["refence_no"];
}

?>

    <div class="container">
        <div class="row">
            <div class="col">

                <div>
                    <h2 class="text-center coralview-orange" style="margin-top: 20%;">An email is sent to your email account.</h2>
                    <h5 class="text-center coralview-orange">Please click the link in the email sent to confirm your reservation.</h5>
                </div>
                
            </div>
        </div>
    </div>


<?php


include('common/footer.php');


?>

