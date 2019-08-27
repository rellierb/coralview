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
$guest_count = 0;

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST['reference_no'];
}

// INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id  INNER JOIN rooms R ON BR.room_id = R.Id
$reservation_details_query = "SELECT * FROM reservation RES
    INNER JOIN guest G ON G.id = RES.guest_id
    WHERE RES.reference_no = '$reference_no'";
$reservation_details_result = mysqli_query($db, $reservation_details_query);
echo $reservation_details_query;
if($reservation_details_result) {
    $html .= '
    <div class="row">
        <div class="col">
    ';
    while($reservation = mysqli_fetch_assoc($reservation_details_result)) {

        $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
        $diff = $dateDiff->format('%d');
        $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
        $payment_type = $reservation["payment"];
        
        $html .= '
            <table class="table table-bordered">
                <tr>
                    <th class="pr-3 pb-3" scope="col">FULL NAME</th>
                    <td class="pb-3 pl-4">' . $full_name . '</td>
                    <th class="pr-3 pb-3" scope="col">CONTACT NUMBER</th>
                    <td class="pb-3 pl-4">' . $reservation["contact_number"] . '</td>
                </tr>
                <tr>
                    <th class="pr-3 pb-3" scope="col">EMAIL ADDRESS</th>
                    <td class="pb-3 pl-4">' . $reservation["email"] . '</td>
                    <th class="pr-3 pb-3" scope="col">ADDRESS</th>
                    <td class="pb-3 pl-4">' . $reservation["address"] . '</td>
                </tr>
            </table>
            <br />

            <h5 class="text-center mt-3 text-info">BOOKING DETAILS</h5>
            <table class="table table-bordered">
                <tr>
                    <th class="pr-3 pb-3">REFERENCE CODE</th>
                    <td class="pb-3 pl-4" id="referenceCode">' . $reservation["reference_no"] . '</td>
                    <th class="pr-3 pb-3"><b>STATUS</b></th>
                    <td class="pb-3 pl-4">' . $reservation["status"] . '</td>
                    <th class="pr-3 pb-3"><b>GUEST/S NUMBER </b></th>
                    <td class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
                </tr>
                <tr>
                    <th class="pr-3 pb-3"><b>CHECK-IN DATE</b></th>
                    <td class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                    <th class="pr-3 pb-3"><b>CHECK-OUT DATE</b></th>
                    <td class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                    <th class="pr-3 pb-3"><b>DAY/S </b></th>
                    <td class="pb-3 pl-4">' . $diff . '</td>
                    
                </tr>
                
            </table>

            <br>

        ';
    }

    $html .= '
            </div>
        </div>
    ';
}

















