<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$quiz_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz || $quiz['created_by'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);

$stmt = $pdo->prepare('DELETE FROM questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);

$stmt = $pdo->prepare('DELETE FROM answers WHERE question_id IN (SELECT id FROM questions WHERE quiz_id = ?)');
$stmt->execute([$quiz_id]);

header('Location: index.php');
exit;
?>