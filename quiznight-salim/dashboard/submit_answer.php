<!-- Cette page gère la soumission des réponses d'un utilisateur pendant le quiz. -->

<?php
session_start();
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer'])) {
        $answer_id = $_POST['answer'];
        
        // Vérifier si la réponse est correcte
        $answer_sql = "SELECT * FROM answers WHERE id = ?";
        $answer_stmt = $pdo->prepare($answer_sql);
        $answer_stmt->execute([$answer_id]);
        $answer = $answer_stmt->fetch();

        if ($answer['is_correct']) {
            $_SESSION['correct_answers']++;
        }

        // Stocker la réponse dans la session
        $_SESSION['answers'][] = [
            'question_id' => $answer['question_id'],
            'answer_id' => $answer_id,
            'is_correct' => $answer['is_correct']
        ];
    }

    if (isset($_POST['next_question'])) {
        $_SESSION['current_question']++;
    }

    if ($_SESSION['current_question'] >= $_SESSION['total_questions']) {
        // Jeu terminé
        header('Location: final_score.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}
?>
