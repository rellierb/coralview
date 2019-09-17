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

function check_peak_rate($date_reserved) {

    $date = date("Y-m-d", strtotime($date_reserved));

    $off_peak_date_start_1 = date("Y-m-d", strtotime("01/02/2019"));
    $off_peak_date_end_1 = date("Y-m-d", strtotime("03/11/2019"));

    $off_peak_date_start_2 = date("Y-m-d", strtotime("07/18/2019"));
    $off_peak_date_end_2 = date("Y-m-d", strtotime("11/19/2019"));

    $peak_date_start_1 = date("Y-m-d", strtotime("03/12/2019"));
    $peak_date_end_1 = date("Y-m-d", strtotime("07/17/2019"));

    $peak_date_start_2 = date("Y-m-d", strtotime("11/20/2019"));
    $peak_date_end_2 = date("Y-m-d", strtotime("01/01/2020"));

    $type_of_rate = "";
    
    if((($date >= $off_peak_date_start_1) && ($date <= $off_peak_date_end_1)) || (($date >= $off_peak_date_start_2) && ($date <= $off_peak_date_end_2))) {
        $type_of_rate = 0;
    } else if((($date >= $peak_date_start_1) && ($date <= $peak_date_end_1)) || (($date >= $peak_date_start_2) && ($date <= $peak_date_end_2))) {
        $type_of_rate = 1;
    }

    return $type_of_rate;

}


