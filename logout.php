<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Détruire toutes les variables de session
$_SESSION = [];

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion
header("Location: login.php");
exit;
?>
