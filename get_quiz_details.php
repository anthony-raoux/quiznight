<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Quiz ID is required']);
    exit;
}

$quiz_id = $_GET['id'];

// Obtenez les détails du quiz 
$stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz) {
    http_response_code(404);
    echo json_encode(['error' => 'Quiz not found']);
    exit;
}

// Obtenez les questions associées au quiz
$stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

// Pour chaque question, obtenez les réponses associées
foreach ($questions as &$question) {
    $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
    $stmt->execute([$question['id']]);
    $question['answers'] = $stmt->fetchAll();
}

// Préparez la réponse en JSON
$response = [
    'title' => $quiz['title'],
    'description' => $quiz['description'],
    'questions' => $questions
];

header('Content-Type: application/json');
echo json_encode($response);
?>
