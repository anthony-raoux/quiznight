<?php
include 'db.php';

if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Préparer la requête pour récupérer les détails du quiz
    $quizQuery = 'SELECT * FROM quizzes WHERE id = ?';
    $stmt = $pdo->prepare($quizQuery);
    $stmt->execute([$quiz_id]);
    $quiz = $stmt->fetch();

    if ($quiz) {
        // Préparer la requête pour récupérer les questions associées au quiz
        $questionsQuery = 'SELECT * FROM questions WHERE quiz_id = ?';
        $stmt = $pdo->prepare($questionsQuery);
        $stmt->execute([$quiz_id]);
        $questions = $stmt->fetchAll();

        foreach ($questions as &$question) {
            // Préparer la requête pour récupérer les réponses associées à chaque question
            $answersQuery = 'SELECT * FROM answers WHERE question_id = ?';
            $stmt = $pdo->prepare($answersQuery);
            $stmt->execute([$question['id']]);
            $question['answers'] = $stmt->fetchAll();
        }

        // Ajouter les questions au tableau de données du quiz
        $quiz['questions'] = $questions;

        // Renvoyer les détails du quiz au format JSON
        echo json_encode($quiz);
    } else {
        // Gérer le cas où aucun quiz n'est trouvé avec l'ID spécifié
        echo json_encode(['error' => 'Quiz not found']);
    }
} else {
    // Gérer le cas où aucun ID de quiz n'est spécifié
    echo json_encode(['error' => 'No quiz ID specified']);
}
?>
