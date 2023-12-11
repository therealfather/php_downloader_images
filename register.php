<?php
// Conectare la baza de date
$servername = "localhost:3006";
$username = "root";
$password = "";
$dbname = "php_download_images";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Conexiune esuata: " . $conn->connect_error);
}

// Mesajul de succes
$successMessage = "";

// Procesare formular de înregistrare
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Verificare dacă utilizatorul există deja utilizând instrucțiuni pregătite
    $checkQuery = "SELECT id FROM users WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "Acest utilizator există deja!";
    } else {
        // Adăugare utilizator nou în baza de date utilizând instrucțiuni pregătite
        $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ss", $username, $password);

        if ($insertStmt->execute()) {
            $successMessage = "Înregistrare reușită!";

            // Adaugă un link pentru a reveni la pagina de login
            $successMessage .= ' <a href="login.php">Autentificare</a>';
        } else {
            echo "Eroare la înregistrare: " . $insertStmt->error;
        }

        $insertStmt->close();
    }

    $checkStmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Înregistrare</title>
</head>
<body>
    <h2>Formular de înregistrare</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Nume de utilizator:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Parolă:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Înregistrează-te">
    </form>
    <?php
    if (!empty($successMessage)) {
        echo '<p>' . $successMessage . '</p>';
    }
    ?>
</body>
</html>