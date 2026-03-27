<?php
session_start();
require 'db.php';

// protect admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

// ADD CATEGORY
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }
}

// DELETE CATEGORY
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
}

// FETCH categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Manage Categories</h2>

        <div class="form-container">
            <form method="post" class="add-form">
                <input type="text" name="name" placeholder="New category">
                <button type="submit">Add</button>
            </form>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td class="category-name"><?= htmlspecialchars($cat['name']) ?></td>
                        <td>
                            <a href="?delete=<?= $cat['id'] ?>" class="btn-delete" onclick="return confirm('Delete this category?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>