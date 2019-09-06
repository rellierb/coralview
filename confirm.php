<?php

session_start();

include('common/header.php');
include('common/reserve_navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

if(isset($_REQUEST["arrival_date"])) {
    $arrival_date = $_REQUEST["arrival_date"];
}

if(isset($_REQUEST["departure_date"])) {
    $departure_date = $_REQUEST["departure_date"];
}

if(isset($_REQUEST["adult_count"])) {
    if(empty($_REQUEST["adult_count"])) {
        $adult_count = 0;
    } else {
        $adult_count = $_REQUEST["adult_count"];
    }
}

if(isset($_REQUEST["kids_count"])) {
    if(empty($_REQUEST["kids_count"])) {
        $kids_count = 0;
    } else {
        $kids_count = $_REQUEST["kids_count"];
    }
}

if(isset($_REQUEST["no_of_days"])) {
    $no_of_days = $_REQUEST["no_of_days"];
}

if(isset($_REQUEST["first_name"])) {
    $first_name = $_REQUEST["first_name"];
} 

if(isset($_REQUEST["last_name"])) {
    $last_name = $_REQUEST["last_name"];
}

if(isset($_REQUEST["contact_number"])) {
    $contact_number = $_REQUEST["contact_number"];
}

if(isset($_REQUEST["email"])) {
    $email = $_REQUEST["email"];
}

if(isset($_REQUEST["address"])) {
    $address = $_REQUEST["address"];
}

if(isset($_REQUEST["rooms_reserved"])) {
    $rooms_reserved = $_REQUEST["rooms_reserved"];
}

?>

    <form action="/coralview/functions/user/booking.php" method="POST">
    
        <div class="container">
        
            <input type="hidden" name="p_date_arrival" value="<?php echo $arrival_date; ?>">
            <input type="hidden" name="p_date_departure" value="<?php echo $departure_date; ?>">
            <input type="hidden" name="p_adult_count" value="<?php echo $adult_count; ?>">
            <input type="hidden" name="p_kids_count" value="<?php echo $kids_count; ?>">
            <input type="hidden" name="p_no_of_days" value="<?php echo $no_of_days; ?>">
            <input type="hidden" name="p_first_name" value="<?php echo $first_name; ?>">
            <input type="hidden" name="p_last_name" value="<?php echo $last_name; ?>">
            <input type="hidden" name="p_contact_number" value="<?php echo $contact_number; ?>">
            <input type="hidden" name="p_email" value="<?php echo $email; ?>">
            <input type="hidden" name="p_address" value="<?php echo $address; ?>">

            <div class="row">
                <div class="col">

                    <a href="/coralview/reserve.php#step-1" class="btn btn-primary">Back</a>

                    <h2 class="text-center mt-3 coralview-blue">Reservation Summary</h2>
                    <hr />
                    <h5 class="text-center mt-5 coralview-blue">Guest Details</h5>
                    <hr />
                    <table style="width: 60%; margin: 0 auto;" class="table table-bordered mt-4">
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3">FIRST NAME</th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $first_name; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3">LAST NAME</th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $last_name; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3">EMAIL</th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $email; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3">CONTACT NUMBER</th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $contact_number;  ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3">ADDRESS</th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $address; ?></td>
                        </tr>
                    </table>
                    <br />

                    <h5 class="text-center mt-3 coralview-blue">Booking Details</h5>
                    <hr />
                    <table style="width: 60%; margin: 0 auto;" class="table table-bordered">
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3"><b>CHECK-IN DATE</b></th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $arrival_date; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3"><b>CHECK-OUT DATE</b></th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $departure_date; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3"><b>DAYS</b></th>
                            <td style="width: 70%;" class="pb-3 pl-4"><?php echo $no_of_days; ?></td>
                        </tr>
                        <tr>
                            <th style="width: 30%;" class="text-right pr-4 pb-3"><b>GUEST NUMBER</b></th>
                            <td style="width: 70%;" class="pb-3 pl-4">
                                <div>
                                    <span class="pr-5 ">Adult: <?php echo $adult_count; ?> </span>
                                    <span class="">Children:  <?php echo $kids_count; ?></span>
                                </div>
                            </span></td>
                        </tr>

                    </table>
                    <br>

                    <h5 class="text-center mt-3 text-info">Room Details</h5>
                    <hr />                
                    <table style="width: 100%;" class="table table-bordered">
                        <tr>
                            <th class="text-center" style="width: 55%;">ROOM/S RESERVE</th>
                            <th class="text-center" style="width: 15%;">QUANTITY</th>
                            <th class="text-center" style="width: 15%;">PRICE</th>
                            <th class="text-center" style="width: 15%;">TOTAL</th>
                        </tr>

                        <?php

                        $reserved_rooms = json_decode(json_decode($rooms_reserved));
                        $total_amount = 0;
                        foreach($reserved_rooms as $test) {

                            $id = $test->roomId;
                            $room_count = $test->roomNumber;
                            
                            $room_query = "SELECT * FROM rooms WHERE Id=$id";
                            
                            $rooms_result = mysqli_query($db, $room_query);
                                    
                            if(mysqli_num_rows($rooms_result) > 0) {
                                while($room = mysqli_fetch_assoc($rooms_result)) {
                                    
                                    $room_price = $room_count * $room["peak_rate"];
                                    $total_amount += $room_price;

                                    echo '
                                        <tr>
                                            <td class="text-center" style="width: 55%;"><h5>' . $room["type"] . '</h5></td>
                                            <td class="text-center" style="width: 15%;">' . $room_count . '</td>
                                            <td class="text-center" style="width: 15%;">' . number_format($room["peak_rate"], 2) . '</td>
                                            <td class="text-center" style="width: 15%;">PHP ' .  number_format($room_price, 2) .  '</td>
                                        </tr>
                                    ';

                                }
                            }

                        }                    
                        
                        ?>

                    </table>

                    <h5 class="text-center mt-3 text-info">Payment Details</h5>
                    <hr />                
                    <table style="width: 40%; margin: 0 auto;" class="table table-bordered">

                        <?php 
                        
                            $vatable_amount = $total_amount / 1.12;
                            $vat = $total_amount - $vatable_amount;

                        ?>
                        <input type="hidden" name="p_total_amount" value="<?php echo $total_amount; ?>">
                        <tr>
                            <td><h6>TOTAL ROOM FEE</h6> </td>
                            <td class="text-right pr-4 pb-3">PHP <?php echo number_format($total_amount, 2); ?></td>
                        </tr>
                        <tr>
                            <td><h6>VATABLE AMOUNT</h6></td>
                            <td class="text-right pr-4 pb-3">PHP <?php echo number_format($vatable_amount, 2); ?></td>
                        </tr>
                        <tr>
                            <td><h6>TOTAL AMOUNT<h6></td>
                            <td class="text-right pr-4 pb-3">PHP <?php echo number_format($vat, 2); ?></td>
                        </tr>
                        <tr>
                            <td><h6>DEADLINE OF PAYMENT</h6></td>
                            <?php
                               
                            $deadline = Date('F d, o', strtotime("+3 days"));
                            
                            ?>

                            <td  class="text-right pr-4 pb-3"><h6 ><?php echo $deadline; ?></h6></td>
                        </tr>

                    </table>

                    <br>

                    <div class="mt-3 mb-3">
                        <button type="submit" class="btn btn-primary btn-block">Confirm Reservation</button>
                    </div>
                    
            
                </div>    
            </div>  

        </div>

    </form>




<?php


include('common/footer.php');


?>

