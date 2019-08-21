<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["reference_no"])) {

    $reference_no = $_REQUEST["reference_no"];

}

$total_room_amount = 0;
$overall_total_price = 0;


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

                                <div class="col-4">
                                
                                    <h5 class="text-center mt-3">Guest Details</h5>
                                    <hr />
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

                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];

                                            echo '
                                                <table style="margin-left: 2em;">
                                                    
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3">Full Name:</th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $full_name . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3">Contact Number:</th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $reservation["contact_number"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3">Email:</th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $reservation["email"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3">Address:</th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $reservation["address"] . '</td>
                                                    </tr>
                                                </table>
                                                <br />

                                                <h5 class="text-center mt-3">Booking Details</h5>
                                                <hr />
                                                <table style="margin-left: 2em;">
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3">Reference Coe:</th>
                                                        <td style="width: 60%;" class="pb-3 pl-4" id="referenceCode">' . $reservation["reference_no"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Status</b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $reservation["status"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Payment</b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $reservation["payment"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Check-in Date: </b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Check-out Date: </b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Day/s: </b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4">' . $diff . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 40%;" class="pr-3 pb-3"><b>Guest/s Number: </b></th>
                                                        <td style="width: 60%;" class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
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

                                            echo '<h5 class="text-center mt-3">Room Details</h5>';
                                            echo '
                                                <hr />  
                                                <table style="width: 100%;">
                                                <tr>
                                                    <th class="text-center" style="width: 55%;">Room/s Reserve</th>
                                                    <th class="text-center" style="width: 15%;">Quantity</th>
                                                    <th class="text-center" style="width: 15%;">Price</th>
                                                    <th class="text-center" style="width: 15%;">Total</th>
                                                </tr>
                                            ';

                                            

                                            while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                                                
                                                $room_id = $room_reservation["room_id"];
                                                $room_quantity = $room_reservation["quantity"];

                                                $rooms_reserved[$room_id] = $room_quantity; 

                                                $quantity += $room_quantity;

                                                $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

                                                echo '
                                                    <tr>
                                                        <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                                                        <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                                                        <td class="text-center" style="width: 15%;">' . $room_reservation["peak_rate"] . '</td>
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

                                <div class="col-3">
                                    <h5 class="text-center mt-3">Assign Rooms</h5>
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
                                                            <input class="form-check-input" name="room_number[]" type="checkbox" value="' . $room_assign["room_number"] . '">                                      
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

                                    //echo '<br><button class="btn btn-primary btn-block">ASSIGN</button>'
                                    
                                    ?>

                                </div>

                                <div class="col-5">
                                    <h5 class="text-center mt-3">Add Extras</h5>
                                    <hr />
                                    
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

                                    <a style="color: white;" id="addExtra" class="btn btn-primary">Add</a>

                                    <?php

                                    $expense_total = 0;
                                                                        
                                    $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE BE.reference_no='$reference_no'";
                                    $extra_list_result = mysqli_query($db, $extra_list_query);
                                    
                                    if(mysqli_num_rows($extra_list_result) > 0) {

                                        echo '<table class="table">';
                                        echo '
                                            <tr>
                                                <th scope="col">Extra Name</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                        ';


                                        while($extra = mysqli_fetch_assoc($extra_list_result)) {
                                            $extra_price = $extra['price'];
                                            echo '
                                                <tr>
                                                    <td>' . $extra['description'] . '</td>    
                                                    <td>' . $extra['quantity'] . '</td>
                                                    <td>' . $extra['price'] . '</td>
                                                </tr>
                                            ';

                                            $expense_total += $extra_price;
                                        }

                                        $overall_total_price += $expense_total;

                                        echo '</table>';
                                    }
                                    
                                    ?>    


                                    <!-- <div id="extraList">
                                    
                                    </div> -->


                                    <h5 class="text-center mt-3">Payment Details</h5>

                                    <?php
                                    
                                    $billing_query = "SELECT * FROM billing WHERE reference_no='$reference_no'";
                                    $billing_result = mysqli_query($db, $billing_query);

                                    if(mysqli_num_rows($billing_result) > 0) {
                                        
                                        $balance = $overall_total_price;

                                        while($billing = mysqli_fetch_assoc($billing_result)) {

                                            $amount_paid = $billing["amount_paid"];
                                            $balance -= $amount_paid;                                            

                                        }

                                        echo '
                                            <table class="table">
                                                <tr>
                                                    <th scope="col" class="text-center">Total Amount (Rooms and Extras)</th>
                                                    <td>' . number_format($overall_total_price, 2)  .  '</td>
                                                </tr>
                                                <tr>
                                                    <th scope="col" class="text-center text-danger">Remaining Balance</th>
                                                    <td class="text-danger">' . number_format($balance, 2)  .  '</td>
                                                </tr>
                                            </table>
                                        ';

                                    }
                                    
                                    ?>

                                    <div class="form-row">
                                        <p class="col-3 text-right pr-4" for="dpAmount">Amount </p>
                                        <input type="number" name="down_payment_amount" class="form-control col-8" id="dpAmount" min="0">
                                        <input type="hidden" name="down_total_amount" class="form-control col-8" value="<?php echo $overall_total_amount; ?>" min="0">
                                    </div>
                                    <br>
                                    
                                </div>
                            
                            </div>
                            
                            <br>
                            <br>

                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-block float-right">CHECK-IN</button>
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

include('../common/footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>