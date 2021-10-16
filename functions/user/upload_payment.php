<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

function uploadRoom($fileToUpload) {
    
    $target_dir = "../uploads/payment/";

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
    && $imageFileType != "gif" && $imageFileType != "pngpng") {
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

    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }

    $file_path = uploadRoom($_FILES["photo_payment"]["name"]);

    if($file_path !== "error" && $file_path !== 0) {
        $payment_path = $file_path; 

        $update_query = "
            UPDATE reservation SET payment_path='$payment_path' WHERE reference_no='$reference_no'
        ";

        $update_result = mysqli_query($db, $update_query); 

        if($update_result) {

            $html = '<p>Please be informed that Reservation with reference no. ' . $reference_no . ' has uploaded the image of his/her deposit slip for partial payment.</p>';

            $mail = new PHPMailer(true);

            try {
                $message = $html;
                $mail->SMTPDebug = 1;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'klir.waterresort@gmail.com';  // Fill this up // 
                $mail->Password = '!2klirwaterresort';  // Fill this up // 
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('klir.waterresort@gmail.com');
                $mail->isHTML(true);
                $mail->addAddress('klir.waterresort@gmail.com');
                $mail->Subject = 'Klir Water Resort Payment Reservation';
                $mail->Body = $message;
                $mail->send();

                    
                $_SESSION['alert'] = "alert alert-success"; 
                $_SESSION['message'] = "Your reservation was successfully cancelled.";

                header('Location: ../../view_reservation.php?reference_no='.$reference_no.'');

            } catch (Exception $e) {
                $_SESSION['email_error_msg'] = "There\'s an error processing your request";
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            $_SESSION['message'] = "Payment is successfully Uploaded";
            $_SESSION['alert'] = "alert alert-success";

        } else if($file_path == 0) {
            header("location: ../../view_reservation.php?reference_no=" . $reference_no ."");
        } 

    } else {

        $_SESSION['message'] = "There's an error processing your request";
        $_SESSION['alert'] = "alert alert-danger";

        header("location: ../../view_reservation.php?reference_no=" . $reference_no ."");

    }

}


