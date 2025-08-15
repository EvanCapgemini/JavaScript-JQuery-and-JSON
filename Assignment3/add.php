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
ensureLoggedIn();

if ( isset($_POST['cancel']) ) { header("Location: index.php"); return; }

if ( isset($_POST['first_name']) ) {
    $msg = validateProfile();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: add.php"); return; }
    $msg = validatePos();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: add.php"); return; }
    $msg = validateEdu();
    if ( $msg !== true ) { $_SESSION['error'] = $msg; header("Location: add.php"); return; }

    $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute([
        ':uid'=>$_SESSION['user_id'],
        ':fn'=>$_POST['first_name'],
        ':ln'=>$_POST['last_name'],
        ':em'=>$_POST['email'],
        ':he'=>$_POST['headline'],
        ':su'=>$_POST['summary']
    ]);
    $profile_id = $pdo->lastInsertId();
    insertPositions($pdo, $profile_id);
    insertEducations($pdo, $profile_id);
    $_SESSION['success'] = "Profile added";
    header("Location: index.php"); return;
}
?>
<h1>Adding Profile for Evan Elijah Mendonsa</h1>
<?php flashMessages(); ?>
<form method="post">
<p>First Name: <input type="text" name="first_name" size="60"/></p>
<p>Last Name: <input type="text" name="last_name" size="60"/></p>
<p>Email: <input type="text" name="email" size="30"/></p>
<p>Headline:<br/><input type="text" name="headline" size="80"/></p>
<p>Summary:<br/><textarea name="summary" rows="8" cols="80"></textarea></p>

<p>Education: <input type="submit" id="addEdu" class="btn btn-default" value="+"></p>
<div id="education_fields"></div>

<p>Position: <input type="submit" id="addPos" class="btn btn-default" value="+"></p>
<div id="position_fields"></div>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
countPos = 0;
countEdu = 0;

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
});
</script>
</div></body></html>
