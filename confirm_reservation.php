<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["reference_no"])) {
    $reference_no = $_REQUEST["reference_no"];
}

$html = '';
$payment_html = '';
$room_html = '';
$rate_type = 0;


?>

    <!-- CRLVW-E909936 -->
    <div class="container">
    
        <div class="row">
            <div class="col">
            
            <div class="row">
                <div class="col">
                    <div class="float-right">
                        
                        <form action="functions/admin/print_reservation.php" method="POST">
                            <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>">
                            <button type="submit" class="btn btn-primary animated bounce">PRINT DETAILS</button>
                        </form>
                        
                    </div>                
                </div>
            </div>
           


            <h2 class="text-center text-info">Thank you for booking with us!</h2>

            <div>
                <p class="text-center">Here are the details of your reservation.</p>
            </div>

            <h2 class="text-center mt-3 text-info">Reservation Summary</h2>
            <hr />
            
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

                    $rate_type = $reservation["is_peak_rate"];

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
                        <th class="text-center" style="width: 15%;">NIGHT/S OF STAY</th>
                        <th class="text-center" style="width: 15%;">TOTAL</th>
                    </tr>
                ';

                $overall_total_price = 0;
                $total_amount = 0;

                while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {

                    $room_rate = 0;


                    if($rate_type == 0) {
                        $room_rate = $room_reservation["off_peak_rate"];
                    } else if ($rate_type == 1) {
                        $room_rate = $room_reservation["peak_rate"];
                    }

                    $total_price = $room_rate * $room_reservation["quantity"];
                    $total_amount += $total_price * $diff;

                    $room_html .= '
                        <tr>
                            <td class="text-center" style="width: 40%;">' . $room_reservation["type"] . '</td>
                            <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                            <td class="text-center" style="width: 15%;">' . number_format($room_rate, 2) . '</td>
                            <td class="text-center" style="width: 15%;">' . $diff . '</td>
                            <td class="text-center" style="width: 15%;"> ' . number_format($total_price * $diff, 2)  . '</td>
                        </tr>
                    ';

                    $overall_total_price += $total_price;
                }
                
                $room_html .= '
                    <tr>
                        <td class="text-center" style="width: 40%;">SUBTOTAL</td>
                        <td class="text-center" style="width: 15%;"></td>
                        <td class="text-center" style="width: 15%;"></td>
                        <td class="text-center" style="width: 15%;"></td>
                        <td class="text-center" style="width: 15%;">' . number_format($total_amount, 2). '</td>
                    </tr>
        
                ';

                $room_html .= '</table>';

                
            }
            ?>
 
            <?php
            
            $overall_total_amount = $overall_total_price * $diff;
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
                        <td class="text-right pr-4 pb-3">' . number_format($total_amount, 2) . '</td>
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
            <?php echo $room_html; ?>


            <br />
            <hr>
            

           
        <!-- <h5 class="text-center mt-3">Room Details</h5>
                <hr />                
                <table style="width: 100%;">
                    <tr>
                        <th class="text-center" style="width: 55%;">Room/s Reserve</th>
                        <th class="text-center" style="width: 15%;">Quantity</th>
                        <th class="text-center" style="width: 15%;">Price</th>
                        <th class="text-center" style="width: 15%;">Total</th>
                    </tr>
                    
                    <tr>
                        <td class="text-center" style="width: 55%;">' . $reservation["type"] . '</td>
                        <td class="text-center" style="width: 15%;">test</td>
                        <td class="text-center" style="width: 15%;">' . $reservation["peak_rate"] . '</td>
                        <td class="text-center" style="width: 15%;">test</td>
                    </tr>
                </table> -->
    



        </div>   
        

<!-- 
        <div class="row">
            <div class="col-lg-12 offset-lg-2">              
                <h5>Resort Rules and Regulation</h5>    

                <ol style="width: 60% margin: 0 auto;">
                    <li class="mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    <li class="mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    <li class="mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    <li class="mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                    <li class="mb-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                </ol>
            </div>
        </div> -->

    </div>  


    <div class="container">
            
        <div class="row">               
            <div class="col text-center">
                <a href="index.php" style="color: white;" class="btn btn-primary mb-5 mt-2">GO BACK TO HOME PAGE</a>                
            </div> 
        </div>
    
    </div>
    




<?php


include('common/footer.php');


?>
