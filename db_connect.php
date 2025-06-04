<?php
$host = "localhost";
$user = "root";
$pass = "12345678";
$dbname = "voting_system1";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Set UTF-8 encoding for better character support
$conn->set_charset("utf8mb4");
?>
