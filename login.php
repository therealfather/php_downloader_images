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

// Procesare formular de logare
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Verificare utilizator în baza de date
    $checkQuery = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            header("location: welcome.php");
        } else {
            echo "Parolă incorectă!";
        }
    } else {
        echo "Utilizator inexistent!";
    }
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
    if (!empty($errorMessage)) {
        echo '<p>' . $errorMessage . '</p>';
    }
    ?>
    <p>Nu ai un cont? <a href="register.php">Înregistrează-te aici</a>.</p>
</body>
</html>