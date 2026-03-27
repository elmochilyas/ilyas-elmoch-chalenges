<?php
session_start();
require 'db.php';

// 1. Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. Check if ID exists
if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

// 3. Fetch prompt
$stmt = $pdo->prepare("SELECT * FROM prompts WHERE id = ?");
$stmt->execute([$id]);
$prompt = $stmt->fetch();

// 4. Check if prompt exists AND belongs to user
if (!$prompt || $prompt['user_id'] != $_SESSION['user_id']) {
    die("Unauthorized action");
}

// 5. Delete prompt
$stmt = $pdo->prepare("DELETE FROM prompts WHERE id = ?");
$stmt->execute([$id]);

// 6. Redirect
header("Location: index.php");
exit;