<?php

include('common/header.php');
include('common/navbar.php');

require('functions/assets/connection.php');

$db = connect_to_db();

$off_peak_date_start_1 = date("F d, Y", strtotime("01/02/2021"));
$off_peak_date_end_1 = date("F d, Y", strtotime("03/11/2021"));

$off_peak_date_start_2 = date("F d, Y", strtotime("07/18/2021"));
$off_peak_date_end_2 = date("F d, Y", strtotime("11/19/2021"));

$peak_date_start_1 = date("F d, Y", strtotime("03/12/2021"));
$peak_date_end_1 = date("F d, Y", strtotime("07/17/2021"));

$peak_date_start_2 = date("F d, Y", strtotime("11/20/2021"));
$peak_date_end_2 = date("F d, Y", strtotime("01/01/2022"));


?>

<div class="container">

    <div class="row">
        <div class="col">

            <div>
                <h2 class="text-center coralview-orange mt-5 mb-5">Rooms</h2>
                <hr>
                
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">OFF-PEAK DATES</th>
                        <td class="text-center"><?php echo $off_peak_date_start_1 . ' - ' . $off_peak_date_end_1; ?></td>
                        <td class="text-center"><?php echo $off_peak_date_start_2 . ' - ' . $off_peak_date_end_2; ?></td>                    
                    </tr>
                    <tr>
                        <th class="text-center">PEAK DATES</th>
                        <td class="text-center"><?php echo $peak_date_start_1 . ' - ' . $peak_date_end_1; ?></td>
                        <td class="text-center"><?php echo $peak_date_start_2 . ' - ' . $peak_date_end_2; ?></td>                    
                    </tr>
                </table>
                

                <hr>
                <div class="mt-5">


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
                                    <img style="border-radius: 0;" class="card-img-top" src="' . $room['image'] . '" alt="Card image cap">
                                    <div class="card-header">
                                        <h5 class="coralview-blue">' . $room['type'] . '</h5>
                                    </div>
                                    <div class="card-body">
                                    
                                        <div class="row">
                                            <div class="col-4">
                                                <span style="font-family: \'Segoe UI\', sans-serif;" class="coralview-blue">PEAK RATE: ' . number_format($room['peak_rate'], 2)  . '</span>
                                            </div>
                                            <div class="col-5">
                                                <span style="font-family: \'Segoe UI\', sans-serif;" class="coralview-blue">OFF-PEAK RATE: ' . number_format($room['off_peak_rate'], 2) . '</span>
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

                </div>
<?php

include('common/foot.php');
include('common/footer.php');

?>
