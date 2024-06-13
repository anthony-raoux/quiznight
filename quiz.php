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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
</head>
<body>
    <h1>Quiz</h1>
    <form action="submit_quiz.php" method="post">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <?php foreach ($questions as $question) : ?>
            <div>
                <p><?php echo $question['question']; ?></p>
                <?php $answers = $answer->getAnswersByQuestion($question['id']); ?>
                <?php foreach ($answers as $ans) : ?>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $ans['id']; ?>">
                    <label><?php echo $ans['answer']; ?> (ID: <?php echo $ans['id']; ?>)</label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
