<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../functions.php';
$id = $_GET['id'];
$type = $_GET['type'];
delete($type,$id);
?>