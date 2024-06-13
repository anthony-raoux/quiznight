<?php
require 'db.php';  // Fichier de connexion à la base de données
require 'Quiz.php';

$quiz = new Quiz($pdo);
$quizzes = $quiz->getAllQuizzes();

echo json_encode($quizzes);
?>
