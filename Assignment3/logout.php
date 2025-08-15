<?php
require_once "util.php";
session_destroy();
session_start();
$_SESSION['success'] = "Logged out.";
header("Location: index.php");
return;
