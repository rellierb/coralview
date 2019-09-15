<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["reference_no"])) {
    $reference_no = $_REQUEST["reference_no"];
    $_SESSION["reference_no"] = $reference_no;

    $isCancelled = false;

    $cancel_query = "SELECT * FROM reservation WHERE reference_no='$reference_no' AND status='CANCELLED'";
    $cancel_result = mysqli_query($db, $cancel_query);

    if(mysqli_num_rows($cancel_result) > 0) {
        $isCancelled = true;
    }

    $isPaymentUploaded = false;

    $is_uploaded = "SELECT payment_path FROM reservation WHERE reference_no='$reference_no'";
    $is_uploaded_result = mysqli_query($db, $is_uploaded);
    
    if(mysqli_num_rows($cancel_result) > 0) {
        $isPaymentUploaded = true;
    }

}


$html = '';
$payment_html = '';
$room_html = '';
$payment_photo = '';

?>
    
    <div class="container">

        <?php
        
        if(isset($_SESSION['msg']) && isset($_SESSION['alert'])) {
            echo '
                <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                    ' . $_SESSION['msg']  . '
                </div>
            ';
        } else if(isset($_SESSION["fileType"])) {
            echo '
                <div class="alert alert-error text-center" role="alert">
                    ' . $_SESSION['fileError']  . '
                    ' . $_SESSION['fileType']  . '
                </div>
            ';
        } else if(isset($_SESSION["fileExists"])) {
            echo '
                <div class="alert alert-error text-center" role="alert">
                    ' . $_SESSION['fileError']  . '
                    ' . $_SESSION['fileExists']  . '
                </div>
            ';
        } else if(isset($_SESSION["fileImage"])) {
            echo '
                <div class="alert alert-error text-center" role="alert">
                    ' . $_SESSION['fileError']  . '
                    ' . $_SESSION['fileImage']  . '
                </div>
            ';
        } else if(isset($_SESSION["fileType"])) {
            echo '
                <div class="alert alert-error text-center" role="alert">
                    ' . $_SESSION['fileError']  . '
                    ' . $_SESSION['fileType']  . '
                </div>
            ';
        }
        
        ?>

        <div class="row">
                <div class="col">

                    <?php
                    
                    if(!$isCancelled) {
                        echo '<button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#cancelReservation">Cancel Reservation</button>';  
                    } 
                    
                    ?>


                    <?php
                    
                    if($isPaymentUploaded) {
                        echo '<button type="button" class="btn btn-info float-right mr-2" data-toggle="modal" data-target="#uploadPaymentModal">Upload Payment</button>';
                    }
                    
                    ?>


            </div>
        </div>


        <div class="row">
            <div class="col-12">
            
            <?php
            // INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id  INNER JOIN rooms R ON BR.room_id = R.Id
            $reservation_details_query = "SELECT * FROM reservation RES
                INNER JOIN guest G ON G.id = RES.guest_id
                WHERE RES.reference_no = '$reference_no'";

            $reservation_details_result = mysqli_query($db, $reservation_details_query);

            if($reservation_details_result) {
                $html .= '
                <h5 class="text-center mt-3 text-info mt-3">Guest Details</h5>
                <hr />

                <div class="row">
                    <div class="col">
                ';

                while($reservation = mysqli_fetch_assoc($reservation_details_result)) {

                    $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
                    $diff = $dateDiff->format('%d');
                    $payment_photo = $reservation["payment_path"];

                    $html .= '
                        <table style="width: 60%; margin: 0 auto;" class="table table-bordered">
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3">FIRST NAME</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["first_name"]  . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3">LAST NAME</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["last_name"] . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3">EMAIL</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["email"] . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3">ADDRESS</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["address"] . '</td>
                            </tr>
                        </table>
                        <br />

                        <h5 class="text-center mt-3 table text-info" >Booking Details</h5>
                        <hr />
                        <table style="width: 60%; margin: 0 auto;" class="table table-bordered">
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3"><b>CHECK-IN DATE</b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3"><b>CHECK-OUT DATE</b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3"><b>NIGHT/S</b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $diff . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-4 pb-3"><b>GUEST/S NUMBER</b></th>
                                <td style="width: 70%;" class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
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
                       
            ?>

            <?php
            //   INNER JOIN rooms R ON BR.room_id = R.Id
            $room_reservation_details_query = "SELECT * FROM reservation RES
                INNER JOIN guest G ON G.id = RES.guest_id
                INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
                INNER JOIN rooms R ON BR.room_id = R.Id
                WHERE RES.reference_no = '$reference_no'";


            $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);

            if($room_reservation_details_result) {
                
                $room_html .= '<br><h5 class="text-center mt-3 text-info">Room Details</h5>';
                $room_html .= '
                    <hr />  
                    <table style="width: 100%;" class="table table-bordered">
                    <tr>
                        <th class="text-center" style="width: 55%;">ROOMS/S RESERVE</th>
                        <th class="text-center" style="width: 15%;">QUANTITY</th>
                        <th class="text-center" style="width: 15%;">PRICE</th>
                        <th class="text-center" style="width: 15%;">TOTAL</th>
                    </tr>
                
                ';

                $overall_total_price = 0;

                while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {

                    $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

                    $room_html .= '
                        <tr>
                            <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                            <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                            <td class="text-center" style="width: 15%;">' . $room_reservation["peak_rate"] . '</td>
                            <td class="text-center" style="width: 15%;"> ' . number_format($total_price, 2)  . '</td>
                        </tr>
                    ';

                    $overall_total_price += $total_price;
                }
                
                // echo '
                //     <tr>
                //         <td></td>
                //         <td></td>
                //         <td></td>
                //         <td class="text-center"><b>PHP '  . number_format($overall_total_price, 2) .  '</b></td>
                //     </tr>
                // ';

                $room_html .= '</table>';
            }
            ?>
 
            <?php
            
            $overall_total_amount = $diff * $overall_total_price;
            $vatable_amount = $overall_total_amount / 1.12;
            $vat = $overall_total_amount - $vatable_amount;
            
            $payment_html .= '

                <br>
                <h5 class="text-center mt-3 text-info mt-3">Payment Details</h5>
                <hr />

                <table style="width: 40%; margin: 0 auto;" class="table table-bordered">
                    <tr>
                        <td class="text-center"><h6>TOTAL AMOUNT</h6></td>
                        <td class="text-right pr-4 pb-3">PHP ' . number_format($overall_total_amount, 2)  . '</td>
                    </tr>
                    <tr>
                        <td class="text-center"><h6>Total Room Fee</h6></td>
                        <td class="text-right pr-4 pb-3">' . number_format($overall_total_price, 2) . '</td>
                    </tr>
                    <tr>
                        <td class="text-center"><h6>VATABLE AMOUNT</h6></td>
                        <td class="text-right pr-4 pb-3">' . number_format($vatable_amount, 2) . '</td>
                    </tr>
                    <tr>
                        <td class="text-center"><h6>VALUE ADDED TAX</h6></td>
                        <td class="text-right pr-4 pb-3">' . number_format($vat, 2) . '</td>
                    </tr>
                </table>
  
            ';

            ?>

            <?php echo $html; ?>
            <?php echo $payment_html; ?>
           
            <?php
            
            if($payment_photo != '') {
                echo '
                
                <br>
                <h5 class="text-center mt-3 text-info mt-3">Deposit Slip Details</h5>
                <hr />
                
                ';
                echo '<div style="text-align: center;"><img style="width: 50%; height: 25%;" src="' . $payment_photo .   '"></img></div>';
                echo '
                    <br>
                    <div class="text-center">
                        <p>Please wait an email from the resort administrator for the processing of your payment.</p>
                    
                    </div>
                ';
            }            
            
            ?>
            
            <?php echo $room_html; ?>


            <br />
            <hr>
        


        </div>




            </div>
        </div>
    </div>


    



    <div class="container">
            
        <div class="row">               
            <div class="col text-center">
                <button href="/coralview/index.php" class="btn btn-primary mb-5 mt-2">GO BACK TO HOME PAGE</button>                
            </div> 
        </div>
    
    </div>
    




<?php

include('common/cancel_reservation_modal.php');
include('common/upload_payment_modal.php');
include('common/footer.php');

session_destroy();

?>
