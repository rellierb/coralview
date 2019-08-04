<?php
session_start();

include('../../common/header.php');
require('../../functions/assets/connection.php');

$db = connect_to_db();


?>

    <?php include('../../common/admin_sidebar.php') ?>

    <div class="main-panel">
        <div class="container-fluid">
            <h1>Room Availability</h1>
            
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
                    
                    <div style="width: 70%; background-color: white; padding: 15px;">
                    
                        <table class="table" id="roomStatusTable">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Room Number</th> 
                                    <th scope="col">Room Name</th> 
                                    <th scope="col">Status</th> 
                                    <th scope="col">Change Status</th> 
                                </tr>                            
                            </thead>
                            
                            <tbody>
                                <?php

                                $room_status_query = "SELECT * FROM rooms_status RS INNER JOIN rooms R ON RS.room_id = R.id";
                                $room_status_result = mysqli_query($db, $room_status_query);

                                while($room_status = mysqli_fetch_assoc($room_status_result)) {
                                    
                                    $room_stats =  $room_status["status"];
                                    $room_class = "";

                                    $occupied_selected = "";
                                    $repair_selected = "";
                                    $unavailable_selected = "";
                                    $available_selected = "";

                                    switch($room_stats) {
                                        case "OCCUPIED":
                                            $room_class = "badge-primary";
                                            $occupied_selected = "selected";
                                            break;
                                        case "FOR REPAIR":
                                            $room_class = "badge-secondary";
                                            $repair_selected = "selected";
                                            break;
                                        case "UNAVAILABLE":
                                            $room_class = "badge-danger";
                                            $unavailable_selected = "selected";
                                            break;
                                        default:
                                            $room_class = "badge-success";
                                            $available_selected = "selected";
                                            break;
                                    }
                                        
                                    echo '
                                        <tr>
                                            
                                            <td>' . $room_status["room_number"] . '</td>
                                            <td>' . $room_status["type"] . '</td>
                                            <td><p class="badge badge-pill ' . $room_class . '">' . $room_status["status"] . '</p></td>
                                            <td>
                                                <form action="../../functions/admin/update_room_status.php" method="POST">
                                                    <input type="hidden" value="' . $room_status["id"] . '" name="room_id">
                                                    <select class="form-control" name="room_status" style="width: 55%; display: inline;">
                                                        <option ' . $available_selected . ' value="AVAILABLE">Available</option>
                                                        <option ' . $occupied_selected . ' value="OCCUPIED">Occupied</option>
                                                        <option ' . $repair_selected . ' value="FOR REPAIR">For Repair</option>                                                    
                                                        <option ' . $unavailable_selected . ' value="UNAVAILABLE">Unavailable</option>                                                    
                                                    </select>
                                                    <button type="submit" style="width: 35%; display: inline;" class="btn btn-primary">Update</button>
                                                </form>
                                            </td>
                                        </tr> 
                                    ';
                                }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>

                    

                </div>
            </div>

        </div>
    </div>




<?php

include('../../common/footer.php');
session_destroy();

?>