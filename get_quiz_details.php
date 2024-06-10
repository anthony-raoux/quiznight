<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header('Location: login.php');
    exit;
}

// Check if quiz ID is provided in the request
if (!isset($_GET['quiz_id'])) {
    // Quiz ID is not provided, redirect to homepage
    header('Location: index.php');
    exit;
}

// Get the quiz ID from the request
$quiz_id = $_GET['quiz_id'];

// Prepare and execute query to fetch quiz details
$stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

// Check if quiz exists
if (!$quiz) {
    // Quiz does not exist, redirect to homepage
    header('Location: index.php');
    exit;
}

// Prepare and execute query to fetch questions for the quiz
$stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

// Prepare and execute query to fetch answers for each question
foreach ($questions as &$question) {
    $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
    $stmt->execute([$question['id']]);
    $question['answers'] = $stmt->fetchAll();
}

// Output quiz details in JSON format
header('Content-Type: application/json');
echo json_encode([
    'quiz' => $quiz,
    'questions' => $questions
]);
?>
