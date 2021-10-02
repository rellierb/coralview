<?php

session_start();

if(isset($_POST["adult_count"])) {

    $_SESSION["adult_count"] = $_POST["adult_count"];
    echo "success";

}



