<?php

include('common/header.php');
include('common/navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

?>

<div class="container">

    <div class="row">
        <div class="col">

            <div>
                <h2 class="text-center coralview-orange">Rooms</h2>
                
                <div>


                    <?php
                    
                    $room_query = "SELECT * FROM rooms;";
                    $room_result = mysqli_query($db, $room_query);
                    $room_length = 0;

                    if(mysqli_num_rows($room_result) > 0) {
                        while($room = mysqli_fetch_assoc($room_result)) {

                            $room_length++;

                            if($room_length % 2 == 1) {
                                echo '
                                <div class="row">
                                ';

                            }

                            echo '
                            <div class="col-6">
                                <div class="card">
                                    <img class="card-img-top" src="' . $room['image'] . '" alt="Card image cap">
                                    <div class="card-header">
                                        <h5>' . $room['type'] . '</h5>
                                    </div>
                                    <div class="card-body">
                                    
                                        <div class="row">
                                            <div class="col-6">
                                                <span>Peak Rate: ' . number_format($room['peak_rate'], 2)  . '</span>
                                            </div>
                                            <div class="col-6">
                                                <span>Off-peak Rate: ' . number_format($room['off_peak_rate'], 2) . '</span>
                                            </div>
                                            
                                        </div>
                                       
                                        <br>
                                        
                                        <p class="card-text">' . $room['description'] . '</p>
                                        <a href="room.php?room_id='. $room['Id'] .'" class="btn btn-primary btn-outline float-right">More Details</a>
                                    </div>
                                </div>
                            </div>
                            ';

                            if($room_length % 2 == 0) {
                                echo '
                                </div>
                                ';

                            }

                        }

                    }
                    
                    
                    
                    ?>
               

                </div>    
                
            

            </div>

        </div>
    </div>

</div>


<?php

include('common/foot.php');
include('common/footer.php');

?>
