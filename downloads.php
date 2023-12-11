<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && !empty($_GET["id"])) {
    $imageId = $_GET["id"];

    // Obținem calea către imagine din baza de date
    $stmt = $conn->prepare("SELECT link FROM linkuri WHERE id = ?");
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($imageLink);
        $stmt->fetch();
        $stmt->close();

        $imagePath = "downloads/" . basename($imageLink);

        // Verificăm dacă fișierul există și este o imagine
        $imageType = exif_imagetype($imagePath);

        if ($imageType !== false) {
            // Asigurăm că nu s-au emis date către browser înainte de setarea antetelor
            ob_clean();

            // Setăm tipul de conținut pentru a asigura descărcarea cu extensia corectă
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($imagePath) . '"');
            readfile($imagePath);
            exit;
        }
    }
}

// Dacă nu s-a putut descărca imaginea sau parametrul "id" lipsește, redirecționăm utilizatorul către pagina de admin
header("Location: admin.php");
exit;

