<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

    /*
        FullName
        UserName
        Password
        Email
        Type
        PhoneNumber
    */

    /*
        user_id
        user_full_name
        user_name
        user_password
        user_email
        user_type
        user_phone_number
    */


if($_SERVER['REQUEST_METHOD'] == 'POST') {


    /*
        DECLARE VARIABLES
    */

    if(isset($_POST["user_full_name"])) {
        $user_full_name = mysqli_real_escape_string($db, trim($_POST['user_full_name']));
    }

    if(isset($_POST["user_name"])) {
        $user_name = mysqli_real_escape_string($db, trim($_POST['user_name']));
    }

    if(isset($_POST["user_password"])) {
        $user_password = mysqli_real_escape_string($db, trim($_POST['user_password']));
    }

    if(isset($_POST["user_email"])) {
        $user_email = mysqli_real_escape_string($db, trim($_POST['user_email']));
    }

    if(isset($_POST["user_type"])) {
        $user_type = mysqli_real_escape_string($db, trim($_POST['user_type']));
    }

    if(isset($_POST["user_phone_number"])) {
        $user_phone_number = mysqli_real_escape_string($db, trim($_POST['user_phone_number']));
    }

    /*
        CREATE USER
    */

    if(isset($_POST['enter_user'])) {

        $insert_query = "
            INSERT INTO users (FullName, UserName, Password, Email, Type, PhoneNumber)
            VALUES ('$user_full_name', '$user_name', SHA1('$user_password'), '$user_email', '$user_type', '$user_phone_number')
        ";
    
        $insert_result = mysqli_query($db, $insert_query); 
    
        if($insert_result) {
            $_SESSION['msg'] = "User is successfully Added";
            $_SESSION['alert'] = "alert alert-success";

            header("location: ../../admin/maintenance/user.php");
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($db);
        }

    }


    /*
        DELETE USER
    */

    if(isset($_POST['delete_user'])) {

        $id = $_POST['delete_user'];

        $delete_query = "DELETE FROM users WHERE id = ? ";

        echo $delete_query;
        echo $id;

        $stmt = $db->prepare($delete_query);
        $stmt->bind_param('i', $id);
        if($stmt->execute()) {
            $_SESSION['msg'] = "User is successfully deleted";
            $_SESSION['alert'] = "alert alert-danger";
        }
        
        $stmt->close();
        $db->close();

        header("location: ../../admin/maintenance/user.php");
    }


    if(isset($_POST['edit_user'])) {

        $id = $_POST['user_id'];
        
        $update_query = "UPDATE users SET FullName='$user_full_name', UserName='$user_name', Password='$user_password', Email='$user_email', Type='$user_type', PhoneNumber='$user_phone_number' WHERE id=$id";

        if(mysqli_query($db, $update_query)) {
            $_SESSION['msg'] = "User is successfully Edited";
            $_SESSION['alert'] = "alert alert-info";

            header("location: ../../admin/maintenance/user.php");    
        } else {
            echo "Error: " . $update_query . "<br>" . mysqli_error($db);
        }

    }
    

}



