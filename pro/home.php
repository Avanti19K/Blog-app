<?php
include 'db.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Blog App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">My Blog</a>
        <div class="d-flex">
            <a href="create_blog.php" class="btn btn-light me-2">Create Blog</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h2 class="mb-4">Welcome, <?= $_SESSION['username'] ?></h2>

    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM blogs ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="uploads/<?= $row['image'] ?>" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                        <p class="text-muted small">By: <?= htmlspecialchars($row['author']) ?></p>
                        <?php if ($_SESSION['username'] == $row['author']): ?>
                            <a href="edit_blog.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_blog.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
