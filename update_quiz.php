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
    $answers = $_POST['answers']; // Tableau des réponses à mettre à jour

    // Mettre à jour le quiz principal
    if ($quiz->updateQuiz($quiz_id, $title, $description)) {
        echo "Quiz updated successfully.";

        // Mettre à jour chaque réponse
        foreach ($answers as $answer_id => $answer_data) {
            $answer_text = $answer_data['answer_text'];
            if ($quiz->updateAnswer($answer_id, $answer_text)) {
                echo "Answer updated successfully.";
            } else {
                echo "Failed to update answer.";
            }
        }
    } else {
        echo "Failed to update quiz.";
    }
}
?>
