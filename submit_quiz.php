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
    ?>
    <?php include 'navbar.php'; ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Quiz Results</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-w1QBRaD/smK2uWRzyD1O3uTps9pBBHiC4N9iwUb5Kr8KSkM+SOJwB3TrC6K06fxE" crossorigin="anonymous">
    </head>
    <body>
    <div class="container mt-4">
        <h1 class="mb-4">Quiz Results</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quiz: <?php echo $quiz_details['title']; ?></h5>
                <p class="card-text">Total Questions: <?php echo $total_questions; ?></p>
                <p class="card-text">Correct Answers: <?php echo $correct_answers; ?></p>
                <p class="card-text">Score: <?php echo $score; ?>%</p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>
    </body>
    </html>
    <?php
    exit; // Stop further execution
} else {
    // Redirect if not a POST request (to prevent direct access)
    header("Location: quizzes.php");
    exit;
}
?>
