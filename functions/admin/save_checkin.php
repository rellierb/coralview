<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Add Extras to Add - ons
    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }

    $room_name = $_POST["room_to_save"];

    if(!empty($_SESSION["save_rooms"])) {

        array_push($_SESSION["save_rooms"], $room_name);
        
    }  else {

        $_SESSION["save_rooms"] = array();
        array_push($_SESSION["save_rooms"], $room_name);

    }

    

}