<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    function multiplyNumbers($a, $b){
        if (is_numeric($a) && is_numeric($b)){ return $a * $b ;}
        else {return "Error: Invalid Input.";}
    }
    echo multiplyNumbers(5,20) . "<br>";
    echo multiplyNumbers(5,"apple");
    ?>
</body>
</html>