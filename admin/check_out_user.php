<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["reference_no"])) {

    $reference_no = $_REQUEST["reference_no"];

}

$reservation_id = 0;
$overall_total_extra = 0;
$overall_total_price = 0;
$guest_number = 0;

?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/check_out_user.php" method="POST">
        <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>" >
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Reservation Check-out</h1>
                
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
                                            $reservation_id = $reservation["id"];
                                            $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
                                            $diff = $dateDiff->format('%d');

                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
                                            $guest_number = $reservation["adult_count"];

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
                                                        <th style="width: 40%;" class="pr-3 pb-3">Reference Code:</th>
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

                                    echo '<input type="hidden" id="guestNumber" value="' . $guest_number . '">';

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

                                                $overall_total_price += $total_price;
                                            }
                                            

                                            echo '</table>';
                                        }
                                        ?>
                                
                                </div>

                                <div class="col-3">
                                    <h5 class="text-center mt-3">Assigned Rooms</h5>
                                    
                                    <?php
                                    
                                    $assigned_room_query = "SELECT * FROM check_in_rooms CIR
                                        INNER JOIN rooms_status RS ON CIR.room_number = RS.room_number
                                        INNER JOIN rooms R ON RS.room_id = R.id
                                        WHERE CIR.reference_no='$reference_no'";
                                    $assigned_room_result = mysqli_query($db, $assigned_room_query);
                                   
                                    if(mysqli_num_rows($assigned_room_result)  > 0) {

                                        echo '<table class="table">';
                                        echo '
                                            <thead>
                                                <th scope="col">Room Number</th>
                                                <th scope="col">Room Name</th>
                                            </thead>
                                        ';
                                        while($assigned_room = mysqli_fetch_assoc($assigned_room_result)) {
                                            echo '
                                            <tr> 
                                                <td>' . $assigned_room['room_number'] . '</td>
                                                <td>' . $assigned_room['type'] . '</td>       
                                            </tr>
                                            ';
                                        }

                                        echo '</table>';
                                    }
                                    
                                    ?>

                                    <h5 class="text-center mt-3">Extras</h5>

                                    <?php 
                                    
                                    $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
                                    $extra_list_result = mysqli_query($db, $extra_list_query);

                                    if(mysqli_num_rows($extra_list_result) > 0) {

                                        echo '
                                            <table class="table">
                                                <tr>
                                                    <th scope="col">Extra</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Total</th>
                                                <tr>

                                        ';

                                        while($extra = mysqli_fetch_assoc($extra_list_result)) {

                                            $total_extra = $extra["price"] * $extra["quantity"];

                                            $overall_total_extra += $total_extra;

                                            echo '
                                                <tr>
                                                    <td>' . $extra["description"] . '</td>
                                                    <td>' . $extra["quantity"] . '</td>
                                                    <td>' . $extra["price"] . '</td>                                                
                                                    <td>' . number_format($total_extra, 2) . '</td>                                                
                                                </tr>
                                            ';

                                        }

                                        echo '
                                            </table>
                                        ';

                                        $overall_total_price += $overall_total_extra;
                                    } else {

                                        echo '<h2 class="text text-info text-center">No extras</h2>';

                                    }

                                    ?>


                                </div>

                                <div class="col-5">
                                   
                                    <!-- <a style="color: white;" id="addExtra" class="btn btn-primary">Add</a> -->

                                    <!-- <div id="extraList">
                                    
                                    </div> -->

                                    <h5 class="text-center mt-3">Payment Details</h5>

                                    <?php
                                    
                                    $billing_query = "SELECT * FROM billing WHERE reference_no='$reference_no'";
                                    $billing_result = mysqli_query($db, $billing_query);

                                    if(mysqli_num_rows($billing_result) > 0) {
                                        $remaining_balance = $overall_total_price;
                                        while($billing = mysqli_fetch_assoc($billing_result)) {

                                            $amount_paid = $billing["amount_paid"];
                                            $remaining_balance -= $amount_paid;

                                        }

                                        echo '
                                            <table class="table" style="width: 70%; margin: 0 auto;">
                                                <tr>
                                                    <th style="width: 50%;" scope="col" class="text-right">Total Amount (Rooms and Extras)</th>
                                                    <td style="width: 50%;" class="text-right" id="textTotalAmount">' . number_format($overall_total_price, 2)  . '</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 50%;" scope="col" class="text-right">Discount</th>
                                                    <td style="width: 50%;" class="text-right" id="textDiscount">0</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 50%;" scope="col" class="text-right">NET TOTAL</th>
                                                    <td style="width: 50%;" class="text-right" id="textNetTotal">0</td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 50%;" scope="col" class="text-right text-danger">Remaining Balance</th>
                                                    <td style="width: 50%;" class="text-right" id="textRemainingBalance">PHP ' . number_format($remaining_balance, 2)  .  '</td>
                                                </tr>
                                            </table>
                                        ';

                                    }
                                    

                                    ?>

                                    <h5 class="text-center mt-3">Additional Payment</h5>
                                    <hr>
                                        <div class="form-group" style="width: 60%; margin: 0 auto;" >
                                            <label for="exampleInputEmail1">Additional Payment</label>
                                            <input class="form-control" name="check_out_add_payment" type="number" min="0">
                                        </div>
                                        <div class="form-group" style="width: 60%; margin: 0 auto;" >
                                            <label for="exampleInputEmail1">Description</label>
                                            <textarea type="email" name="check_out_description" class="form-control"></textarea>
                                        </div>  
                                    <br>


                                    <h5 class="text-center mt-3">Discount</h5>
                                    <hr>
                                    <div >
                                        <div class="form-group row">
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
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <a class="btn btn-primary float-right" id="btnApplyDiscount" style="color: white;">Apply Discount</a>    
                                            </div>
                                        </div>
                                        
                                    </div>

                                    
                                    
                                </div>
                            
                            </div>
                            
                            <br>
                            <br>

                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-danger btn-block float-right">CHECK-OUT</button>
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
    
        let btnApplyDiscount = document.getElementById('btnApplyDiscount');
        let textDiscount = document.getElementById('textDiscount');
        let textNetTotal = document.getElementById('textNetTotal');
        let textRemainingBalance = document.getElementById('textRemainingBalance');
        let textTotalAmount = document.getElementById('textTotalAmount');
        let seniorDiscount = document.getElementById('seniorDiscount');
        let guestNumber = document.getElementById('guestNumber');
        let pwdDiscount = document.getElementById('pwdDiscount');

        if(document.body.contains(document.getElementById('btnApplyDiscount'))) {

            btnApplyDiscount.addEventListener('click', function() {


                // $PWD_DISCOUNT = .2;
                // $discount = 0;
                // $pwd_discount_price = $overall_total_price / $guest_count;
                // $pwd_discount_price *= $PWD_DISCOUNT;
                // $overall_total_price -= $pwd_discount_price;
                // $pwd_count = $_SESSION["pwd_discount"];
                let totalPrice = textTotalAmount.innerHTML;
                let trimTotalPrice = totalPrice.replace(',', '');
                
                let guest = guestNumber.value;
                
                if(seniorDiscount.value !== '') {

                    let senior_discount = .2;
                    let discount = 0;
                    let senior_discount_price = parseInt(trimTotalPrice) / guest;                    
                    senior_discount_price *= senior_discount;
                    trimTotalPrice -= senior_discount_price;
                    textDiscount.innerHTML = senior_discount_price.toFixed(2);
                    textNetTotal.innerHTML = trimTotalPrice.toFixed(2);

                }

                if(pwdDiscount.value !== '') {

                    let pwd_discount = .2;

                    let discount = 0;
                    
                    let pwd_discount_price = parseInt(trimTotalPrice) / guest;
                    
                    pwd_discount_price *= pwd_discount;
                    trimTotalPrice -= pwd_discount_price;

                    textDiscount.innerText = pwd_discount_price.toFixed(2);
                    textNetTotal.innerText = trimTotalPrice.toFixed(2);
                    
                }




            })

        }
    
    
    </script>

<?php

include('../common/footer.php');
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);
unset($_SESSION["empty_description"]);
unset($_SESSION["empty_payment"]);

?>