<?php

session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

?>

    <?php include('../common/admin_sidebar.php') ?>
   
    <div class="main-panel">
        <div class="container-fluid">
            <h1>List of Reservation</h1>
            
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

                            <table class="table text-center" id="reservationCheckIn">
                                <thead>
                                    <tr>
                                        <th scope="col">Reservation ID</th> 
                                        <th scope="col">Status</th> 
                                        <th scope="col">Guest Name</th>
                                        <th scope="col">Arrival Date</th> 
                                        <th scope="col">Departure Date</th> 
                                        <th scope="col">Date Reserved</th> 
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>                            
                                </thead>
                                <tbody>
                                    <?php
                                    
                                        $reservation_query = "SELECT * FROM reservation RES INNER JOIN guest G ON RES.guest_id = G.Id";
                                        $reservation_result = mysqli_query($db, $reservation_query);
                                                
                                        if(mysqli_num_rows($reservation_result) > 0) {
                                            while($reservation = mysqli_fetch_assoc($reservation_result)) {
                                                
                                                $check_in_date = date_format(new Datetime($reservation["check_in_date"]), "m-d-Y");
                                                $check_out_date = date_format(new Datetime($reservation["check_out_date"]), "m-d-Y");

                                                $reservation_status =  $reservation["status"];
                                                $hide_class = "";
                                                // PENDING, FOR CHECK IN, REJECTED, 
                                                switch($reservation_status) {
                                                    case "REJECTED":
                                                        $reservation_class = "badge-danger";
                                                        $hide_class = "display: none;";
                                                        break;
                                                    case "FOR CHECK IN":
                                                    case "CHECKED IN":  
                                                        $reservation_class = "badge-info";
                                                        break;
                                                    case "PENDING":
                                                        $reservation_class = "badge-secondary";                                                        
                                                        break;
                                                    case "CANCELLED":
                                                        $reservation_class = "badge-warning";                                                        
                                                        break;
                                                    case "FOR CHECK OUT":
                                                        $reservation_class = "badge-light";
                                                        break;
                                                    case "COMPLETE":
                                                        $reservation_class = "badge-success";                                                                  
                                                        break;
                                                    default:
                                                        $reservation_class = "";
                                                        break;
                                                }

                                                echo '
                                                    <tr>
                                                        <td>' . $reservation["reference_no"] . '</td>
                                                        <td><p class="' . $reservation_class . '">' . $reservation["status"]  . '</p></td>
                                                        <td>' . $reservation["first_name"] . " " . $reservation["last_name"]  . '</td>
                                                        <td>' . $check_in_date . '</td>
                                                        <td>' . $check_out_date .  '</td>
                                                        <td>' . $reservation["date_created"] . '</td>               
                                                        <td>
                                                ';
                                                
                                                if($reservation_status == 'FOR CHECK IN') {
                                                    echo '<a style="width: 98%;' . $hide_class . '" href="cancel.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-warning ml-1">Cancel</a>';
                                                } else if ($reservation_status == 'CANCELLED') {
                                                    echo ' <a style="width: 98%; display: inline-block;" href="view.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-info btn-block">View</a>';
                                                } else if ($reservation_status == 'CHECKED IN') { 
                                                    echo '<a style="width: 98%;' . $hide_class . '" href="checked_in.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-block btn-primary">View</a>';
                                                } else if ($reservation_status == 'FOR CHECKED IN' ||  $reservation_status == 'PENDING' ) {
                                                    // <a style="width: 48%;' . $hide_class . '" href="accept.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-success">Accept</a>
                                                    // <a style="width: 48%;' . $hide_class . '" href="reject.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-danger">Reject</a>
                                                    echo '
                                                        <a style="width: 98%;' . $hide_class . '" href="pending.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-info">Pending</a>
                                                    ';
                                                } else if($reservation_status == 'COMPLETE') {
                                                    echo '<a style="width: 98%;' . $hide_class . '" href="view.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-block btn-primary">View</a>';
                                                } else if($reservation_status == 'FOR CHECK OUT') {
                                                    echo ' <a style="width: 98%; display: inline-block;" href="check_out_user.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-info btn-block">Check-out</a>';
                                                } else if($reservation_status == 'REJECTED') {
                                                    echo ' <a style="width: 98%; display: inline-block;" href="view.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-info btn-block">View</a>';
                                                }

                                                echo '
                                                        </td>                        
                                                    </tr>
                                                ';

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
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>