// 
// $html .= '
//     <div style="display: block; margin: 0 auto; text-align: center;">
//         <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCABnAOEDASIAAhEBAxEB/8QAHQABAAEFAQEBAAAAAAAAAAAAAAcDBAUGCAIBCf/EAEoQAAAFAgMEBAkJBQUJAAAAAAACAwQFAQYHEhMIESIyFBUjQhYhM1JicoKSoiQxQ1FTY3GTsgkXRWHCJTRBc/A1RFRlgYORo7H/xAAbAQEAAQUBAAAAAAAAAAAAAAAABQECAwQGB//EADYRAAEDAgMGBAQDCQAAAAAAAAEAAgMEEQUSIQYxQVFhkRMUcaEiMoHwFbHBByMkQmJyktHx/9oADAMBAAIRAxEAPwD9UwAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAAARAHg59wwNz3fFWfGKyMq7TatEqeOqn/ygua0vcGtFyVjkkZE0vebALN5wzjlW49py4LvkuprJitJZbgSWU7RU/ql5SjMx2Bt/wB3tar3Rerpmqr/ALohxZPdNlEwcKdCAaqQRk8NSewXMDHmVL8tDEZRzFgPS54ro0y24fTnHPLvZ2uWAb60Df8AIdK+ycch/iEcS+NmJVgOHcBMaXSuSiy6Zc3rFMXmGSHChUuy0soceuh91r1W0DsObmrqdzBz0cPZdpZ99BUEZbP+t+62HWdKqrKrUOtvVrmNxGMYSUIaWMxSOjPDRdTSzCphbMBo4XXsAAYltIA8ACL2A8ZxqszidbNvuFUJCaaNVkfKpKKcRRc1jnmzASeixSSxwjNI4AdVtoCyavUX7ZFdKtFE1KZyVF6LehV4cHC4QB4OfcPhVaVBXKoAACIAACIAACIAACIAACIPGcexbqF3gqErF3RcLO14R5KvldJq0SMspWv1bvmHBmJWJMrifcfTHP8AdNXIxaJ8hCd32hNe2DdirdvEW0krSmr8scenl4SlEX7OlspXPixHaiVFWrTO5U3+jy/FlHoGC0sdHROxKQa2JHoP1K8d2lr5sRxBmEwmzbgG3M8foulcCsImuHFuJLOkdWadJ53K3m+gUSsRPdQeyE3D2QcNUTvqnmSQ3JXqdFRRUMDYYhYDv17rwYghTabw98MLL6Yxba0tHqFOlRNPMc5K8xP6hOIw1wS7e34WQknPkmiR1j+yXMLqaV8E7JI949+itxCljqqZ8M3ykceHVa5aC7CwbAgUZR0jH0RZJJqVXUy7j5S+IXMRitakw66E0nmizr7LUym+IQvggdXGS6pi67hp0xJmpoMWanjSb7+LxF5c2UxRe7V9tsKWQymU0kmkg0ekImsnwnym7vwlEp5Rjqvy0xOd288AT+a55uJSsw7zdO0ZGA2B3uDdL9L8LroNV2iiXtVKJ09OuUa4hiNbLlw8QSnGiijNLWcUIpyE86phH8DcdJ/Z46ynUqOq9WrVVop38mYpTfDQRNs1YYNL3jp5aUot1VqFR6ImplIsfLm3my+bmFkdCzw5pJnEZDbTibrJPjEvmIIKZgJkF9TawtddSW/ecLdDZZ5DyKEi1SPpqKoHzZT/AFDFPcXbPj3GitcLBJbk8pmGkS+DMfbGF0vAQMsrB9MU1lZB4p8JjcPDlKNDui4LXtDB17AQaKstTo3RqS6bfsDKmrz1VCnpIah+WMuNzYC1tOdzppyVanFKmkjzTBrSG3OpIvwAtr7aLplhJtJloi7aqpOmytOzVTrmKYcm7SzJpI4sQMa0bJJulsmrVNPiPnU73siVtlNks2wmQXVru6W5WWTp5pc2X+kRjHUVvbaxXWU3KJNHNa+yknlL8QkcOh8tVzPY64ja7p6KJxirNfh9KxzbOmc3TfbiV082fR8foxnSmyTrTyJNNUuc+6nmi+eSzCO0aOnaLTWU00tdQpNU/wBRc3NUcD7a1px2EG0xs+YlwLSke7d3J1TJdH4SONUxS5j+llUV94THthYXQuM8nhpb+91W70LhbyUaqzUMXobRI5TO11fqT06Zc32ihCjkwSdTvXoTG5WgD07KR9pGwEsW8L5K00r5WsN07VRqnLMHJU1iGKfNp8xeblG9WRAK2haUNCqyC0srHskmvWLvyrjIUpc5/SMOJMabMhcSP2k2FttNY5HTt+MVuabVQTy9IPqG0NXzjFUKQ3/Ub9tF4xyF8Y5w+ANsXB4LtOjdbXlcRHOgq0j+boyCv0ainLm7pTCquXU3hhA9Z9WddR/WP/CdLT1fczZhdnl2CDpFmo8bUcq+TRqoWhz/AIFHBuOVkW9jh4LYT4H280/s+SbyEtf8c3yN4khK+PI8L5ZdTx8hjekL/aysiJwg2odne/4FHq9WQuBWIk9NThd6qeUpjl7xuI4Iu6VnqTem9RVNOm+hO0rl4q8tBQkJVnFFSVfO0miaqhU06uFCkoc9eUtN/eHC37QXEq4OusN0YZTRs+EvyJQl1k65elu9TUKiX0Uyl4vSMXzRNm2JhxE4zWlatjOdVS4Hc40kI1VsqYqrTQNmWdcPKUqZjlzecoUEXRwCkgnoo0oKoIgAAIgAAIgt1vmqLgeDk3gqEXXFm1qirXEdnqeR6tS0/eMMlsfKJ+G0vn8r0Ls/w1BJW1Dhord9uIzDBHVfxOY6iXeOll4qFHMOGd7q2DekdMJJVVSSppqJU7xDc1P0j0yjIxDBHQRH4mi1vReG4ix2E7Riql+Vxvf10PZfowA1u1bxjrxjEpKLWTcoq0/w5ifyqM5q0rWo81c1zHFrhYhe2xzRytD43Ag7jzVRX5hrF/Qqlz2jMRiVaUWdslUE93pEFjf+KUBh/HVWlHaWrWnZtE+JVX+RSjSsNGdy3tcnhxO1UiY/TyRsQSvcN9If0jDahheGicmwG6/EqMqaqKV3k2/EXaG3Af7Wg7Od2tMOKz1tXQt1E76TrJdM4c/DlNlMb1RsOKybrG6SjbftzVpCIqa72W0+xzd0pTd43zidXsQzkPLNkVf8xPMPh1Wse38omil7oyVGLQsnNY4hr+p0vbetKHBX+VGHuf8Au+l7kXvY9FC2O8owsfCdW04uvypVJJsminz6WbiNXL6Iv9lojRvhiigmr8q1DLOEu+SpjcOb3RvT+9YBv9L0tX7tPMME5xQSb0+Sx/5g5Wt22wmlpXU8kouTmJGuv0UpBs7VurhVgWAblA5BQ1tWXG/8PoGNdUVpAJJEX0e64Pn4vhFLHy+uv7AiGUXAO4+F1SVTWcJ6RD5S8pS+0JOkb3lZf6JL7re3Kf8AULNy2mp9tuVSdO0ufRUTzEEKP2pULBC2lo3yGPkLAnmsM2xlVMagyVQb4vMAkdL8ll8P7ohbIwtiOlO9Fo1Yk7ZTlVOYpjGIXzjCG9mycj64kT0zMu+iO3aXZdI4dY6ihjGy+rlL7wlEljzTj+H/AJmUXLPDmQceSSa/mF/pEezb3FHxTCHDX5peJO7W6kH7LQmSme+pblh4czZQF+0ff1u+ysN/Bhg6mpuPvFk5bNGyfPloc27N3eUTZh1K9TyL25biSVdXfLJkI50/JMUKeTaIfdl7xu8bMYbBTC+Qr5V01/13hclwld0/iCX5Q5iTFNsJomtipQ12tzz9+C6sQYYz5pL/AH6LiHZsxZdzO1jjVeKttv3dxyEmSKa0dpVSbsYxI/EcyuXLmylLw97MUa7c15ROCG3Zet8YjWhSctC72yNI6RcMuloMjkKmXfym83/Dzh+gVMJdCm+kgkl/28oFwlSf0/2gksl/llNxiT/ENqXTl3lD4ZbbLmaDfmDr2IKwiPDwywfr9bKM7Q2nlbwctGdkWrXwfT8rIuEDs2hCeakUxSmOb2coiDbxc3LiPC4Yo24wS6/aXa3ctleI5ETlTNxn9EuXN7I6xphDv/iH/rFL91H/ADBL8vMIoybXsqBKIjkH8pIN/U6e1vRbA/DHNy315rkLbQjFa7MERAQ0UrJSMVckfJcHG4duKqG1Dm85RQynxDoPC+6Xbdytdt2MFfCqWSIToZFCnJFtOYrUn6jG7xhuC2GCvkUn7VVX7Kv+vVFsrhfKoU7LSV+EaMmI7ZQQNj8IlwJJNgSQdwtyHuswhwtzy7Ppy6ram+KkVWnapKo/iMwzviFf07J+l7fCIvWsSbb/AMP/AC1Ciwcwcg38q0V/KGizbHaSjd/F01x/aQshw6gl0jkt9VPKDpJxTs1KKC4IOeGb53H+SVVRV+74RMdkSruYhaLPktFXl9f+Y9D2a2tZjspgfEWPAvxt/wBUPWYeaUZmuuFs4AA9GUQgAAIqCie+u7d4hCmImzDCXg4WexhqQb9bjUqnxJGP52UTkPlfmG3TVU1I/wASF1io+toKbEY/CqWBwXHLfZrxEtZ1rQUsjT71ByZL3ijZ2eEuMkx2L+9uiNfq1M36S/1CfpW6Y+H8Squ9X7JPiMMMq+uCf8TFr1U1+1cc5xr1e2DgSwNbJJ/SwE97WUNBshSR6Ne9rTv+Mge36LSbSwFtuyHNZmZf9dyvP0yQ5SeqUbi+xJj2/YsEVHav8uEe22G6S9aLSjtWQV+AbOxg2EfT5K1SR/AcdVT7Q4u+7nNhb/k7tu/NdXS0eHYezJE257dzvPdaGpI3fcH92R6Il7v6gRwxfyFdV/IeP3hJOQViCPbsjBOc2ITvlPIuIHYKS8+9gtC0NH3zWkMsKo5Cm9VZVb4RmWtmRTWnAwS9viGwgJun2dwqk1hgaD6LVfUzSfM4qwQj0W9OyRTS9SguaJUp8wrAJtlPFH8jQPotYkk3Ko5BybbOB+JFoxtyO4tqk0m1o1w1ZKpvE0jVOpKKr7ymT+5PTiPxd0uUdcAM4FlRcx2zhtixGwdyPZCQWd3LIW9DMklesS5SO0NQrs5acpTG1M2b4hgntj7RC8JDoJze6QStFaPfOySKZNaTq2UKmuUuXhNraRs3reqOuQAj70VLfeqgCmH9/PsFMTLamna0tKySb5tB1eOE1DaSrRMqZTn9FbVN6o163MJ8TbejoxvFvOo2y1w0eSVG6jcqx2lGxUylyFLolLqF5ScxfSHUAAqrnCRtTGNzJ3gvSRVTj3j5pRi0bSRSqkblcqa+kcxezMZEyRvZy+kMpZ+Gl52/jFIzPS1fB+QfKqPUTvSmIsl0FFNJSqXn6yagnsAAsmq5lSwavRjj8teDZq1UgVpd2roaiaSyOoybpJuqnLxKJ5k1imbm84phXtmwMaGsLh7SVupR3KtZw724O0S0XDWpcpki5cpsmY1VCl4vmKU3nDpMAtbci54gLBxTXfxCc/cLvodJtZw+WaPClOZv0YxSlL6Blshil7okXDC05W1/CukmqqvWQm3D1sou51/k58uUtPNy8XCJCAWlgO9FYmZJV+hT/wDAuESCsAtbExhJaLFVJJ3oAAMqogpm+YVBauG+vTdq1S9QWuvY2RWT2XRbdlTtVfsU+Iwxp2MpMV+VK9XJfYt+c3rGGdSYIt6V006U+v0vxFzkEW6kfPpO7TkNB9VkDg06LERkA0iaUo3SpWv2qnEavtDLk8QZB7G7DTxQNDYmgBWEkm7jdAABsqiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAiAAAi//9k=" alt="" />
//     </div>
//     <style type="text/css">
//         .tg  {border-collapse:collapse;border-spacing:0;}
//         .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
//         .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
//         .tg .tg-4688{font-weight:bold;font-size:16px;font-family:Arial, Helvetica, sans-serif !important;;border-color:black;text-align:left;vertical-align:top;background-color:#47a9df;color:white;}
//         .tg .tg-0lax{text-align:center;vertical-align:top}
//         .tg .tg-0pky{border-color:inherit;text-align:center;vertical-align:top}
//     </style>
//     <br>
//     <br>
//     <br>
//     <br>
//     <br>
//     <br>
//     <table class="tg" style="width: 100%;">
//         <tr>
//             <th class="tg-0pky" colspan="2"><span style="font-weight:bold">Name</span></th>
//             <th class="tg-0pky" colspan="2">' . $full_name . '</th>
//         </tr>
//         <tr>
//             <td class="tg-0pky" colspan="2"><span style="font-weight:bold">Address</span></td>
//             <td class="tg-0pky" colspan="2">' . $address . '</td>
//         </tr>
//         <tr>
//             <td class="tg-0pky" colspan="2"><span style="font-weight:bold">Date</span></td>
//             <td class="tg-0pky" colspan="2">' . date("F m, Y") . '</td>
//         </tr>
//         <tr>
//             <th class="tg-4688" colspan="2" style="text-align: center;">Description</th>
//             <th class="tg-4688" style="text-align: center;"><span style="font-weight:bold;">Quantity</span></th>
//             <th class="tg-4688" style="text-align: center;">Amount</th>
//         </tr>
// ';

