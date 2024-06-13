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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_answers = $_POST['answers'] ?? [];
    $quiz_id = $_POST['quiz_id'] ?? null;

    echo "<pre>";
    print_r($user_answers);
    echo "</pre>";

    $total_questions = count($user_answers);
    $correct_answers = 0;
    foreach ($user_answers as $question_id => $selected_answer) {
        $correct_answer_id = $answer->getCorrectAnswer($question_id);
        echo "Question ID: $question_id, Selected Answer: $selected_answer, Correct Answer ID: $correct_answer_id <br>";
        if ($selected_answer == $correct_answer_id) {
            $correct_answers++;
        }
    }

    $score = ($correct_answers / $total_questions) * 100;
    $quiz_details = $quiz->getQuizById($quiz_id);

    echo "Total Questions: $total_questions <br>";
    echo "Correct Answers: $correct_answers <br>";
    echo "Score: $score% <br>";
    exit;
}
?>
