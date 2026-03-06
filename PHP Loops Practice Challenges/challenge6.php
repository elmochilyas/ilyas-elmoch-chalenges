<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $line = 1;
    while ($line<=5){
        $nbr = 1;
        while ($nbr<=$line){
            echo "*" ;
            $nbr += 1;
        }
        $line += 1;
        echo "<br>";    
    }
    ?>
</body>
</html>