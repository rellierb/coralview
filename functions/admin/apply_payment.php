<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["amount_paid"])) {
    $amount_paid = $_POST["amount_paid"];
}

if(isset($_POST["description"])) {
    $description = $_POST["description"];
}

$amount_paid_query = "INSERT INTO billing(reference_no, amount_paid, description, time_stamp) VALUES ($reference_no, $amount_paid, $description, NOW())";
$amount_paid_result = mysqli_query($db, $amount_paid_query);

if($amount_paid_result) {

    echo 'SUCCESS';

} else {

    echo 'FAILED';

}



