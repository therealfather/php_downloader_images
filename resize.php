<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'config.php';

function resizeImage($sourcePath, $destinationPath, $width, $height) {
    list($originalWidth, $originalHeight) = getimagesize($sourcePath);

    $newImage = imagecreatetruecolor($width, $height);
    $sourceImage = imagecreatefromjpeg($sourcePath);

    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    imagejpeg($newImage, $destinationPath);

    imagedestroy($newImage);
    imagedestroy($sourceImage);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["resize"])) {
    $imageId = $_POST["imageId"];
    $width = $_POST["width"];
    $height = $_POST["height"];

    $sql = "SELECT link FROM linkuri WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Eroare la pregătirea interogării: " . $conn->error);
    }

    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $stmt->bind_result($imageLink);
    $stmt->fetch();
    $stmt->close();

    $sourcePath = "downloads/" . $imageLink;
    $destinationPath = "downloads/resized_" . $imageLink;

    resizeImage($sourcePath, $destinationPath, $width, $height);
    echo "Imagine redimensionată cu succes și salvată ca 'resized_" . $imageLink . "'.";
}
?>