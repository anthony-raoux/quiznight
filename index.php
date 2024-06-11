<?php
session_start();
include 'db.php';

// Fetch all quizzes
$stmt = $pdo->query('SELECT * FROM quizzes');
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// afficher les réponse avec php et changer get quiz details avec php
$quizDetails = [];
if (isset($_GET['id'])) {
    $quizId = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
    $stmt->execute([$quizId]);
    $quizDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($quizDetails) {
        // Fetch questions
        $stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
        $stmt->execute([$quizId]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $quizDetails['questions'] = $questions;

        // Fetch answers for each question
        foreach ($questions as &$question) {
            $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
            $stmt->execute([$question['id']]);
            $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $question['answers'] = $answers;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Quiz App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Create Quiz</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Available Quizzes</h1>
        <ul class="list-group">
            <?php foreach ($quizzes as $quiz): ?>
                <li class="list-group-item">
                    <a href="index.php?id=<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></a>
                    <?php if (isset($_SESSION['user_id']) && $quiz['created_by'] == $_SESSION['user_id']): ?>
                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-secondary btn-sm ml-2">Edit</a>
                        <a href="delete_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-danger btn-sm ml-2">Delete</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if (!empty($quizDetails)): ?>
            <div id="quizDetails" class="mt-4">
                <h3><?php echo htmlspecialchars($quizDetails['title']); ?></h3>
                <p><?php echo htmlspecialchars($quizDetails['description']); ?></p>
                <?php foreach ($quizDetails['questions'] as $index => $question): ?>
                    <h4>Question <?php echo ($index + 1) . ': ' . htmlspecialchars($question['question_text']); ?></h4>
                    <button class="btn btn-show-answer btn-sm ml-2" onclick="toggleAnswer('answer-<?php echo $question['id']; ?>', this)">Voir la réponse</button>
                    <div id="answer-<?php echo $question['id']; ?>" style="display: none;">
                        <ul>
                            <?php foreach ($question['answers'] as $answer): ?>
                                <li><?php echo htmlspecialchars($answer['answer_text']) . ($answer['is_correct'] ? ' (Correct)' : ''); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function toggleAnswer(answerId, button) {
        var answerElement = document.getElementById(answerId);
        if (answerElement.style.display === 'none') {
            answerElement.style.display = 'block';
            button.textContent = 'Masquer la réponse';
            button.classList.remove('btn-show-answer');
            button.classList.add('btn-hide-answer');
        } else {
            answerElement.style.display = 'none';
            button.textContent = 'Voir la réponse';
            button.classList.remove('btn-hide-answer');
            button.classList.add('btn-show-answer');
        }
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


