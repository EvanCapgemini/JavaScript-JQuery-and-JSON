<?php
require_once "util.php";
ensureLoggedIn();
$pdo = pdoConnection();

header('Content-Type: application/json; charset=utf-8');
$term = $_GET['term'] ?? '';
$retval = array();
if ( strlen($term) > 0 ) {
    $stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix ORDER BY name LIMIT 15');
    $stmt->execute(array(':prefix' => $term."%"));
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        $retval[] = $row['name'];
    }
}
echo(json_encode($retval, JSON_PRETTY_PRINT));
