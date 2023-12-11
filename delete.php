<?php
$dir = 'uploads/';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $filepath = $dir . $filename;

    if (file_exists($filepath)) {
        unlink($filepath);
        echo "File deleted successfully.";
    } else {
        echo "The file $filename does not exist.";
    }
}
?>