<?php
function flashMessages() {
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">' . htmlentities($_SESSION['error']) . "</p>";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">' . htmlentities($_SESSION['success']) . "</p>";
        unset($_SESSION['success']);
    }
}

function validateProfile($data) {
    if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) ||
        empty($data['headline']) || empty($data['summary'])) {
        $_SESSION['error'] = "All fields are required";
        return false;
    }
    if (strpos($data['email'], '@') === false) {
        $_SESSION['error'] = "Email address must contain @";
        return false;
    }
    return true;
}
?>
