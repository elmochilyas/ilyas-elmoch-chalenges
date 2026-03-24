<?php
require 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM assets WHERE id = ?");
$stmt->execute([$_GET['id']]);

header("Location: index.php");
exit;