// $room_reservation_details_query = "SELECT * FROM reservation RES
//     INNER JOIN guest G ON G.id = RES.guest_id
//     INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
//     INNER JOIN rooms R ON BR.room_id = R.Id
//     WHERE RES.reference_no = '$reference_no'";

// $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);
// $rooms_reserved = array();
// $quantity = 0;

// if($room_reservation_details_result) {

//     while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
        
//         $room_id = $room_reservation["room_id"];
//         $room_quantity = $room_reservation["quantity"];
//         $rooms_reserved[$room_id] = $room_quantity; 
//         $quantity += $room_quantity;
//         $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

//         $html .= '
//             <tr>
//                 <td class="tg-0pky" colspan="2">' . $room_reservation["type"] . '</td>
//                 <td class="tg-0lax">' . $room_reservation["quantity"]  . '</td>
//                 <td class="tg-0pky">' . number_format($total_price, 2) . '</td>
//             </tr>
//         ';
        
//         $overall_total_price += $total_price;
//     }
    
// }

// $_SESSION["senior_discount"] = 0;
// $_SESSION["pwd_discount"] = 1;

// if(!empty($_SESSION["senior_discount"])) { 

//     $SENIOR_DISCOUNT = .2;
//     $discount = 0;
//     $senior_discount_price = $overall_total_price / $guest_count;
//     $senior_discount_price *= $SENIOR_DISCOUNT;
//     $overall_total_price -= $senior_discount_price;
//     $senior_count = $_SESSION["senior_discount"];

