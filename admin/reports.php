<?php

session_start();

include('../common/admin_header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();

?>

    <?php include('../common/admin_sidebar.php') ?>
    


    <div class="main-panel">
        <div class="container-fluid">
            <h1>Reports Generation</h1>
            
        <?php

        if(isset($_SESSION['msg']) && $_SESSION['alert']) {
            echo '
                <div class="' . $_SESSION['alert'] . ' text-center" role="alert">
                    ' . $_SESSION['msg']  . '
                </div>
            ';
        }
        
        ?>

            <div class="row" >
                <div class="col">
                    
                    <div class="card" style="height: 80vh;">
                        <div class="card-header">
                            <h2 class="text-center">Choose Report</h2>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-6">
                                    <a href="reports/guest_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Guest Report</a>
                                    <!-- <a href="reports/receptionist_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Receptionist Report</a> -->
                                    <a href="reports/room_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Room Report</a>
                                    <a href="reports/reservation_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Reservation Report</a>
                                    <a href="reports/check_in_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Check-in Report</a>
                                    <a href="reports/billing_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Billing Report</a>
                                    <a href="reports/summary_report.php" style="margin-bottom: 30px;" class="btn btn-outline-info btn-block btn-lg">Summary Report</a>
                                </div>
                            </div>
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