<?php
$servername = "localhost";
$username = "root";
$password = "zahid";
$dbname = "book_review_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
