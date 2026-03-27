<?php
session_start();
require 'db.php';

// protect page (optional depending on your design)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ALWAYS fetch prompts with JOIN (not only on POST)
$stmt = $pdo->query("
    SELECT 
        prompts.id,
        prompts.title,
        prompts.content,
        prompts.user_id,
        users.username,
        categories.name AS category_name
    FROM prompts
    INNER JOIN users ON prompts.user_id = users.id
    INNER JOIN categories ON prompts.category_id = categories.id
    ORDER BY prompts.id DESC
");

$prompts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Prompts</title>
    <a href="create_prompt.php" class="btn-add">➕ Add Prompt</a>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>All Prompts</h2>
    <?php if (empty($prompts)): ?>
        <p class="no-prompts">No prompts found.</p>
    <?php else: ?>
        <table class="prompts-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                 <?php foreach ($prompts as $prompt): ?>
                    <tr>
                        <td class="title-cell"><?= htmlspecialchars($prompt['title']) ?></td>
                        <td class="content-cell"><?= htmlspecialchars($prompt['content']) ?></td>
                        <td class="meta-cell"><?= htmlspecialchars($prompt['username']) ?></td>
                        <td class="meta-cell">
                            <span class="badge"><?= htmlspecialchars($prompt['category_name']) ?></span>
                        </td>

                        <td class="actions-cell">
                            <a href="edit.php?id=<?= $prompt['id'] ?>">Edit</a>
                            <a href="delete.php?id=<?= $prompt['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>