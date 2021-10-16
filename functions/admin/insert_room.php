<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();

// echo "hellow";
echo $_POST['edit_room'];
// echo isset($_POST['insert_room']);
echo isset($_POST['edit_room']);

echo $_FILES["room_image"]["name"];

function uploadRoom($fileToUpload) {

    $target_dir = "";
    $temp_target_dir = "/coralview/uploads/rooms/" . $target_dir;
    $target_file = $_SERVER['DOCUMENT_ROOT'] . $temp_target_dir . basename($fileToUpload);

    $path_to_return = $target_dir . basename($fileToUpload);

    // echo $path_to_return; 

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($fileToUpload, PATHINFO_EXTENSION));
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

    // echo $imageFileType;

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $_SESSION["fileImage"] = "File type is not an image.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION["fileError"] = "There is an error uploading your file";
        header("location: ../../admin/room.php");
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

if($_SERVER['REQUEST_METHOD'] == 'POST') {


    /*
        DECLARE VARIABLES
    */


    if(isset($_POST["room_number"])) {
        $room_number = mysqli_real_escape_string($db, trim($_POST['room_number']));
    }

    if(isset($_POST["room_type"])) {
        $room_type = mysqli_real_escape_string($db, trim($_POST['room_type']));
    }

    if(isset($_POST["room_peak_rate"])) {
        $room_peak_rate = mysqli_real_escape_string($db, trim($_POST['room_peak_rate']));
    }

    if(isset($_POST["room_off_peak_rate"])) {
        $room_off_peak_rate = mysqli_real_escape_string($db, trim($_POST['room_off_peak_rate']));
    }

    if(isset($_POST["room_description"])) {
        $room_description = mysqli_real_escape_string($db, trim($_POST['room_description']));
    }
    
    if(isset($_POST["room_image_hidden"])) {
        $room_image_hidden = mysqli_real_escape_string($db, trim($_POST['room_image_hidden']));
    }
  
  
    if(isset($_POST['enter_room']) || isset($_POST['edit_room'])) {
        
        if(!empty($_FILES["room_image"]["name"])) {
            $file_path = uploadRoom($_FILES["room_image"]["name"]);
            if($file_path !== "error") {
                $room_image = $file_path; 
            }
        }
    } 

    
    /*
        CREATE ROOM
    */

    if(isset($_POST['enter_room'])) {

        $insert_query = "
            INSERT INTO rooms (type, peak_rate, off_peak_rate, description, image)
            VALUES ('$room_type', '$room_peak_rate', '$room_off_peak_rate', '$room_description', '$room_image')
        ";
    
        $insert_result = mysqli_query($db, $insert_query); 
    
        if($insert_result) {
            $_SESSION['msg'] = "Room is successfully Added";
            $_SESSION['alert'] = "alert alert-success";

            header("location: ../../admin/room.php");
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($db);
        }

    }


    // /*
    //     DELETE ROOM
    // */

    if(isset($_POST['delete_room'])) {

        $id = $_POST['delete_room'];

        echo $id;

        $delete_query = "DELETE FROM rooms WHERE id = ? ";

        echo $delete_query;

        $stmt = $db->prepare($delete_query);
        $stmt->bind_param('i', $id);
        if($stmt->execute()) {
            $_SESSION['msg'] = "Room is successfully Deleted";
            $_SESSION['alert'] = "alert alert-danger";
        }
        
        $stmt->close();
        $db->close();

        header("location: ../../admin/room.php");
    }

    // var_dump(_POST['edit_room'])

    if(isset($_POST['edit_room'])) {

        $id = $_POST['room_id'];

        $update_query = "UPDATE rooms SET type=$room_type, peak_rate=$room_peak_rate, off_peak_rate=$room_off_peak_rate, description=$room_description, image='$room_image_hidden' WHERE id=$id";

        if(mysqli_query($db, $update_query)) {
            $_SESSION['msg'] = "Room is successfully Edited";
            $_SESSION['alert'] = "alert alert-info";

            header("location: ../../admin/room.php");
        } else {
            echo "Error: " . $update_query . "<br>" . mysqli_error($db);
        }

    }
    
}