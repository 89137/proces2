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

    header("Location: dashboard.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$result = $conn->query("SELECT * FROM photos WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Your Photos</h1>
    <a href="dashboard.php?logout=true" class="logout-button">Logout</a>
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
    <div class="photo-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="photo-item">
            <img src="<?php echo $row['path']; ?>" alt="<?php echo $row['title']; ?>">
            <div class="photo-info">
                <h3><?php echo $row['title']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p><?php echo $row['is_public'] ? 'Public' : 'Private'; ?></p>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</body>
</html>
