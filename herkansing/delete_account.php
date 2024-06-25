<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$conn->query("DELETE FROM photos WHERE user_id = $user_id");
$conn->query("DELETE FROM users WHERE id = $user_id");

session_destroy();
header("Location: register.php");
?>
