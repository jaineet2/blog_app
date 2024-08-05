<?php
session_start();
require_once 'db_connect.php';

function fetchArticles($conn, $search = '', $sort = 'DESC', $category = '', $date = '') {
    $sql = "SELECT * FROM articles WHERE (title LIKE '%$search%' OR description LIKE '%$search%')";

    if ($category) {
        $sql .= " AND category = '$category'";
    }

    if ($date) {
        $sql .= " AND DATE(created_at) = '$date'";
    }

    $sql .= " ORDER BY created_at $sort";

    $result = $conn->query($sql);
    $articles = $result->fetch_all(MYSQLI_ASSOC);

    return $articles;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_articles'])) {
    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    $sort = isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : 'DESC';
    $category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '';
    $date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';

    $articles = fetchArticles($conn, $search, $sort, $category, $date);
    echo json_encode($articles);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_article'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM articles WHERE id = $id";
    $success = $conn->query($sql);
    
    echo json_encode(['success' => $success, 'message' => $success ? 'Article deleted successfully' : 'Error deleting article']);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sleek Blog Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

    <?php include("navbar.php"); ?>

    <div class="container content">
        <div class="row filter-row align-items-end">
            <div class="col-md-3 mb-3">
                <label for="searchInput" class="form-label">Search</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search articles">
            </div>
            <div class="col-md-2 mb-3">
                <label for="sortSelect" class="form-label">Sort</label>
                <select id="sortSelect" class="form-select">
                    <option value="DESC">Newest first</option>
                    <option value="ASC">Oldest first</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="categorySelect" class="form-label">Category</label>
                <select id="categorySelect" class="form-select">
                    <option value="">All Categories</option>
                    <option value="Food">Food</option>
                    <option value="Education">Education</option>
                    <option value="Business">Business</option>
                    <option value="Position">Position</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="dateInput" class="form-label">Date</label>
                <input type="date" id="dateInput" class="form-control">
            </div>
            <div class="col-md-3 mb-3 text-end">
                <a href="add_article.php" class="btn btn-custom"><i class="fas fa-plus me-2"></i>Add Article</a>
            </div>
        </div>

        <div id="alertContainer"></div>
        <div id="articleList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this article?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>