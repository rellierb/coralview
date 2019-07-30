<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {


    if(isset($_POST["room_status"])) {
        $room_status = $_POST["room_status"];
    }

    if(isset($_POST["room_id"])) {
        $room_id = $_POST["room_id"];
    }

    $room_status_query = "UPDATE rooms_status SET status='$room_status' WHERE id=$room_id";
    $room_status_result = mysqli_query($db, $room_status_query);

    if($room_status_result) {

        $_SESSION['msg'] = "Room status successfully updated";
        $_SESSION['alert'] = "alert alert-success"; 
      
    } else {

        $_SESSION['msg'] = "Room status cannot be updated";
        $_SESSION['alert'] = "alert alert-danger"; 

    }

    //header("location: ../../admin/maintenance/room_number.php");

}