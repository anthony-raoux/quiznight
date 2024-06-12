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

    // Vérifier chaque réponse de l'utilisateur
    $total_questions = count($user_answers);
    $correct_answers = 0;
    foreach ($user_answers as $question_id => $selected_answer) {
        $correct_answer = $answer->getCorrectAnswer($question_id);
        if ($selected_answer == $correct_answer) {
            $correct_answers++;
        }
    }

    // Calculer le score
    $score = ($correct_answers / $total_questions) * 100;

    // Récupérer les détails du quiz
    $quiz_details = $quiz->getQuizById($quiz_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results</title>
</head>
<body>
    <h1>Quiz Results</h1>
    <p>Quiz: <?php echo isset($quiz_details['title']) ? $quiz_details['title'] : 'N/A'; ?></p>
    <p>Total Questions: <?php echo $total_questions; ?></p>
    <p>Correct Answers: <?php echo $correct_answers; ?></p>
    <p>Score: <?php echo isset($score) ? $score : 'N/A'; ?>%</p>
</body>
</html>
