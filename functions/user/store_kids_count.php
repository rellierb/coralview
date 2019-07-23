<?php

session_start();

if(isset($_POST["kids_count"])) {

    $_SESSION["kids_count"] = $_POST["kids_count"];
    echo "success";

}



