<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

$html = '';
$guest_count = 0;

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST['reference_no'];
}

// INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id  INNER JOIN rooms R ON BR.room_id = R.Id
$reservation_details_query = "SELECT * FROM reservation RES
    INNER JOIN guest G ON G.id = RES.guest_id
    WHERE RES.reference_no='$reference_no'";
$reservation_details_result = mysqli_query($db, $reservation_details_query);
if($reservation_details_result) {
    
    while($reservation = mysqli_fetch_assoc($reservation_details_result)) {

        $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
        $diff = $dateDiff->format('%d');
        $check_in_date = date_create($reservation["check_in_date"]);
        $check_out_date = date_create($reservation["check_out_date"]);
        $formatted_check_in_date =  date_format($check_in_date, 'F d, Y');
        $formatted_check_out_date = date_format($check_out_date, 'F d, Y');
        $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
        $guest_count = $reservation["adult_count"] + $reservation["kids_count"];
        
        $html .= '
            <style type="text/css">
                .tg  {border-collapse:collapse;border-spacing:0;}
                .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                .tg .tg-cxql{font-weight:bold;background-color:#47a9df;color:#ffffff;border-color:#000000;text-align:center;vertical-align:top}
                .tg .tg-9wq8{border-color:inherit;text-align:center;vertical-align:middle}
                .tg .tg-baqh{text-align:center;vertical-align:top}
                .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
                .tg .tg-kcp0{background-color:#47a9df;color:#ffffff;border-color:black;text-align:center;vertical-align:top}
                .tg .tg-vcon{font-weight:bold;font-size:32px;border-color:inherit;text-align:center;vertical-align:middle}
                .tg .tg-f7f6{background-color:#47a9df;border-color:black;text-align:left;vertical-align:top;color:white;}
                .tg .tg-ho3n{font-size:26px;border-color:inherit;text-align:center;vertical-align:top}
                .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
                .tg .tg-0lax{text-align:left;vertical-align:top}
                .tg .tg-dvpl{border-color:inherit;text-align:center;vertical-align:top}
            </style>
            <table class="tg" style="width: 100%;">
            <tr>
                <th class="tg-vcon" colspan="14">KLIR WATER RESORT<br></th>
            </tr>
            <tr>
                <td class="tg-f7f6" colspan="14" style="text-align: center;">CHECK IN VOUCHER</td>
            </tr>
            <tr>
                <td class="tg-9wq8" colspan="5">REFERENCE NO</td>
                <td class="tg-c3ow" colspan="9" style="text-align: center;">' . $reservation["reference_no"] . '</td>
            </tr>
            <tr>
                <td class="tg-9wq8" colspan="5">CHECK-IN</td>
                <td class="tg-0pky" colspan="9" style="text-align: center;">' . $formatted_check_in_date . '</td>
            </tr>
            <tr>
                <td class="tg-9wq8" colspan="5">CHECK-OUT</td>
                <td class="tg-0pky" colspan="9" style="text-align: center;">' . $formatted_check_out_date . '</td>
            </tr>
            <tr>
                <td class="tg-baqh" colspan="5">NIGHT/S OF STAY</td>
                <td class="tg-0lax" colspan="9" style="text-align: center;">' . $diff . '</td>
            </tr>
            <tr>
                <td class="tg-baqh" colspan="5">GUEST COUNT</td>
                <td class="tg-0lax" colspan="9" style="text-align: center;">' . $guest_count . '</td>
            </tr>
            <tr>
                <td class="tg-kcp0" colspan="14"><span style="font-weight:bold">GUEST INFORMATION</span><br></td>
            </tr>
            <tr>
                <td class="tg-c3ow" colspan="3">NAME</td>
                <td class="tg-0pky" colspan="11" style="text-align: center;">' . $full_name . '</td>
            </tr>
            <tr>
                <td class="tg-dvpl" colspan="3">EMAIL ADDRESS<br></td>
                <td class="tg-0pky" colspan="11" style="text-align: center;">' . $reservation["email"] . '</td>
            </tr>
            <tr>
                <td class="tg-c3ow" colspan="3">CONTACT NUMBER</td>
                <td class="tg-0pky" colspan="11" style="text-align: center;">' . $reservation["contact_number"] . '</td>
            </tr>
           
        ';
    }
}

$room_reservation_details_query = "SELECT * FROM reservation RES
    INNER JOIN guest G ON G.id = RES.guest_id
    INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
    INNER JOIN rooms R ON BR.room_id = R.Id
    WHERE RES.reference_no = '$reference_no'";

$room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);
$rooms_reserved = array();
$quantity = 0;

if($room_reservation_details_result) {

    $html .= '
        <tr>
            <td class="tg-cxql" colspan="14">BOOKING DETAILS</td>
        </tr>
        <tr>
            <td class="tg-c3ow" colspan="7">ROOM/S</td>
            <td class="tg-0pky" colspan="3">QUANTITY</td>
            <td class="tg-c3ow" colspan="4">AMOUNT<br></td>
        </tr>
    ';

    while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
        
        $room_id = $room_reservation["room_id"];
        $room_quantity = $room_reservation["quantity"];
        $rooms_reserved[$room_id] = $room_quantity; 
        $quantity += $room_quantity;
        $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

        $html .= '
            <tr>
                <td class="tg-0pky" colspan="7" style="text-align: center;">' . $room_reservation["type"] . '</td>
                <td class="tg-0pky" colspan="3" style="text-align: center;">' . $room_reservation["quantity"]  . '</td>
                <td class="tg-0pky" colspan="4" style="text-align: center;">' . number_format($total_price, 2) . '</td>
            </tr>
        ';
    }
    
}
$html .= '</table>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream();