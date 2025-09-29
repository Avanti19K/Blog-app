<?php
include 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_SESSION['username'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO blogs (image, title, description, author) VALUES ('$image', '$title', '$description', '$author')";
        if ($conn->query($sql) === TRUE) {
            header("Location: home.php");
            exit;
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Blog - Blog App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h3 class="mb-4">Create New Blog Post</h3>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Blog Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="mb-3">
            <textarea name="description" class="form-control" rows="5" placeholder="Description" required></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Create Blog</button>
    </form>
</div>

</body>
</html>
