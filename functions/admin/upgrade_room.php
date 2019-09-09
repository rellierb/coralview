<?php

session_start();
require('../assets/connection.php');

$db = connect_to_db();


$reservation_id = $_POST['reservation_id'];
$previous_roomid = $_POST['previous_room_id'];
$new_room_id = $_POST['new_room_id'];

$select_reservation_id = "SELECT id FROM reservation WHERE guest_id=$reservation_id";
$select_reservation_result = mysqli_query($db, $select_reservation_id);

if(mysqli_num_rows($select_reservation_result) == 1) {

    while($id = mysqli_fetch_assoc($select_reservation_result)) {

        $reservation_id_to_update = $id['id'];

        $select_find_br_id = "SELECT id FROM booking_rooms WHERE reservation_id='$reservation_id_to_update' AND room_id=$previous_roomid";
        $select_find_br_id_result = mysqli_query($db, $select_find_br_id);

        if(mysqli_num_rows($select_find_br_id_result) == 1) {

            while($br_id = mysqli_fetch_assoc($select_find_br_id_result)) {

                $id = $br_id['id'];
                
                $update_query = "UPDATE booking_rooms SET room_id=$new_room_id WHERE id='$id'";
                $update_result = mysqli_query($db, $update_query);

                if($update_result) {
                    
                    echo 'SUCCESS';

                } else {
                    echo "Error: " . $update_query . "<br>" . mysqli_error($db);
                }

            }

            

        }



    } 

}







