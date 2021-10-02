<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

/*
 * CONSTANT
 */
define('DOWNPAYMENT_PERCENT', 0.50);


if(isset($_REQUEST["reference_no"])) {

    $reference_no = $_REQUEST["reference_no"];

}

$payment_type = '';
$days_of_stay = 0;
$status = '';


$total_room_amount = 0;
$overall_total_price = 0;
$overall_total_extra = 0;
$payment_type = '';
$nights_of_stay = 0;
$is_peak_rate;

$payment_photo = '';

?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/accept_reservation.php" method="POST">
    
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Accept Reservation</h1>
                
                <?php
        
                if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                            ' . $_SESSION['msg']  . '
                        </div>
                    ';
                }
                
                ?>

                <br>

                <div class="row">
                    <div class="col">
                        
                        <div class="card">
                            <div class="card-body">

                            <div class="row">

                                <div class="col-12">
                                
                                    <h5 class="text-center mt-3 text-info">GUEST DETAILS</h5>

                                    <?php
                                    // INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id  INNER JOIN rooms R ON BR.room_id = R.Id
                                    $reservation_details_query = "SELECT * FROM reservation RES
                                        INNER JOIN guest G ON G.id = RES.guest_id
                                        WHERE RES.reference_no = '$reference_no'";

                                    $reservation_details_result = mysqli_query($db, $reservation_details_query);

                                    if($reservation_details_result) {
                                        echo '
                                        <div class="row">
                                            <div class="col">
                                        ';

                                        while($reservation = mysqli_fetch_assoc($reservation_details_result)) {
                                            
                                            $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
                                            $diff = $dateDiff->format('%d');
                                            $nights_of_stay = $diff;
                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
                                            $payment_type = $reservation["payment"];
                                            $payment_photo = $reservation['payment_path'];
                                            $status = $reservation["status"];
                                            
                                            $is_peak_rate = $reservation["is_peak_rate"];

                                            echo '
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
                                                        <th class="pr-3 pb-3"><b>NIGHT/S OF STAY </b></th>
                                                        <td class="pb-3 pl-4">' . $diff . '</td>
                                                        
                                                    </tr>
                                                   
                                                </table>

                                                <br>

                                            ';
                                        }

                                        echo '
                                                </div>
                                            </div>
                                        ';
                                    }
                                    ?>
                                </div>

                                <div class="col-12">
                                    <?php
                                        
                                        $room_reservation_details_query = "SELECT * FROM reservation RES
                                            INNER JOIN guest G ON G.id = RES.guest_id
                                            INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
                                            INNER JOIN rooms R ON BR.room_id = R.Id
                                            WHERE RES.reference_no = '$reference_no'";

                                        $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);

                                        $rooms_reserved = array();

                                        $quantity = 0;

                                        if($room_reservation_details_result) {

                                            echo '<h5 class="text-center mt-3 text-info">RESERVED ROOM/S</h5>';
                                            echo '
                                                <table class="table table-bordered">
                                                <tr>
                                                    <th class="text-center" style="width: 40%;">ROOM/S NAME</th>
                                                    <th class="text-center" >QUANTITY</th>
                                                    <th class="text-center" >PRICE</th>
                                                    <th class="text-center" >TOTAL</th>
                                                </tr>
                                            ';

                                            while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                                                
                                                $room_id = $room_reservation["room_id"];
                                                $room_quantity = $room_reservation["quantity"];
                                                $rooms_reserved[$room_id] = $room_quantity; 
                                                $quantity += $room_quantity;    

                                                $room_rate = 0;
                                                if($is_peak_rate == 0) {
                                                    $room_rate = $room_reservation["off_peak_rate"];
                                                } else if ($is_peak_rate == 1) {
                                                    $room_rate = $room_reservation["peak_rate"];
                                                    
                                                }

                                                $total_price = $room_rate * $room_reservation["quantity"];


                                                echo '
                                                    <tr>
                                                        <td class="text-center" >' . $room_reservation["type"] . '</td>
                                                        <td class="text-center" >' . $room_reservation["quantity"] . '</td>
                                                        <td class="text-center" >' . number_format($room_rate, 2)   . '</td>
                                                        <td class="text-center" > ' . number_format($total_price, 2)  . '</td>
                                                    </tr>
                                                ';
                                                $total_room_amount += $total_price;
                                                $overall_total_price += $total_price;
                                            }
                                            
                                            // $overall_total_price *= $nights_of_stay;

                                            echo '</table>';
                                        }
                                        ?>
                                    <?php
                                        
                                        // $room_reservation_details_query = "SELECT * FROM reservation RES
                                        //     INNER JOIN guest G ON G.id = RES.guest_id
                                        //     INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
                                        //     INNER JOIN rooms R ON BR.room_id = R.Id
                                        //     WHERE RES.reference_no = '$reference_no'";

                                        // $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);
                                        // $rooms_reserved = array();
                                        // $quantity = 0;

                                        // if($room_reservation_details_result) {
                                            
                                        //     echo '<h5 class="text-center mt-3 text-info">RESERVED ROOM/S</h5>';
                                        //     echo '
                                        //         <table class="table table-bordered">
                                        //         <tr>
                                        //             <th class="text-center" style="width: 40%;">ROOM/S NAME</th>
                                        //             <th class="text-center" >QUANTITY</th>
                                        //             <th class="text-center" >PRICE</th>
                                        //             <th class="text-center" >TOTAL</th>
                                        //         </tr>
                                        //     ';

                                        //     $overall_total_price = 0;

                                        //     while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                                                
                                        //         $room_id = $room_reservation["room_id"];
                                        //         $room_quantity = $room_reservation["quantity"];
                                        //         $rooms_reserved[$room_id] = $room_quantity; 
                                        //         $quantity += $room_quantity;
                                        //         $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

                                        //         echo '
                                        //             <tr>
                                        //                 <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                                        //                 <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                                        //                 <td class="text-center" style="width: 15%;">' . number_format($room_reservation["peak_rate"], 2)  . '</td>
                                        //                 <td class="text-center" style="width: 15%;"> ' . number_format($total_price, 2)  . '</td>
                                        //             </tr>
                                                    
                                        //         ';

                                        //         $overall_total_price += $total_price;
                                        //     }

                                        //     echo '</table>';
                                        // }
                                    ?>      

                                    <?php
                                    
                                    echo '<br/>';
                                    echo '<br/>';
                                    
                                    ?>

                                    <h5 class="text-center mt-3 text-info">PAYMENT DETAILS</h5> 

                                    <?php
                                    
                                    $overall_total_price *= $nights_of_stay;
                                    $vatable_amount = $overall_total_price / 1.12;
                                    $vat = $overall_total_price - $vatable_amount;
                                    $total_amount = $vatable_amount + $vat;

                                    echo '
                                        <table class="table table-bordered">   
                                            <tr>
                                                <th scope="col" class="text-center">NIGHT/S OF STAY</th>
                                                
                                                
                                                <td class="text-center">' . $nights_of_stay . '</td>
                                            </tr>                                         
                                            <tr>
                                                <th scope="col" class="text-center">AMOUNT</th>
                                                
                                                
                                                <td class="text-center">' .  number_format($vatable_amount, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">VAT(12%)</th>
                                                
                                                
                                                <td class="text-center">' . number_format($vat, 2) . '</td>
                                            </tr>
                                            <tr>
                                                <th scope="col" class="text-center">TOTAL AMOUNT</th>
                                                
                                                
                                                <td class="text-center">PHP ' . number_format($total_amount, 2)  . '</td>
                                            </tr>
                                        </table>
                                    ';
                                            
                                    ?>


                                    
                                    
                                    <?php
                                    
                                        if($payment_photo != '') {

                                            echo '
                                        
                                                <br>
                                                <h5 class="text-center mt-3 text-info">DEPOSIT SLIP PAYMENT</h5>

                                                <div style="text-align: center;">
                                                
                                                    <img src="' . $payment_photo . '" style="width: 50%; height: 50%;" />
                                                
                                                </div>
                                            
                                            
                                            ';

                                        }

                                        
                                    
                                    ?>

                                    <br>
        
                                    <h5 class="text-center mt-3 text-info">RESERVATION DOWN PAYMENT</h5>
                                    
                                    <table class="table table-bordered">
                                        <tr>
                                            <th scope="col" class="text-center">RESERVATION TOTAL PRICE</th>
                                            
                                            
                                            <td class="text-center">PHP <?php echo number_format($overall_total_price, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">REQUIRED DOWNPAYMENT</th>
                                            
                                            
                                            <?php

                                            $required_down_payment = DOWNPAYMENT_PERCENT * $overall_total_price;
                                            
                                            // if($payment_type == 'BANK DEPOSIT') {
                                                
                                            // } else if($payment_type == 'CASH') {
                                            //     $required_down_payment = $overall_total_price;
                                            // }
                                            
                                            ?>
                                            <td class="text-center">PHP <?php  echo number_format($required_down_payment, 2); ?></td>
                                        </tr>
                                    </table>

                                    <br>
                                    <h5 class="text-center text-info mt-3">Downpayment Details</h5>
                                
                                    <input type="hidden" name="down_payment_reference_no" value="<?php echo $reference_no; ?>">
                                    <input type="hidden" name="down_payment_total_amount" value="<?php echo $overall_total_price; ?>">
                                    <input type="hidden" name="down_payment_required" value="<?php echo $required_down_payment; ?>">

                                    <?php
                                    
                                    $disabled = '';
                                    if($status != 'PENDING')
                                    {
                                        $disabled = 'disabled';
                                    }
                                    ?>
                                    <div>
                                        <div class="form-row">
                                            <p class="col-3 text-right pr-4" for="dpAmount">Amount</p>
                                            <input type="number" name="down_payment_amount" class="form-control col-6" <?php echo $disabled; ?> id="dpAmount" min="0">
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <p class="col-3 text-right pr-4" for="dpDescription">Description</p>
                                            <textarea class="form-control col-6" name="down_payment_description" id="dpDescription" <?php echo $disabled; ?> placeholder="Enter Deposit slip details"></textarea>
                                        </div>

                                        <br>

                                        <div class="row justify-content-md-center">
                                            <div class="col-7 offset-col-4">
                                                <?php
                                                
                                                if($status == 'PENDING') {
                                                    echo '<button type="submit" class="btn btn-success btn-block float-right ">ACCEPT RESERVATION</button>';
                                                }
                                                
                                                ?>
                                                    
                                            </div>
                                        </div>
                                    </div>
                            
                                </div>
                            
                            </div>
                            
                            <br>
                            <br>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>


    

<?php

include('../common/footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);


?>