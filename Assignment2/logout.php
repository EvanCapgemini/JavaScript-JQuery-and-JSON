<?php
require_once("pdo.php");
session_destroy();
session_start();
$_SESSION['success'] = 'Logged out.';
header("Location: index.php");
exit();
?>
