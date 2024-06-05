<?php
// Database configuration
$servername = "localhost"; // Change this to your database servername if necessary
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "admin_login"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the charset and other configurations here
$conn->set_charset("utf8mb4"); // Example of setting charset to utf8mb4
