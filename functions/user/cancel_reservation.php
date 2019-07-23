<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') { 

    var_dump($_POST['reference_no']);
    
    if(isset($_POST['reference_no'])) {
        $reference_no = trim($_POST['reference_no']);
    }

    $query = "UPDATE reservation SET status = 'CANCELLED' WHERE reference_no='$reference_no'";
    $result = mysqli_query($db, $query);

    if($result) {
        
        $_SESSION['alert'] = "alert alert-success"; 
        $_SESSION['message'] = "Your reservation was successfully cancelled.";
        
    } else {

        $_SESSION['alert'] = "alert alert-danger"; 
        $_SESSION['message'] = "Sorry, we could not cancel your reservation.";
        
    }

    header('Location: ../../view_reservation.php?reference_no='.$reference_no.'');

}