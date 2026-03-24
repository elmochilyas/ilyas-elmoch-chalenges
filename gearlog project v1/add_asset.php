<?php
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial_number = $_POST['serial_number'];
    $device_name = $_POST['device_name'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $category_id = $_POST['category_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO assets (serial_number, device_name, price, status, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$serial_number, $device_name, $price, $status, $category_id]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$catStmt = $pdo->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Asset - GearLog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Add New Asset</h1>
            <a href="index.php" class="back-btn">Back to Dashboard</a>
        </header>

        <?php if ($message): ?>
            <div class="message error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="asset-form">
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" required>
            </div>

            <div class="form-group">
                <label for="device_name">Device Name</label>
                <input type="text" id="device_name" name="device_name" required>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Available">Available</option>
                    <option value="Deployed">Deployed</option>
                    <option value="Under Repair">Under Repair</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="submit-btn">Add Asset</button>
        </form>
    </div>
</body>
</html>