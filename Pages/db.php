<?php
$servername = "localhost";
$username = "root";
$password = "";

    $conn = new mysqli($servername, $username, $password,'e-voting_system_db');

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
