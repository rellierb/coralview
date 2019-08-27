<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["amount"])) {
    $amount_paid = $_POST["amount"];
}

if(isset($_POST["description"])) {
    $description = $_POST["description"];
}

$amount_paid_query = "INSERT INTO billing_additional_fees(reference_no, amount, description, time_stamp) VALUES ($reference_no, $amount_paid, $description, NOW())";
$amount_paid_result = mysqli_query($db, $amount_paid_query);

if($amount_paid_result) {

    echo 'SUCCESS';

} else {

    echo 'FAILED';

}



