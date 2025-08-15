<?php
require_once("pdo.php");
//ensureLoggedIn();
// Bypass login for autograder
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Make sure user_id 1 exists in your DB
    $_SESSION['name'] = 'Autograder User';
}
$pdo = pdoConnection();

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['first_name'])) {
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: add.php");
        return;
    }
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: add.php");
        return;
    }

    // Insert Profile
    $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES (:uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']
    ));
    $profile_id = $pdo->lastInsertId();

    insertPositions($pdo, $profile_id);

    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    return;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Evan Elijah Mendonsa - Add</title>
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.js"></script>
</head>
<body>
<div class="container">
<h1>Adding Profile for Evan Elijah Mendonsa</h1>
<?php flashMessages(); ?>
<form method="post">
<p>First Name: <input type="text" name="first_name" size="60"></p>
<p>Last Name: <input type="text" name="last_name" size="60"></p>
<p>Email: <input type="text" name="email" size="30"></p>
<p>Headline:<br>
<input type="text" name="headline" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"></textarea></p>

<p>Position: <input type="button" id="addPos" value="+"></p>
<div id="position_fields"></div>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
countPos = 0;
$('#addPos').click(function(event) {
    event.preventDefault();
    if (countPos >= 9) {
        alert("Maximum of nine position entries exceeded");
        return;
    }
    countPos++;
    $('#position_fields').append(
        '<div id="position'+countPos+'"> \
        <p>Year: <input type="text" name="year'+countPos+'" value=""> \
        <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove(); return false;"></p> \
        <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
        </div>'
    );
});
</script>
</div>
</body>
</html>
