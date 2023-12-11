<?php

session_start();

if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['submit'])) {
    // Connect to the database
    $db = new mysqli('localhost:3006', 'root', '', 'php_download_images');

    // Check connection
    if ($db->connect_error) {
        die('Connection failed: ' . $db->connect_error);
    }

    // Save image URL
    $imageURL = $_POST['image_url'];
    $sql = "INSERT INTO linkuri (link) VALUES ('$imageURL')";

    if ($db->query($sql) === TRUE) {
        echo 'URL salvat cu succes';
    } else {
        echo 'Eroare la salvarea URL-ului';
    }

    $db->close();

    // Process images and save them as JPG

}
echo '<a href="admin.php">Panou de administrare</a>';
?>