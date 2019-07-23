<?php

session_start();

if(isset($_POST["room_reserved"])) {

    $_SESSION["rooms_reserved"] = $_POST["room_reserved"];
    echo "success";

}



