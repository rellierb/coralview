<?php

session_start();

if(isset($_POST["room_reserved"])) {
    unset($_SESSION["rooms_reserved"]);
    $_SESSION["rooms_reserved"] = $_POST["room_reserved"];
    echo "success";
}



