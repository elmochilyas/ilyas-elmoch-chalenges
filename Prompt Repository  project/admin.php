<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Access denied");
}

// get active users
$stmt = $pdo->query("
    SELECT users.username, COUNT(prompts.id) AS total_prompts
    FROM users
    LEFT JOIN prompts ON users.id = prompts.user_id
    GROUP BY users.id
    ORDER BY total_prompts DESC LIMIT 1
");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>

        <a href="admin_categories.php" class="nav-link">Manage Categories</a>

        <h3>Top Contributors</h3>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Total Prompts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="user-cell"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="count-cell"><?= $user['total_prompts'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>