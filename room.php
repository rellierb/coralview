<?php

include('common/header.php');
include('common/navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();


if(isset($_REQUEST["room_id"])) {
    $room_id = $_REQUEST["room_id"];
}





?>

<div class="container">

    <div class="row">
        <div class="col">

            <?php
            
            $room_query = "SELECT * FROM rooms WHERE Id=$room_id";
            $room_result = mysqli_query($db, $room_query);

            if(mysqli_num_rows($room_result) == 1) {

                while($room = mysqli_fetch_assoc($room_result)) {

                    echo '
                    
                        <h2 class="text-center coralview-orange">' . $room['type'] . '</h2>
                        <hr>
                        <br><br>
                        <div class="row" style="height: 50vh;">
                            
                            <div class="col-12">
                            
                                <div class="card">
                                    <div class="card-header">

                                        <div class="row">
                                        
                                            <div class="col-6">
                                                <img style="border-radius: 0px;" src="/coralview/uploads/rooms/deluxe.jpg" alt="">
                                            </div>

                                            <div class="col-6">
                                                <table class="table">
                                                    <tr>
                                                        <td style="width: 50%;" class="text-center coralview-blue">Capacity</td>
                                                        <td style="width: 50%;">' . $room['capacity'] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50%;" class="text-center coralview-blue">Peak Rate:</td>
                                                        <td style="width: 50%;">PHP ' . number_format($room['peak_rate'], 2) . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50%;" class="text-center coralview-blue">Off-Peak Rate:</td>
                                                        <td style="width: 50%;">PHP ' . number_format($room['off_peak_rate'], 2) . '</td>
                                                    </tr>
                                                </table>
                                                
                                                <p>' . $room['description'] . '</p>
                                                <br>

                                                <div id="room_id">
                                                    ' . $room['inclusions'] . '
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                            
                            </div>


                            
                        </div>                        


                    
                    ';

                }

            }
            
            ?>

            </div>

        </div>
    </div>

</div>


<?php

include('common/foot.php');
include('common/footer.php');

?>
