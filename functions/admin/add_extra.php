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

$select_extra_query = "SELECT * FROM billing_extras WHERE reference_no=$reference_no AND expense_id=$extra_id";
$select_extra_result = mysqli_query($db, $select_extra_query);

if($select_extra_result) {

    $update_extra_id_query = "UPDATE billing_extras SET quantity=$quantity WHERE reference_no=$reference_no AND expense_id=$extra_id";
    $update_extra_id_result = mysqli_query($db, $update_extra_id_query);

    if($update_extra_id_result) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }
    
} else {

    // "INSERT INTO booking_rooms(reservation_id, room_id, quantity) VALUES ('$reservation_id', '$room_id', '$quantity')";
    $extra_add_query = "INSERT INTO billing_extras(reference_no, expense_id, quantity, amount) VALUES ($reference_no, $extra_id, $quantity, $amount)";
    $extra_add_result = mysqli_query($db, $extra_add_query);

    if($extra_add_result) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }

}






