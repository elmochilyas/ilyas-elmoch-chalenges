<?php
session_start();
require 'db.php';

// protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

// get search inputs
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';


// DYNAMIC QUERY
$sql = "
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
    WHERE 1
";

$params = [];

// search
if (!empty($search)) {
    $sql .= " AND (prompts.title LIKE ? OR prompts.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// category filter
if (!empty($category)) {
    $sql .= " AND prompts.category_id = ?";
    $params[] = $category;
}

$sql .= " ORDER BY prompts.id DESC";

// execute
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prompt Repository</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --danger: #ef4444;
            --danger-hover: #dc2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-danger {
            background: transparent;
            color: var(--danger);
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn-danger:hover {
            background: #fef2f2;
            color: var(--danger-hover);
        }

        .btn-edit {
            background: transparent;
            color: var(--primary);
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
            text-decoration: none;
        }

        .btn-edit:hover {
            background: #eef2ff;
        }

        .search-bar {
            background: var(--card-bg);
            padding: 1.25rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .search-form {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 200px;
            padding: 0.625rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-form input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-form select {
            padding: 0.625rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            min-width: 160px;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .search-form select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .search-form button {
            padding: 0.625rem 1.25rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-form button:hover {
            background: var(--primary-hover);
        }

        .clear-link {
            padding: 0.625rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .clear-link:hover {
            color: var(--text);
        }

        .prompts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .prompt-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .prompt-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .prompt-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            gap: 0.5rem;
        }

        .prompt-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.4;
        }

        .prompt-category {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: #eef2ff;
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
            white-space: nowrap;
        }

        .prompt-content {
            flex: 1;
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            max-height: 120px;
            overflow: hidden;
            position: relative;
        }

        .prompt-content::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: linear-gradient(transparent, #f8fafc);
        }

        .prompt-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .prompt-author {
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .prompt-author svg {
            width: 16px;
            height: 16px;
        }

        .prompt-actions {
            display: flex;
            gap: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .prompts-grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                flex-direction: column;
            }

            .search-form input,
            .search-form select,
            .search-form button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>All Prompts</h1>
        <a href="create_prompt.php" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Prompt
        </a>
    </div>

    <div class="search-bar">
        <form method="GET" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Search prompts..."
                value="<?= htmlspecialchars($search) ?>"
            >

            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>"
                        <?= $cat['id'] == $category ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Search</button>

            <?php if ($search || $category): ?>
                <a href="index.php" class="clear-link">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($prompts)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p>No prompts found.</p>
            <a href="create_prompt.php" class="btn btn-primary">Create your first prompt</a>
        </div>
    <?php else: ?>

        <div class="prompts-grid">
            <?php foreach ($prompts as $prompt): ?>
                <div class="prompt-card">
                    <div class="prompt-card-header">
                        <h3><?= htmlspecialchars($prompt['title']) ?></h3>
                        <span class="prompt-category"><?= htmlspecialchars($prompt['category_name']) ?></span>
                    </div>
                    <div class="prompt-content">
                        <?= htmlspecialchars($prompt['content']) ?>
                    </div>
                    <div class="prompt-meta">
                        <div class="prompt-author">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <?= htmlspecialchars($prompt['username']) ?>
                        </div>
                        <?php if ($prompt['user_id'] == $_SESSION['user_id']): ?>
                            <div class="prompt-actions">
                                <a href="edit.php?id=<?= $prompt['id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="delete.php?id=<?= $prompt['id'] ?>" 
                                   onclick="return confirm('Are you sure?')" 
                                   class="btn btn-danger">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>