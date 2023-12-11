<?php
session_start();
if (!isset($_SESSION["username"])) {
    // Redirect user to login page
    header("location: login.php");
    exit;
}
include "menu.php";

// Include config file
require_once "config.php";

$link = mysqli_connect($servername, $username, $password, $dbname);

// Verificare dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["link"]))) {
        $link_err = "Vă rugăm să introduceți un link.";
    } else {
        $user_link = trim($_POST["link"]);

        // Verificăm dacă este un link valid
        if (filter_var($user_link, FILTER_VALIDATE_URL)) {

            // Descărcăm conținutul paginii
            $html = file_get_contents($user_link);

            // Verificăm dacă conține imagini
            if ($html) {
                // Cautăm toate etichetele img
                preg_match_all('/<img[^>]+>/i', $html, $result);

                foreach ($result[0] as $img_tag) {
                    preg_match('/src="([^"]+)/i', $img_tag, $img_src);

                    if (!empty($img_src[1])) {
                        $src = $img_src[1];
                        $extension = pathinfo($src, PATHINFO_EXTENSION);
                        $allowedExtensions = array('jpg', 'jpeg', 'png', 'avif', 'webp');

                        // Verificăm dacă extensia este acceptată
                        if (in_array(strtolower($extension), $allowedExtensions)) {
                            $imageContent = file_get_contents($src);

                            $param_username = $_SESSION["username"];
                            $param_image = base64_encode($imageContent);
                            $param_extension = $extension;

                            // Inserăm în baza de date
                            $sql = "INSERT INTO downloads (username, image, extension) VALUES (?, ?, ?)";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_image, $param_extension);

                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                            }
                        }
                    }
                }
            } else {
                echo "Link-ul introdus nu conține un conținut valid.";
            }

        } else {
            echo "Vă rugăm să introduceți un link valid.";
        }
    }
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descărcare Imagini</title>
</head>
<body>
    <h2>Descărcare Imagini</h2>
    <form action="welcome.php" method="post">
        Introdu link-ul imaginilor:
        <input type="text" name="link" required>
        <br>
        <input type="submit" name="submit" value="Adaugă Link">
    </form>

    <br>
    <a href="logout.php">Deconectare</a>
</body>
</html>