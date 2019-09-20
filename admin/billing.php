<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

date_default_timezone_set("Asia/Singapore");

if(isset($_REQUEST["reference_no"])) {

    $reference_no = $_REQUEST["reference_no"];

}

$reservation_id = 0;
$overall_total_extra = 0;
$overall_total_price = 0;
$guest_count = 0;
$address = '';
$nights_of_stay = 0;
$arrival_date = '';
$departure_date = '';

$TOTAL_PRICE = 0;

$content_html = '';

$is_peak_rate = 0;


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
                                            $address = $reservation["address"];
                                            $guest_count = $reservation["adult_count"] + $reservation["kids_count"];

                                            $arrival_date = $reservation["check_in_date"];
                                            $departure_date = $reservation["check_out_date"];
                                            $is_peak_rate = $reservation["is_peak_rate"];

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

                                </div>
                            
                                <div class="col-12">

                                


                                <!-- <br>
                                
                                <style>       
                                    #receipt *
                                    {
                                        border: 0;
                                        box-sizing: content-box;
                                        color: inherit;
                                        font-family: inherit;
                                        font-size: inherit;
                                        font-style: inherit;
                                        font-weight: inherit;
                                        line-height: inherit;
                                        list-style: none;
                                        margin: 0;
                                        padding: 0;
                                        text-decoration: none;
                                        vertical-align: top;
                                    }

                                    *[contenteditable] { min-width: 1em; outline: 0; }
                                    *[contenteditable] { cursor: pointer; }
                                    #receipt span[contenteditable] { display: inline-block; }
                                    #receipt h1 { font: bold 100% sans-serif; letter-spacing: 0.5em; text-align: center; text-transform: uppercase; }
                                    #receipt table { font-size: 75%; table-layout: fixed; width: 100%; }
                                    #receipt table { border-collapse: separate; border-spacing: 2px; }
                                    #receipt th, #receipt td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
                                    #receipt th, #receipt td { border-style: solid; }
                                    #receipt th { background: #EEE; border-color: #BBB; }
                                    #receipt td { border-color: #DDD; }
                                    #receipt div { margin: 0 0 3em; }
                                    #receipt div:after { clear: both; content: ""; display: table; }
                                    #receipt div h1 { background: #000; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; }
                                    #receipt div address { float: left; font-size: 75%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
                                    #receipt div address p { margin: 0 0 0.25em; }
                                    #receipt div span, #receipt div img { display: block; float: right; }
                                    #receipt div span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
                                    #receipt div img { max-height: 100%; max-width: 100%; }
                                    #receipt div input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
                                    #receipt article, #receipt article address, #receipt table.meta, #receipt table.inventory { margin: 0 0 3em; }
                                    #receipt article:after { clear: both; content: ""; display: table; }
                                    #receipt article h1 { clip: rect(0 0 0 0); position: absolute; }
                                    #receipt article address { float: left; font-size: 125%; font-weight: bold; }
                                    #receipt table.meta, #receipt table.balance { float: right; width: 36%; }
                                    #receipt table.meta:after, #receipt  table.balance:after { clear: both; content: ""; display: table; }
                                    #receipt table.meta th { width: 40%; }
                                    #receipt table.meta td { width: 60%; }
                                    #receipt table.inventory { clear: both; width: 100%; }
                                    #receipt table.inventory th { font-weight: bold; text-align: center; }
                                    #receipt table.inventory td:nth-child(1) { width: 26%; }
                                    #receipt table.inventory td:nth-child(2) { width: 38%; }
                                    #receipt table.inventory td:nth-child(3) { text-align: right; width: 12%; }
                                    #receipt table.inventory td:nth-child(4) { text-align: right; width: 12%; }
                                    #receipt table.inventory td:nth-child(5) { text-align: right; width: 12%; }
                                    #receipt table.balance th, #receipt table.balance td { width: 50%; }
                                    #receipt table.balance td { text-align: right; }
                                    #receipt aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
                                    #receipt aside h1 { border-color: #999; border-bottom-style: solid; }
                                </style>

                                <div id="receipt">
                                
                                    <div class="text-center">
                                        <h1>OFFICIAL RECEIPT</h1>
                                        <address class="text-center" style="width: 100%;">
                                            <p>CORALVIEW BEACH RESORT</p>
                                            <p>POBLACION, MORONG, BATAAN, PHILIPPINES</p>
                                            <p>+632-782-2881</p>
                                            <p>+632-782-2883</p>
                                        </address>
                                        <span><img alt=""><input type="file" accept="image/*"></span>
                                    </div>
                                    <article>
                                        <h1>Recipient</h1>
                                        <address>
                                            <p><?php echo $full_name; ?></p>
                                        </address>
                                        <table class="meta">
                                            <tr>
                                                <th><span>REFERENCE NO</span></th>
                                                <td><span><?php echo $reference_no; ?></span></td>
                                            </tr>
                                            <tr>
                                                <th><span>Address</span></th>
                                                <td><?php echo $address; ?></td>
                                            </tr>
                                            <tr>
                                                <th><span>Date</span></th>
                                                <td><span><?php echo date("F m, Y h:i:s A"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <th><span>Arrival Date</span></th>
                                                <?php $temp_date_arrival = date_create($arrival_date); ?>
                                                <td><span><?php echo date_format($temp_date_arrival, "M d, Y"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <th><span>Departure Date</span></th>
                                                <?php $temp_date_departure = date_create($departure_date); ?>
                                                <td><span><?php echo date_format($temp_date_departure, "M d, Y"); ?></span></td>
                                            </tr>
                                            <tr>
                                                <th><span>Night/s of Stay</span></th>
                                                <td><span><?php echo $nights_of_stay; ?></span></td>
                                            </tr>
                                        </table>
                                        <table class="inventory">
                                            <thead>
                                                <tr>
                                                    
                                                    <th><span>Description</span></th>
                                                    <th><span>Rate</span></th>
                                                    <th><span>Quantity</span></th>
                                                    <th><span>Price</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>

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

                                                        $room_rate = 0;
                                                        if($is_peak_rate == 0) {
                                                            $room_rate = $room_reservation["off_peak_rate"]; 
                                                        } else if ($is_peak_rate == 1) {
                                                            $room_rate = $room_reservation["peak_rate"];
                                                        }

                                                        $total_price = $room_rate * $room_reservation["quantity"];
            
                                                        $content_html .= '
                                                        
                                                            <tr>
                                                                <td class="tg-cly1" colspan="5">' . $room_reservation["type"] . ' (' . $room_quantity . ')</td>
                                                                <td style="text-align: center;" class="tg-0lax" colspan="5">' . number_format($total_price, 2) . '</td>
                                                            </tr>
                                                        
                                                        ';
                                                        
                                                        // echo '
                                                        //     <tr>
                                                        //         <td style="text-align: center;"><span>' . $room_reservation["type"] . '</span></td>
                                                        //         <td style="text-align: center;">' . number_format($room_reservation["peak_rate"]) . '</span></td>
                                                        //         <td style="text-align: center;"><span>' . $room_reservation["quantity"] . '</span></td>
                                                        //         <td style="text-align: center;"><span data-prefix>PHP </span><span>' . number_format($total_price, 2)  . '</span></td>
                                                        //     </tr>
                                                        // ';
            
                                                        $overall_total_price += $total_price;
                                                        $TOTAL_PRICE += $total_price;

                                                    }
                                                    
                                                }
                                                
                                                ?>

                                                <?php

                                                $overall_total_price *= $nights_of_stay;
                                                $TOTAL_PRICE *= $nights_of_stay;
                                                
                                                ?>

                                                <?php
                                                
                                                $extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE reference_no='$reference_no'";
                                                $extra_list_result = mysqli_query($db, $extra_list_query);

                                                if(mysqli_num_rows($extra_list_result) > 0) {

                                                    while($extra = mysqli_fetch_assoc($extra_list_result)) {

                                                        $total_extra = $extra["price"] * $extra["quantity"];
                                                        $overall_total_extra += $total_extra;

                                                        $content_html .= '
                                                        
                                                            <tr>
                                                                <td class="tg-cly1" colspan="5">' . $extra["description"] . ' (' . $extra["quantity"] . ')</td>
                                                                <td style="text-align: center;" class="tg-0lax" colspan="5">' . number_format($total_extra, 2) . '</td>
                                                            </tr>
                                                        
                                                        ';

                                                        // echo '
                                                        //     <tr>
                                                        //         <td style="text-align: center;"><span>' . $extra["description"] . '</span></td>
                                                        //         <td style="text-align: center;">' . $extra["price"]  . '</span></td>
                                                        //         <td style="text-align: center;"><span>' .  $extra["quantity"] . '</span></td>
                                                        //         <td style="text-align: center;"><span data-prefix>PHP </span><span>' . number_format($total_extra, 2)  . '</span></td>
                                                        //     </tr>
                                                        // ';

                                                    }

                                                    // $overall_total_price += $overall_total_extra;
                                                    $TOTAL_PRICE += $overall_total_extra;
                                                }
                                                
                                                
                                                // $overall_total_price *= $nights_of_stay;
                                                

                                                ?>

                                                <?php
                                                
                                                    // Additional Fees
                                                    $add_fees_query = "SELECT * FROM billing_additional_fees WHERE reference_no='$reference_no'";
                                                    $add_fees_result = mysqli_query($db, $add_fees_query);
                                                    $add_fees_amount = 0;

                                                    if(mysqli_num_rows($add_fees_result) > 0) {

                                                        while($fees = mysqli_fetch_assoc($add_fees_result)) {


                                                            $content_html .= '
                                                        
                                                                <tr>
                                                                    <td class="tg-cly1" colspan="5">' . $fees["description"] . '</td>
                                                                    <td style="text-align: center;" class="tg-0lax" colspan="5">' . number_format($fees["amount"], 2) . '</td>
                                                                </tr>
                                                            
                                                            ';

                                                            // echo'
                                                            //     <tr>
                                                            //         <td style="text-align: center;"><span>' . $fees["description"] . '</span></td>
                                                            //         <td style="text-align: center;">' . number_format($fees["price"] , 2) . '</span></td>
                                                            //         <td style="text-align: center;"><span>' .  $fees["quantity"] . '</span></td>
                                                            //         <td style="text-align: center;"><span data-prefix>PHP </span><span>' . number_format($total_extra, 2)  . '</span></td>
                                                            //     </tr>
                                                            // ';

                                                            $TOTAL_PRICE += $fees['amount'];

                                                        }

                                                    }
                                                
                                                ?>

                                                <?php
                                                
                                                $check_discount_query = "SELECT * FROM billing_discount BD INNER JOIN discount D on BD.discount_id=D.Id  WHERE BD.reference_no='$reference_no'";
                                                $check_discount_result = mysqli_query($db, $check_discount_query);
                                                $discount_price = 0;
    
                                                if(mysqli_num_rows($check_discount_result) > 0) {
                                                   
                                                    while($discount = mysqli_fetch_assoc($check_discount_result)) {
                                                        
                                                        $discount_amount = $discount["amount"];
                                                        $comp_discount = $overall_total_price / $guest_count;
                                                       
                                                        if($discount_amount < 1) {
                                                            $temp_discount_price = ($comp_discount * $discount["quantity"]) * $discount_amount;
                                                            $discount_price += $temp_discount_price;
                                                        } 
    
                                                        $change_to_percent = $discount['amount'] * 100;
                                                        

                                                        $content_html .= '
                                                        
                                                            <tr>
                                                                <td class="tg-cly1" colspan="5">' . $discount["name"] . ' (' . $change_to_percent . ')</td>
                                                                <td style="text-align: center;" class="tg-0lax" colspan="5">(' . number_format($discount_price, 2) . ')</td>
                                                            </tr>
                                                        
                                                        ';

                                                        // echo'
                                                            
                                                        //     <tr>
                                                        //         <td style="text-align: center;"><span>' . $discount["name"] . '</span></td>
                                                        //         <td style="text-align: center;">' . $change_to_percent . ' %</span></td>
                                                        //         <td style="text-align: center;"><span>' .  $discount["quantity"] . ' </span></td>
                                                        //         <td style="text-align: center;">(<span data-prefix>PHP </span><span>' . number_format($discount_price, 2)  . '</span>)  </td>
                                                        //     </tr>
                                                        
                                                        // ';
                                                          
                                                    }
                                                }
                                                
                                                ?>
                                            
                                            </tbody>
                                        </table>

                                        <?php

                                        $overall_total_amount = $TOTAL_PRICE;
                                        $vatable_amount = $overall_total_amount / 1.12;
                                        $vat = $overall_total_amount - $vatable_amount;

                                        $net_amount = $overall_total_amount - $discount_price;


                                        ?>
                                        
                                        <table class="balance">
                                            <tr>
                                                <th><span>Total</span></th>
                                                <td><span data-prefix>PHP </span><span><?php echo number_format($net_amount, 2); ?></span></td>
                                            </tr>
                                        </table>
                                    </article>
                                   
                                
                                </div> -->

                                </div>

                                <div class="col-12">
                                
                                <div style="width: 100%; display: inline;">
                                    <div style="width: 40%; float: left;">   
                                        <style type="text/css">
                                            .tg  {border-collapse:collapse;border-spacing:0;}
                                            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                                            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
                                            .tg .tg-cly1{text-align:left;vertical-align:middle}
                                            .tg .tg-baqh{text-align:center;vertical-align:top}
                                            .tg .tg-nrix{text-align:center;vertical-align:middle}
                                            .tg .tg-0lax{text-align:left;vertical-align:top}
                                        </style>
                                        <table class="tg" style="width: 100%;">
                                            <tr>
                                                <th class="tg-nrix" colspan="10">In Settlement of the following:</th>
                                            </tr>
                                            <tr>
                                                <td class="tg-nrix" colspan="5">PARTICULARS</td>
                                                <td class="tg-baqh" colspan="5">AMOUNT</td>
                                            </tr>
                                            <?php

                                            echo $content_html;
                                            
                                            ?>
                                            <tr>
                                                <td class="tg-0lax" colspan="5">VATABLE SALES</td>
                                                <td style="text-align: center;" class="tg-0lax" colspan="5"><? echo number_format($TOTAL_PRICE, 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="5">VAT-EXEMPT SALES</td>
                                                <td style="text-align: center;" class="tg-0lax" colspan="5"><? echo number_format($vatable_amount, 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="5">VAT Amount</td>
                                                <td style="text-align: center;" class="tg-0lax" colspan="5"><? echo number_format($vat, 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="5">TOTAL AMOUNT DUE</td>
                                                <td style="text-align: center;" class="tg-0lax" colspan="5"><? echo number_format($net_amount, 2) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="5"><span style="font-weight:bold">Form of Payment</span></td>
                                                <td class="tg-baqh" colspan="5"></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="10">CASH ______ CHECK ______<br></td>
                                            </tr>
                                            <tr>
                                                <td class="tg-0lax" colspan="10">BANK  _____________ DATE _______</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div style="width: 57%;  float: right; margin-left: 15px; font-family: Arial;">

                                        <h3 style="text-align: center;">CORALVIEW BEACH RESORT</h3>         
                                        <h5 style="text-align: center;">OWNED AND OPERATED BY: CJ MORONG INC.</h5>
                                        <h6 style="text-align: center;">Sitio Panibatuhan, Poblacion, Morong, Bataan</h6>
                                        <h6 style="text-align: center;">VAT Reg. TIN 008-854-530-000</h6>


                                        <div >
                                            <h6 style="text-align: right;">NO: <u><? echo $reference_no; ?></u> </h6>
                                            <h6 style="text-align: right;">Date: <u><? echo date("F m, Y h:i:s A"); ?></u></h6>
                                        </div>
                                        
                                        <div style="display: block;">
                                            <h3>Official Receipt</h3>
                                        </div>

                                        <div style="display: block;">
                                            <h6>Received from <span style="width: 50%:"><b><u><?php echo $full_name;?></u></b></span> With TIN _____ </h6>
                                            <h6>and Address at  <b><u><?php echo $address; ?></u></b> </h6>
                                            <h6>engaged in the business style of _____ </h6>
                                            <h6>the sum of <b><u><?php echo number_format($net_amount, 2); ?></u></b> pesos </h6>
                                            <h6>(P <b><u><?php echo number_format($net_amount, 2); ?></u></b> ) in partial / full payment for _____ </h6>
                                            <br>
                                            <span>RDO 20 OCN 4AU0001460621</span><br>
                                            <span>100 Bkits. 50x2 000001-005000</span><br>
                                            <span>Date Issued: 02-27-2015</span><br>
                                            <span>Valid Until: 02-26-2020</span><br>
                                        </div>

                                        <div style="display: block; float: right; margin-left: 40%;">
                                            <h6>BY: ________________________________</h6>
                                            <span style="text-align: center;">Cashier/Authorized Representative</span>
                                            <br>
                                            <span>THIS OFFICIAL RECEIPT SHALL BE VALID FOR</span>
                                            <br>
                                            <span>FIVE (5) YEARS FROM DATE OF ATP</span>
                                        </div>

                                    </div>

                                </div>




                                
                                </div>



                        
                            </div>
                        </div>

                        <div class="col">
                            <button type="submit" class="btn btn-info btn-block float-right">Generate Receipt</button>
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