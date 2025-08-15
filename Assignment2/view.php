<?php
require_once("pdo.php");
$pdo = pdoConnection();

if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = 'Missing profile_id';
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid');
$stmt->execute(array(':pid'=>$_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}
$positions = loadPositions($pdo, $_GET['profile_id']);

function h($s){ return htmlentities($s); }

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Evan Elijah Mendonsa</title>
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">
</head>
<body>
<div class="container">
<h1>Profile Information</h1>
HTML;

echo "<p>First Name: ".h($profile['first_name'])."</p>";
echo "<p>Last Name: ".h($profile['last_name'])."</p>";
echo "<p>Email: ".h($profile['email'])."</p>";
echo "<p>Headline:<br/>".h($profile['headline'])."</p>";
echo "<p>Summary:<br/>".nl2br(h($profile['summary']))."</p>";

if ( count($positions) > 0 ) {
    echo "<p>Position:</p><ul>";
    foreach($positions as $pos) {
        echo "<li>".htmlentities($pos['year']).": ".htmlentities($pos['description'])."</li>";
    }
    echo "</ul>";
}

echo '<p><a href="index.php">Done</a></p>';
echo "</div></body></html>";
?>
