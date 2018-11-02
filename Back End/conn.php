<?php
//$servername = "localhost";
$servername = "busalot.cii0wtdaiw0c.us-east-1.rds.amazonaws.com";
$username = "root";
//$password = "1234";
$password = "bus123alot";
$dbname = "busalot";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
