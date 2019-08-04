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

                                <div class="col-6">
                                
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
                                                        <th  class="text-right pr-3 pb-3">Full Name:</th>
                                                        <td  class="pb-3 pl-4">' . $full_name . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3">Contact Number:</th>
                                                        <td  class="pb-3 pl-4">' . $reservation["contact_number"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3">Email:</th>
                                                        <td  class="pb-3 pl-4">' . $reservation["email"] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right pr-3 pb-3">Address:</th>
                                                        <td class="pb-3 pl-4">' . $reservation["address"] . '</td>
                                                    </tr>
                                                </table>
                                                <br />

                                                <h5 class="text-center mt-3">Booking Details</h5>
                                                <hr />
                                                <table style="margin-left: 2em;">
                                                    <tr>
                                                        <th class="text-right pr-3 pb-3"><b>Status:</b></th>
                                                        <td class="pb-3 pl-4">' . $reservation["status"]  . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right pr-3 pb-3"><b>Mode of Payment</b></th>
                                                        <td class="pb-3 pl-4">' . $reservation["payment"]  . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3"><b>Check-in Date: </b></th>
                                                        <td  class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3"><b>Check-out Date: </b></th>
                                                        <td  class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3"><b>Day/s: </b></th>
                                                        <td  class="pb-3 pl-4">' . $diff . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th  class="text-right pr-3 pb-3"><b>Guest/s Number: </b></th>
                                                        <td  class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right pr-3 pb-3"><b>Date Reserved:</b></th>
                                                        <td class="pb-3 pl-4">' . $reservation["date_created"]  . '</td>
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

                                <div class="col-6">
                                
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

                                            echo '<h5 class="text-center mt-3">Room/s Reserved</h5>';
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

                                            $overall_total_price = 0;

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
                                                        <td class="text-center" style="width: 15%;">' . number_format($room_reservation["peak_rate"], 2)  . '</td>
                                                        <td class="text-center" style="width: 15%;"> ' . number_format($total_price, 2)  . '</td>
                                                    </tr>
                                                ';

                                                $overall_total_price += $total_price;
                                            }
                                            

                                            echo '</table>';
                                        }
                                    ?>      
        
                                    <h5 class="text-center mt-3">Reservation Down Payment</h5>
                                    
                                    
                                    <table class="table">
                                        <tr>
                                            <th scope="col">Reservation Total Price:</th>
                                            <td>PHP <?php echo number_format($total_price, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="col">Required Downpayment:</th>
                                            <?php
                                            
                                            $required_down_payment = DOWNPAYMENT_PERCENT * $total_price;
                                            
                                            ?>
                                            <td>PHP <?php  echo number_format($required_down_payment, 2); ?></td>
                                        </tr>
                                    </table>

                                    <p>Enter Downpayment Details</p>

                                    <br>
                                    <br>
                                
                                    <input type="hidden" name="down_payment_reference_no" value="<?php echo $reference_no; ?>">
                                    <input type="hidden" name="down_payment_total_amount" value="<?php echo $total_price; ?>">
                                    
                                    <div>
                                        <div class="form-row">
                                            <p class="col-3 text-right pr-4" for="dpAmount">Amount</p>
                                            <input type="number" name="down_payment_amount" class="form-control col-8" id="dpAmount">
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <p class="col-3 text-right pr-4" for="dpDescription">Description</p>
                                            <textarea class="form-control col-8" name="down_payment_description" id="dpDescription"></textarea>
                                        </div>

                                        <br>

                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success btn-block float-right ">ACCEPT RESERVATION</button>    
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
session_destroy();

?>