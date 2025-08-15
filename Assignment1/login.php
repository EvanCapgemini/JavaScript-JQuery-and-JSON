<?php
session_start();
require_once "pdo.php";
require_once "util.php";
$salt = 'XyZzy12*_';
if (isset($_POST['email']) && isset($_POST['pass'])) {
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Evan Elijah Mendonsa</title>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        var addr = document.getElementById('email').value;
        var pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if (addr.indexOf('@') == -1) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
}
</script>
</head>
<body>
<h1>Please Log In</h1>
<?php flashMessages(); ?>
<form method="POST" onsubmit="return doValidate();">
<p>Email <input type="text" name="email" id="email"></p>
<p>Password <input type="password" name="pass" id="id_1723"></p>
<p><input type="submit" value="Log In"/></p>
</form>
</body>
</html>