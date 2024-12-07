<?php
$conn = new mysqli("localhost", "root", "", "examin");
if ($conn->connect_error) {
    die("Database not connected: " . $conn->connect_error);
}
?>