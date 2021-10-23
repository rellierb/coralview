<?php
ob_start();
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Mpdf\Mpdf;

require('../assets/connection.php');
require_once('../../composer/vendor/autoload.php');

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

$date_transaction = '';

if(isset($_POST["date_transaction"])) {
    $date_transaction = mysqli_real_escape_string($db, trim($_POST['date_transaction']));
}

echo $date_transaction;

$transaction_query = "SELECT * FROM reservation RES
    INNER JOIN billing B ON RES.reference_no = B.reference_no
    WHERE B.time_stamp LIKE '%$date_transaction%'  
";

echo $transaction_query;

$guest_count = 0;

$reservation_details_result = mysqli_query($db, $transaction_query);

$reference_no = '';
$arrival_date = '';
$departure_date = '';
$nights_of_stay = 0;
$overall_total_price = 0;
$overall_total_extra = 0;
$guest_id = '';
$payment_type = '';
$nights_of_stay = '';
$is_peak_rate = 0;
$html = '';


if(mysqli_num_rows($reservation_details_result) > 0) {

    $html = '
    <div style="text-align: center;">
        <h1 style="font-family: Arial;">REPORT SUMMARY</h1>
    </div>
    ';
    
    while($reservation = mysqli_fetch_assoc($reservation_details_result)) {
        $reference_no = $reservation["reference_no"];
        $guest_count = $reservation["adult_count"] + $reservation["kids_count"];
        $arrival_date = $reservation["check_in_date"];
        $departure_date = $reservation["check_out_date"];
        $status = $reservation["status"];
        $is_peak_rate = $reservation["is_peak_rate"];
        $payment_type = $reservation["payment"];

        $amount_paid = $reservation["amount_paid"];
        $description = $reservation["description"];

        $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
        $diff = $dateDiff->format('%d');

        $nights_of_stay = $diff;
    
        $temp_arrival_date = date_create($arrival_date);
        $temp_departure_date = date_create($departure_date);

        $html .= '

            <br>

            <style type="text/css">
                .tg  {border-collapse:collapse;border-spacing:0;border-color:#000;}
                .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#9ABAD9;color:#444;background-color:#EBF5FF;}
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
                    <td class="tg-7btt" colspan="6">Guest Count</td>
                    <td class="tg-0pky" colspan="9" style="text-align: center;">' . $guest_count . '</td>
                </tr>
                <tr>
                    <td class="tg-yq5v" colspan="15">TRANSACTION DETAILS</td>
                </tr>
                <tr>
                    <td class="tg-uzvj" colspan="6">Amount Paid</td>
                    <td class="tg-lboi" colspan="9" style="text-align: center;">' . number_format($amount_paid, 2) . '</td>
                </tr>
                <tr>
                    <td class="tg-uzvj" colspan="6">Description</td>
                    <td class="tg-lboi" colspan="9" style="text-align: center;">' . $description . '</td>
                </tr>
        ';

        // $room_reservation_details_query = "SELECT * FROM reservation RES
        // INNER JOIN guest G ON G.id = RES.guest_id
        // INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
        // INNER JOIN rooms R ON BR.room_id = R.Id
        // WHERE RES.reference_no = '$reference_no'";

        // $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);
        // $rooms_reserved = array();
        // $quantity = 0;

        // if($room_reservation_details_result) {

        //     $html .= '
        //         <tr>
        //             <td class="tg-8nlf" colspan="15">ROOM DETAILS</td>
        //         </tr>
        //         <tr>
        //             <td class="tg-7btt" colspan="11">Room Name</td>
        //             <td class="tg-7btt" colspan="4">Qty</td>
                    
        //         </tr>
        //     ';
        //     // <td class="tg-7btt" colspan="4">Amount</td>

        //     while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                
        //         $room_id = $room_reservation["room_id"];
        //         $room_quantity = $room_reservation["quantity"];
        //         $rooms_reserved[$room_id] = $room_quantity; 
        //         $quantity += $room_quantity;
                

        //         $room_rate = 0;
        //         if($is_peak_rate == 0) {

        //             $room_rate = $room_reservation["off_peak_rate"]; 

        //         } else if($is_peak_rate == 1) {

        //             $room_rate = $room_reservation["peak_rate"];

        //         }
        //         $total_price = $room_rate * $room_reservation["quantity"];

        //         $html .= '
        //             <tr>
        //                 <td class="tg-0pky" colspan="11" style="text-align: center;">' . $room_reservation["type"] . '</td>
        //                 <td class="tg-0pky" colspan="4" style="text-align: center;">' . $room_reservation["quantity"] . '</td>
        //             </tr>
        //         ';
        //         // <td class="tg-0pky" colspan="4" style="text-align: center;">' . number_format($room_reservation["peak_rate"]) . '</td>
        //         $overall_total_price += $total_price;
        //     }

        // }

        $html .= '
        
        </table>
        <br>
        <br>
        <br>
        
        ';
    
    }

    $html .= '
            
       

        <div>
            <p style="font-family: Arial;"><b>PRINTED BY: </b> ' . $user . '</p>
            <p style="font-family: Arial;"><b>DATE PRINTED: </b> ' . $date . ' </p>
        </div>
    ';


    $mpdf->WriteHTML($html);
    $mpdf->Output('summary_report.pdf', 'D');
    ob_end_flush();

} else {

    $_SESSION['msg'] = 'No Data Exist';
    $_SESSION['alert'] = 'alert alert-warning';

    header("location: ../../admin/summary_report.php"); 
    
}




