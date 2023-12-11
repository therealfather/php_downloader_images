<?php
// FILEPATH: /Users/attitude3/Desktop/php_image_downloader/index.php

session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extractor de Fotografii</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Extractor de Fotografii</h2>
    <form action="index.php" method="post">
        <label for="links">Lipiți Link-uri:</label><br>
        <textarea id="links" name="links" rows="4" cols="50"></textarea><br>
        <label for="width">Lățime:</label>
        <input type="number" id="width" name="width" value="100"><br>
        <label for="height">Înălțime:</label>
        <input type="number" id="height" name="height" value="100"><br>
        <input type="submit" value="Extrage și Descarcă Fotografii">
    </form>

    <!-- Adaugăm aici afișarea imaginilor -->
    <?php
    // Aici poți adăuga logica pentru a afișa imaginile extrase din directorul "downloads"
    $downloadDir = "downloads/";
    $files = scandir($downloadDir);
    foreach ($files as $file) {
        if ($file !== "." && $file !== "..") {
            echo "<img src='" . $downloadDir . $file . "' alt='Imagine'><br>";
        }
    }
    ?>

    <br>
    <a href="logout.php">Deconectare</a>
</body>
</html>