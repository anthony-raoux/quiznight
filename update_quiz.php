<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Inclusion des fichiers requis
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';

// Initialisation de la connexion à la base de données et de l'objet Quiz
$database = new Database();
$db = $database->getConnection();
$quiz = new Quiz($db);

// Vérifie si des données ont été soumises via POST
if ($_POST) {
    $quiz_id = $_POST['quiz_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Appel de la méthode update() avec les trois arguments nécessaires
    if ($quiz->update($quiz_id, $title, $description)) {
        echo "Quiz updated successfully.";
    } else {
        echo "Failed to update quiz.";
    }
}
?>
