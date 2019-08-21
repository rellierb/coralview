<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;

// require_once 'dompdf/lib/html5lib/Parser.php';
// require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
// require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
// require_once 'dompdf/src/Autoloader.php';
// Dompdf\Autoloader::register();

require('../assets/connection.php');
require('../../composer/vendor/autoload.php');

$db = connect_to_db();

$html = '';
$overall_total_extra = 0;
$overall_total_price = 0;


if(isset($_POST["reference_no"])) {
    $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
}

$reservation_details_query = "SELECT * FROM reservation RES
INNER JOIN guest G ON G.id = RES.guest_id
WHERE RES.reference_no = '$reference_no'";

$reservation_details_result = mysqli_query($db, $reservation_details_query);

if($reservation_details_result) {
    while($reservation = mysqli_fetch_assoc($reservation_details_result)) {
        $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
        $address = $reservation["address"];
    }
}

$html .= '

    <style type="text/css">
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
        .tg .tg-4688{font-weight:bold;font-size:16px;font-family:Arial, Helvetica, sans-serif !important;;border-color:inherit;text-align:left;vertical-align:top}
        .tg .tg-0lax{text-align:center;vertical-align:top}
        .tg .tg-0pky{border-color:inherit;text-align:center;vertical-align:top}
    </style>
    <table class="tg" style="width: 100%;">
    <tr>
        <th class="tg-0pky" colspan="2"><span style="font-weight:bold">Name</span></th>
        <th class="tg-0pky" colspan="2">' . $full_name . '</th>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"><span style="font-weight:bold">Address</span></td>
        <td class="tg-0pky" colspan="2">' . $address . '</td>
    </tr>
        <tr>
            <th class="tg-4688" colspan="2" style="text-align: center;">Description</th>
            <th class="tg-0lax" style="text-align: center;"><span style="font-weight:bold;">Quantity</span></th>
            <th class="tg-4688" style="text-align: center;">Amount</th>
        </tr>


';



$room_reservation_details_query = "SELECT * FROM reservation RES
    INNER JOIN guest G ON G.id = RES.guest_id
    INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
    INNER JOIN rooms R ON BR.room_id = R.Id
    WHERE RES.reference_no = '$reference_no'";

$room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);
$rooms_reserved = array();
$quantity = 0;

if($room_reservation_details_result) {

    while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
        
        $room_id = $room_reservation["room_id"];
        $room_quantity = $room_reservation["quantity"];
        $rooms_reserved[$room_id] = $room_quantity; 
        $quantity += $room_quantity;
        $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

        $html .= '
            <tr>
                <td class="tg-0pky" colspan="2">' . $room_reservation["type"] . '</td>
                <td class="tg-0lax">' . $room_reservation["quantity"]  . '</td>
                <td class="tg-0pky">' . number_format($total_price, 2) . '</td>
            </tr>
        ';
        
        $overall_total_price += $total_price;
    }
    
}

$extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
$extra_list_result = mysqli_query($db, $extra_list_query);

if(mysqli_num_rows($extra_list_result) > 0) {

    while($extra = mysqli_fetch_assoc($extra_list_result)) {

        $total_extra = $extra["price"] * $extra["quantity"];
        $overall_total_extra += $total_extra;

        $html .= '
            <tr>
                <td class="tg-0pky" colspan="2">' . $extra["description"] . '</td>
                <td class="tg-0lax">' . $extra["quantity"]  . '</td>
                <td class="tg-0pky">' . number_format($total_extra, 2) . '</td>
            </tr>
        ';

    }

    $overall_total_price += $overall_total_extra;
}

$vatable_amount = $overall_total_price / 1.12;
$vat = $overall_total_price - $vatable_amount;

$html .= '

    <tr>
        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Total</span></td>
        <td class="tg-0lax"><b>' . number_format($overall_total_price, 2) .'</b></td>
    </tr>
    <tr>
        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Vatable Amount</span></td>
        <td class="tg-0lax">' . number_format($vatable_amount, 2) .'</td>
    </tr>
    <tr>
        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">VAT 12%</span></td>
        <td class="tg-0lax">' . number_format($vat, 2) .'</td>
    </tr>

</table>
';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();