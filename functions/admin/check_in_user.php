<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update room to occupied

    // Update Billing

    // Add Extras to Add - ons
    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }

    echo $reference_no;

    if(!empty($_POST['room_number'])) {

        foreach($_POST['room_number'] as $room_number) {
            $update_room_query = "UPDATE rooms_status SET status='OCCUPIED' WHERE room_number='$room_number'";
            $update_room_result = mysqli_query($db, $update_room_query);

            if(!$update_room_result) {
             
                $_SESSION['msg'] = "Room status cannot be updated";
                $_SESSION['alert'] = "alert alert-danger"; 
                
                header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");

            } else {
                continue;
            }

        }

        $_SESSION['msg'] = "Room status successfully updated";
        $_SESSION['alert'] = "alert alert-success";
        
        header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");
        
    }

}