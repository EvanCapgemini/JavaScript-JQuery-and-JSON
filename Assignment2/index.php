<?php
require_once("pdo.php");
$pdo = pdoConnection();
echo "<?php\n// Common head snippet included by pages\n?>\n<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n<meta charset=\"utf-8\">\n<title>Evan Elijah Mendonsa</title>\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n<link rel=\"stylesheet\" \n    href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css\" \n    integrity=\"sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7\" \n    crossorigin=\"anonymous\">\n<link rel=\"stylesheet\" \n    href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css\" \n    integrity=\"sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r\" \n    crossorigin=\"anonymous\">\n<script\n  src=\"https://code.jquery.com/jquery-3.2.1.js\"\n  integrity=\"sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=\"\n  crossorigin=\"anonymous\"></script>\n<style>\n.container{max-width:900px}\ntextarea{width:100%}\n.position-entry{margin-bottom:10px;padding:10px;border:1px solid #ddd;border-radius:4px}\n</style>\n</head>\n<body>\n<div class=\"container\">\n";
echo "<h1>Resume Registry for Evan Elijah Mendonsa</h1>";
flashMessages();

if ( isset($_SESSION['name']) ) {
    echo '<p><a href="add.php">Add New Entry</a> | <a href="logout.php">Logout</a></p>';
} else {
    echo '<p><a href="login.php">Please log in</a></p>';
}

// Fetch profiles
$stmt = $pdo->query('SELECT profile_id, first_name, last_name, headline FROM Profile ORDER BY last_name, first_name');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ( count($rows) == 0 ) {
    echo "<p>No rows found</p>";
} else {
    echo '<table class="table table-striped"><thead><tr><th>Name</th><th>Headline</th><th>Action</th></tr></thead><tbody>';
    foreach($rows as $row) {
        $name = htmlentities($row['first_name'].' '.$row['last_name']);
        $headline = htmlentities($row['headline']);
        $pid = $row['profile_id'];
        echo "<tr><td>$name</td><td>$headline</td><td>";
        echo '<a href="view.php?profile_id='.$pid.'">View</a>';
        if ( isset($_SESSION['user_id']) ) {
            echo ' | <a href="edit.php?profile_id='.$pid.'">Edit</a> | <a href="delete.php?profile_id='.$pid.'">Delete</a>';
        }
        echo "</td></tr>";
    }
    echo '</tbody></table>';
}

echo "\n</div>\n</body>\n</html>\n";
?>
