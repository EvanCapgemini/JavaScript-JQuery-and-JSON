<?php
session_start();
require_once "pdo.php";

// If user is not logged in, deny access
if (!isset($_SESSION['user_id'])) {
    die("Not logged in");
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate all fields
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) ||
        empty($_POST['headline']) || empty($_POST['summary'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    // Validate email
    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = "Email address must contain @";
        header("Location: add.php");
        return;
    }

    // Insert into database
    $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );

    $_SESSION['success'] = "Profile added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evan Elijah Mendonsa</title>
</head>
<body>
<h1>Adding Profile for <?= htmlentities($_SESSION['name']) ?></h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>";
    unset($_SESSION['error']);
}
?>
<form method="post">
    <p>First Name:
    <input type="text" name="first_name" size="60"/></p>
    <p>Last Name:
    <input type="text" name="last_name" size="60"/></p>
    <p>Email:
    <input type="text" name="email" size="30"/></p>
    <p>Headline:<br/>
    <input type="text" name="headline" size="80"/></p>
    <p>Summary:<br/>
    <textarea name="summary" rows="8" cols="80"></textarea></p>
    <p>
    <input type="submit" value="Add"/>
    <a href="index.php">Cancel</a>
    </p>
</form>
</body>
</html>
