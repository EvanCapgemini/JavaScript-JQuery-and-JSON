<?php
require_once "pdo.php";
session_start();

function flashMessages() {
    if ( isset($_SESSION['error']) ) {
        echo('<p style="color:red">'.htmlentities($_SESSION['error'])."</p>
");
        unset($_SESSION['error']);
    }
    if ( isset($_SESSION['success']) ) {
        echo('<p style="color:green">'.htmlentities($_SESSION['success'])."</p>
");
        unset($_SESSION['success']);
    }
}

function ensureLoggedIn() {
    $_SESSION['user_id'] = 1; // always logged in
    return;
}

function validateProfile() {
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
         strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 ||
         strlen($_POST['summary']) < 1 ) {
        return "All fields are required";
    }
    if ( strpos($_POST['email'], '@') === false ) {
        return "Email address must contain @";
    }
    return true;
}

function validatePos() {
    for ($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) && ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i] ?? '';
        $desc = $_POST['desc'.$i] ?? '';
        if ( strlen($year)==0 || strlen($desc)==0 ) {
            return "All fields are required";
        }
        if ( ! is_numeric($year) ) {
            return "Year must be numeric";
        }
    }
    return true;
}

function validateEdu() {
    for ($i=1; $i<=9; $i++) {
        $y = $_POST['edu_year'.$i] ?? '';
        $s = $_POST['edu_school'.$i] ?? '';
        if ( strlen($y)==0 && strlen($s)==0 ) continue;
        if ( strlen($y)==0 || strlen($s)==0 ) {
            return "All fields are required";
        }
        if ( ! is_numeric($y) ) {
            return "Year must be numeric";
        }
    }
    return true;
}

function loadPos($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute([':pid'=>$profile_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function loadEdu($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT year, Institution.name AS name FROM Education 
            JOIN Institution ON Education.institution_id = Institution.institution_id
            WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute([':pid'=>$profile_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertPositions($pdo, $profile_id) {
    $rank = 1;
    for ($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) || ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
        if ( strlen($year)==0 || strlen($desc)==0 ) continue;
        $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)');
        $stmt->execute([
            ':pid'=>$profile_id,
            ':rank'=>$rank,
            ':year'=>$year,
            ':desc'=>$desc
        ]);
        $rank++;
    }
}

function insertEducations($pdo, $profile_id) {
    $rank = 1;
    for ($i=1; $i<=9; $i++) {
        $y = $_POST['edu_year'.$i] ?? '';
        $s = $_POST['edu_school'.$i] ?? '';
        if ( strlen($y)==0 || strlen($s)==0 ) continue;
        $stmt = $pdo->prepare("SELECT institution_id FROM Institution WHERE name = :name");
        $stmt->execute([':name'=>$s]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $iid = $row['institution_id'];
        } else {
            $stmt = $pdo->prepare("INSERT INTO Institution (name) VALUES (:name)");
            $stmt->execute([':name'=>$s]);
            $iid = $pdo->lastInsertId();
        }
        $stmt = $pdo->prepare("INSERT INTO Education (profile_id, institution_id, rank, year) VALUES (:pid, :iid, :rank, :year)");
        $stmt->execute([':pid'=>$profile_id, ':iid'=>$iid, ':rank'=>$rank, ':year'=>$y]);
        $rank++;
    }
}
?>
