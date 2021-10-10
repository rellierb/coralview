<?php
session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

date_default_timezone_set('Australia/Melbourne');

// update to 'FOR CHECK-IN' Status when date is today
$check_in_query = "SELECT reference_no FROM reservation WHERE check_out_date = CURDATE() AND status='CHECKED IN'";
$check_in_result = mysqli_query($db, $check_in_query);

while($reference_no = mysqli_fetch_assoc($check_in_result)) {
    $number = $reference_no["reference_no"];
    $update_query_check_in = "UPDATE reservation SET status='FOR CHECK OUT' WHERE reference_no='$number'";
    $update_result_check_in = mysqli_query($db, $update_query_check_in);
}

?>

    <?php include('../common/admin_sidebar.php') ?>

    <div class="main-panel">
        <div class="container-fluid">
            <h1>Reservation Check-out</h1>

            <h4 class="text-info">Date Today: <?php echo date("M d, Y"); ?></h4>
            
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
                <div class="col">
                    
                    <div class="card">
                        <div class="card-body">

                            <table class="table" id="reservationCheckIn">
                                <thead>
                                    <tr>
                                        <th scope="col">Reservation ID</th> 
                                        <th scope="col">Status</th> 
                                        <th scope="col">Guest Name</th>
                                        <th scope="col">Date of Stay</th> 
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>                            
                                </thead>
                                <tbody>
                                    <?php
                                        $reservation_query = "SELECT * FROM reservation RES INNER JOIN guest G ON RES.guest_id = G.Id WHERE RES.check_out_date = CURDATE()";
                                        $reservation_result = mysqli_query($db, $reservation_query);
                                                
                                        if(mysqli_num_rows($reservation_result) > 0) {
                                            while($reservation = mysqli_fetch_assoc($reservation_result)) {
                                                
                                                $check_in_date = date_format(new Datetime($reservation["check_in_date"]), "m-d-Y");
                                                $check_out_date = date_format(new Datetime($reservation["check_out_date"]), "m-d-Y");

                                                echo '
                                                    <tr>
                                                        <td>' . $reservation["reference_no"] . '</td>
                                                        <td><p class="badge badge-info">' . $reservation["status"]  . '</p></td>
                                                        <td>' . $reservation["first_name"] . " " . $reservation["last_name"]  . '</td>
                                                        <td>' . $check_in_date . " - " . $check_out_date . '</td>                        
                                                        <td style="width: 20%;">
                                                            <a style="width: 98%; display: inline-block;" href="check_out_user.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-info btn-block">View</a>
                                                            
                                                        </td>                        
                                                    </tr>
                                                '; // <a style="width: 48%; display: inline-block;" href="cancel.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-warning btn-block">cancel</a>

                                            }
                                        }
                                    
                                    ?>
                                </tbody>
                            </table> 

                        </div>
                    </div>
                      
                </div>
            </div>

        </div>
    </div>


<?php

include('../common/admin_footer.php');    

?>