//     $html .= '
//         <tr>
//             <td class="tg-0lax" colspan="3"><span style="font-weight:bold">SENIOR CITIZEN DISCOUNT</span></td>
//             <td class="tg-0lax"><b>' . number_format($senior_discount_price, 2) . '</b></td>
//         </tr>
//     ';
    
// }

// // echo $_SESSION["pwd_discount"];
// // echo $_SESSION["pwd_discount"];

// if(!empty($_SESSION["pwd_discount"])) {
//     $PWD_DISCOUNT = .2;
//     $discount = 0;
//     $pwd_discount_price = $overall_total_price / $guest_count;
//     $pwd_discount_price *= $PWD_DISCOUNT;
//     $overall_total_price -= $pwd_discount_price;
//     $pwd_count = $_SESSION["pwd_discount"];

//     $html .= '
//         <tr>
//             <td class="tg-0lax" colspan="3"><span style="font-weight:bold">PWD DISCOUNT</span></td>
//             <td class="tg-0lax"><b>' . number_format($pwd_discount_price, 2) . '</b></td>
//         </tr>
//     ';
// }

// $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
// $extra_list_result = mysqli_query($db, $extra_list_query);

// if(mysqli_num_rows($extra_list_result) > 0) {

//     while($extra = mysqli_fetch_assoc($extra_list_result)) {

