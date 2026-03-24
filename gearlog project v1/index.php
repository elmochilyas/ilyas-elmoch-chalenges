<?php
require 'db.php';

$search = $_GET['search'] ?? '';
$totalStmt = $pdo->query("SELECT SUM(price) as total FROM assets");
$total = $totalStmt->fetch()['total'] ?? 0;

if ($search) {
    $stmt = $pdo->prepare("SELECT a.*, c.name as category_name 
                           FROM assets a 
                           INNER JOIN categories c ON a.category_id = c.id 
                           WHERE a.device_name LIKE ? OR a.serial_number LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT a.*, c.name as category_name 
                        FROM assets a 
                        INNER JOIN categories c ON a.category_id = c.id");
}
$assets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearLog - Asset Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>GearLog Asset Management</h1>
            <div class="total-value">Total Inventory Value: $<?= number_format($total, 2) ?></div>
        </header>

        <div class="toolbar">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Search by name or serial number..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
                <?php if ($search): ?>
                    <a href="index.php" class="clear-btn">Clear</a>
                <?php endif; ?>
            </form>
            <a href="add_asset.php" class="add-btn">Add New Asset</a>
        </div>

        <table class="asset-table">
            <thead>
                <tr>
                    <th>Device Name</th>
                    <th>Serial Number</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $asset): ?>
                <tr>
                    <td><?= htmlspecialchars($asset['device_name']) ?></td>
                    <td><?= htmlspecialchars($asset['serial_number']) ?></td>
                    <td><?= htmlspecialchars($asset['category_name']) ?></td>
                    <td>$<?= number_format($asset['price'], 2) ?></td>
                    <td><span class="status status-<?= strtolower(str_replace(' ', '-', $asset['status'])) ?>"><?= htmlspecialchars($asset['status']) ?></span></td>
                    <td>
                        <a href="edit_asset.php?id=<?= $asset['id'] ?>" class="edit-btn">Edit</a>
                        <a href="delete_asset.php?id=<?= $asset['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($assets)): ?>
                <tr><td colspan="6">No assets found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>