<?php
session_start();
require('../../assets/connection.php');

$db = connect_to_db();


function uploadRoom($fileToUpload) {

    $target_dir = "../../uploads/rooms/";
    $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . basename($fileToUpload);

    $path_to_return = $target_dir . basename($fileToUpload);

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    
    $check = getimagesize($_FILES["room_image"]["tmp_name"]);
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
    if ($_FILES["room_image"]["size"] > 500000) {
        $_SESSION["fileSize"] = "File type is not an image.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION["fileImage"] = "File type is not an image.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION["fileError"] = "There is an error uploading your file";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["room_image"]["tmp_name"], $target_file)) {
            return $path_to_return;
        } else {
            $_SESSION["fileError"] = "There is an error uploading your file";
        }
    }

    $_SESSION["alertType"] = "alert alert-danger";
    return $path_to_return;
}

echo 'Hello World';

// $file_path = uploadRoom($_FILES["room_image"]["name"]);
// echo $file_path;
// header("location: ../../../admin/room.php");


if($_SERVER['REQUEST_METHOD'] == 'POST') {


    /*
        DECLARE VARIABLES
    */


    // if(isset($_POST["room_number"])) {
    //     $room_number = mysqli_real_escape_string($db, trim($_POST['room_number']));
    // }

    // if(isset($_POST["room_type"])) {
    //     $room_type = mysqli_real_escape_string($db, trim($_POST['room_type']));
    // }

    // if(isset($_POST["room_peak_rate"])) {
    //     $room_peak_rate = mysqli_real_escape_string($db, trim($_POST['room_peak_rate']));
    // }

    // if(isset($_POST["room_off_peak_rate"])) {
    //     $room_off_peak_rate = mysqli_real_escape_string($db, trim($_POST['room_off_peak_rate']));
    // }

    // if(isset($_POST["room_description"])) {
    //     $room_description = mysqli_real_escape_string($db, trim($_POST['room_description']));
    // }

    // $file_path = uploadRoom($_FILES["room_image"]["name"]);
    // if(uploadRoom($_FILES["room_image"]["name"]) !== "error") {
    //     $room_image = substr_replace($file_path, "../..", 0, 5); 
    // }

    // echo $room_image;

    // /*
    //     CREATE ROOM
    // */
    // echo $_POST['enter_room'];

    // if(isset($_POST['enter_room'])) {

    //     $insert_query = "
    //         INSERT INTO rooms (number, type, peak_rate, off_peak_rate, description, image)
    //         VALUES ('$room_number', '$room_type', '$room_peak_rate', '$room_off_peak_rate', '$room_description', '$room_image')
    //     ";
    
    //     $insert_result = mysqli_query($db, $insert_query); 
    
    //     if($insert_result) {
    //         $_SESSION['msg'] = "Room is successfully Added";
    //         $_SESSION['alert'] = "alert alert-success";

    //         header("location: ../../../admin/room.php");
    //     } else {
    //         echo "Error: " . $insert_query . "<br>" . mysqli_error($db);
    //     }

    // }


    // // /*
    // //     DELETE ROOM
    // // */

    // if(isset($_POST['delete_room'])) {

    //     $id = $_POST['delete_room'];

    //     $delete_query = "DELETE FROM rooms WHERE id = ? ";

    //     $stmt = $db->prepare($delete_query);
    //     $stmt->bind_param('i', $id);
    //     if($stmt->execute()) {
    //         $_SESSION['msg'] = "Room is successfully Deleted";
    //         $_SESSION['alert'] = "alert alert-danger";
    //     }
        
    //     $stmt->close();
    //     $db->close();

    //     header("location: ../../../admin/room.php");
    // }

    // // var_dump(_POST['edit_room'])

    // if(isset($_POST['edit_room'])) {

    //     $id = $_POST['room_id'];

    //     $update_query = "UPDATE rooms SET number=$room_number, type=$room_type, peak_rate=$room_peak_rate, off_peak_rate=$room_off_peak_rate, description=$room_description WHERE id=$id";


    //     if(mysqli_query($db, $update_query)) {
    //         $_SESSION['msg'] = "Room is successfully Edited";
    //         $_SESSION['alert'] = "alert alert-info";

    //         header("location: ../../../admin/room.php");
    //     } else {
    //         echo "Error: " . $update_query . "<br>" . mysqli_error($db);
    //     }

    // }
    
}