<?php
include 'db.php';

if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
    $stmt->execute([$quiz_id]);
    $quiz = $stmt->fetch();

    $stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
    $stmt->execute([$quiz_id]);
    $questions = $stmt->fetchAll();

    foreach ($questions as &$question) {
        $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
        $stmt->execute([$question['id']]);
        $question['answers'] = $stmt->fetchAll();
    }

    $quiz['questions'] = $questions;

    echo json_encode($quiz);
}
?>
