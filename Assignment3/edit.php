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

if ( isset($_POST['cancel']) ) {
    header("Location: index.php"); return;
}

if ( isset($_POST['first_name']) ) {
    $msg = validateProfile();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: edit.php?profile_id=".$pid); return; }
    $msg = validatePos();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: edit.php?profile_id=".$pid); return; }
    $msg = validateEdu();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: edit.php?profile_id=".$pid); return; }

    $stmt = $pdo->prepare('UPDATE Profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su WHERE profile_id=:pid AND user_id=:uid');
    $stmt->execute([
        ':fn'=>$_POST['first_name'], ':ln'=>$_POST['last_name'], ':em'=>$_POST['email'],
        ':he'=>$_POST['headline'], ':su'=>$_POST['summary'], ':pid'=>$pid, ':uid'=>$_SESSION['user_id']
    ]);

    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute([':pid'=>$pid]);
    $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
    $stmt->execute([':pid'=>$pid]);

    insertPositions($pdo, $pid);
    insertEducations($pdo, $pid);

    $_SESSION['success'] = "Profile updated";
    header("Location: index.php"); return;
}

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
</head>
<body><div class="container">
<h1>Editing Profile for Evan Elijah Mendonsa</h1>
<?php flashMessages(); ?>
<form method="post">
<p>First Name: <input type="text" name="first_name" size="60" value="<?= htmlentities($profile['first_name']) ?>"/></p>
<p>Last Name: <input type="text" name="last_name" size="60" value="<?= htmlentities($profile['last_name']) ?>"/></p>
<p>Email: <input type="text" name="email" size="30" value="<?= htmlentities($profile['email']) ?>"/></p>
<p>Headline:<br/><input type="text" name="headline" size="80" value="<?= htmlentities($profile['headline']) ?>"/></p>
<p>Summary:<br/><textarea name="summary" rows="8" cols="80"><?= htmlentities($profile['summary']) ?></textarea></p>

<p>Education: <input type="submit" id="addEdu" class="btn btn-default" value="+"></p>
<div id="education_fields">
<?php $countEdu = 0; foreach($education as $edu): $countEdu++; ?>
  <div id="edu<?= $countEdu ?>">
    <p>Year: <input type="text" name="edu_year<?= $countEdu ?>" value="<?= htmlentities($edu['year']) ?>"/>
    <input type="button" value="-" onclick="$('#edu<?= $countEdu ?>').remove();return false;"></p>
    <p>School: <input type="text" size="80" name="edu_school<?= $countEdu ?>" class="school" value="<?= htmlentities($edu['name']) ?>"/></p>
  </div>
<?php endforeach; ?>
</div>

<p>Position: <input type="submit" id="addPos" class="btn btn-default" value="+"></p>
<div id="position_fields">
<?php $countPos = 0; foreach($positions as $pos): $countPos++; ?>
  <div id="position<?= $countPos ?>">
    <p>Year: <input type="text" name="year<?= $countPos ?>" value="<?= htmlentities($pos['year']) ?>"/>
    <input type="button" value="-" onclick="$('#position<?= $countPos ?>').remove();return false;"></p>
    <textarea name="desc<?= $countPos ?>" rows="8" cols="80"><?= htmlentities($pos['description']) ?></textarea>
  </div>
<?php endforeach; ?>
</div>

<p>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
countPos = <?= isset($countPos)?$countPos:0 ?>;
countEdu = <?= isset($countEdu)?$countEdu:0 ?>;
$(document).ready(function() {
    $('#addPos').click(function(event){
        event.preventDefault();
        if (countPos >= 9) { alert("Maximum of nine position entries exceeded"); return; }
        countPos++;
        $('#position_fields').append(
            '<div id="position'+countPos+'">             <p>Year: <input type="text" name="year'+countPos+'" value="" />             <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"></p>             <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea></div>');
    });
    $('#addEdu').click(function(event){
        event.preventDefault();
        if (countEdu >= 9) { alert("Maximum of nine education entries exceeded"); return; }
        countEdu++;
        $('#education_fields').append(
            '<div id="edu'+countEdu+'">              <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" />              <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"></p>              <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value=""/></p>              </div>');
        $('.school').autocomplete({ source: "school.php" });
    });
    $('.school').autocomplete({ source: "school.php" });
});
</script>

</div></body></html>
