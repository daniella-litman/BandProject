<?php
$servername = "localhost";
$username = "daniella_band";
$password = "kawaiibooks";
$dbname = "daniella_band";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>