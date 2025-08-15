<?php
session_start();
require_once "pdo.php";
require_once "util.php";
flashMessages();
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<title>Evan Elijah Mendonsa</title>
</head>
<body>
<h1>Resume Registry</h1>
<?php if (isset($_SESSION['name'])): ?>
<p><a href="logout.php">Logout</a></p>
<p><a href="add.php">Add New Entry</a></p>
<?php else: ?>
<p><a href="login.php">Please log in</a></p>
<?php endif; ?>
<table border="1">
<tr><th>Name</th><th>Headline</th><th>Action</th></tr>
<?php
foreach ($rows as $row) {
    echo "<tr><td>";
    echo htmlentities($row['first_name'].' '.$row['last_name']);
    echo "</td><td>";
    echo htmlentities($row['headline']);
    echo "</td><td>";
    echo '<a href="view.php?profile_id='.$row['profile_id'].'">View</a>';
    if (isset($_SESSION['user_id'])) {
        echo ' / <a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>';
        echo ' / <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>';
    }
    echo "</td></tr>";
}
?>
</table>
</body>
</html>