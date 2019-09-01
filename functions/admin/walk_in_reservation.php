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
    $first_name = mysqli_real_escape_string($db, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($db, trim($_POST['last_name']));
    $contact_number = mysqli_real_escape_string($db, trim($_POST['contact_number']));
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $address = mysqli_real_escape_string($db, trim($_POST['address']));
    $payment = 'WALK-IN / CASH';

    var_dump($_SESSION["rooms_reserved"]);
    
    $guest_insert_query = "INSERT INTO guest(first_name, last_name, address, email, contact_number) ";
    $guest_insert_query .= "VALUES ('$first_name', '$last_name', '$address', '$email', '$contact_number')";

    $result = mysqli_query($db, $guest_insert_query);

    if($result) {

        /* 
         * Insert GUEST details 
         */

        $guest_id = $db->insert_id;

        $reference_no = generate_reference_no();
        $arrival_date = mysqli_real_escape_string($db, trim($_POST['arrival_date']));
        $departure_date = mysqli_real_escape_string($db, trim($_POST['departure_date']));
        $adult_count = mysqli_real_escape_string($db, trim($_POST['adult_count']));
        $kids_count = mysqli_real_escape_string($db, trim($_POST['kids_count']));

        // p_total_amount
        // $total_amount = mysqli_real_escape_string($db, trim($_POST['p_total_amount']));

        /* 
         * Insert RESERVATION details 
         */

        $reservation_insert_query = "INSERT INTO reservation(guest_id, reference_no, status, payment, check_in_date, check_out_date, adult_count, kids_count, date_created)";
        $reservation_insert_query .= " VALUES ('$guest_id', '$reference_no', 'FOR CHECK IN', '$payment', STR_TO_DATE('$arrival_date', '%m/%d/%Y'), STR_TO_DATE('$departure_date', '%m/%d/%Y'), '$adult_count', '$kids_count', NOW())";
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
                    
                }
                
            }

            if($is_success_or_failed == "SUCCESS") {
                
                $_SESSION['msg'] = "WALK-IN RESERVATION SUCCESSFULLY PROCESSED";
                $_SESSION['alert'] = "alert alert-success";
                
                header('location: ../../admin/check_in_user.php?reference_no='. $reference_no . '');    

            } else {

                $_SESSION['msg'] = "WALK-IN RESERVATION COULD NOT BE PROCESSED";
                $_SESSION['alert'] = "alert alert-danger";
                
                header('location: ../../admin/walk_in_reservation.php');

            }


        } else {
            echo mysqli_error($db);
        }
       
    }
}