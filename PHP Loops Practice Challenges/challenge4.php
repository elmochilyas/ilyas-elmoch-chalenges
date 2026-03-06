<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $a = 1;
    $evNbr = 0;
    while ($a<=50){
        if ($a % 2 == 0){
            $evNbr += 1;
        }
        $a += 1;
    }
    echo "Total even numbers:" . $evNbr
    ?>
</body>
</html>