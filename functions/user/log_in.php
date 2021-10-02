<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
    
    if(isset($_POST['account_email'])) {
        $account_email = trim($_POST['account_email']);
    }

    if(isset($_POST['account_password'])) {
        $account_password = trim($_POST['account_password']);
    }

    $query = "SELECT * FROM users WHERE Email='$account_email' AND Password='$account_password'";
    $result = mysqli_query($db, $query);

    echo $query;

    if(mysqli_num_rows($result) == 1) {
        
        $account_details = mysqli_fetch_assoc($result);

        $_SESSION['full_name'] = $account_details['FullName'];
        $_SESSION['user_name'] = $account_details['UserName'];
        $_SESSION['account_type'] = $account_details['Type'];

        header('Location: /coralview/admin/list_reservation.php');

    } else {

        $_SESSION['alert'] = "alert alert-danger"; 
        $_SESSION['message'] = "Invalid Email or Password";

        header('Location: /coralview/account.php');
    }   

    
}