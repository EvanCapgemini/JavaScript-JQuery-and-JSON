<?php
require_once "util.php";
$pdo = pdoConnection();
ensureLoggedIn();

if ( ! isset($_GET['profile_id']) ) { die('Missing profile_id'); }
$pid = $_GET['profile_id']+0;

$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid');
$stmt->execute([':pid'=>$pid, ':uid'=>$_SESSION['user_id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ( ! $profile ) { die('ACCESS DENIED'); }

if ( isset($_POST['delete']) ) {
    $stmt = $pdo->prepare('DELETE FROM Profile WHERE profile_id = :pid AND user_id=:uid');
    $stmt->execute([':pid'=>$pid, ':uid'=>$_SESSION['user_id']]);
    $_SESSION['success'] = "Profile deleted";
    header("Location: index.php"); return;
}
if ( isset($_POST['cancel']) ) {
    header("Location: index.php"); return;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Evan Elijah Mendonsa</title>
  <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">
</head>
<body><div class="container">
<h1>Confirm: Deleting Profile</h1>
<p><?= htmlentities($profile['first_name'].' '.$profile['last_name']) ?></p>
<form method="post">
  <input type="submit" name="delete" value="Delete">
  <input type="submit" name="cancel" value="Cancel">
</form>
</div></body></html>
