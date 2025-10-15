<?php

    //database configuration
    $servern    = 'localhost';
    $user      = 'root';
    $pass       = '';
    $database   = 'your_restaurant_app_db';

    $connect = new mysqli($host, $user, $pass, $database);

    if (!$connect) {
        die ("connection failed: " . mysqli_connect_error());
    } else {
        $connect->set_charset('utf8mb4');
    }

    $ENABLE_RTL_MODE = 'false';

?>