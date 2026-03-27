<?php
session_start();
require 'db.php';

// 1. Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Check ID
if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

// 3. Fetch prompt
$stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch();

// 4. Ownership check
if (!$prompt || $prompt['user_id'] != $_SESSION['user_id']) {
    die("Unauthorized action");
}

// 5. Fetch categories (for dropdown)
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();


// =====================
//  POST → UPDATE
// =====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category_id = $_POST['category_id'];

    // validation
    if (empty($title) || empty($content) || empty($category_id)) {
        $error = "All fields are required";
    } else {
        $stmt = $pdo->prepare("
            UPDATE prompts 
            SET title = ?, content = ?, category_id = ?
            WHERE id = ?
        ");
        $stmt->execute([$title, $content, $category_id, $id]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prompt</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        .form-group input[type="text"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: block;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-back:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #ff6b6b;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="index.php" class="btn-back">← Back to Prompts</a>
    
    <div class="form-container">
        <h2>Edit Prompt</h2>

        <form method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" 
                    value="<?= htmlspecialchars($prompt['title']) ?>">
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content"><?= htmlspecialchars($prompt['content']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= $cat['id'] == $prompt['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-submit">Update Prompt</button>
        </form>

        <?php if (!empty($error)): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>