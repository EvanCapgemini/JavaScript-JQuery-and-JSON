<?php
function pdoConnection() {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=education_study', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");
    return $pdo;
}
?>
