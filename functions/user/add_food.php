<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_POST["reference_no"])) {
    $reference_no = $_POST["reference_no"];
}

if(isset($_POST["subject"])) {
    $subject = $_POST["subject"];
}

if(isset($_POST["description"])) {
    $description = $_POST["description"];
}

$insert_food_query = "INSERT INTO food(reference_no, subject, description) VALUES ($reference_no, $subject, $description)";
$insert_food_result = mysqli_query($db, $insert_food_query);

echo $insert_food_query;

if($insert_food_result) {

    echo 'SUCCESS';

} else {

    echo 'FAILED';

}



