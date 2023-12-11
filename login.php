<?php
// Verificare dacă utilizatorul este deja autentificat
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: welcome.php");
    exit;
}

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

// Variabilă pentru mesajele de eroare la autentificare
$login_err = "";

// Procesare formular de logare
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verificare utilizator în baza de date utilizând instrucțiuni pregătite
    $checkQuery = "SELECT id, username, password, is_admin FROM users WHERE username = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($id, $dbUsername, $hashed_password, $is_admin);
        $checkStmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["username"] = $username;

            // Setează variabila de sesiune isAdmin în funcție de valoarea din baza de date
            $_SESSION["isAdmin"] = ($is_admin == 1);

            header("location: welcome.php"); // Redirecționați către pagina de bun venit
            exit;
        } else {
            $login_err = "Datele de autentificare sunt incorecte.";
        }
    } else {
        $login_err = "Utilizator inexistent!";
    }

    $checkStmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Autentificare</title>
</head>
<body>
    <h2>Formular de autentificare</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">Nume de utilizator:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Parolă:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Autentificare">
    </form>
    <?php
    if (!empty($login_err)) {
        echo '<p>' . $login_err . '</p>';
    }
    ?>
    <p>Nu ai un cont? <a href="register.php">Înregistrează-te aici</a>.</p>
</body>
</html>