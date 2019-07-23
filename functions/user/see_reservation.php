<?php

session_start();

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') { 

    var_dump($_POST['reference_num']);
    
    if(isset($_POST['reference_num'])) {
        $reference_no = trim($_POST['reference_num']);
    }

    $query = "SELECT * FROM reservation WHERE reference_no='$reference_no'";
    $result = mysqli_query($db, $query);

    if(mysqli_num_rows($result) > 0) {

        header('Location: ../../view_reservation.php?reference_no='.$reference_no.'');
        
    } else {
        $_SESSION['alert'] = "alert alert-danger"; 
        $_SESSION['message'] = "Sorry, your reference number could not be found.";

        header('Location: ../../insert_reservation.php');
    }

}