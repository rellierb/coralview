<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

function generate_reference_no() {

    $ref_number = "CRLVW-";
	$source = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 
		'B', 'C', 'D', 'E', 'F');
	for($i = 1; $i <= 7; $i++) {
		$index = rand(0, 15);
		$ref_number = $ref_number . $source[$index];
    }

    return $ref_number;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* Guest Details */
    $first_name = mysqli_real_escape_string($db, trim($_POST['p_first_name']));
    $last_name = mysqli_real_escape_string($db, trim($_POST['p_last_name']));
    $contact_number = mysqli_real_escape_string($db, trim($_POST['p_contact_number']));
    $email = mysqli_real_escape_string($db, trim($_POST['p_email']));
    $address = mysqli_real_escape_string($db, trim($_POST['p_address']));
    $payment = mysqli_real_escape_string($db, trim($_POST['p_mode_of_payment']));
    
    $guest_insert_query = "INSERT INTO guest(first_name, last_name, address, email, contact_number) ";
    $guest_insert_query .= "VALUES ('$first_name', '$last_name', '$address', '$email', '$contact_number')";

    $result = mysqli_query($db, $guest_insert_query);

    if($result) {

        /* 
         * Insert GUEST details 
         */

        $guest_id = $db->insert_id;

        $reference_no = generate_reference_no();
        $arrival_date = mysqli_real_escape_string($db, trim($_POST['p_date_arrival']));
        $departure_date = mysqli_real_escape_string($db, trim($_POST['p_date_departure']));
        $adult_count = mysqli_real_escape_string($db, trim($_POST['p_adult_count']));
        $kids_count = mysqli_real_escape_string($db, trim($_POST['p_kids_count']));

        // p_total_amount
        $total_amount = mysqli_real_escape_string($db, trim($_POST['p_total_amount']));

        /* 
         * Insert RESERVATION details 
         */

        $reservation_insert_query = "INSERT INTO reservation(guest_id, reference_no, status, payment, check_in_date, check_out_date, adult_count, kids_count, date_created)";
        $reservation_insert_query .= " VALUES ('$guest_id', '$reference_no', 'PENDING', '$payment', STR_TO_DATE('$arrival_date', '%m/%d/%Y'), STR_TO_DATE('$departure_date', '%m/%d/%Y'), '$adult_count', '$kids_count', NOW())";
        $reservation_result = mysqli_query($db, $reservation_insert_query);

        if($reservation_result) {

            $reservation_id = $db->insert_id;
            $reserved_rooms = json_decode($_SESSION["rooms_reserved"]);
            $total_amount = 0;
            $table_data = "";

            foreach($reserved_rooms as $room) {

                /* 
                 * Insert BOOKING_ROOMS details 
                 */
                
                $room_id = $room->roomId;
                $quantity = $room->roomNumber;
                $booking_room_query = "INSERT INTO booking_rooms(reservation_id, room_id, quantity) VALUES ('$reservation_id', '$room_id', '$quantity')";
                $booking_room_result = mysqli_query($db, $booking_room_query);

                $is_success_or_failed = '';

                if(!$booking_room_result) {

                    $is_success_or_failed = "FAILED";
                    break;
                    echo mysqli_error($db);                    

                } else {
                    
                    $is_success_or_failed = "SUCCESS";
                    
                    $select_room_query = "SELECT * FROM rooms WHERE Id='$room_id'";
                    
                    $select_room_result = mysqli_query($db, $select_room_query);

                    if($select_room_result) {
                        while($room = mysqli_fetch_assoc($select_room_result)) {

                            $amount = $quantity * $room["peak_rate"];

                            $table_data .= '
                                <tr>
                                    <td>' . $room["type"]  . '</td>
                                </tr>
                                <tr>
                                    <td>' . number_format($room["peak_rate"], 2)  . '</td>
                                </tr>
                                <tr>
                                    <td>' . $quantity  . '</td>
                                </tr>
                                <tr>
                                    <td>' . $amount . '</td>
                                </tr>
                            ';


                        }
                    }

                    
                }
                
            }

            if($is_success_or_failed == "SUCCESS") {
                
                $to_pay_on_deadline = 0;

                if($payment == 'CASH UPON WALK IN') {
                    $to_pay_on_deadline = $total_amount;
                    $payment_note = '<b>Please be informed that you have to pay ' . number_format($to_pay_on_deadline, 2) . ' on the payment deadline</b>';
                } else if($payment == 'BANK DEPOSIT') {
                    $to_pay_on_deadline = $total_amount * .5;
                    $payment_note = '<b>Please be informed that you have to pay ' . number_format($to_pay_on_deadline, 2) . ' on the payment deadline</b>';
                }

                $reservation_details = '
                    <table>                        
                        <tr>
                            <td><b>Reference No.</b></td>
                            <td>' . $reference_no . '</td>
                        </tr>
                        <tr>
                            <td><b>Check-in Date</b></td>
                            <td>' . date("m dd, YY", strtotime($arrival_date)) . '</td>
                        </tr>
                        <tr>
                            <td><b>Check-out Date</b></td>
                            <td>' . date("m dd, YY", strtotime($departure_date)) . '</td>
                        </tr>
                        <tr>
                            <td><b>Mode of Payment</b></td>
                            <td>' . $payment . '</td>
                        </tr>
                        <tr>
                            <td><b>Deadline of Payment</b></td>
                            <td>' . $payment . '</td>
                        </tr>
                    </table>
                ';

                $room_details = '
                    <table>
                        ' . $table_data . '
                    </table>
                ';

                $reservation_message = '
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
                        <p>Thank you for booking with us!</p>

                        ' . $reservation_details . '
                        <br>
                        ' . $room_details . '

                        <p>Please click the <a href="http://localhost/coralview/confirm_reservation.php?refence_no=' . $reference_no . '">link</a> to acknowledge your reservation.</p>
                    </div>
                ';

                $mail = new PHPMailer(true);
                try {
                    $message = $reservation_message;
                    $mail->SMTPDebug = 1;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'coralviewthesis@gmail.com';  // Fill this up
                    $mail->Password = 'Qwerty1234%';  // Fill this up
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->setFrom('coralviewthesis@gmail.com');
                    $mail->isHTML(true);
                    $mail->addAddress($email);
                    $mail->Subject = 'Coralview Reservation';
                    $mail->Body = $message;
                    $mail->send();

                    header('location: ../../success_confirmation.php');

                } catch (Exception $e) {
                    $_SESSION['email_error_msg'] = "There\'s an error processing your request";
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

            }


        } else {
            echo mysqli_error($db);
        }
       
    }
}
