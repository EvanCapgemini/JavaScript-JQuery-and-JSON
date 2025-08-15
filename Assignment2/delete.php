<?php
require_once("pdo.php");
ensureLoggedIn();
$pdo = pdoConnection();

if ( ! isset($_GET['profile_id']) && ! isset($_POST['profile_id']) ) {
    $_SESSION['error'] = 'Missing profile_id';
    header('Location: index.php');
    return;
}

if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $stmt = $pdo->prepare('DELETE FROM Profile WHERE profile_id = :pid');
    $stmt->execute(array(':pid'=>$_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
    header('Location: index.php');
    return;
}

// Load to show
$stmt = $pdo->prepare('SELECT first_name, last_name FROM Profile WHERE profile_id=:pid');
$stmt->execute(array(':pid'=>$_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}

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
<h1>Delete Profile</h1>
HTML;
echo "<p>Name: ".htmlentities($row['first_name'].' '.$row['last_name'])."</p>";
$pid = $_GET['profile_id'];
echo <<<HTML
<form method="post">
<input type="hidden" name="profile_id" value="{$pid}"/>
<input type="submit" value="Delete" name="delete"/>
<input type="submit" name="cancel" value="Cancel"/>
</form>
<p><a href="index.php">Cancel</a></p>
</div>
</body>
</html>
HTML;
?>
