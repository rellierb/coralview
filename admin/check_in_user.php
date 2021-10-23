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
$guest_number = 0;
$payment_type = '';
$nights_of_stay = 0;
$reservation_id = 0;

$payment_photo = '';

$is_peak_rate = 0;


?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/check_in_user.php" method="POST">
        <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>" >
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Reservation Check-in</h1>
                
                <?php
        
                if(isset($_SESSION['msg']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                            ' . $_SESSION['msg']  . '
                        </div>
                    ';
                }
                
                ?>

                <div class="row">
                    <div class="col-9">
                    </div>
                    <div class="col-3">
                        
                    </div>
                </div>

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
                                            $guest_number = $reservation["adult_count"] + $reservation["kids_count"];
                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
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
                                

                                    ?>

                                    <br>
                                    
                                    
                                    <?php

                                        if($payment_type != 'WALK-IN / CASH') {
                                            
                                            echo '
                                                <h5 class="text-center mt-3 text-info">DEPOSIT SLIP PAYMENT</h5>
                                                <div style="text-align: center;">
                                                
                                                    <img src="../uploads/payment/' . $payment_photo . '" style="width: 50%; height: 50%;" />
                                                
                                                </div>
                                            ';
                                        
                                        }
                                    
                                    ?>

                                    <br>
        




                                    <div class="float-right">
                                        <button type="button" data-toggle="modal" data-target="#upgradeRoom" style="color-white" class="btn btn-primary">Upgrade Rooms</button>
                                    </div>

                                    <h5 class="text-center mt-3 text-info">ROOM DETAILS</h5>

                                    <div class="row">
                                      
                                        <div class="col-8">
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
                                                    } else if($is_peak_rate == 1) {
                                                        $room_rate = $room_reservation["peak_rate"];
                                                    }

                                                    $total_price = $room_rate * $room_reservation["quantity"];

                                                    echo '
                                                        <tr>
                                                            <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                                                            <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                                                            <td class="text-center" style="width: 15%;">' . number_format($room_rate) . '</td>
                                                            <td class="text-center" style="width: 15%;"> ' . number_format($total_price, 2)  . '</td>
                                                        </tr>
                                                    ';
                                                    $total_room_amount += $total_price;
                                                    $overall_total_price += $total_price;
                                                    
                                                }
                                                

                                                echo '</table>';
                                               
                                            }
                                            ?>
                                        
                                            

                                        </div>

                                        <div class="col-4">
                                            <h5 class="text-center">Room Assignment</h5>
                                            <hr />

                                         

                                            <h6>Room Count</h6>
                                            <br>
                                            <?php
                                            
                                            echo '<table class="table table-striped">
                                                <tr>
                                                    <th scope="col">ROOM NAME</th>
                                                    <th scope="col">COUNT</th>
                                                <tr>
                                            ';

                                            foreach($rooms_reserved as $k => $v) {
                                                $assign_room_query = "SELECT COUNT(R.Id), R.type FROM rooms_status RS INNER JOIN rooms R ON R.Id = RS.room_id WHERE RS.room_id = $k AND RS.status='AVAILABLE' ORDER BY R.Id";
                                                $assign_room_result = mysqli_query($db, $assign_room_query);


                                                if(mysqli_num_rows($assign_room_result) > 0) {
                                                    while($room_assign = mysqli_fetch_assoc($assign_room_result)) {

                                                        echo '
                                                            
                                                            <tr>
                                                                <td>' . $room_assign["type"] . '</td>
                                                                <td>' . $room_assign["COUNT(R.Id)"] . '</td> 
                                                            </tr>
                                                        
                                                        '; 

                                                    }
                                                }

                                            }

                                            echo '</table>';
                                            
                                            ?>


                                            <?php
                                            
                                            if(!empty($_SESSION["save_rooms"])) {

                                                echo '<h6>Saved Rooms</h6>';
                                                echo '<br>';
                                                echo '<table class="table table-bordered">';
                                                echo '<tr><th>Room Name</th></tr>';
                                                foreach($_SESSION["save_rooms"] as $room) {

                                                    echo '
                                                        
                                                        <tr><td>' . $room . '</td></tr>
                                                    
                                                    ';

                                                }
                                                echo '</table>';

                                            }

                                            ?>



                                            <hr />
                                            <h6>List of Available Rooms</h6>
                                            <br>
                        
                                            <?php
                                            
                                            $html_assign = "";
                                           
                                            foreach($rooms_reserved as $k => $v) {
                                                $assign_room_query = "SELECT * FROM rooms_status RS INNER JOIN rooms R ON R.Id = RS.room_id WHERE RS.room_id = $k AND RS.status='AVAILABLE'";
                                                $assign_room_result = mysqli_query($db, $assign_room_query);

                                                if(mysqli_num_rows($assign_room_result) > 0) {
                                                    while($room_assign = mysqli_fetch_assoc($assign_room_result)) {
                                                        
                                                        $html_assign .= '

                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input" data-room-id="' . $room_assign["room_number"] . '" name="room_number[]" type="checkbox" value="' . $room_assign["room_number"] . '">                                      
                                                                    <span class="form-check-sign">
                                                                        <span class="check">' . $room_assign["room_number"] . ' - ' . $room_assign["type"] . ' -  <span class="badge badge-success">'. $room_assign["status"] .  '</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            

                                                        ';

                                                    }
                                                }

                                            }
                                            
                                            echo $html_assign;
                                            
                                            echo '<button type="button" class="btn btn-primary float-right" id="saveCheckIn" >Save</button>';
                                            
                                            ?>

                                        
                                        </div>
                                    </div>


                                    <br>
                                    <h5 class="text-center mt-3 text-info">EXTRAS</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <h5 class="text-center mt-3">Add Extras</h5>
                                            
                                            <?php 
                                            
                                            $extra_query = "SELECT * FROM extras;";
                                            $extra_result = mysqli_query($db, $extra_query);

                                            if($extra_result) {
                                                while($extra = mysqli_fetch_assoc($extra_result)) {

                                                    echo '
                                                        <div class="form-group row">
                                                            <p class="col-4 text-right mr-2 pt-2" for="">' . $extra["description"] . '</p>
                                                            <input type="number" data-price=' .  $extra["price"] . ' data-name="' . $extra["description"] . '" id="extras-' . $extra["Id"] . '" data-id="' . $extra["Id"] . '" class="form-control col-5" placeholder="Quantity">
                                                        </div>
                                                    ';

                                                }
                                            }
                                            
                                            ?>

                                            <div class="row">
                                                <div class="col-12">
                                                    <a style="color: white;" id="addExtra" class="btn btn-primary float-right">Add</a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-8">
                                            <?php

                                            $expense_total = 0;
                                                                                
                                            $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE BE.reference_no='$reference_no'";
                                            $extra_list_result = mysqli_query($db, $extra_list_query);

                                            if(mysqli_num_rows($extra_list_result) > 0) {

                                                echo '<table class="table table-bordered">';
                                                echo '
                                                    <tr>
                                                        <th class="text-center" scope="col">EXTRA NAME</th>
                                                        <th class="text-center" scope="col">QUANTITY</th>
                                                        <th class="text-center" scope="col">AMOUNT</th>
                                                    </tr>
                                                ';


                                                while($extra = mysqli_fetch_assoc($extra_list_result)) {
                                                    $extra_price = $extra['price'] *  $extra['quantity'];
                                                    echo '
                                                        <tr>
                                                            <td class="text-center" scope="col">' . $extra['description'] . '</td>    
                                                            <td class="text-center" scope="col">' . $extra['quantity'] . '</td>
                                                            <td class="text-center" scope="cooverall_total_pricel">' . number_format($extra['price'], 2) . '</td>
                                                        </tr>
                                                    ';

                                                    $expense_total += $extra_price;
                                                             
                                                } 

                                                //$overall_total_price += $expense_total;
                                                

                                                echo '</table>';
                                            }else {

                                                echo '<h4 class="text-center text-warning">No Extras</h4>';
                                            }
                                            
                                            ?>    

                                        </div>
                                        

                                        <br>
                                        
                                        

                                
                                    </div>       

                                    <br>
                                    <h5 class="text-center text-info mt-3">PAYMENT DETAILS</h5> 
                                    <hr>
                                    <div class="row">
                                        <div class="col-4">
                                            <h5 class="text-center mt-3">Discount</h5>
                                            <hr>

                                            <?php 
                                        
                                            $discount_query = "SELECT * FROM discount";
                                            $discount_result = mysqli_query($db, $discount_query);

                                            if(mysqli_num_rows($discount_result) > 0) {

                                                while($discount = mysqli_fetch_assoc($discount_result)) {
                                                    echo '
                                                        <div class="form-group row">
                                                            <p for="inputEmail3" class="col-sm-6 col-form-label text-right">'. $discount["name"] .'</p>
                                                            <div class="col-sm-3">
                                                                <input type="number" value="1" id="seniorDiscount" data-discount="' . $discount["Id"] .'" data-discount-amount="' . $discount["amount"] . '"  placeholder="Quantity" class="form-control" min="0" max="">
                                                            </div>
                                                        </div>
                                                    ';
                                                }

                                            }
                                            
                                            ?>

                                            <div >
                                                <!-- <div class="form-group row">
                                                    <p for="inputEmail3" class="col-sm-6 col-form-label text-right">Senior Citizen Discount (20%)</p>
                                                    <div class="col-sm-3">
                                                        <input type="number" id="seniorDiscount" placeholder="Quantity" name="senior_discount" class="form-control" min="0" max="<?php echo $guest_number; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <p for="inputEmail3" class="col-sm-6 col-form-label text-right">PWD Discount (20%)</p>
                                                    <div class="col-sm-3">
                                                        <input type="number" id="pwdDiscount" placeholder="Quantity" name="pwd_discount" class="form-control" min="0" max="<?php echo $guest_number; ?>">
                                                    </div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col">
                                                        <a class="btn btn-primary float-right" id="btnApplyDiscount" style="color: white;">Apply Discount</a>    
                                                    </div>
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
                                        <div class="col-8">


                                            <?php
                                            
                                            $overall_total_price *= $nights_of_stay;
                                            
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
                                                        $temp_discount_price =  ($comp_discount * $discount["quantity"]) * $discount_amount;
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
                                            $balance = $overall_total_price;

                                            // echo $balance;
                                            if(mysqli_num_rows($billing_result) > 0) {
                                            
                                                while($billing = mysqli_fetch_assoc($billing_result)) {
                                                    
                                                    $amount_paid = $billing["amount_paid"];
                                                    $balance -= $amount_paid;                                            

                                                }

                                            }
                                            
                                            // $check_discount_query = "SELECT * FROM billing_discount BD INNER JOIN discount D on BD.discount_id=D.Id  WHERE BD.reference_no='$reference_no'";
                                            
                                            // $check_discount_result = mysqli_query($db, $check_discount_query);
                                            // $discount_price = 0;
                                            // $discount_applied = '<table class="table">';

                                            // if(mysqli_num_rows($check_discount_result) > 0) {

                                            //     while($discount = mysqli_fetch_assoc($check_discount_result)) {
                                                    
                                            //         $discount_amount = $discount["amount"];
                                            //         $comp_discount = $overall_total_price / $quantity;
                                                   
                                            //         if($discount_amount < 1) {
                                            //             $temp_discount_price = $comp_discount * $discount_amount;
                                            //             $discount_price += $temp_discount_price;
                                            //         } 
                                                      
                                            //     }

                                            // }
                                            
                                            $discount_applied = '</table>';
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

                                            $price_add_extra = $discounted_price + $expense_total;
                                            $total_amount_rooms_extras = $overall_total_price + $expense_total;
                                            // echo $balance;
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
                                                        <td class="text-center">' . number_format($expense_total, 2)  .  '</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center">TOTAL AMOUNT (ROOMS AND EXTRAS)</th>
                                                        <td class="text-center">' . number_format($total_amount_rooms_extras, 2) .  '</td>
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
                                                        <td class="text-center">' . number_format($price_add_extra, 2)  .  '</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col" class="text-center text-danger">REMAINING BALANCE </th>
                                                        <td class="text-danger text-center" id="remainingBalance">' . number_format($price_add_extra, 2)  .  '</td>
                                                    </tr>
                                                </table>
                                            ';

                                            ?>
                                            
                                        </div>
                                    </div>                
                                </div>
                            </div>
                            
                            <br>
                            <br>

                            <div class="row">
                                <div class="col">

                                    <?php
                                   
                                    if(number_format($price_add_extra, 2) != 0 || number_format($price_add_extra, 2) < 0) {
                                        $disabled = "disabled";
                                    } else {
                                        $disabled = '';
                                    }
                                    
                                    ?>
                                    
                                    <button type="submit" id="checkInSubmitBtn" class="btn btn-primary btn-block float-right" <?php echo $disabled; ?>>CHECK-IN</button>
                                </div>
                            </div>

                            </div>
                        </div>




                    </div>
                </div>

            </div>
        </div>
    
    </form>

<?php

include('../common/upgrade_room_modal.php');
include('../common/admin_footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>