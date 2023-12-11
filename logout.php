<?php
session_start();

// Distrugere sesiune și redirecționare către login.php
session_destroy();
header("Location: login.php");
exit();
?>
