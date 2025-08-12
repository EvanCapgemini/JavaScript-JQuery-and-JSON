<?php
require_once "pdo.php";

if (!isset($_GET['profile_id'])) {
    die("Missing profile_id");
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(":pid" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    die("Profile not found");
}
?>

<h2>Profile Details</h2>
<p>First Name: <?= htmlentities($row['first_name']) ?></p>
<p>Last Name: <?= htmlentities($row['last_name']) ?></p>
<p>Email: <?= htmlentities($row['email']) ?></p>
<p>Headline: <?= htmlentities($row['headline']) ?></p>
<p>Summary:<br><?= htmlentities($row['summary']) ?></p>
