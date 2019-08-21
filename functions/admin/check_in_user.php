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

    if(!empty($_POST['room_number'])) {

        foreach($_POST['room_number'] as $room_number) {
            $update_room_query = "UPDATE rooms_status SET status='OCCUPIED' WHERE room_number='$room_number'";
            $update_room_result = mysqli_query($db, $update_room_query);

            if(!$update_room_result) {

                $_SESSION['msg'] = "Room status cannot be updated";
                $_SESSION['alert'] = "alert alert-danger"; 
                
                header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");

            } else {

                $insert_check_in_query = "INSERT INTO check_in_rooms(reference_no, room_number) VALUES('$reference_no', '$room_number')";
                $insert_check_in_result = mysqli_query($db, $insert_check_in_query);

                echo $insert_check_in_query;

                continue;
            }

        }
        
    }

    if(!empty($_POST['down_payment_amount'])) {
        $amount_paid_by_client = mysqli_real_escape_string($db, trim($_POST['down_payment_amount']));
    }

    // if(!empty($_POST['down_total_amount'])) {
    //     $total_amount = mysqli_real_escape_string($db, trim($_POST['down_total_amount']));
    // }

    $amount_paid_query = "INSERT INTO billing(reference_no, amount_paid, total_amount, description, time_stamp) VALUES ('$reference_no', '$amount_paid_by_client', NULL, NULL, CURDATE())";
    $amount_paid_result = mysqli_query($db, $amount_paid_query);

    echo $amount_paid_query;

    if($amount_paid_result) {

        $_SESSION['msg'] = "Check-in successfully processed";
        $_SESSION['alert'] = "alert alert-success"; 
        
        header("location: ../../admin/check_in_user.php?reference_no=$reference_no");

    } else {

        $_SESSION['msg'] = "Payment cannot be processed";
        $_SESSION['alert'] = "alert alert-danger"; 
        
        header("location: ../../admin/check_in_user.php?reference_no=$reference_no");

    }




}