<?php
require_once("pdo.php");
ensureLoggedIn();
$pdo = pdoConnection();

if (!isset($_GET['profile_id']) && !isset($_POST['profile_id'])) {
    $_SESSION['error'] = 'Missing profile_id';
    header('Location: index.php');
    return;
}

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    return;
}

if (isset($_POST['first_name'])) {
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: edit.php?profile_id='.$_POST['profile_id']);
        return;
    }
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header('Location: edit.php?profile_id='.$_POST['profile_id']);
        return;
    }
    // Update profile
    $stmt = $pdo->prepare('UPDATE Profile SET
        first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su
        WHERE profile_id=:pid');
    $stmt->execute(array(
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':pid' => $_POST['profile_id']
    ));

    // Clear old positions & insert new
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array(':pid' => $_POST['profile_id']));
    insertPositions($pdo, $_POST['profile_id']);

    $_SESSION['success'] = 'Profile updated';
    header('Location: index.php');
    return;
}

// Load existing data
$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid');
$stmt->execute(array(':pid' => $_GET['profile_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}
$positions = loadPositions($pdo, $_GET['profile_id']);
function h($s){ return htmlentities($s); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Evan Elijah Mendonsa</title>
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
<style>
.container { max-width: 900px }
textarea { width: 100% }
.position-entry { margin-bottom: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 4px }
.sticky-buttons {
    position: sticky;
    bottom: 10px;
    background: white;
    padding: 10px 0;
}
</style>
</head>
<body>
<div class="container">
<h1>Editing Profile for Evan Elijah Mendonsa</h1>
<?php flashMessages(); ?>
<form method="post">
<input type="hidden" name="profile_id" value="<?= $profile['profile_id'] ?>" />
<p>First Name: <input type="text" name="first_name" size="60" value="<?= h($profile['first_name']) ?>"/></p>
<p>Last Name: <input type="text" name="last_name" size="60" value="<?= h($profile['last_name']) ?>"/></p>
<p>Email: <input type="text" name="email" size="30" value="<?= h($profile['email']) ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= h($profile['headline']) ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8"><?= h($profile['summary']) ?></textarea></p>

<p>Position: <input type="button" id="addPos" value="+"></p>
<div id="position_fields">
<?php
$rank = 0;
foreach ($positions as $pos) {
    $rank++;
    $y = htmlentities($pos['year']);
    $d = htmlentities($pos['description']);
    echo '<div class="position-entry" id="position'.$rank.'">
    <p>Year: <input type="text" name="year'.$rank.'" value="'.$y.'" />
    <input type="button" value="-" onclick="$(\'#position'.$rank.'\').remove(); return false;"></p>
    <textarea name="desc'.$rank.'" rows="8" cols="80">'.$d.'</textarea>
    </div>';
}
?>
</div>

<div class="sticky-buttons">
    <input type="submit" value="Save" class="btn btn-success"/>
    <input type="submit" name="cancel" value="Cancel" class="btn btn-secondary"/>
</div>
</form>

<script>
countPos = <?= $rank ?>;
$('#addPos').click(function(event) {
    event.preventDefault();
    if (countPos >= 9) {
        alert("Maximum of nine position entries exceeded");
        return;
    }
    countPos++;
    $('#position_fields').append(
        '<div class="position-entry" id="position'+countPos+'"> \
        <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
        <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove(); return false;"></p> \
        <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
        </div>'
    );
});
</script>
</div>
</body>
</html>
