<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST["down_payment_reference_no"])) {
        $dp_reference_no = mysqli_real_escape_string($db, trim($_POST['down_payment_reference_no']));
    }

    if(isset($_POST["down_payment_amount"])) {
        $dp_amount = mysqli_real_escape_string($db, trim($_POST['down_payment_amount']));
    }

    if(isset($_POST["down_payment_total_amount"])) {
        $dp_total_amount = mysqli_real_escape_string($db, trim($_POST['down_payment_total_amount']));
    }

    if(isset($_POST["down_payment_description"])) {
        $dp_description = mysqli_real_escape_string($db, trim($_POST['down_payment_description']));
    }

    $insert_query = "
        INSERT INTO billing (reference_no, amount_paid, total_amount, description, time_stamp)
        VALUES ('$dp_reference_no', '$dp_amount', '$dp_total_amount', '$dp_description', CURDATE())
    ";

    $insert_result = mysqli_query($db, $insert_query); 

    if($insert_result) {

        // Update status of reservation to Check-in
        $room_status_query = "UPDATE reservation SET status='FOR CHECK IN' WHERE reference_no='$dp_reference_no'";
        $room_status_result = mysqli_query($db, $room_status_query);


    } else {

        echo 'cannot insert';

    }
    /*
     *  // Insert to billing
     * reference_no
     * amount paid
     * total amount
     * description
     * time_stamp
     *   
     * /




    /*
     * 
     * insert to transaction 
     * 
     *  
     * /
    
     /*
      *
      * // update reservation status
      *
    */

    // send email to client




    //header("location: ../../admin/maintenance/room_number.php");

}