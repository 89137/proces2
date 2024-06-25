<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="user_data.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Username']);

$user_result = $conn->query("SELECT id, username FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();
fputcsv($output, $user);

fputcsv($output, []);
fputcsv($output, ['Photo ID', 'Title', 'Description', 'Path', 'Is Public']);

$photo_result = $conn->query("SELECT id, title, description, path, is_public FROM photos WHERE user_id = $user_id");
while ($photo = $photo_result->fetch_assoc()) {
    fputcsv($output, $photo);
}

fclose($output);
?>
