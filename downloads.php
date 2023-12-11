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

// Verifică dacă este setat un ID valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $download_id = $_GET['id'];

    // Interogare pentru a obține informații despre fișierul de descărcat
    $stmt = $conn->prepare("SELECT stored_name, original_name FROM downloads WHERE download_id = ?");
    $stmt->bind_param("i", $download_id);
    $stmt->execute();
    $stmt->bind_result($stored_name, $original_name);
    $stmt->fetch();
    $stmt->close();

    // Verifică dacă informațiile despre fișier există
    if ($stored_name && $original_name) {
        // Construiește calea la fișier în baza de date (presupunând că este un câmp BLOB)
        $file_content = $stored_name;

        // Setează antetele pentru descărcare
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . $original_name . '"');

        // Returnează conținutul fișierului către utilizator
        echo $file_content;
        exit;
    }
}

// Dacă ajungem aici, există o problemă cu ID-ul primit
echo "ID invalid sau fișierul nu există.";
?>


