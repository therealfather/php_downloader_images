<?php
session_start();
require_once 'config.php';

// Funcție pentru verificarea drepturilor de administrator
function checkAdminRights() {
    if (!isset($_SESSION["isAdmin"]) || $_SESSION["isAdmin"] !== true) {
        header("location: login.php"); // Redirecționează către o pagină de eroare sau de avertisment pentru acces neautorizat
        exit;
    }
}

// Include meniul
include 'menu.php';

// Funcție pentru afișarea imaginilor descărcate într-o listă
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

        // Utilizăm fetch_assoc pentru a obține fiecare rând ca un array asociativ
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='selectedImages[]' value='" . $row["download_id"] . "'> " . $row["original_name"] . "<br>";
        }

        echo "<input type='submit' name='downloadSelected' value='Descarcă Imaginile Selectate'>";
        echo "<input type='submit' name='viewSelected' value='Vizualizează Imaginile Selectate'>";
        echo "<input type='submit' name='deleteSelected' value='Șterge Imaginile Selectate'>";
        echo "<input type='submit' name='resizeImages' value='Redimensionează Imaginile Selectate'>";
        echo "</form>";
    } else {
        echo "Nu există imagini descărcate.";
    }

    $stmt->close();
}

// Collect the image URLs from Images and Description
$index = 0;
$temparray = array();
$tmp = NULL;

// Modificare: Verificare dacă variabila $marray este definită și nu este goală
if (isset($marray) && !empty($marray)) {
    // Adăugare: Iterare prin fiecare element din $marray
    foreach ($marray as $x => $value) {
        $imageGroupID = $value['ID'];
        $images = $value['Images'];
        $html = $value['Description'];
        $doc = new DOMDocument();

        $internalErrors = libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        libxml_use_internal_errors($internalErrors);

        $imageTags = $doc->getElementsByTagName('img');
        foreach ($imageTags as $tag) {
            $tmp = $tmp . $tag->getAttribute('src') . ',';
        }
        $tmp = $tmp . $images;
        $temparray[$imageGroupID] = $tmp;
        $tmp = NULL;
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

    <h3>Redimensionare și Descărcare Imagini</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="width">Lățime:</label>
        <input type="number" name="width" required>

        <label for="height">Înălțime:</label>
        <input type="number" name="height" required>

        <label for="selectedImages">Alegeți imaginile:</label>
        <?php
        $stmt = $conn->prepare("SELECT download_id, original_name FROM downloads WHERE username = ?");
        $stmt->bind_param("s", $_SESSION["username"]);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo '<input type="checkbox" name="selectedImages[]" value="' . $row["download_id"] . '"> ' . $row["original_name"] . '<br>';
        }

        $stmt->close();
        ?>
        
        <input type="submit" name="downloadSelected" value="Descarcă Imaginile Selectate">
        <input type="submit" name="viewSelected" value="Vizualizează Imaginile Selectate">
        <input type="submit" name="deleteSelected" value="Șterge Imaginile Selectate">
        <input type="submit" name="resizeImages" value="Redimensionează Imaginile Selectate">
    </form>
    <br>
    <a href="logout.php">Deconectare</a>
</body>
</html>