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

$test = json_decode('[' . $quantity . ']', true);

if(isset($_POST["amount"])) {
    $amount = $_POST["amount"];
}

$select_extra_query = "SELECT * FROM billing_extras WHERE reference_no=$reference_no AND expense_id=$extra_id";
$select_extra_result = mysqli_query($db, $select_extra_query);

if(mysqli_num_rows($select_extra_result) > 0) {

    $select_extra_quantity = "SELECT quantity FROM billing_extras WHERE reference_no=$reference_no AND expense_id=$extra_id";
    $select_extra_result = mysqli_query($db, $select_extra_quantity);

    $temp_quantity = 0;
    if(mysqli_num_rows($select_extra_result) > 0) {
    
        while($extra = mysqli_fetch_assoc($select_extra_result)) {
            $temp_quantity = $extra['quantity'];
        }
    }
    
    $int_quantity = intval($quantity);
    $overall_quantity = $temp_quantity + $test[0];
    
    $update_extra_id_query = "UPDATE billing_extras SET quantity=$overall_quantity WHERE reference_no=$reference_no AND expense_id=$extra_id";
    $update_extra_id_result = mysqli_query($db, $update_extra_id_query);

    if($update_extra_id_result) {
        echo "UPDATE SUCCESS";
    } else {
        echo "UPDATE FAILED";
    }
    
} else {

    // "INSERT INTO booking_rooms(reservation_id, room_id, quantity) VALUES ('$reservation_id', '$room_id', '$quantity')";
    $extra_add_query = "INSERT INTO billing_extras(reference_no, expense_id, quantity, amount) VALUES ($reference_no, $extra_id, $quantity, $amount)";
    $extra_add_result = mysqli_query($db, $extra_add_query);

    if($extra_add_result) {
        echo "INSERT SUCCESS";
    } else {
        echo "INSERT FAILED";
    }

}






