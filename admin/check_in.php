<?php
session_start();

include('../common/header.php');
require('../functions/assets/connection.php');

$db = connect_to_db();


?>

    <?php include('../common/admin_sidebar.php') ?>

    <div class="main-panel">
        <div class="container-fluid">
            <h1>Reservation Check-in</h1>
            
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
                                        <th scope="col" class="text-center">>Action</th>
                                    </tr>                            
                                </thead>
                                <tbody>
                                    <?php
                                    
                                        $reservation_query = "SELECT * FROM reservation RES INNER JOIN guest G ON RES.guest_id = G.Id";
                                        $reservation_result = mysqli_query($db, $reservation_query);
                                                
                                        if(mysqli_num_rows($reservation_result) > 0) {
                                            while($reservation = mysqli_fetch_assoc($reservation_result)) {
                                                
                                                $check_in_date = date_format(new Datetime($reservation["check_in_date"]), "m-d-Y");
                                                $check_out_date = date_format(new Datetime($reservation["check_in_date"]), "m-d-Y");

                                                echo '
                                                    <tr>
                                                        <td>' . $reservation["reference_no"] . '</td>
                                                        <td>' . $reservation["status"]  . '</td>
                                                        <td>' . $reservation["first_name"] . " " . $reservation["last_name"]  . '</td>
                                                        <td>' . $check_in_date . " - " . $check_out_date . '</td>                        
                                                        <td><a href="check_in_user.php?reference_no=' . $reservation["reference_no"] . '" class="btn btn-link btn-info">View</a></td>                        
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

include('../common/footer.php');
session_destroy();

?>