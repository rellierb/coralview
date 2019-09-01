<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_GET["capacity"])) {
    $capacity = $_GET["capacity"];
}

$list_of_rooms_query = "SELECT rooms.type, rooms.image, rooms.inclusions, rooms.peak_rate, rooms.off_peak_rate, room_id, count('room_id') as room_count 
FROM `rooms_status` INNER JOIN rooms ON rooms.Id = rooms_status.room_id  WHERE rooms_status.status = 'AVAILABLE' AND rooms.capacity >= $capacity  GROUP BY `room_id` ASC";

$list_of_rooms_result = mysqli_query($db, $list_of_rooms_query);

if(mysqli_num_rows($list_of_rooms_result) > 0) {

    $data = [];

    while($rooms = mysqli_fetch_assoc($list_of_rooms_result)) {
        array_push($data, $rooms);
    }

    echo json_encode($data);

}