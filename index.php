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

// Récupérer toutes les questions du quiz
$quiz_id = $_GET['quiz_id'] ?? null;
if ($quiz_id) {
    $questions = $question->getQuestionsByQuiz($quiz_id);
} else {
    // Rediriger l'utilisateur s'il n'a pas sélectionné de quiz
    header("Location: quizzes.php");
    exit;
}

// Gérer la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_answers = $_POST['answers'] ?? [];

    // Vérifier chaque réponse de l'utilisateur
    $total_questions = count($questions);
    $correct_answers = 0;
    foreach ($user_answers as $question_id => $selected_answer) {
        $correct_answer = $answer->getCorrectAnswer($question_id);
        if ($selected_answer == $correct_answer) {
            $correct_answers++;
        }
    }

    // Calculer le score
    $score = ($correct_answers / $total_questions) * 100;
}
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
</head>
<body>
    <h1>Quiz</h1>
    <form action="" method="post">
        <?php foreach ($questions as $question) : ?>
            <div>
                <p><?php echo $question['question']; ?></p>
                <?php $answers = $answer->getAnswersByQuestion($question['id']); ?>
                <?php foreach ($answers as $ans) : ?>
                    <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $ans['id']; ?>">
                    <label><?php echo $ans['answer']; ?></label><br>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit">Submit</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <h2>Results</h2>
        <p>Score: <?php echo isset($score) ? $score : 'N/A'; ?>%</p>
    <?php endif; ?>
</body>
</html>
