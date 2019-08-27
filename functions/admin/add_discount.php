<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["discount_id"])) {
    $discount_id = $_POST["discount_id"];
}

if(isset($_POST["quantity"])) {
    $quantity = $_POST["quantity"];
}

$select_discount_query = "SELECT * FROM billing_discount WHERE reference_no=$reference_no AND discount_id=$discount_id";
$select_discount_result = mysqli_query($db, $select_discount_query);

if(mysqli_num_rows($select_discount_result) > 0) {

    $update_discount_id_query = "UPDATE billing_discount SET quantity=$quantity WHERE reference_no=$reference_no AND discount_id=$discount_id";
    $update_discount_id_result = mysqli_query($db, $update_discount_id_query);

    if($update_discount_id_result) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }
    
} else {

    // "INSERT INTO booking_rooms(reservation_id, room_id, quantity) VALUES ('$reservation_id', '$room_id', '$quantity')";
    $discount_add_query = "INSERT INTO billing_discount(reference_no, discount_id, quantity) VALUES ($reference_no, $discount_id, $quantity)";
    $discount_add_result = mysqli_query($db, $discount_add_query);

    if($discount_add_result) {
        echo "SUCCESS";
    } else {
        echo "FAILED";
    }

}






