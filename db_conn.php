<?php
    include 'config/config.php';
    $conn = mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], $db_config['database_name']);

    if (!$conn){
        echo "Connection failed!";
    }