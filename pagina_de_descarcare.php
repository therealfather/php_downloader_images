<?php
include 'menu.php';
require_once 'config.php';
session_start();

$downloadDir = "descarcari/";
$files = scandir($downloadDir);

// Procesare formular pentru filtrarea imaginilor după dimensiune
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["dimensiune"])) {
    $dimensiune = $_POST["dimensiune"];
    if (isset($dimensiune) && strpos($dimensiune, "x") !== false) {
        list($latime, $inaltime) = explode("x", $dimensiune);

        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                $filePath = $downloadDir . $file;

                // Verificare dimensiunile imaginii
                $imageInfo = getimagesize($filePath);
                if ($imageInfo) {
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    // Verificare dacă imaginea îndeplinește criteriile de dimensiune
                    if ($width >= $latime && $height >= $inaltime) {
                        echo "<img src='{$filePath}' alt='Imagine' style='max-width: 100%; height: auto;'><br>";
                    }
                }
            }
        }
    }
} else {
    // Afișează toate imaginile dacă nu se aplică filtrul
    foreach ($files as $file) {
        if ($file !== "." && $file !== "..") {
            $filePath = $downloadDir . $file;
            echo "<img src='{$filePath}' alt='Imagine' style='max-width: 100%; height: auto;'><br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Descărcare</title>
</head>
<body>
    <h2>Pagina de Descărcare</h2>
    
    <!-- Formular pentru filtrarea imaginilor după dimensiune -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        Dimensiune minimă (lățime x înălțime): 
        <input type="text" name="dimensiune" required>
        <br>
        <input type="submit" value="Filtrează Imagini">
    </form>
    
    <br>
    <a href="welcome.php">Înapoi la Pagina Principală</a>
    <br>
    <a href="logout.php">Deconectare</a>
</body>
</html>