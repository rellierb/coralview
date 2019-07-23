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

    if($cancel_result) {
        $isCancelled = true;
    }

    
    
}



?>
    <!-- CRLVW-E909936 -->
    <div class="container">

        <div class="row">
            <div class="col">
            
            <?php

                if(isset($_SESSION['message']) && $_SESSION['alert']) {
                    echo '
                        <div class="' . $_SESSION["alert"] . ' mb-3" role="alert">
                            <p class="text-center">' . $_SESSION["message"]  . '</p>
                        </div>
                    ';
                }
            
            ?>

            <h2 class="text-center mt-3">Reservation Summary</h2>
            <hr />
            <div class="row">
                <div class="col">

                    <?php
                    
                    if(!$isCancelled) {
                        echo '<button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#cancelReservation">Cancel Reservation</button>';  
                    } 
                    
                    ?>
                    
                </div>
            </div>
            
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

                    echo '
                        <table style="width: 60%; margin: 0 auto;">
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3">First Name:</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["first_name"]  . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3">Last Name:</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["last_name"] . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3">Email:</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["email"] . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3">Address:</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["address"] . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3">Status:</th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $reservation["status"] . '</td>
                            </tr>
                        </table>
                        <br />

                        <h5 class="text-center mt-3">Booking Details</h5>
                        <hr />
                        <table style="width: 60%; margin: 0 auto;">
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3"><b>Check-in Date: </b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_in_date"]), "m-d-Y")  . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3"><b>Check-out Date: </b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . date_format(new Datetime($reservation["check_out_date"]), "m-d-Y") . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3"><b>Day/s: </b></th>
                                <td style="width: 70%;" class="pb-3 pl-4">' . $diff . '</td>
                            </tr>
                            <tr>
                                <th style="width: 30%;" class="text-right pr-5 pb-3"><b>Guest/s Number: </b></th>
                                <td style="width: 70%;" class="pb-3 pl-4"><span>Adult: ' . $reservation["adult_count"] .  '</span> <span>Kids: ' . $reservation["kids_count"] . '</span></td>
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
            //   INNER JOIN rooms R ON BR.room_id = R.Id
            $room_reservation_details_query = "SELECT * FROM reservation RES
                INNER JOIN guest G ON G.id = RES.guest_id
                INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
                INNER JOIN rooms R ON BR.room_id = R.Id
                WHERE RES.reference_no = '$reference_no'";


            $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);

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

                $overall_total_price = 0;

                while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {

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
                

                echo '
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-center"><b>PHP '  . number_format($overall_total_price, 2) .  '</b></td>
                    </tr>
                ';

                echo '</table>';
            }
            ?>




            <br />
            <hr />

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
include('common/footer.php');

session_destroy();

?>
