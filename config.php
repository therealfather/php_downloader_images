<?php
    $servername = "localhost:3006";       
    $username = "root";
    $password = "";
    $dbname = "php_download_images";

    $conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată pentru baza de date: " . $conn->connect_error);
}
?>
