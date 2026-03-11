<?php
session_start();
$message = "" ; 
$message = "Hello " . $_SESSION["username"] . ", you love ". $_SESSION["fav"] . "!" ;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p style= "color:green;" ><?php echo $message; ?></p>
</body>
</html>