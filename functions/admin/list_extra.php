<?php

session_start();

require('../assets/connection.php');

$db = connect_to_db();

if(isset($_GET["reference_no"])) {
    $reference_no = $_GET["reference_no"];
}

$extra_list_query = "SELECT * FROM billing_extras BE INNER JOIN extras E ON BE.expense_id = E.Id WHERE BE.reference_no=$reference_no";

// echo $extra_list_query;

$extra_list_result = mysqli_query($db, $extra_list_query);

if(mysqli_num_rows($extra_list_result) > 0) {

    $data = [];

    while($extra = mysqli_fetch_assoc($extra_list_result)) {
        array_push($data, $extra);
    }

    echo json_encode($data);

}