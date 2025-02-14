<?php
$host = 'localhost';
$username = 'root';  // or your MySQL username
$password = '';      // or your MySQL password
$dbname = 'business_blog';

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
