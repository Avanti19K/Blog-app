<?php
include 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM blogs WHERE id=$id");
if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger text-center'>Blog post not found.</div>";
    exit;
}
$row = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Check if new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $imageUpdate = ", image='$image'";
    } else {
        $imageUpdate = "";
    }

    $sql = "UPDATE blogs SET title='$title', description='$description' $imageUpdate WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: home.php");
        exit;
    } else {
        $error = "Error updating blog: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog - Blog App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h3 class="mb-4">Edit Blog Post</h3>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Blog Image" class="img-thumbnail mb-3" style="width: 200px; height: 150px; object-fit: cover;">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Image</label>
            <input type="file" name="image" class="form-control">
            <div class="form-text">Leave empty to keep the current image.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($row['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($row['description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Blog</button>
    </form>

    <div class="mt-3 text-center">
        <a href="home.php" class="btn btn-secondary">Back to Home</a>
    </div>
</div>

</body>
</html>
