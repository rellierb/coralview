<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    /*
        DECLARE VARIABLES
    */

    if(isset($_POST["extra_description"])) {
        $extra_description = mysqli_real_escape_string($db, trim($_POST['extra_description']));
    }

    if(isset($_POST["extra_price"])) {
        $extra_price = mysqli_real_escape_string($db, trim($_POST['extra_price']));
    }

    /*
        CREATE EXTRA
    */

    if(isset($_POST['enter_extra'])) {

        $insert_query = "
            INSERT INTO extras (description, price)
            VALUES ('$extra_description', '$extra_price')
        ";
    
        $insert_result = mysqli_query($db, $insert_query); 
    
        if($insert_result) {
            $_SESSION['msg'] = "Extra is successfully Added";
            $_SESSION['alert'] = "alert alert-success";

            header("location: ../../admin/extras.php");
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($db);
        }

    }

    /*
        DELETE EXTRA
    */

    if(isset($_POST['delete_extra'])) {

        $id = $_POST['delete_extra'];
        echo $id;
        $delete_query = "DELETE FROM extras WHERE id = ? ";

        $stmt = $db->prepare($delete_query);
        $stmt->bind_param('i', $id);
        if($stmt->execute()) {
            $_SESSION['msg'] = "Extra is successfully deleted";
            $_SESSION['alert'] = "alert alert-danger";
        }
        
        $stmt->close();
        $db->close();

        header("location: ../../admin/extras.php");
    }

    if(isset($_POST['edit_extra'])) {

        $id = $_POST['extra_id'];
        
        $update_query = "UPDATE extras SET description='$extra_description', price='$extra_price' WHERE Id=$id";

        if(mysqli_query($db, $update_query)) {
            $_SESSION['msg'] = "Extra is successfully Edited";
            $_SESSION['alert'] = "alert alert-info";

            header("location: ../../admin/extras.php");    
        } else {
            echo "Error: " . $update_query . "<br>" . mysqli_error($db);
        }

    }
    

}



