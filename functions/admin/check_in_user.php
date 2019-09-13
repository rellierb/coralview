<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add Extras to Add - ons
    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }

    if(!empty($_SESSION["save_rooms"])) {

        foreach($_SESSION["save_rooms"] as $room_number) {
            $update_room_query = "UPDATE rooms_status SET status='OCCUPIED' WHERE room_number=$room_number";
            $update_room_result = mysqli_query($db, $update_room_query);

            if(!$update_room_result) {

                $_SESSION['msg'] = "ROOM STATUS CANNOT BE UPDATED";
                $_SESSION['alert'] = "alert alert-danger"; 
                
                header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");

            } else {

                $insert_check_in_query = "INSERT INTO check_in_rooms(reference_no, room_number) VALUES('$reference_no', $room_number)";
                $insert_check_in_result = mysqli_query($db, $insert_check_in_query);

                continue;
            }

        }
        
    }

    $status_to_checked_in_query = "UPDATE reservation set status='CHECKED IN' WHERE reference_no='$reference_no'"; 
    $status_to_checked_in_result = mysqli_query($db, $status_to_checked_in_query);

    if($status_to_checked_in_query) {

        $_SESSION['msg'] = "CHECK-IN SUCCESFULLY PROCESSED";
        $_SESSION['alert'] = "alert alert-success"; 
        
        header("location: ../../admin/checked_in.php?reference_no=$reference_no");

    } else {

        $_SESSION['msg'] = "RESERVATION CANNOT BE PROCESSED";
        $_SESSION['alert'] = "alert alert-danger"; 
        
        header("location: ../../admin/check_in_user.php?reference_no=$reference_no");    

    }

    unset($_SESSION["save_rooms"]);

}