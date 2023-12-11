    <?php
    if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificare dacă există dimensiunea în cerere
    if (isset($_POST["dimensiune"])) {
        $dimensiune = $_POST["dimensiune"];

        // Aici poți adăuga logica pentru a prelua, filtra și afișa imaginile din directorul "descarcari"
        $downloadDir = "descarcari/";
        $files = scandir($downloadDir);

        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                $imagePath = $downloadDir . $file;

                // Verificăm dimensiunea imaginii
                $imageSize = getimagesize($imagePath);
                if ($imageSize !== false) {
                    $width = $imageSize[0];
                    $height = $imageSize[1];

                    // Comparăm dimensiunea cu cerința specificată
                    if ($width >= $dimensiune && $height >= $dimensiune) {
                        // Afișăm imaginea
                        echo "<img src='" . $imagePath . "' alt='Imagine'><br>";
                    }
                }
            }
        }
    } else {
        echo "Nu a fost furnizată o dimensiune validă.";
    }
} else {
    echo "Acces interzis. Acest script trebuie să fie apelat printr-o cerere POST.";
}
?>
