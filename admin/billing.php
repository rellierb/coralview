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

?>

    <?php include('../common/admin_sidebar.php') ?>

    <form action="../functions/admin/generate_receipt.php" method="POST">
        <input type="hidden" name="reference_no" value="<?php echo $reference_no; ?>" >
        <div class="main-panel">
            <div class="container-fluid">
                <h1>Billing</h1>
                
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
                                            $reservation_id = $reservation["id"];
                                            $dateDiff = date_diff(date_create($reservation["check_in_date"]), date_create($reservation["check_out_date"]));
                                            $diff = $dateDiff->format('%d');

                                            $full_name = $reservation["first_name"] . " " . $reservation["last_name"];
                                            $address = $reservation["address"];

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
                                

                                    ?>

                                    

                                
                                </div>
                            
                                <div class="col-8">

                                <br>


                                <style type="text/css">
                                    .tg  {border-collapse:collapse;border-spacing:0;}
                                    .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                                    .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                                    .tg .tg-4688{font-weight:bold;font-size:16px;font-family:Arial, Helvetica, sans-serif !important;;border-color:inherit;text-align:left;vertical-align:top}
                                    .tg .tg-0lax{text-align:center;vertical-align:top}
                                    .tg .tg-0pky{border-color:inherit;text-align:center;vertical-align:top}
                                </style>
                                <table class="tg" style="width: 100%;">
                                <tr>
                                    <th class="tg-0pky" colspan="2"><span style="font-weight:bold">Name</span></th>
                                    <th class="tg-0pky" colspan="2"><?php echo $full_name; ?></th>
                                </tr>
                                <tr>
                                    <td class="tg-0pky" colspan="2"><span style="font-weight:bold">Address</span></td>
                                    <td class="tg-0pky" colspan="2"><?php echo $address; ?></td>
                                </tr>
                                    <tr>
                                        <th class="tg-4688" colspan="2" style="text-align: center;">Description</th>
                                        <th class="tg-0lax" style="text-align: center;"><span style="font-weight:bold;">Quantity</span></th>
                                        <th class="tg-4688" style="text-align: center;">Amount</th>
                                    </tr>


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

                                        while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                                            
                                            $room_id = $room_reservation["room_id"];
                                            $room_quantity = $room_reservation["quantity"];
                                            $rooms_reserved[$room_id] = $room_quantity; 
                                            $quantity += $room_quantity;
                                            $total_price = $room_reservation["peak_rate"] * $room_reservation["quantity"];

                                            echo '
                                                <tr>
                                                    <td class="tg-0pky" colspan="2">' . $room_reservation["type"] . '</td>
                                                    <td class="tg-0lax">' . $room_reservation["quantity"]  . '</td>
                                                    <td class="tg-0pky">' . number_format($total_price, 2) . '</td>
                                                </tr>
                                            ';

                                            $overall_total_price += $total_price;
                                        }
                                        
                                    }

                                ?>

                                <?php
                                
                                $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
                                $extra_list_result = mysqli_query($db, $extra_list_query);

                                if(mysqli_num_rows($extra_list_result) > 0) {

                                    while($extra = mysqli_fetch_assoc($extra_list_result)) {

                                        $total_extra = $extra["price"] * $extra["quantity"];
                                        $overall_total_extra += $total_extra;

                                        echo '
                                            <tr>
                                                <td class="tg-0pky" colspan="2">' . $extra["description"] . '</td>
                                                <td class="tg-0lax">' . $extra["quantity"]  . '</td>
                                                <td class="tg-0pky">' . number_format($total_extra, 2) . '</td>
                                            </tr>
                                        ';

                                    }

                                    $overall_total_price += $overall_total_extra;
                                }
                                
                                $vatable_amount = $overall_total_price / 1.12;
                                $vat = $overall_total_price - $vatable_amount;
                                
                                ?>
                                    
                                    <tr>
                                        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Total</span></td>
                                        <td class="tg-0lax"><b><?php echo number_format($overall_total_price, 2); ?></b></td>
                                    </tr>
                                    <tr>
                                        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">Vatable Amount</span></td>
                                        <td class="tg-0lax"><?php echo number_format($vatable_amount, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="tg-0lax" colspan="3"><span style="font-weight:bold">VAT 12%</span></td>
                                        <td class="tg-0lax"><?php echo number_format($vat, 2); ?></td>
                                    </tr>
                                   
                                </table>
                                    
                                    <br>
                                    <button type="submit"  class="btn btn-info btn-block float-right">Generate Receipt</button>
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