<?php
session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["reference_no"])) {

    $reference_no = $_REQUEST["reference_no"];

}

$total_room_amount = 0;
$overall_total_price = 0;
$overall_total_extra = 0;
$payment_type = '';
$nights_of_stay = 0;
$guest_number = 0;
$TOTAL_PRICE = 0;

$is_peak_rate = 0;

$payment_photo = '';

?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/check_out_user.php" method="POST">
        <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>" >
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Reservation Early Check-out</h1>
                
                <?php
        
                if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION['alert'] . '" text-center" role="alert">
                            ' . $_SESSION['msg']  . '
                        </div>
                    ';
                }
                
                if(isset($_SESSION['empty_description']) || isset($_SESSION['empty_payment'])) {
                    echo '
                        <div class="alert alert-danger" text-center" role="alert">
                            ' . $_SESSION['empty_description']  . ' <br>
                            ' . $_SESSION['empty_payment']  . '
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
                                            $reservation_id = $reservation["id"];
                                            $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
                                            $diff = $dateDiff->format('%d');
                                            $nights_of_stay = $diff;
                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
                                            $guest_number = $reservation["adult_count"] + $reservation["kids_count"];
                                            $payment_type = $reservation["payment"];
                                            $payment_photo = $reservation['payment_path'];
                                            $is_peak_rate = $reservation['is_peak_rate'];

                                            echo '
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th class="pr-3 pb-3">FULL NAME</th>
                                                        <td class="pb-3 pl-4">' . $full_name . '</td>
                                                        <th class="pr-3 pb-3">CONTACT NUMBER</th>
                                                        <td class="pb-3 pl-4">' . $reservation["contact_number"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pr-3 pb-3">EMAIL ADDRESS</th>
                                                        <td class="pb-3 pl-4">' . $reservation["email"] . '</td>
                                                        <th class="pr-3 pb-3">ADDRESS</th>
                                                        <td class="pb-3 pl-4">' . $reservation["address"] . '</td>
                                                    </tr>
                                                </table>
                                                <br />

                                                <h5 class="text-center text-info mt-3">BOOKING DETAILS</h5>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th class="pr-3 pb-3">REFERENCE CODE</th>
                                                        <td class="pb-3 pl-4" id="referenceCode">' . $reservation["reference_no"] . '</td>
                                                        <th class="pr-3 pb-3"><b>STATUS</b></th>
                                                        <td class="pb-3 pl-4">' . $reservation["status"] . '</td>
                                                        <th class="pr-3 pb-3"><b>PAYMENT</b></th>
                                                        <td class="pb-3 pl-4">' . $reservation["payment"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="pr-3 pb-3"><b>CHECK-IN DATE</b></th>
                                                        <td class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                                                        <th class="pr-3 pb-3"><b>CHECK-OUT DATE</b></th>
                                                        <td class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                                                        <th class="pr-3 pb-3"><b>NIGHT/S</b></th>
                                                        <td class="pb-3 pl-4">' . $diff . '</td>
                                                        <th class="pr-3 pb-3"><b>GUEST/S NUMBER</b></th>
                                                        <td class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
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

                                    echo '<input type="hidden" id="guestNumber" value="' . $guest_number . '">';

                                    ?>

                                    <?php

                                        
                                    if($payment_photo != '') {

                                        echo '
                                        
                                            <div class="row">
                                                <div class="col">

                                                <br>
                                                <h5 class="text-center mt-3 text-info">DEPOSIT SLIP PAYMENT</h5>
                                            
                                                <div style="text-align: center;">
                                                
                                                    <img src="' . $payment_photo . '" style="width: 50%; height: 50%;" />
                                                
                                                </div>
                                                
                                                <br>
                                        
                                                </div>
                                            </div>

                                        ';

                                    }
                                        
                                    
                                    ?>

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

                                            echo '<h5 class="text-center mt-3 text-info">ROOM DETAILS</h5>';
                                            echo '
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th class="text-center" style="width: 55%;">ROOM/S RESERVE</th>
                                                        <th class="text-center" style="width: 15%;">QUANTITY</th>
                                                        <th class="text-center" style="width: 15%;">PRICE</th>
                                                        <th class="text-center" style="width: 15%;">TOTAL</th>
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
                                                        <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                                                        <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                                                        <td class="text-center" style="width: 15%;">' . $room_rate . '</td>
                                                        <td class="text-center" style="width: 15%;"> ' . number_format($total_price, 2)  . '</td>
                                                    </tr>
                                                ';

                                                $overall_total_price += $total_price;
                                                $TOTAL_PRICE += $total_price;
                                            }

                                            echo '</table>';
                                        }
                                        ?>
                                
                                </div>

                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="text-center mt-3 text-info">ASSIGNED ROOMS</h5>
                                    
                                    <?php
                                    
                                    $assigned_room_query = "SELECT * FROM check_in_rooms CIR
                                        INNER JOIN rooms_status RS ON CIR.room_number = RS.room_number
                                        INNER JOIN rooms R ON RS.room_id = R.id
                                        WHERE CIR.reference_no='$reference_no'";
                                    $assigned_room_result = mysqli_query($db, $assigned_room_query);
                                
                                    if(mysqli_num_rows($assigned_room_result)  > 0) {

                                        echo '<table class="table table-bordered">';
                                        echo '
                                            <tr>
                                                <th scope="col" class="text-center">ROOM NUMBER</th>
                                                <th scope="col" class="text-center">ROOM NAME</th>
                                            </tr>
                                        ';
                                        while($assigned_room = mysqli_fetch_assoc($assigned_room_result)) {
                                            echo '
                                            <tr> 
                                                <td class="text-center">' . $assigned_room['room_number'] . '</td>
                                                <td class="text-center">' . $assigned_room['type'] . '</td>       
                                            </tr>
                                            ';
                                        }

                                        echo '</table>';
                                    }
                                    
                                    ?>

                                </div>

                                <div class="col-6">
                                    <h5 class="text-center mt-3 text-info">EXTRAS</h5>

                                    <?php 
                                    
                                    $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
                                    $extra_list_result = mysqli_query($db, $extra_list_query);

                                    if(mysqli_num_rows($extra_list_result) > 0) {

                                        echo '
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th scope="col" class="text-center">EXTRA</th>
                                                    <th scope="col" class="text-center">QUANTITY</th>
                                                    <th scope="col" class="text-center">AMOUNT</th>
                                                    <th scope="col" class="text-center">TOTAL</th>
                                                <tr>

                                        ';

                                        while($extra = mysqli_fetch_assoc($extra_list_result)) {
                                            $total_extra = $extra["price"] * $extra["quantity"];
                                            $overall_total_extra += $total_extra;
                                            echo '
                                                <tr>
                                                    <td  class="text-center">' . $extra["description"] . '</td>
                                                    <td  class="text-center">' . $extra["quantity"] . '</td>
                                                    <td  class="text-center">' . $extra["price"] . '</td>                                                
                                                    <td  class="text-center">' . number_format($total_extra, 2) . '</td>                                                
                                                </tr>
                                            ';

                                        }

                                        echo '
                                            </table>
                                        ';
                                        
                                        // $TOTAL_PRICE += $overall_total_extra;

                                    } else {
                                        echo '<h2 class="text text-info text-center">No extras</h2>';

                                    }

                                    ?>

                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-center mt-3 text-info">PAYMENT DETAILS</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-8">
                                
                                <!-- <a style="color: white;" id="addExtra" class="btn btn-primary">Add</a> -->

                                <!-- <div id="extraList">
                                
                                </div> -->

                                <?php
                                        
                                // Additional Fees
                                $add_fees_query = "SELECT * FROM billing_additional_fees WHERE reference_no='$reference_no'";
                                $add_fees_result = mysqli_query($db, $add_fees_query);
                                $add_fees_amount = 0;

                                if(mysqli_num_rows($add_fees_result) > 0) {

                                    echo '<table class="table table-bordered">';
                                    echo '<thead><th class="text-center" scope="col">Additional Fees</th><th class="text-center" scope="col">Amount</th><thead>';
                                    while($fees = mysqli_fetch_assoc($add_fees_result)) {
                                        echo'
                                            <tr>
                                                <td class="text-center">' . $fees['description'] . '</td>
                                                <td class="text-center">' . $fees['amount'] . '</td>
                                            </tr>
                                        ';

                                        $add_fees_amount += $fees['amount'];

                                    }
                                    echo '</table>';

                                }
                                
                                ?>

                                <?php

                                $overall_total_price *= $nights_of_stay;
                                $TOTAL_PRICE *= $nights_of_stay;
                                
                                $TOTAL_PRICE += $add_fees_amount;
                                $TOTAL_PRICE += $overall_total_extra;

                                ?>

                                <?php
                                
                                $check_discount_query = "SELECT * FROM billing_discount BD INNER JOIN discount D on BD.discount_id=D.Id  WHERE BD.reference_no='$reference_no'";
                                $check_discount_result = mysqli_query($db, $check_discount_query);
                                $discount_price = 0;

                                if(mysqli_num_rows($check_discount_result) > 0) {
                                    echo '<table class="table table-bordered">';
                                    echo '<thead><th class="text-center" scope="col">Discount</th><th class="text-center" scope="col">Amount</th><thead>';
                                    while($discount = mysqli_fetch_assoc($check_discount_result)) {
                                        
                                        $discount_amount = $discount["amount"];
                                        $comp_discount = $overall_total_price / $guest_number;
                                        
                                        if($discount_amount < 1) {
                                            $temp_discount_price = ($comp_discount * $discount["quantity"]) * $discount_amount;
                                            $discount_price += $temp_discount_price;
                                        } 

                                        $change_to_percent = $discount['amount'] * 100;

                                        echo'
                                            <tr>
                                                <td class="text-center">' . $discount['name'] . '</td>
                                                <td class="text-center">' . $change_to_percent  . ' %</td>
                                            </tr>
                                        ';
                                            
                                    }
                                    echo '</table>';
                                }                                            
                                
                                
                                ?>

                                <?php
                            
                                $billing_query = "SELECT * FROM billing WHERE reference_no='$reference_no'";
                                $billing_result = mysqli_query($db, $billing_query);
                                $balance = $TOTAL_PRICE;
                                // echo $balance;
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
                                // $overall_total_price += $add_fees_amount;
                                $discounted_price -= $downpayment_amount;
                                
                                echo '
                                    <table class="table table-bordered">
                                        <tr>
                                            <th scope="col" class="text-center">PAYMENT TYPE</th>
                                            <td class="text-center">' . $payment_type .  '</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">TOTAL AMOUNT (ROOMS)</th>
                                            <td class="text-center">' . number_format($overall_total_price, 2)  .  '</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">TOTAL AMOUNT (EXTRAS)</th>
                                            <td class="text-center">' . number_format($overall_total_extra, 2)  .  '</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">DOWNPAYMENT</th>
                                            <td class="text-center">' . number_format($downpayment_amount, 2)  .'</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">TOTAL DISCOUNT</th>
                                            <td class="text-center">' . number_format($discount_price, 2) . '</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">AMOUNT AFTER DISCOUNT</th>
                                            <td class="text-center">' . number_format($discounted_price, 2)  .  '</td>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center text-danger">REMAINING BALANCE</th>
                                            <td class="text-danger text-center" id="remainingBalance">' . number_format($discounted_price, 2)  .  '</td>
                                        </tr>
                                    </table>
                                ';

                                ?>

                                </div>
                                <div class="col-4">
                                    
                                    <h5 class="text-center">Additional Fees</h5>
                                    <hr>
                                    <div class="form-group" style="width: 60%; margin: 0 auto;" >
                                        <label>Amount</label>
                                        <input class="form-control" id="addPayment" name="check_out_add_payment" type="number" min="0">
                                    </div>
                                    <div class="form-group" style="width: 60%; margin: 0 auto;" >
                                        <label>Description</label>
                                        <textarea type="email" id="addDescription" name="check_out_description" class="form-control"></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary float-right" id="applyAddFees">Apply Additonal Fees</button>
                                        </div>
                                    </div>

                                    <h5 class="text-center mt-3">Customer Payment</h5>
                                    <hr>
                                    <div class="form-row">
                                        <p class="col-3 text-right pr-4" for="dpAmount">Amount</p>
                                        <input type="number" name="down_payment_amount" class="form-control col-8" id="dpAmount" min="0">
                                        <input type="hidden" name="down_total_amount" class="form-control col-8" value="" min="0" >
                                    </div>
                                    <br>
                                    <div class="form-row">
                                        <p class="col-3 text-right pr-4" for="dpAmount">Description</p>
                                        <textarea name="down_payment_description" class="form-control col-8" id="dpDescription">Customer Client Payment</textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <a class="btn btn-primary float-right" id="btnApplyPayment" style="color: white;">Apply Payment</a>    
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <br>

                            <div class="row">
                                <div class="col">

                                    <?php

                                    if($discounted_price != 0) {
                                        $disabled = "disabled";
                                    } else {
                                        $disabled = '';
                                    }

                                    ?>

                                    <button type="submit" class="btn btn-danger btn-block float-right" <?php echo $disabled; ?>>CHECK-OUT</button>
                                </div>
                            </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    
    </form>

    <script>
    
       
    
    </script>

<?php

include('../common/admin_footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);
unset($_SESSION["empty_description"]);
unset($_SESSION["empty_payment"]);

?>