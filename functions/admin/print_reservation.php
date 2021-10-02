<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Mpdf\Mpdf;

require('../assets/connection.php');
require_once('../../composer/vendor/autoload.php');

var_dump(__DIR__);

date_default_timezone_set("Asia/Manila");

$user = $_SESSION['full_name'];
$date = date("Y/m/d h:i:sa");

try {
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => __DIR__ . '/tmp', // uses the current directory's parent "tmp" subfolder
        'setAutoTopMargin' => 'stretch',
        'setAutoBottomMargin' => 'stretch'
    ]);
} catch (\Mpdf\MpdfException $e) {
    print "Creating an mPDF object failed with" . $e->getMessage();
}

print 'Folder is writable: '.(is_writable('/opt/lampp/htdocs/coralview/functions/admin') ? 'yes' : 'no').'<br />';

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = mysqli_real_escape_string($db, trim($_POST['reference_no']));
}

$reservation_details_query = "SELECT * FROM reservation RES
INNER JOIN guest G ON G.id = RES.guest_id
WHERE RES.reference_no = '$reference_no'";

$guest_count = 0;

$reservation_details_result = mysqli_query($db, $reservation_details_query);

$arrival_date = '';
$departure_date = '';
$nights_of_stay = 0;
$overall_total_price = 0;
$overall_total_extra = 0;
$guest_id = '';
$payment_type = '';
$nights_of_stay = '';
$html = '';
$room_html = '';

