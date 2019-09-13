<?php

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

if(isset($_POST["first_name"])) {
    $first_name = mysqli_real_escape_string($db, trim($_POST['first_name']));
}

if(isset($_POST["first_name"])) {
    $last_name = mysqli_real_escape_string($db, trim($_POST['last_name']));
}

if(isset($_POST["date_reservation_from"])) {
    $date_reservation_from = mysqli_real_escape_string($db, trim($_POST['date_reservation_from']));
}

if(isset($_POST["date_reservation_to"])) {
    $date_reservation_to = mysqli_real_escape_string($db, trim($_POST['date_reservation_to']));
}

$reference_no = '';
$guest_id = '';
$payment_type = '';
$nights_of_stay = '';
$html = '';

$guest_count = 0;

$find_guest_id = "SELECT id FROM guest WHERE first_name LIKE '%$first_name%' AND last_name LIKE '%$last_name%'";

$find_guest_id_result = mysqli_query($db, $find_guest_id);

if(mysqli_num_rows($find_guest_id_result) > 0) {

    while($guest = mysqli_fetch_assoc($find_guest_id_result)) {
        $guest_id = $guest['id'];

        $find_ref_no = "SELECT reference_no FROM reservation WHERE guest_id='$guest_id' AND date_created BETWEEN '$date_reservation_from' AND '$date_reservation_to'";
        $find_ref_no_result = mysqli_query($db, $find_ref_no);

        if(mysqli_num_rows($find_ref_no_result) > 0) {

            while($reservation = mysqli_fetch_assoc($find_ref_no_result)) {
                $reference_no = $reservation['reference_no'];

                $reservation_details_query = "SELECT * FROM reservation RES
                INNER JOIN guest G ON G.id = RES.guest_id
                WHERE RES.reference_no = '$reference_no'";

                $reservation_details_result = mysqli_query($db, $reservation_details_query);

                $arrival_date = '';
                $departure_date = '';
                $nights_of_stay = 0;
                $overall_total_price = 0;
                $overall_total_extra = 0;
                $TOTAL_PRICE = 0;

                if($reservation_details_result) {
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
                }

                $temp_arrival_date = date_create($arrival_date);
                $temp_departure_date = date_create($departure_date);
                

                $html .= '

                    <div style="text-align: center;">
                        <img src="/coralview/assets/images/coralview-logo.jpg"  />
                        <h1 style="font-family: Arial;">GUEST/S REPORT</h1>
                    </div>

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

                    $html .= '
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

                        $html .= '
                            <tr>
                                <td class="tg-0pky" colspan="8" style="text-align: center;">' . $room_reservation["type"] . '</td>
                                <td class="tg-0pky" colspan="3" style="text-align: center;">' . $room_reservation["quantity"] . '</td>
                                <td class="tg-0pky" colspan="4" style="text-align: center;">' . number_format($room_reservation["peak_rate"]) . '</td>
                            </tr>
                        ';

                        $overall_total_price += $total_price; 
                        $TOTAL_PRICE += $total_price; 
                    }

                }

                $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
                $extra_list_result = mysqli_query($db, $extra_list_query);

                if(mysqli_num_rows($extra_list_result) > 0) {

                    $html .= '
                        <tr>
                            <td class="tg-o21n" colspan="15">EXTRA DETAILS</td>
                        </tr>
                        <tr>
                            <td class="tg-mqa1" colspan="8">Description</td>
                            <td class="tg-mqa1" colspan="3">Qty</td>
                            <td class="tg-mqa1" colspan="4">Amount</td>
                        </tr>
                    ';

                    while($extra = mysqli_fetch_assoc($extra_list_result)) {

                        $total_extra = $extra["price"] * $extra["quantity"];
                        $overall_total_extra += $total_extra;

                        $html .= '
                            <tr>
                                <td class="tg-73oq"  style="text-align: center;" colspan="8">' . $extra["description"] . '</td>
                                <td class="tg-73oq"  style="text-align: center;" colspan="3">' . $extra["quantity"] . '</td>
                                <td class="tg-73oq"  style="text-align: center;" colspan="4">' .  $extra["price"] . '</td>
                            </tr>
                        ';

                    }
                    
                    // $overall_total_price += $overall_total_extra;
                }
                
                $overall_total_price *= $nights_of_stay;
                $TOTAL_PRICE *= $nights_of_stay;
                $TOTAL_PRICE += $overall_total_extra;

                $add_fees_query = "SELECT * FROM billing_additional_fees WHERE reference_no='$reference_no'";
                $add_fees_result = mysqli_query($db, $add_fees_query);
                $add_fees_amount = 0;

                $html .= '
                        <tr>
                            <td class="tg-o21n" colspan="15">PAYMENT DETAILS</td>
                        </tr>
                        <tr>
                            <td class="tg-mqa1" colspan="15">ADDITIONAL FEES</td>
                        </tr>
                    ';

                if(mysqli_num_rows($add_fees_result) > 0) {

                    while($fees = mysqli_fetch_assoc($add_fees_result)) {

                        $html .= '
                            <tr>
                                <td class="tg-73oq" colspan="8" style="text-align: center;">' . $fees["description"] . '</td>
                                <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($fees["amount"], 2) . '</td>
                            </tr>
                        ';

                        // $overall_total_price += $fees['amount'];
                        $add_fees_amount += $fees['amount'];

                    } 

                } else {

                    $html .= '
                        <tr>
                            <td class="tg-73oq" colspan="15" style="text-align: center;">No Additional Fees</td>
                        </tr>
                    ';
                }

                
                $TOTAL_PRICE += $add_fees_amount;

                $check_discount_query = "SELECT * FROM billing_discount BD INNER JOIN discount D on BD.discount_id=D.Id  WHERE BD.reference_no='$reference_no'";
                $check_discount_result = mysqli_query($db, $check_discount_query);
                $discount_price = 0;

                if(mysqli_num_rows($check_discount_result) > 0) {

                    $html .= '
                        <tr>
                            <td class="tg-mqa1" colspan="15">DISCOUNT<br></td>
                        </tr>
                    ';

                    while($discount = mysqli_fetch_assoc($check_discount_result)) {
                        
                        $discount_amount = $discount["amount"];
                        $comp_discount = $overall_total_price / $quantity;
                    
                        if($discount_amount < 1) {
                            $temp_discount_price = $comp_discount * $discount_amount;
                            $discount_price += $temp_discount_price;
                        } 

                        $change_to_percent = $discount['amount'] * 100;

                        $html .= '
                            <tr>
                                <td class="tg-73oq" colspan="8" style="text-align: center;"> ' . $discount["name"] . '</td>
                                <td class="tg-73oq" colspan="7" style="text-align: center;">' . $change_to_percent . '%</td>
                            </tr>
                        ';
                        
                    }
                }

              
                $billing_query = "SELECT * FROM billing WHERE reference_no='$reference_no'";
                $billing_result = mysqli_query($db, $billing_query);
                $balance = $TOTAL_PRICE;
            
                if(mysqli_num_rows($billing_result) > 0) {
                
                    while($billing = mysqli_fetch_assoc($billing_result)) {
                        
                        $amount_paid = $billing["amount_paid"];
                        $balance -= $amount_paid;                                            

                    }

                }
            
                $discounted_price = $balance - $discount_price;

                // DOWNPAYMENT
                $check_down_payment = "SELECT * FROM downpayment WHERE reference_no='$reference_no'";
                $check_down_payment_result = mysqli_query($db, $check_down_payment);

                $downpayment_amount = 0;

                if(mysqli_num_rows($check_down_payment_result) > 0) {

                    while($down_payment = mysqli_fetch_assoc($check_down_payment_result)) {
                        $downpayment_amount = $down_payment["amount"];
                        $balance -= $downpayment_amount;
                    }

                }
                
                $discounted_price -= $downpayment_amount;
                
                $html .= '
                        <tr>
                            <td class="tg-mqa1" colspan="15">OTHER DETAILS</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">PAYMENT TYPE</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . $payment_type .  '</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">TOTAL AMOUNT (ROOMS)</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($overall_total_price, 2)  .  '</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">TOTAL AMOUNT (EXTRAS)</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($overall_total_extra, 2)  .  '</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">DOWNPAYMENT</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($downpayment_amount, 2)  .'</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">TOTAL DISCOUNT</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($discount_price, 2) . '</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">AMOUNT AFTER DISCOUNT</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($discounted_price, 2)  .  '</td>
                        </tr>
                        <tr>
                            <td class="tg-73oq" colspan="8" style="text-align: center;">REMAINING BALANCE</td>
                            <td class="tg-73oq" colspan="7" style="text-align: center;">' . number_format($discounted_price, 2)  .  '</td>
                        </tr>
                    </table>
                    <br>
                    <br>
                    <br>

                    <div>
                        <p style="font-family: Arial;"><b>PRINTED BY: </b> ' . $user . '</p>
                        <p style="font-family: Arial;"><b>DATE PRINTED: </b> ' . $date . ' </p>
                    </div>
                ';

                                
                $mpdf->WriteHTML($html);
                $mpdf->Output('guests_reports.pdf', 'D');

            }
        
        } else {

          continue;

        }

    }

} else {

    $_SESSION['msg'] = 'No Data Exist';
    $_SESSION['alert'] = 'alert alert-warning';

    header("location: ../../admin/reports/guest_report.php"); 

}



