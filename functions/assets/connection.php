<?php

function connect_to_db() {
    static $conneciton;
    $server = 'localhost';
    $db_name = 'klir';
    $password = '';
    $username = 'root';

    $conneciton = mysqli_connect($server, $username, $password, $db_name);
    
    if(!$conneciton) {
        die("Connection failed" . mysqli_connect_error());
    } 
    
    return $conneciton;
}