if(mysqli_num_rows($reservation_details_result) > 0) {
    while($reservation = mysqli_fetch_assoc($reservation_details_result)) {
        $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
        $address = $reservation["address"];
        $guest_count = $reservation["adult_count"] + $reservation["kids_count"];
        $arrival_date = $reservation["check_in_date"];
        $departure_date = $reservation["check_out_date"];
        $contact_number = $reservation["contact_number"];
        $email = $reservation["email"];
        $status = $reservation["status"];

        $payment_type = $reservation["payment"];

        $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
        $diff = $dateDiff->format('%d');

        $nights_of_stay = $diff;
    }

    $temp_arrival_date = date_create($arrival_date);
    $temp_departure_date = date_create($departure_date);

    $html .= '

        <div style="text-align: center;">
            <img src="/coralview/assets/images/coralview-logo.jpg"  />
            <h2 style="font-family: Arial;">Guest Reservation Details</h2>
        </div>
        <style type="text/css">
            .tg  {border-collapse:collapse;border-spacing:0;border-color:#000;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#9ABAD9;color:#444;background-color:#fff;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#9ABAD9;color:#fff;background-color:#409cff;}
            .tg .tg-lboi{border-color:inherit;text-align:left;vertical-align:middle}
            .tg .tg-yq5v{background-color:#409cff;color:#ffffff;border-color:inherit;text-align:center;vertical-align:middle}
            .tg .tg-9wq8{border-color:inherit;text-align:center;vertical-align:middle}
            .tg .tg-uzvj{font-weight:bold;border-color:inherit;text-align:center;vertical-align:middle}
            .tg .tg-7btt{font-weight:bold;border-color:inherit;text-align:center;vertical-align:top}
            .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
            .tg .tg-8nlf{background-color:#409cff;color:#ffffff;border-color:inherit;text-align:center;vertical-align:top}
            .tg .tg-o21n{background-color:#409cff;color:#ffffff;border-color:#000000;text-align:center;vertical-align:top}
            .tg .tg-mqa1{font-weight:bold;border-color:#000000;text-align:center;vertical-align:top}
            .tg .tg-73oq{border-color:#000000;text-align:left;vertical-align:top}
        </style>
        <table class="tg" style="width: 100%;">
            <tr>
                <th class="tg-9wq8" colspan="15">GUEST DETAILS</th>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Full Name</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $full_name . '</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Address</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $address . '</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Contact Number</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $contact_number . '</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Email</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $email . '</td>
            </tr>
            <tr>
                <td class="tg-yq5v" colspan="15">RESERVATION DETAILS</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Reference No</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $reference_no . '</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Status</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $status . '</td>
            </tr>
            <tr>
                <td class="tg-7btt" colspan="6">Arrival Date</td>
                <td class="tg-0pky" colspan="9" style="text-align: center;">' . date_format($temp_arrival_date, "M d, Y") . '</td>
            </tr>
            <tr>
                <td class="tg-7btt" colspan="6">Departure Date</td>
                <td class="tg-0pky" colspan="9" style="text-align: center;">' . date_format($temp_departure_date, "M d, Y") . '</td>
            </tr>
            <tr>
                <td class="tg-uzvj" colspan="6">Night/s</td>
                <td class="tg-lboi" colspan="9" style="text-align: center;">' . $diff . '</td>
            </tr>
            <tr>
                <td class="tg-7btt" colspan="6">Guest Count</td>
                <td class="tg-0pky" colspan="9" style="text-align: center;">' . $guest_count . '</td>
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

        $room_html .= '
            <tr>
                <td class="tg-8nlf" colspan="15">ROOM DETAILS</td>
            </tr>
            <tr>
                <td class="tg-7btt" colspan="8">Room Name</td>
                <td class="tg-7btt" colspan="3">Qty</td>
                <td class="tg-7btt" colspan="4">Amount</td>
            </tr>
        ';

        while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
            
            $room_id = $room_reservation["room_id"];
            $room_quantity = $room_reservation["quantity"];
            $rooms_reserved[$room_id] = $room_quantity; 
            $quantity += $room_quantity;
            $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

            $room_html .= '
                <tr>
                    <td class="tg-0pky" colspan="8" style="text-align: center;">' . $room_reservation["type"] . '</td>
                    <td class="tg-0pky" colspan="3" style="text-align: center;">' . $room_reservation["quantity"] . '</td>
                    <td class="tg-0pky" colspan="4" style="text-align: center;">' . number_format($room_reservation["peak_rate"]) . '</td>
                </tr>
            ';

            $overall_total_price += $total_price;
        }

    }

    $overall_total_amount = $diff * $overall_total_price;
    $vatable_amount = $overall_total_amount / 1.12;
    $vat = $overall_total_amount - $vatable_amount; 

    $html .= '
            <tr>
                <td class="tg-8nlf" colspan="15">PAYMENT DETAILS</td>
            </tr>
            <tr>
                <td class="tg-73oq" colspan="8" style="text-align: center;"><b>PAYMENT TYPE</b></td>
                <td class="tg-73oq" colspan="7" style="text-align: center;">' . $payment_type .  '</td>
            </tr>
            <tr>
                <td class="tg-73oq" colspan="8" style="text-align: center;"><b>TOTAL AMOUNT</b></td>
                <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($overall_total_amount, 2)  .  '</td>
            </tr>
            <tr>
                <td class="tg-73oq" colspan="8" style="text-align: center;"><b>TOTAL ROOM FEE</b></td>
                <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($overall_total_price, 2)   . '</td>
            </tr>
            <tr>
                <td class="tg-73oq" colspan="8" style="text-align: center;"><b>VATABLE AMOUNT</b></td>
                <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($vatable_amount, 2)  . '</td>
            </tr>
            <tr>
                <td class="tg-73oq" colspan="8" style="text-align: center;"><b>VALUE ADDED TAX</b></td>
                <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($vat, 2)  .  '</td>
            </tr>
       
    ';

    $html .= $room_html;

    $html .= '
        </table>

        <br>
    ';

} else {

    $_SESSION['msg'] = 'No Data Exist';
    $_SESSION['alert'] = 'alert alert-success';

    header("location: ../../admin/reports/guest_report.php"); 
    
}

// echo $html;

$mpdf->WriteHTML($html);
$mpdf->Output('guest_details.pdf', 'D');




