<?php
session_start();

function pdoConnection() {
    // XAMPP defaults: username 'root', empty password, database 'misc'
    $host = 'localhost';
    $dbname = 'resume_upg';
    $user = 'root';
    $pass = '';
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function flashMessages() {
    if ( isset($_SESSION['error']) ) {
        echo('<p style="color:red">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
    if ( isset($_SESSION['success']) ) {
        echo('<p style="color:green">'.htmlentities($_SESSION['success'])."</p>\n");
        unset($_SESSION['success']);
    }
}

function ensureLoggedIn() {
    if ( ! isset($_SESSION['user_id']) ) {
        die("ACCESS DENIED");
    }
}

function validateProfile() {
    if ( ! isset($_POST['first_name']) || ! isset($_POST['last_name']) ||
         ! isset($_POST['email']) || ! isset($_POST['headline']) ||
         ! isset($_POST['summary']) ) {
        return "All fields are required";
    }
    $fn = trim($_POST['first_name']);
    $ln = trim($_POST['last_name']);
    $em = trim($_POST['email']);
    $he = trim($_POST['headline']);
    $su = trim($_POST['summary']);
    if ( strlen($fn) == 0 || strlen($ln) == 0 || strlen($em) == 0 ||
         strlen($he) == 0 || strlen($su) == 0 ) {
        return "All fields are required";
    }
    if ( strpos($em,'@') === false ) {
        return "Email address must contain @";
    }
    return true;
}

function validatePos() {
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) && ! isset($_POST['desc'.$i]) ) continue;
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = trim($_POST['year'.$i]);
        $desc = trim($_POST['desc'.$i]);
        if ( strlen($year) == 0 || strlen($desc) == 0 ) {
            return "All fields are required";
        }
        if ( ! is_numeric($year) ) {
            return "Year must be numeric";
        }
    }
    return true;
}

function loadPositions($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute(array(':pid'=>$profile_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertPositions($pdo, $profile_id) {
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = trim($_POST['year'.$i]);
        $desc = trim($_POST['desc'.$i]);
        if ( strlen($year) == 0 || strlen($desc) == 0 ) continue;
        $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description)
            VALUES (:pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid'=>$profile_id,
            ':rank'=>$rank,
            ':year'=>$year,
            ':desc'=>$desc
        ));
        $rank++;
    }
}
?>
