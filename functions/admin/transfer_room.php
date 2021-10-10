<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["new_room_name"])) {
    $new_room_name = $_POST["new_room_name"];
}

if(isset($_POST["old_room_name"])) {
    $old_room_name = $_POST["old_room_name"];
}

$update_checkout_date = "UPDATE check_in_rooms SET room_number=$new_room_name WHERE reference_no=$reference_no AND room_number=$old_room_name";
$update_checkout_date_result = mysqli_query($db, $update_checkout_date);

if($update_checkout_date_result) {
    echo "UPDATE SUCCESS";
} else {
    echo "UPDATE FAILED";
}






