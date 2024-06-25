<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['photo'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    $photo = $_FILES['photo'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($photo["name"]);
    move_uploaded_file($photo["tmp_name"], $target_file);

    $stmt = $conn->prepare("INSERT INTO photos (user_id, title, description, path, is_public) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $user_id, $title, $description, $target_file, $is_public);
    $stmt->execute();
}

$result = $conn->query("SELECT * FROM photos WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h1>Your Photos</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" required placeholder="Title">
    <textarea name="description" placeholder="Description"></textarea>
    <input type="file" name="photo" required>
    <label>
        <input type="checkbox" name="is_public"> Make public
    </label>
    <button type="submit">Upload</button>
</form>

<h2>Your Uploaded Photos</h2>
<?php while ($row = $result->fetch_assoc()): ?>
    <div>
        <img src="<?php echo $row['path']; ?>" width="100">
        <p><?php echo $row['title']; ?></p>
        <p><?php echo $row['description']; ?></p>
        <p><?php echo $row['is_public'] ? 'Public' : 'Private'; ?></p>
    </div>
<?php endwhile; ?>
</body>
</html>
