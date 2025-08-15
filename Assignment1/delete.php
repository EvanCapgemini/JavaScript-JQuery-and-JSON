<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name FROM Profile WHERE profile_id = :pid AND user_id = :uid");
$stmt->execute(array(":pid" => $_GET['profile_id'], ":uid" => $_SESSION['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = "Could not find profile or you do not have permission";
    header("Location: index.php");
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("DELETE FROM Profile WHERE profile_id = :pid AND user_id = :uid");
    $stmt->execute(array(":pid" => $_GET['profile_id'], ":uid" => $_SESSION['user_id']));
    $_SESSION['success'] = "Profile deleted";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evan Elijah Mendonsa</title>
</head>
<body>
    <h1>Confirm Deletion</h1>
    <p>Are you sure you want to delete the profile of <?= htmlentities($row['first_name']) ?> <?= htmlentities($row['last_name']) ?>?</p>
    <form method="post">
        <input type="submit" value="Delete">
        <a href="index.php">Cancel</a>
    </form>
</body>
</html>
