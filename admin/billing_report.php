<?php

session_start();

include('../common/admin_header.php');
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
                    
                    <div class="card" style="height: 60vh;">
                        <div class="card-body">
                            <h4 class="text-center text-info">Billing Report</h4>
                            
                            <div class="row justify-content-md-center">
                                <div class="col-4">

                                    <form action="../functions/admin/reports_billing.php" method="POST">

                                        <span class="text-success">Reservation Status: <span>COMPLETE</span></span>
                                        <br>
                                        <br>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="">Date of Reservation (From)</label>
                                                    <input type="text" class="form-control datetimepicker" name="date_reservation_from" data-language='en' id="dateFrom">
                                                </div>
                                                <div class="col">
                                                    <label for="">Date of Reservation (To)</label>
                                                    <input type="text" class="form-control datetimepicker" name="date_reservation_to" data-language='en' id="dateTo">
                                                </div>
                                            </div>
                                        </div>

                                        <a class="btn btn-outline-default btn-block" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Filter
                                        </a>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                <label for="">Reference No.</label>
                                                <input type="text" class="form-control" name="reference_no">
                                            </div>
                                        </div>



                                        <!-- <div class="form-group">
                                            <label for="">Reservation Reference No.</label>
                                            <select class="form-control" name="reference_no" >
                                            <?php
                                                
                                                $room_query = "SELECT reference_no, id FROM reservation";
                                                $room_result = mysqli_query($db, $room_query); 
                                                
                                                if(mysqli_num_rows($room_result) > 0) {                                                    

                                                    while($room = mysqli_fetch_assoc($room_result)) {
                                                        echo '<option value="' . $room['reference_no'] . '">' . $room['reference_no'] . '</option>';
                                                    }

                                                } 

                                            ?>
                                            </select>
                                        </div> -->
                                        
                                        <!-- <div class="form-group">
                                            <label for="">First Name</label>
                                            <input type="text" class="form-control" name="first_name" id="">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Last Name</label>
                                            <input type="text" class="form-control" name="last_name" id="">
                                        </div> -->
                                        
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-block">Generate Report</button>
                                    </form>

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