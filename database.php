<?php
// Start session only if not already started
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Database configuration
$host = "localhost";
$user = "root";
$pass = "";   // set your MySQL root password here
$dbname = "abk"; // your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8mb4");
?>