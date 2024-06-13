<?php
session_start();

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin->username = $_POST['username'];
    $admin->password = $_POST['password']; // Le mot de passe est déjà hashé dans la méthode register()

    if ($admin->register()) {
        $_SESSION['admin_id'] = $admin->id; // S'il y a un champ 'id' dans votre classe Admin
        $_SESSION['username'] = $admin->username; // Sauvegarde du nom d'utilisateur dans la session
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['register_error'] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        header("Location: register.php");
        exit;
    }
} else {
    header("Location: register.php");
    exit;
}
?>
