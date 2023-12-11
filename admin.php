<?php
// Conectare la baza de date
$servername = "localhost:3006";
$username = "root";
$password = "";
$dbname = "php_download_images";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare dacă conexiunea la baza de date este stabilită
if ($conn === null || $conn->connect_error) {
    die("Conexiune esuata: " . ($conn ? $conn->connect_error : 'Conexiunea nu a fost stabilită.'));
}
session_start();

// Funcție pentru verificarea drepturilor de administrator
function checkAdminRights() {
    if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== true) {
        header("location: login.php"); // Redirecționează către o pagină de eroare sau de avertisment pentru acces neautorizat
        exit;
    }
}

// Apelare funcția pentru verificarea drepturilor de administrator
checkAdminRights();

// Include meniul
include 'menu.php';

// Funcție pentru afișarea imaginilor descărcate într-o listă cu opțiunea de ștergere
function viewDownloadedImages() {
    global $conn;

    $stmt = $conn->prepare("SELECT download_id, original_name FROM downloads WHERE username = ?");
    $stmt->bind_param("s", $_SESSION["username"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h3>Imagini Descărcate</h3>";
        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
        echo "<label for='selectedImages'>Alegeți imaginile:</label>";

        while ($row = $result->fetch_assoc()) {
            echo "<div style='margin-bottom: 10px;'>";
            echo "<input type='checkbox' name='selectedImages[]' value='" . $row["download_id"] . "'> " . $row["original_name"];
            echo "<button type='submit' name='deleteImage' value='" . $row["download_id"] . "'>Șterge</button>";
            echo "</div>";
        }

        echo "<input type='submit' name='downloadSelected' value='Descarcă Imaginile Selectate'>";
        echo "</form>";
    } else {
        echo "Nu există imagini descărcate.";
    }

    $stmt->close();
}

// Adaugare functie pentru descarcarea imaginilor și ștergerea lor din baza de date
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["downloadSelected"])) {
        if (isset($_POST["selectedImages"]) && is_array($_POST["selectedImages"])) {
            foreach ($_POST["selectedImages"] as $imageId) {
                if (is_numeric($imageId)) {
                    $stmt = $conn->prepare("SELECT image_data, original_name FROM downloads WHERE download_id = ?");
                    $stmt->bind_param("i", $imageId);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($imageData, $originalName);
                        $stmt->fetch();

                        // Descarcă imaginea
                        header('Content-Disposition: attachment; filename="' . $originalName . '"');
                        echo $imageData;
                        exit;
                    }
                    $stmt->close();
                }
            }
        }
    } elseif (isset($_POST["deleteImage"])) {
        $imageToDelete = $_POST["deleteImage"];
        $stmt = $conn->prepare("DELETE FROM downloads WHERE download_id = ? AND original_name = ?");
        $stmt->bind_param("is", $imageToDelete, $_SESSION["username"]);
        $stmt->execute();
        $stmt->close();
        header("Location: admin.php"); // Redirect la pagina pentru a actualiza lista de imagini
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>
    <h2>Pagina de administrare</h2>

    <!-- Adaugare functie pentru afisarea imaginilor descarcate -->
    <?php viewDownloadedImages(); ?>

    <br>
    <a href="logout.php">Deconectare</a>
</body>
</html>