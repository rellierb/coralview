<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["room_reserved"])) {
    $room_reserved = $_POST["room_reserved"];
}


if(isset($_POST["capacity"])) {
    $guest_capacity = $_POST["capacity"];
}


$room_list = json_decode($room_reserved);

$total_room_capacity = 0;

foreach($room_list as $room) {

    $room_id = $room->roomId;
    $room_number = $room->roomNumber;

    $find_capacity = "SELECT capacity FROM rooms WHERE Id=$room_id";
    $find_capacity_result = mysqli_query($db, $find_capacity);

    while($room = mysqli_fetch_assoc($find_capacity_result)) {

        $room_capacity = (int)$room['capacity'];
        $total_room_capacity .= ($room_capacity * $room_number);

    }

}

$orig_capacity = intval(str_replace('"', '', $guest_capacity)); 

if($orig_capacity > $total_room_capacity) {
    echo "greater";    
} else if ($orig_capacity < $total_room_capacity) {
    echo "less";
}

// echo $total_room_capacity;


// $list_of_rooms_result = mysqli_query($db, $list_of_rooms_query);

// if(mysqli_num_rows($list_of_rooms_result) > 0) {

//     $data = [];

//     while($rooms = mysqli_fetch_assoc($list_of_rooms_result)) {
//         array_push($data, $rooms);
//     }

//     echo json_encode($data);

// }