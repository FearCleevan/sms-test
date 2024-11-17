<?php

$host = "localhost";       
$user = "root";            
$password = "";            
$dbname = "samson_management_system";

// Create the database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
