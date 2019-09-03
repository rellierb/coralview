<?php

session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

?>

    <?php include('../common/admin_sidebar.php') ?>
   
    <div class="main-panel">
        <div class="container-fluid">
            <h1>REPORTS GENERATION</h1>
            
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

                            <div class="row">
                                <div class="col">
                                    <a href="reports/guest_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Guest Report</a>
                                    <a href="reports/receptionist_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Receptionist Report</a>
                                    <a href="reports/room_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Room Report</a>
                                </div>

                                <div class="col">
                                    <a href="reports/reservation_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Reservation Report</a>
                                    <a href="reports/check_in_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Check-in Report</a>
                                    <a href="reports/billing_report.php" style="color: white;" class="btn btn-info btn-block btn-lg">Billing Report</a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
  
                </div>
            </div>

        </div>
    </div>


<?php

include('../common/footer.php');    
unset($_SESSION["alert"]);
unset($_SESSION["msg"]);

?>