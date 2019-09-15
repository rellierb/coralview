<?php
session_start();

require('../assets/connection.php');

$db = connect_to_db();

function uploadRoom($fileToUpload) {
    
    $target_dir = "/coralview/uploads/payment/";

    $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . basename($fileToUpload);

    $path_to_return = $target_dir . basename($fileToUpload);

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["photo_payment"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION["fileType"] = "File type is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $_SESSION["fileExists"] = "File already exists";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["photo_payment"]["size"] > 500000) {
        $_SESSION["fileSize"] = "File type is not an image.";
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION["fileImage"] = "File type is not an image.";
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION["fileError"] = "There is an error uploading your file";
        return $uploadOk;
    } else {
        if (move_uploaded_file($_FILES["photo_payment"]["tmp_name"], $target_file)) {
            return $path_to_return;
        } else {
            $_SESSION["fileError"] = "There is an error uploading your file";
        }
    }

    $_SESSION["alertType"] = "alert alert-danger";
    return $path_to_return;
}




if($_SERVER['REQUEST_METHOD'] == 'POST') {


    /*
        DECLARE VARIABLES
    */

    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }

    var_dump($_FILES["photo_payment"]["name"]);
    $file_path = uploadRoom($_FILES["photo_payment"]["name"]);
    if(uploadRoom($_FILES["photo_payment"]["name"]) !== "error") {
        $payment_path = $file_path; 
    } else if($file_path == 0) {
        header("location: ../../view_reservation.php?reference_no='$reference_no'");
    }

    /*
        CREATE ROOM
    */

    $update_query = "
        UPDATE reservation SET payment_path='$payment_path' WHERE reference_no='$reference_no'
    ";

    $update_result = mysqli_query($db, $update_query); 

    if($update_result) {
        $_SESSION['msg'] = "Payment is successfully Uploaded";
        $_SESSION['alert'] = "alert alert-success";

        header("location: ../../view_reservation.php?reference_no=$reference_no");
    } else {
        echo "Error: " . $update_result . "<br>" . mysqli_error($db);
    }

    


    

}


