<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Prompt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    session_start();
    require "db.php";
    $stmt = $pdo->query("SELECT id, name FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $category_id = $_POST['category_id'];
        $user_id = $_SESSION['user_id'];

        if (empty($title) || empty($content) || empty($category_id)) {
            $error = "All fields are required";
        } else {
            $stmt = $pdo->prepare("INSERT INTO prompts (title, content, user_id, category_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $content, $user_id, $category_id]);

            header("Location: index.php");
            exit;
        }
    }
    ?>
    <div class="create-prompt-container">
        <h2>Create New Prompt</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" name="title" placeholder="Title" >
            </div>
            <div class="form-group">
                <textarea name="content" placeholder="Prompt content" ></textarea>
            </div>
            <div class="form-group">
                <select name="category_id" >
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Create Prompt</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>