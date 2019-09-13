<!-- Modal -->
<div class="modal fade" id="upgradeRoom" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">

        <form action="/functions/admin/upgrade_room.php">
        
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">UPGRADE ROOMS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <?php
                                            
            $room_reservation_details_query = "SELECT * FROM reservation RES
                INNER JOIN guest G ON G.id = RES.guest_id
                INNER JOIN booking_rooms BR ON RES.id = BR.reservation_id
                INNER JOIN rooms R ON BR.room_id = R.Id
                WHERE RES.reference_no = '$reference_no'";

            $room_reservation_details_result = mysqli_query($db, $room_reservation_details_query);

            $rooms_reserved = array();

            $quantity = 0;

            if($room_reservation_details_result) {

                echo '
                    <table class="table table-bordered">
                    <tr>
                        <th class="text-center" style="width: 55%;">ROOM/S RESERVE</th>
                        <th class="text-center" style="width: 15%;">QUANTITY</th>
                        <th class="text-center" style="width: 15%;">PRICE</th>
                        
                    </tr>
                ';

                while($room_reservation = mysqli_fetch_assoc($room_reservation_details_result)) {
                    
                    $room_id = $room_reservation["room_id"];
                    $room_quantity = $room_reservation["quantity"];
                    $rooms_reserved[$room_id] = $room_quantity; 

                    echo '
                        <tr>
                            <td class="text-center" style="width: 55%;">' . $room_reservation["type"] . '</td>
                            <td class="text-center" style="width: 15%;">' . $room_reservation["quantity"] . '</td>
                            <td class="text-center" style="width: 15%;">' . number_format($room_reservation["peak_rate"]) . '</td>
                        </tr>
                    </table>
                    ';
                    
                    $select_all_rooms = "SELECT * FROM rooms WHERE id != " . $room_id  . " ";
                    $select_all_rooms_result = mysqli_query($db, $select_all_rooms);

                    if(mysqli_num_rows($select_all_rooms_result) > 1) {
                        
                        echo '<div style="margin: 0 auto;">
                                <select class="form-control" name="room_id" data-room-id-' . $room_id . ' style="display: inline-block; width: 48%;">';
                        echo '<option value=""></option>';
                        while($room = mysqli_fetch_assoc($select_all_rooms_result)) {

                            echo '<option value="' . $room['Id'] . '">' . $room['type'] . '</option>';

                        }
                        echo '</select>     
                                <button type="button" name="upgrade_room" data-previous-room-id="' . $room_id . '"  data-reservation-id="' . $reservation_id  . '"  data-upgrade-button style="inline-block; width: 50%;" type="submit" class="btn btn-primary">Upgrade</button>  
                            </div>
                        ';
                    }
                    
                }
                

            }
            
            ?>
                    
                </table>

            </div>
            <!-- <div class="modal-footer">
                <div class="left-side">
                    <button type="button" class="btn btn-default btn-link" data-dismiss="modal">Yes</button>
                </div>
                <div class="divider"></div>
                <div class="right-side">
                    <button type="button" class="btn btn-danger btn-link"> No</button>
                </div>
            </div> -->
        
        </form>

        
    </div>
  </div>
</div>