<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["check_out_date"])) {
    $check_out_date = $_POST["check_out_date"];
}

$update_checkout_date = "UPDATE reservation SET check_out_date=$check_out_date WHERE reference_no=$reference_no";
$update_checkout_date_result = mysqli_query($db, $update_checkout_date);


if($update_checkout_date_result) {
    echo "UPDATE SUCCESS";
} else {
    echo "UPDATE FAILED";
}






