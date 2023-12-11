<!-- menu.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meniu Pagini</title>
</head>
<body>
    <h2>Meniu Pagini</h2>

    <?php
    // Definește paginile pe care le deții
    $pages = array(
        "Pagina pentru Admin" => "admin.php",
        "Pagina principala" => "welcome.php",
        // Adaugă aici alte pagini
    );

    // Afiseaza butoanele pentru fiecare pagina
    foreach ($pages as $pageName => $pageURL) {
        echo '<a href="' . $pageURL . '"><button>' . $pageName . '</button></a><br>';
    }
    ?>
</body>
</html>