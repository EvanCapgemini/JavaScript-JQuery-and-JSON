<?php
session_start();
require_once "pdo.php";
$salt = 'XyZzy12*_';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $check = hash('md5', $salt . $_POST['pass']);
    $stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE email = :em AND password = :pw");
    $stmt->execute([':em' => $_POST['email'], ':pw' => $check]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        header("Location: index.php");
        return;
           $_SESSION['error'] = "Incorrect email or password";
        header("Location: login.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
    function doValidate() {
        console.log('Validating...');
        try {
            let pw = document.getElementById('id_1723').value;
            let em = document.getElementById('email').value;
            if (pw === "" || em === "") {
                alert("Both fields must be filled out");
                           }
            if (!em.includes('@')) {
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
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
?>
<form method="post">
    <p>Email: <input type="text" name="email" id="email"></p>
    <p>Password: <input type="password" name="pass" id="id_1723"></p>
    <p><input type="submit" onclick="return doValidate();" value="Log In"></p>
</form>
</body>
</html>