//         $total_extra = $extra["price"] * $extra["quantity"];
//         $overall_total_extra += $total_extra;

//         $html .= '
//             <tr>
//                 <td class="tg-0pky" colspan="2">' . $extra["description"] . '</td>
//                 <td class="tg-0lax">' . $extra["quantity"]  . '</td>
//                 <td class="tg-0pky">' . number_format($total_extra, 2) . '</td>
//             </tr>
//         ';

//     }

//     $overall_total_price += $overall_total_extra;
// }

// $vatable_amount = $overall_total_price / 1.12;
// $vat = $overall_total_price - $vatable_amount;

// $html .= '

//     <tr>
//         <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Total</span></td>
//         <td class="tg-0lax"><b>' . number_format($overall_total_price, 2) .'</b></td>
//     </tr>
//     <tr>
//         <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Vatable Amount</span></td>
//         <td class="tg-0lax">' . number_format($vatable_amount, 2) .'</td>
//     </tr>
//     <tr>
//         <td class="tg-0lax" colspan="3"><span style="font-weight:bold">VAT 12%</span></td>
//         <td class="tg-0lax">' . number_format($vat, 2) .'</td>
//     </tr>

// </table>
// ';
echo $html;
// $dompdf = new Dompdf();
// $dompdf->loadHtml($html);
// $dompdf->setPaper('A4', 'landscape');
// $dompdf->render();
// $dompdf->stream();