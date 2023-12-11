<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["username"] !== "admin") {
    header('Location: welcome.php');
    exit();
}

// Connect to the database
$db = new mysqli('localhost:3006', 'root', '', 'php_download_images');

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

// Display saved images
$sql = "SELECT imagine FROM imagini";
$result
?>