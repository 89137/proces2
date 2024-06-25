<?php
$servername = "localhost:3306";
$username = "89137";
$password = "ditiseenww";
$dbname = "db_89137";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
