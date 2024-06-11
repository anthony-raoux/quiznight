<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)');
    $stmt->execute([$title, $description, $created_by]);

    $quiz_id = $pdo->lastInsertId();

    foreach ($_POST['questions'] as $question) {
        $question_text = $question['text'];

        if (!empty($question_text)) {
            $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)');
            $stmt->execute([$quiz_id, $question_text]);
            $question_id = $pdo->lastInsertId();

            if (isset($question['answers']) && is_array($question['answers'])) {
                foreach ($question['answers'] as $answer) {
                    $answer_text = $answer;
                    if (!empty($answer_text)) {
                        $stmt = $pdo->prepare('INSERT INTO answers (question_id, answer_text) VALUES (?, ?)');
                        $stmt->execute([$question_id, $answer_text]);
                    }
                }
            }
        }
    }

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Quiz App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Create Quiz</h1>
        <form method="post" action="create_quiz.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            </div>
            <div id="questions">
                <h2>Questions</h2>
                <div class="question">
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control" name="questions[0][text]">
                    </div>
                    <div class="form-group answers">
                        <label>Answers</label>
                        <input type="text" class="form-control" name="questions[0][answers][text]">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addAnswer(this)">Add Answer</button>
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-3" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Create Quiz</button>
        </form>
    </div>

    <script>
        let questionIndex = 1;

        function addQuestion() {
            var questionsDiv = document.getElementById('questions');
            var questionDiv = document.createElement('div');
            questionDiv.classList.add('question', 'mt-4');

            questionDiv.innerHTML = `
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" class="form-control" name="questions[${questionIndex}][text]">
                </div>
                <div class="form-group answers">
                    <label>Answers</label>
                    <input type="text" class="form-control" name="questions[${questionIndex}][answers][]">
                </div>
                <button type="button" class="btn btn-primary" onclick="addAnswer(this)">Add Answer</button>
            `;

            questionsDiv.appendChild(questionDiv);
            questionIndex++;
        }

        function addAnswer(button) {
            var answersDiv = button.previousElementSibling;
            var answerInput = document.createElement('input');
            answerInput.type = 'text';
            answerInput.className = 'form-control mt-2';
            answerInput.name = answersDiv.querySelector('input').name;
            answersDiv.appendChild(answerInput);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
