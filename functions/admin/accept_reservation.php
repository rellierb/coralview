<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST["down_payment_reference_no"])) {
        $dp_reference_no = mysqli_real_escape_string($db, trim($_POST['down_payment_reference_no']));
    }

    if(empty($_POST["down_payment_amount"]) && empty($_POST["down_payment_description"])) {
        $_SESSION['msg'] = "DESCRIPTION AND AMOUNT IN DOWNPAYMENT DETAILS IS EMPTY";
        $_SESSION['alert'] = "alert alert-danger";
        header('location: ../../admin/accept.php?reference_no='. $dp_reference_no . '');
    } else {

        if(!empty($_POST["down_payment_description"])) {
            $dp_description = mysqli_real_escape_string($db, trim($_POST['down_payment_description']));
        } else {
            $_SESSION['msg'] = "DESCRIPTION IN DOWNPAYMENT DETAILS IS EMPTY";
            $_SESSION['alert'] = "alert alert-danger";
            header('location: ../../admin/accept.php?reference_no='. $dp_reference_no . '');
        }

        if(!empty($_POST["down_payment_amount"])) {
            $dp_amount = mysqli_real_escape_string($db, trim($_POST['down_payment_amount']));
        } else {
            $_SESSION['msg'] = "AMOUNT IN DOWNPAYMENT DETAILS IS EMPTY";
            $_SESSION['alert'] = "alert alert-danger";
            header('location: ../../admin/accept.php?reference_no='. $dp_reference_no . '');
        }

    }

    $insert_to_downpayment = "
        INSERT INTO downpayment (reference_no, amount, description, time_stamp)
        VALUES ('$dp_reference_no' , '$dp_amount', '$dp_description' , NOW())
    ";

    $insert_to_downpayment_result = mysqli_query($db, $insert_to_downpayment);

    if($insert_to_downpayment_result) {

        // $insert_to_downpayment = "
        //     INSERT INTO downpayment (reference_no, amount, time_stamp)
        //     VALUES ('$dp_reference_no', '$dp_amount', NOW())
        // ";

        // $insert_to_downpayment_result = mysqli_query($db, $insert_to_downpayment);

        // Update status of reservation to Check-in
        $room_status_query = "UPDATE reservation SET status='FOR CHECK IN' WHERE reference_no='$dp_reference_no'";
        $room_status_result = mysqli_query($db, $room_status_query);

        if($room_status_result) {

            // GET EMAIL OF THE CLIENT
            $find_guestid_query = "SELECT guest_id FROM reservation WHERE reference_no='$dp_reference_no'";
            $find_guestid_result = mysqli_query($db, $find_guestid_query);

            if(mysqli_num_rows($find_guestid_result) == 1) {

                while($guest = mysqli_fetch_assoc($find_guestid_result)) {

                    $guest_id = $guest["guest_id"];

                    $find_email_query = "SELECT email FROM guest WHERE id=$guest_id";
                    $find_email_result = mysqli_query($db, $find_email_query);

                    if(mysqli_num_rows($find_email_result) == 1) {

                        while($email = mysqli_fetch_assoc($find_email_result)) {
                            
                            $guest_email = $email["email"];

                            // SEND EMAIL TO CLIENT
                            $reservationMessage = '
                                <style>

                                    h1, p {
                                        font-family: \'Segoe UI\', sans-serif;
                                    }

                                    p {
                                        font-size: 16px;
                                    }

                                </style>

                                <div style="width: 100%;">
                                    <h1>CORALVIEW  RESORT</h1>
                                    <p>This is to inform you that your reservation has been confirmed. Please be reminded of the date of your check in date and of the resort house rules.</p>

                                    <hr>
                                    <h4>House Rules</h4>
                                    <br>
                                    <h4>TERMS AND CONDITIONS</h4>
                                    <ul>
                                        <li>We assume that all amenities inside the room are in good working conditions and any damage or lost shall be charge to my account.</li>
                                        <li>Check-in time is 2:00pm while check-out time is 12:00nn</li>
                                        <li>Late check-out will be charge 20% total of the room accommodation/hour and is subject to availability.</li>
                                        <li>CANCELLATION of the event by the guest for whatever reason made one (1) month will be charge (50%) percent penalty while fifteen (15) days before the reservation date is eighty (80%) percent penalty for the total package, the deposited to our account shall be forfeited.</li>
                                        <li>RESCHEDULING of the event should be advised one (1) month before the event and subject for availability. Later than one (1) month is subject to (30%) percent penalty. Rescheduling shall only be allowed once. </li>
                                        <li>Lost Key will be charge Five (500) hundred pesos.</li>
                                        <li>Resort Management reserves the right to refuse entry of any persons or group of persons.</li>
                                        <li>The Management is not liable for any damaged or lost of item left in and outside the room.</li>
                                        <li>The Management is not responsible for any accident, death or injuries within the resort premises.</li>
                                        <li>Early arrival at the resort for overnight stay will be charge entrance fee.</li>
                                        <li>CANCELLATION on the same date of reservation will be charge full payment.</li>
                                    </ul>
                                    <br>
                                    <h4>POLICIES</h4>
                                    <ul>
                                        <li>NO SHOWS are NON REFUNDABLE</li>
                                        <li>4 years old and above will be count as excess person</li>
                                        <li>Wear a white color of shirt for swimming attire</li>
                                        <li>Strictly NOT ALLOWED MAONG SHORTS wearing in the pool</li>
                                        <li>Electric appliances are not allowed inside the resort, use portable or gas burner stove only</li>
                                        <li>No eating inside the room</li>
                                        <li>NO TOWELS provided</li>
                                        <li>Swimming pools are open until 12 midnight only</li>
                                        <li>Swimming at the beach until 6pm only</li>
                                        <li>NOT ALLOWED after the Breakwater</li>
                                    </ul>
                                    <br>
                                    <h4 class="text-info">POOL RULES</h4>
                                    <ul>
                                        <li>Strictly NO DIVING.</li>
                                        <li>NO PUSHING</li>
                                        <li>NO BOMBING</li>
                                        <li>WATCH YOUR CHILDREN</li>
                                        <li>NO FOOD OR DRINKS</li>
                                        <li>NO ROUGH PLAY</li>
                                    </ul>
                                    <hr>

                                    <p>Please click the <a href="http://localhost/coralview/confirm_reservation.php?reference_no=' . $reference_no . '">link</a> to print your reservation voucher.</p>
                                </div>
                            ';

                            $mail = new PHPMailer(true);
                            try {
                                $message = $reservationMessage;
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
                                $mail->addAddress($guest_email);
                                $mail->Subject = 'Coralview Reservation Accepted';
                                $mail->Body = $message;
                                $mail->send();
                                $_SESSION['msg'] = "RESERVATION IS SUCCESSFULLY TAG AS ACCEPTED";
                                $_SESSION['alert'] = "alert alert-success";
                                header('location: ../../admin/accept.php?reference_no='. $dp_reference_no . '');
                            } catch (Exception $e) {
                                $_SESSION['msg'] = "There\'s an error processing your request";
                                $_SESSION['alert'] = "alert alert-danger";
                                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            }


                        }

                    }

                }

            }

        } 


    } else {

        $_SESSION['msg'] = "Cannot process reservation";
        $_SESSION['alert'] = "alert alert-danger";
        header('location: ../../admin/accept.php?reference_no='. $dp_reference_no . '');

    }
    

}