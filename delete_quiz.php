<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/classes/Quiz.php';
require_once __DIR__ . '/classes/Database.php';

$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz_id'])) {
    $quiz_id = $_POST['quiz_id'];
    if ($quiz->delete($quiz_id)) {
        // Quiz deleted successfully
        header("Location: admin.php");
        exit;
    } else {
        // Handle deletion failure
        echo "Failed to delete quiz.";
    }
} else {
    // Handle invalid request
    echo "Invalid request.";
}
