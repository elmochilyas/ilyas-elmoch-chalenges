<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $_SESSION["fav"] = $_POST["fav"];
    header("Location: page3.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method = "POST" >
        <label for = "fav">Enter Your Favorite Programming Language : </label>
        <input type = "text" name = "fav">
        <input type = "submit">
    </form>
</body>
</html>