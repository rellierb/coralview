<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["refence_no"])) {
    $reference_no = $_REQUEST["refence_no"];
}


?>
    <!-- CRLVW-E909936 -->
    <div class="container">

        <div class="row">
            <div class="col">
            
            <div></div>

            <h2 class="text-center text-info">Thank you for booking with us!</h2>

            <div>
                <p class="text-center">Here are the details of your reservation.</p>
            </div>

            <h2 class="text-center mt-3 text-info">Reservation Summary</h2>
            <hr />
            <h5 class="text-center mt-3 text-info mt-3">Guest Details</h5>
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
                <button href="/coralview/index.php" class="btn btn-primary mb-5 mt-2">GO BACK TO HOME PAGE</button>                
            </div> 
        </div>
    
    </div>
    




<?php


include('common/footer.php');


?>
