<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update rooms to AVAILABLE

    $is_success = true;

    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }
    
    if(!empty($_POST["check_out_add_payment"])) {
        $check_out_payment = mysqli_real_escape_string($db, trim($_POST['check_out_add_payment']));
    } else {
        $_SESSION['empty_payment'] = "Additional Payment field is empty";
    }
  
    if(!empty($_POST["check_out_description"])) {
        $check_out_description = mysqli_real_escape_string($db, trim($_POST['check_out_description']));
    } else {
        $_SESSION['empty_description'] = "Description field is empty";
    }

    if(!empty($_SESSION['empty_description']) && !empty($_SESSION['empty_payment'])) {
        header("location: ../../admin/check_out_user.php?reference_no=$reference_no");
    }
    
    $insert_query = "
        INSERT INTO billing (reference_no, amount_paid, total_amount, description, time_stamp)
        VALUES ('$reference_no', '$check_out_payment', NULL, '$check_out_description', CURDATE())
    ";

    $insert_result = mysqli_query($db, $insert_query);

    if($insert_result) {
        
        $find_room_id_query = "SELECT room_number FROM check_in_rooms WHERE reference_no='$reference_no'";
        $find_room_id_result = mysqli_query($db, $find_room_id_query);
    
        while($room_id = mysqli_fetch_assoc($find_room_id_result)) {
    
            $room_id_to_update = $room_id["room_number"];
            $update_room_id_query = "UPDATE rooms_status SET status='AVAILABLE' WHERE room_number='$room_id_to_update'";
            $update_room_id_result = mysqli_query($db, $update_room_id_query);

        }

        $_SESSION['msg'] = "Client successfully check-out";
        $_SESSION['alert'] = "alert alert-success";

        header("location: ../../admin/billing.php?reference_no=$reference_no");

    } else {

        $_SESSION['msg'] = "Payment transaction cannot be processed";
        $_SESSION['alert'] = "alert alert-danger";

        header("location: ../../admin/check_out_user.php?reference_no=$reference_no");

    }

    // if(!empty($_POST['room_number'])) {

    //     foreach($_POST['room_number'] as $room_number) {
    //         $update_room_query = "UPDATE rooms_status SET status='OCCUPIED' WHERE room_number='$room_number'";
    //         $update_room_result = mysqli_query($db, $update_room_query);

    //         if(!$update_room_result) {

    //             $_SESSION['msg'] = "Room status cannot be updated";
    //             $_SESSION['alert'] = "alert alert-danger"; 
                
    //             header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");

    //         } else {

    //             $insert_check_in_query = "INSERT INTO check_in_rooms(reference_no, room_number) VALUES('$reference_no', '$room_number')";
    //             $insert_check_in_result = mysqli_query($db, $insert_check_in_query);

    //             echo $insert_check_in_query;

    //             continue;
    //         }

    //     }

    //     $_SESSION['msg'] = "Room status successfully updated";
    //     $_SESSION['alert'] = "alert alert-success";
        
    //     header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");
        
    // }

}