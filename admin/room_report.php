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
                            <h4 class="text-center text-info">ROOM REPORTS</h4>
                            
                            <div class="row justify-content-md-center">
                                <div class="col-4">

                                    <form action="../functions/admin/reports_room.php" method="POST">
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

                                                <div class="form-group">
                                                    <label for="">ROOM NAME</label>
                                                    <select class="form-control" name="room_name">
                                                    <?php
                                                        
                                                        $room_query = "SELECT type, Id FROM rooms";
                                                        $room_result = mysqli_query($db, $room_query); 
                                                        
                                                        if(mysqli_num_rows($room_result) > 0) {
                                                            echo '<option value="">All Rooms</option>';
                                                            while($room = mysqli_fetch_assoc($room_result)) {
                                                                echo '<option value="' . $room['Id'] . '">' . $room['type'] . '</option>';
                                                            }

                                                        } 

                                                    ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">ROOM STATUS</label>
                                                    <select class="form-control" id="" name="room_availability">
                                                        <option value="">All Status</option>
                                                        <option value="AVAILABLE">AVAILABLE</option>
                                                        <option value="OCCUPIED">OCCUPIED</option>
                                                        <option value="FOR REPAIR">FOR REPAIR</option>                                                
                                                    </select>
                                                </div>

                                            </div>
                                        </div>

                                        
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