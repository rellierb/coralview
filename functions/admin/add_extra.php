<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["extra_id"])) {
    $extra_id = $_POST["extra_id"];
}

if(isset($_POST["quantity"])) {
    $quantity = $_POST["quantity"];
}

if(isset($_POST["amount"])) {
    $amount = $_POST["amount"];
}

// "INSERT INTO booking_rooms(reservation_id, room_id, quantity) VALUES ('$reservation_id', '$room_id', '$quantity')";
$extra_add_query = "INSERT INTO billing_extras(reference_no, expense_id, quantity, amount) VALUES ($reference_no, $extra_id, $quantity, $amount)";
$extra_add_result = mysqli_query($db, $extra_add_query);

echo $extra_add_query;

if($extra_add_result) {
    echo "SUCCESS";
} else {
    echo "FAILED";
}







