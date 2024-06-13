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

$quiz_id = $_GET['quiz_id'] ?? null;
if ($quiz_id) {
    $questions = $question->getQuestionsByQuiz($quiz_id);
} else {
    header("Location: quizzes.php");
    exit;
}
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-w1QBRaD/smK2uWRzyD1O3uTps9pBBHiC4N9iwUb5Kr8KSkM+SOJwB3TrC6K06fxE" crossorigin="anonymous">
    <link rel="stylesheet" href="./custom.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Quiz</h1>
    <form action="submit_quiz.php" method="post">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <?php foreach ($questions as $question) : ?>
            <div class="mb-4">
                <p class="font-weight-bold"><?php echo $question['question']; ?></p>
                <?php $answers = $answer->getAnswersByQuestion($question['id']); ?>
                <?php foreach ($answers as $ans) : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $ans['id']; ?>" id="answer<?php echo $ans['id']; ?>">
                        <label class="form-check-label" for="answer<?php echo $ans['id']; ?>">
                            <?php echo $ans['answer']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>
</body>
</html>
