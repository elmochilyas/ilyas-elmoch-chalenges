<?php
require 'db.php';
$minPrice = 20;
$sql = "SELECT * FROM books WHERE price > :price";

?>