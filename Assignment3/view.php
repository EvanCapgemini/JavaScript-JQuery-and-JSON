<?php
require_once "util.php";
$pdo = pdoConnection();

if ( ! isset($_GET['profile_id']) ) { die('Missing profile_id'); }
$pid = $_GET['profile_id']+0;
$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid');
$stmt->execute([':pid'=>$pid]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ( ! $profile ) { die('Bad value for profile_id'); }

$positions = loadPos($pdo, $pid);
$education = loadEdu($pdo, $pid);
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
<h1>Profile Information</h1>
<p>First Name: <?= htmlentities($profile['first_name']) ?></p>
<p>Last Name: <?= htmlentities($profile['last_name']) ?></p>
<p>Email: <?= htmlentities($profile['email']) ?></p>
<p>Headline:<br/><?= htmlentities($profile['headline']) ?></p>
<p>Summary:<br/><?= htmlentities($profile['summary']) ?></p>

<?php if (count($education)>0): ?>
<p>Education</p>
<ul>
<?php foreach($education as $edu): ?>
  <li><?= htmlentities($edu['year'].' : '.$edu['name']) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (count($positions)>0): ?>
<p>Position</p>
<ul>
<?php foreach($positions as $pos): ?>
  <li><?= htmlentities($pos['year']) ?>: <?= htmlentities($pos['description']) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<p><a href="index.php">Done</a></p>
</div></body></html>
