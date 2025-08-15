<?php
require_once "util.php";
$pdo = pdoConnection();

$salt = 'XyZzy12*_';
if ( isset($_POST['cancel']) ) {
    header("Location: index.php");
    return;
}

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    }
    if ( strpos($_POST['email'], '@') === false ) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }
    $stmt = $pdo->prepare("SELECT user_id, name, password FROM users WHERE email = :em");
    $stmt->execute([":em"=>$_POST['email']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $check = hash('md5', $salt.$_POST['pass']);
    if ( $row && $check == $row['password'] ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['success'] = "Logged in.";
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}
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
<h1>Please Log In</h1>
<?php flashMessages(); ?>
<form method="POST">
  <label for="email">Email</label>
  <input type="text" name="email" id="email"><br/>
  <label for="pass">Password</label>
  <input type="password" name="pass" id="pass"><br/>
  <input type="submit" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
  <a href="add.php">Add New Entry</a>
</form>
<p>Try the seeded user: <b>evan@umich.edu</b> / <b>php123</b></p>
</div></body></html>