if($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* Guest Details */
    $first_name = mysqli_real_escape_string($db, trim($_POST['p_first_name']));
    $last_name = mysqli_real_escape_string($db, trim($_POST['p_last_name']));
    $contact_number = mysqli_real_escape_string($db, trim($_POST['p_contact_number']));
    $email = mysqli_real_escape_string($db, trim($_POST['p_email']));
    $address = mysqli_real_escape_string($db, trim($_POST['p_address']));
    $payment = 'BANK DEPOSIT';
    
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
        $no_of_days = mysqli_real_escape_string($db, trim($_POST['p_no_of_days']));
        $is_peak_rate = check_peak_rate($arrival_date);

        $total_amount = mysqli_real_escape_string($db, trim($_POST['p_total_amount']));

        /* 
         * Insert RESERVATION details 
         */

        $reservation_insert_query = "INSERT INTO reservation(guest_id, reference_no, status, payment, check_in_date, check_out_date, adult_count, kids_count, is_peak_rate, date_created)";
        $reservation_insert_query .= " VALUES ('$guest_id', '$reference_no', 'PENDING', '$payment', STR_TO_DATE('$arrival_date', '%m/%d/%Y'), STR_TO_DATE('$departure_date', '%m/%d/%Y'), '$adult_count', '$kids_count', $is_peak_rate, NOW())";
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
                                    <td class="tg-0lax">' . $room["type"]  . '</td>
                                    <td class="tg-0lax">' . number_format($room["peak_rate"], 2)  . '</td>
                                    <td class="tg-cey4" style="text-align: center;">' . $quantity  . '</td>
                                    <td class="tg-0pky">' . number_format($amount, 2) . '</td>
                                </tr>
                            ';
                            $total_amount += $amount;

                        }
                    }
                }
            }

            if($is_success_or_failed == "SUCCESS") {
                
                $to_pay_on_deadline = 0;

                if($payment == 'CASH UPON WALK IN') {
                    $to_pay_on_deadline = $total_amount ;
                    $payment_note = '<p><b>Please be informed that you have to pay PHP ' . number_format($to_pay_on_deadline, 2) . ' on the arrival/check-in date</b></p>';
                } else if($payment == 'BANK DEPOSIT') {
                    $to_pay_on_deadline = ($total_amount * $no_of_days) * .5;
                    $payment_deadline = Date('F d, o', strtotime("+3 days"));
                    $payment_note = '<p><b>Please be informed that you have to pay PHP ' . number_format($to_pay_on_deadline, 2) . ' on or before ' . $payment_deadline . '</b></p>';
                }

                $reservation_details = '
                    <style type="text/css">
                        .tg  {border-collapse:collapse;border-spacing:0;}
                        .tg td{font-family:\'Segoe UI\', sans-serif;font-size:16px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                        .tg th{font-family:\'Segoe UI\', sans-serif;font-size:16px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                        .tg .tg-wgsn{font-family:\'Segoe UI\', sans-serif; !important;;border-color:inherit;text-align:left;vertical-align:top}
                        .tg .tg-l1gd{font-size:12px;font-family:\'Segoe UI\', sans-serif; !important;;border-color:inherit;text-align:left;vertical-align:middle}
                        .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
                    </style>
                    <table class="tg">
                        <tr>
                            <th class="tg-0pky" style="font-family:\'Segoe UI\', sans-serif;"><b>Reference No.</b></th>
                            <th class="tg-wgsn">' . $reference_no . '</th>
                        </tr>
                        <tr>
                            <td class="tg-0pky"><b>Check-in Date</b></td>
                            <td class="tg-0pky">' . date("m d, Y", strtotime($arrival_date)) . '</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky"><b>Check-out Date</b></td>
                            <td class="tg-0pky">' . date("m d, Y", strtotime($departure_date)) . '</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky"><b>Total Amount Due</b></td>
                            <td class="tg-0pky">PHP ' . number_format(($total_amount * $no_of_days), 2)   . '</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky"><b>Night/s</b></td>
                            <td class="tg-0pky">' . $no_of_days . '</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky"><b>Mode of Payment</b></td>
                            <td class="tg-0pky">BANK DEPOSIT</td>
                        </tr>
                    </table>
                ';

                $room_details = '

                    <table class="tg">
                        <tr>
                            <th class="tg-b465">Room Name</th>
                            <th class="tg-b465">Price</th>
                            <th class="tg-r2u0">Quantity</th>
                            <th class="tg-4688">Amount</th>
                        </tr>
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

                        .tg  {border-collapse:collapse;border-spacing:0;}
                        .tg td{font-family:\'Segoe UI\', sans-serif;font-size:16px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                        .tg th{font-family:\'Segoe UI\', sans-seriff;font-size:16px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                        .tg .tg-cey4{font-size:16px;border-color:inherit;text-align:left;vertical-align:top}
                        .tg .tg-4688{font-weight:bold;font-size:16px;font-family:\'Segoe UI\', sans-serif !important;;border-color:inherit;text-align:left;vertical-align:top}
                        .tg .tg-b465{font-weight:bold;font-size:16px;font-family:\'Segoe UI\', sans-serif !important;;text-align:left;vertical-align:top}
                        .tg .tg-fzq1{font-size:16px;font-family:\'Segoe UI\', sans-serif !important;;border-color:inherit;text-align:left;vertical-align:top}
                        .tg .tg-r2u0{font-weight:bold;font-size:16px;font-family:\'Segoe UI\', sans-serif !important;;border-color:inherit;text-align:left;vertical-align:middle}
                        .tg .tg-0lax{text-align:left;vertical-align:top}
                        .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}

                    </style>

                    <div style="width: 100%;">
                        <h1>CORALVIEW BEACH RESORT</h1>
                        <p>Thank you for booking with us! Please see details below for reference and attached filed for the resort house rules.</p>
                        ' . $reservation_details . '
                        <br>
                        ' . $room_details . '
                        <br>
                        ' . $payment_note . '

                        <br>
                        <hr>

                        <h4>Payment Rules</h4>
                        <br>
                        <ol>
                            <li>1. Pay the due amount of your reservation at any BDO branches. Deposit the amount at Account Number: 123-456-789</li>
                            <li>2. Deadline of payment is three days after the online reservation date.</li>
                            <li>3. After paying the due amount, you will an email confirmation from the resort regarding the status of your reservation.</li>
                            <li>4. Print the reservation voucher and present it to the resort on your arrival date.</li>
                        <ol>


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

                        <p>Please click the <a href="http://localhost/coralview/confirm_reservation.php?reference_no=' . $reference_no . '">link</a> to acknowledge your reservation.</p>
                    </div>
                ';

                $mail = new PHPMailer(true);

                echo $reservation_message;


                  $attachment = "../../assets/files/house-rules.docx";

                try {
                    $message = $reservation_message;
                    $mail->SMTPDebug = 1;
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'coralviewthesis@gmail.com';  // Fill this up
                    $mail->Password = 'Qwerty1234@1234';  // Fill this up
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // add attachment
                    $mail->addAttachment($attachment, 'House Rules.docx');

                    $mail->setFrom('coralviewthesis@gmail.com');
                    $mail->isHTML(true);
                    $mail->addAddress($email);
                    $mail->Subject = 'Coralview Reservation';
                    $mail->Body = $message;
                    $mail->send();

                    echo  '<script>window.location.assign("../../success_confirmation.php")</script>';

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