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

    // Verificare dacă utilizatorul există deja
    $checkQuery = "SELECT id FROM users WHERE username = '$username'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "Acest utilizator există deja!";
    } else {
        // Adăugare utilizator nou în baza de date
        $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

        if ($conn->query($insertQuery) === TRUE) {
            $successMessage = "Înregistrare reușită!";

            // Adaugă un link pentru a reveni la pagina de login
            $successMessage .= ' <a href="login.php">Autentificare</a>';
        } else {
            echo "Eroare la înregistrare: " . $conn->error;
        }
    }
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