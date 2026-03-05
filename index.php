<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $nbrTea = 5 ;
        $studentOrNot = "notStudent";
        $priceTea = 20 ;
        if ($studentOrNot == "student" && $nbrTea >= 5) {
            $totalPrice = ($nbrTea * $priceTea) * 0.8 - (1 * $nbrTea);
        }
        elseif ($studentOrNot == "student" && $nbrTea < 5){
            $totalPrice = ($nbrTea * $priceTea) * 0.8 ;
        }
        elseif ($studentOrNot == "notStudent" && $nbrTea >= 5){
            $totalPrice = ($nbrTea * $priceTea) - (1 * $nbrTea);
        }
        else {
            $totalPrice = ($nbrTea * $priceTea);
        }
        echo "this is the total price of your command : " . $totalPrice . "DH" ;
    ?>  
</body>
</html>     