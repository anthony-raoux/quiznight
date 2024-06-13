<?php
session_start();

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';
require_once __DIR__ . '/classes/Question.php';
require_once __DIR__ . '/classes/Answer.php';

$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);
$question = new Question($db);
$answer = new Answer($db);

// Process quiz submission if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_answers = $_POST['answers'] ?? [];
    $quiz_id = $_POST['quiz_id'] ?? null;

    // Validate quiz_id (for security, ensure it's a valid quiz id from your database)
    if (!$quiz_id) {
        header("Location: quizzes.php");
        exit;
    }

    // Calculate score
    $total_questions = count($user_answers);
    $correct_answers = 0;
    foreach ($user_answers as $question_id => $selected_answer_id) {
        $correct_answer_id = $answer->getCorrectAnswer($question_id);
        if ($selected_answer_id == $correct_answer_id) {
            $correct_answers++;
        }
    }

    $score = ($correct_answers / $total_questions) * 100;
    $quiz_details = $quiz->getQuizById($quiz_id);

    // Output results
    echo "<h1>Quiz Results</h1>";
    echo "<p>Total Questions: $total_questions</p>";
    echo "<p>Correct Answers: $correct_answers</p>";
    echo "<p>Score: $score%</p>";

    // Example of showing quiz details if needed
    // echo "<p>Quiz Name: {$quiz_details['quiz_name']}</p>";

    // Include navbar and footer
   
    include 'footer.php';

    exit; // Stop further execution
} else {
    // Redirect if not a POST request (to prevent direct access)
    header("Location: quizzes.php");
    exit;
}
?>
