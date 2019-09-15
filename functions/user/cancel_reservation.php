<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') { 

    var_dump($_POST['reference_no']);
    
    if(isset($_POST['reference_no'])) {
        $reference_no = trim($_POST['reference_no']);
    }

    $query = "UPDATE reservation SET status = 'CANCELLED' WHERE reference_no='$reference_no'";
    
    $result = mysqli_query($db, $query);

    if($result) {

        $html = '';
        $find_reservation = "SELECT * FROM reservation R INNER JOIN guest g ON R.guest_id=g.id WHERE R.reference_no='$reference_no'";
        $find_reservation_result = mysqli_query($db, $find_reservation);

        if(mysqli_num_rows($find_reservation_result) > 0) {

            while($result = mysqli_fetch_assoc($find_reservation_result)) {

                $html = '<p>Please be informed that Reservation with reference no. ' . $result['reference_no'] . ' has cancelled his/her reservation.</p>';

            }

        }

      
        $mail = new PHPMailer(true);

        try {
            $message = $html;
            $mail->SMTPDebug = 1;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'coralviewthesis@gmail.com';  // Fill this up
            $mail->Password = 'Qwerty1234@1234';  // Fill this up
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('coralviewthesis@gmail.com');
            $mail->isHTML(true);
            $mail->addAddress('coralviewthesis@gmail.com');
            $mail->Subject = 'Coralview Beach Resort Cancelled Reservation';
            $mail->Body = $message;
            $mail->send();

            echo  'SUCCESS';

        } catch (Exception $e) {
            $_SESSION['email_error_msg'] = "There\'s an error processing your request";
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        
        $_SESSION['alert'] = "alert alert-success"; 
        $_SESSION['message'] = "Your reservation was successfully cancelled.";
        
    } else {

        $_SESSION['alert'] = "alert alert-danger"; 
        $_SESSION['message'] = "Sorry, we could not cancel your reservation.";
        
    }

    header('Location: ../../view_reservation.php?reference_no='.$reference_no.'');

}