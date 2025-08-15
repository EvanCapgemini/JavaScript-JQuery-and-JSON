<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Evan Elijah Mendonsa</title>
  <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">
  <link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">
  <link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
  <script
    src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>
  <script
    src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
    integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
    crossorigin="anonymous"></script>
  <style>
    body { padding:20px; }
    .ui-autocomplete { z-index: 10000; }
  </style>
</head>
<body>
<div class="container">
<?php
require_once "util.php";
$pdo = pdoConnection();
?>
<h1>Resume Registry — Evan Elijah Mendonsa</h1>
<?php flashMessages(); ?>
<?php
$stmt = $pdo->query('SELECT profile_id, first_name, last_name, headline FROM Profile ORDER BY last_name, first_name');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ( count($rows) == 0 ) { echo("<p>No rows found</p>"); }
?>
<?php if (count($rows) > 0) : ?>
<table class="table table-striped table-bordered">
  <tr><th>Name</th><th>Headline</th><th>Action</th></tr>
  <?php foreach($rows as $row): ?>
  <tr>
    <td><a href="view.php?profile_id=<?= urlencode($row['profile_id']) ?>"><?= htmlentities($row['first_name'].' '.$row['last_name']) ?></a></td>
    <td><?= htmlentities($row['headline']) ?></td>
    <td>
      <?php if ( isset($_SESSION['user_id']) ): ?>
          <a href="edit.php?profile_id=<?= urlencode($row['profile_id']) ?>">Edit</a> / 
          <a href="delete.php?profile_id=<?= urlencode($row['profile_id']) ?>">Delete</a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>

<?php if ( ! isset($_SESSION['user_id']) ) : ?>
<p><a href="login.php">Please log in</a></p>
<?php else: ?>
<p><a href="add.php">Add New Entry</a> | <a href="logout.php">Logout</a></p>
<?php endif; ?>
</div></body></html>
