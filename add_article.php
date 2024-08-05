<?php
session_start();
require_once 'db_connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $category = htmlspecialchars($_POST['category']);
    $slug = htmlspecialchars($_POST['slug']);
    
    $sql = "INSERT INTO articles (title, description, category, slug) VALUES ('$title', '$description', '$category', '$slug')";
    
    if ($conn->query($sql)) {
        $_SESSION['message'] = 'Article created successfully';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error creating article: ' . $conn->error;
        $_SESSION['message_type'] = 'danger';
    }
    
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

    <?php include("navbar.php"); ?>

    <div class="container content">
        <h2><i class="fas fa-plus-circle me-2"></i>Add New Article</h2>
        <form action="add_article.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="Food">Food</option>
                    <option value="Education">Education</option>
                    <option value="Business">Business</option>
                    <option value="Position">Position</option>
                </select>
            </div>
            <button type="submit" class="btn btn-custom"><i class="fas fa-save me-2"></i>Add Article</button>
            <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to List</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
</body>
</html>