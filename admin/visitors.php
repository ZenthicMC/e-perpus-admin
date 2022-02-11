<?php 
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<h2>Not yet created</h2>