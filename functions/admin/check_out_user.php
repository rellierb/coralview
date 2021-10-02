<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Update rooms to AVAILABLE

    $is_success = true;

    if(isset($_POST["reference_no"])) {
        $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
    }
    
    // if(!empty($_POST["check_out_add_payment"])) {
    //     $check_out_payment = mysqli_real_escape_string($db, trim($_POST['check_out_add_payment']));
    // } 

    // else {
    //     $_SESSION['empty_payment'] = "Additional Payment field is empty";
    // }
  
    // if(!empty($_POST["check_out_description"])) {
    //     $check_out_description = mysqli_real_escape_string($db, trim($_POST['check_out_description']));
    // } 

    // else {
    //     $_SESSION['empty_description'] = "Description field is empty";
    // }

    // if(!empty($_SESSION['empty_description']) && !empty($_SESSION['empty_payment'])) {
    //     header("location: ../../admin/check_out_user.php?reference_no=$reference_no");
    // } else {

    //     $insert_query = "
    //         INSERT INTO billing (reference_no, amount_paid, total_amount, description, time_stamp)
    //         VALUES ('$reference_no', '$check_out_payment', NULL, '$check_out_description', NOW())
    //     ";

    //     $insert_result = mysqli_query($db, $insert_query);

    //     if(!$insert_result) {
            
    //         $_SESSION['msg'] = "Payment transaction cannot be processed";
    //         $_SESSION['alert'] = "alert alert-danger";

    //         header("location: ../../admin/check_out_user.php?reference_no=$reference_no");

    //     } 

    // }
    
    $find_room_id_query = "SELECT room_number FROM check_in_rooms WHERE reference_no='$reference_no'";
    $find_room_id_result = mysqli_query($db, $find_room_id_query);

    while($room_id = mysqli_fetch_assoc($find_room_id_result)) {

        $room_id_to_update = $room_id["room_number"];
        $update_room_id_query = "UPDATE rooms_status SET status='AVAILABLE' WHERE room_number='$room_id_to_update'";
        $update_room_id_result = mysqli_query($db, $update_room_id_query);

    }

    $update_query = "UPDATE reservation SET status='COMPLETE', date_updated=NOW() WHERE reference_no='$reference_no'";
    $update_result = mysqli_query($db, $update_query);

    if($update_result) {

        $find_guest_email = "SELECT G.email FROM reservation R INNER JOIN guest G ON R.guest_id = G.id WHERE R.reference_no='$reference_no'";
        $find_guest_email_result = mysqli_query($db, $find_guest_email);

        if(mysqli_num_rows($find_guest_email_result) == 1) {

            $email = '';
            while($guest = mysqli_fetch_assoc($find_guest_email_result)) {

                $email = $guest['email'];

                $html = '<p>Thank you for booking with Coralview Beach Resort!</p><p>To further improve our services, you could email us at coralviewthesis@gmail.com</p>';

                $mail = new PHPMailer(true);

                try {
                    $message = $html;
                    $mail->SMTPDebug = 1;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';  // Fill this up // 
                    $mail->Password = '';  // Fill this up //
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('');
                    $mail->isHTML(true);
                    $mail->addAddress($email);
                    $mail->Subject = 'Coralview Beach Resort Thank you Message';
                    $mail->Body = $message;
                    $mail->send();

                    $_SESSION['msg'] = "Client successfully check-out";
                    $_SESSION['alert'] = "alert alert-success";

                    header("location: ../../admin/billing.php?reference_no=$reference_no");

                } catch (Exception $e) {
                    $_SESSION['email_error_msg'] = "There\'s an error processing your request";
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }



            }

        }

    }

    // if(!empty($_POST['room_number'])) {

    //     foreach($_POST['room_number'] as $room_number) {
    //         $update_room_query = "UPDATE rooms_status SET status='OCCUPIED' WHERE room_number='$room_number'";
    //         $update_room_result = mysqli_query($db, $update_room_query);

    //         if(!$update_room_result) {

    //             $_SESSION['msg'] = "Room status cannot be updated";
    //             $_SESSION['alert'] = "alert alert-danger"; 
                
    //             header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");

    //         } else {

    //             $insert_check_in_query = "INSERT INTO check_in_rooms(reference_no, room_number) VALUES('$reference_no', '$room_number')";
    //             $insert_check_in_result = mysqli_query($db, $insert_check_in_query);

    //             echo $insert_check_in_query;

    //             continue;
    //         }

    //     }

    //     $_SESSION['msg'] = "Room status successfully updated";
    //     $_SESSION['alert'] = "alert alert-success";
        
    //     header("location: ../../admin/check_in_user.php?reference_no='$reference_no'");
        
    // }

}