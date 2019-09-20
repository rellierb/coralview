<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Mpdf\Mpdf;

require('../assets/connection.php');
require_once('../../composer/vendor/autoload.php');

var_dump(__DIR__);

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

if(isset($_POST["room_name"])) {
    $room_name = mysqli_real_escape_string($db, trim($_POST['room_name']));
}

if(isset($_POST["room_availability"])) {
    $room_availability = mysqli_real_escape_string($db, trim($_POST['room_availability']));
}

if(isset($_POST["date_reservation_from"])) {
    $date_reservation_from = mysqli_real_escape_string($db, trim($_POST['date_reservation_from']));
}

if(isset($_POST["date_reservation_to"])) {
    $date_reservation_to = mysqli_real_escape_string($db, trim($_POST['date_reservation_to']));
}


$room_query = '';

if(!empty($room_name) && !empty($room_availability)) {

    $room_query = "SELECT RS.room_number, RS.status, R.type FROM rooms_status RS 

    INNER JOIN rooms R ON RS.room_id = R.id 
    INNER JOIN booking_rooms BR ON BR.room_id = R.id
    INNER JOIN reservation RES ON RES.Id = BR.reservation_id

    WHERE RS.status='$room_availability' AND R.Id='$room_name' AND RES.date_created BETWEEN '$date_reservation_from' AND '$date_reservation_to'";

} else if (!empty($room_name) && empty($room_availability)) {

    $room_query = "SELECT RS.room_number, RS.status, R.type FROM rooms_status RS 

    INNER JOIN rooms R ON RS.room_id = R.id 
    INNER JOIN booking_rooms BR ON BR.room_id = R.id
    INNER JOIN reservation RES ON RES.Id = BR.reservation_id

    WHERE RS.status='$room_availability' AND RES.date_created BETWEEN '$date_reservation_from' AND '$date_reservation_to'";


} else if (empty($room_name) && !empty($room_availability)) {


    $room_query = "SELECT RS.room_number, RS.status, R.type FROM rooms_status RS 

    INNER JOIN rooms R ON RS.room_id = R.id 
    INNER JOIN booking_rooms BR ON BR.room_id = R.id
    INNER JOIN reservation RES ON RES.Id = BR.reservation_id

    WHERE R.Id='$room_name' AND RES.date_created BETWEEN '$date_reservation_from' AND '$date_reservation_to'";


} else {

    $room_query = "SELECT RS.room_number, RS.status, R.type FROM rooms_status RS 
    INNER JOIN rooms R ON RS.room_id = R.id ";

}

$room_result = mysqli_query($db, $room_query);

if(mysqli_num_rows($room_result) > 0) {

    $html = '

        <div style="text-align: center;">
            <img src="/coralview/assets/images/coralview-logo.jpg"  />
            <h1 style="font-family: Arial;">ROOM REPORTS</h1>
        </div>
        <br>
        <style type="text/css">
            .tg  {border-collapse:collapse;border-spacing:0;border-color:#9ABAD9;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#9ABAD9;color:#444;background-color:#EBF5FF;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#9ABAD9;color:#fff;background-color:#409cff;}
            .tg .tg-phtq{background-color:#D2E4FC;border-color:inherit;text-align:left;vertical-align:top}
            .tg .tg-lboi{border-color:inherit;text-align:left;vertical-align:middle}
            .tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
            .tg .tg-48yq{background-color:#D2E4FC;border-color:inherit;text-align:left;vertical-align:middle}
            .tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
        </style>
        <table class="tg" style="width: 100%;">
            <tr>
                <th class="tg-0pky" style="text-align: center;" colspan="4">ROOM NUMBER</th>
                <th class="tg-c3ow" style="text-align: center;" colspan="6">ROOM NAME</th>
                <th class="tg-c3ow" style="text-align: center;" colspan="5">STATUS</th>
            </tr>
    ';

    while($room = mysqli_fetch_assoc($room_result)) {
        
        $html .= '
            <tr>
                <td style="width: 30%; text-align: center; " class="tg-phtq" colspan="4">' . $room['room_number'] . '</td>
                <td style="width: 40%; text-align: center; " class="tg-phtq" colspan="6">' . $room['type'] . '</td>
                <td style="width: 30%; text-align: center; " class="tg-phtq" colspan="5">' . $room['status'] . '</td>
            </tr>
        ';

    }

    $html .= '
        </table>

        <br>
        <br>
        <br>

        <div>
            <p style="font-family: Arial;"><b>PRINTED BY: </b> ' . $user . '</p>
            <p style="font-family: Arial;"><b>DATE PRINTED: </b> ' . $date . ' </p>
        </div>
    ';

} else {

    $_SESSION['msg'] = 'No Data Exist';
    $_SESSION['alert'] = 'alert alert-warning';

    header("Location: ../../admin/reports/room_report.php"); 

}

$mpdf->WriteHTML($html);
$mpdf->Output('room_reports.pdf', 'D');




