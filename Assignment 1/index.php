<?php
session_start();
require_once "pdo.php";
require_once "util.php";

flashMessages();

echo "<!DOCTYPE html><html><head><title>Profile Index</title></head><body>";
echo "<h1>Resume Registry</h1>";

if (isset($_SESSION['name'])) {
    echo '<p><a href="logout.php">Logout</a></p>';
} else {
    echo '<p><a href="login.php">Please log in</a></p>';
}

$stmt = $pdo->query("SELECT profile_id, first_name, last_name FROM Profile");
echo "<table border='1'>";
echo "<tr><th>Name</th><th>Action</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>";
    echo "<a href='view.php?profile_id=" . $row['profile_id'] . "'>";
    echo htmlentities($row['first_name'] . " " . $row['last_name']);
    echo "</a></td><td>";
    if (isset($_SESSION['user_id'])) {
        echo "<a href='edit.php?profile_id=" . $row['profile_id'] . "'>Edit</a> / ";
        echo "<a href='delete.php?profile_id=" . $row['profile_id'] . "'>Delete</a>";
    }
    echo "</td></tr>";
}
echo "</table>";

if (isset($_SESSION['user_id'])) {
    echo '<p><a href="add.php">Add New Entry</a></p>';
}

echo "</body></html>";
?>
